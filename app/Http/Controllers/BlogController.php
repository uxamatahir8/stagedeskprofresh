<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    //
    public function index()
    {
        $title = 'Blogs';
        $roleKey = Auth::user()->role->role_key;

        // Master admin sees all blogs, others see only their own
        if ($roleKey === 'master_admin') {
            $blogs = Blog::with(['category', 'user'])->withCount('allComments')->latest()->paginate(15);

            $stats = [
                'total' => Blog::count(),
                'published' => Blog::where('status', 'published')->count(),
                'draft' => Blog::where('status', 'draft')->count(),
                'unapproved' => Blog::where('status', 'unapproved')->count(),
            ];
        } else {
            $blogs = Blog::where('user_id', Auth::id())
                ->with(['category', 'user'])
                ->withCount('allComments')
                ->latest()
                ->paginate(15);

            $stats = [
                'total' => Blog::where('user_id', Auth::id())->count(),
                'published' => Blog::where('user_id', Auth::id())->where('status', 'published')->count(),
                'draft' => Blog::where('user_id', Auth::id())->where('status', 'draft')->count(),
                'unapproved' => Blog::where('user_id', Auth::id())->where('status', 'unapproved')->count(),
            ];
        }

        return view('dashboard.pages.blogs.index', compact('title', 'blogs', 'stats'));
    }

    public function create()
    {
        $title = 'Create Blog';
        $categories = BlogCategory::all();
        $mode = 'create';

        return view('dashboard.pages.blogs.manage', compact('title', 'categories', 'mode'));
    }


    public function edit(Blog $blog)
    {
        $title = 'Edit Blog';
        $categories = BlogCategory::all();
        $mode = 'edit';

        return view('dashboard.pages.blogs.manage', compact('title', 'categories', 'mode', 'blog'));
    }


    public function uploadImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('blogs', 'public');
            return response()->json(['location' => asset('storage/' . $path)]);
        }
        return response()->json(['error' => 'No file uploaded.'], 422);
    }


    /**
     * Store a newly created blog.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:blogs,slug',
            'blog_category_id'  => 'required|exists:blog_categories,id',
            'blog_content'      => 'required|string',
            'excerpt'           => 'nullable|string|max:500',
            'tags'              => 'nullable|string',
            'meta_title'        => 'nullable|string|max:60',
            'meta_description'  => 'nullable|string|max:160',
            'reading_time'      => 'nullable|integer|min:1',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'feature_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at'      => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ Auto-generate slug if not provided
        $slug = $request->slug ?? Str::slug($request->title);
        if (Blog::where('slug', $slug)->exists()) {
            $slug .= '-' . time();
        }

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->slug = $slug;
        $blog->blog_category_id = $request->blog_category_id;
        $blog->content = $request->blog_content;
        $blog->excerpt = $request->excerpt;
        $blog->published_at = $request->published_at;
        $blog->user_id = Auth::user()->id;

        // Master admin can publish directly, others need approval
        $roleKey = Auth::user()->role->role_key;
        if ($roleKey === 'master_admin') {
            $blog->status = $request->has('status') && $request->status == 'active' ? 'published' : 'draft';
        } else {
            // Non-admin users' blogs go to unapproved status
            $blog->status = $request->has('status') && $request->status == 'active' ? 'unapproved' : 'draft';
        }

        $blog->is_featured = $request->has('is_featured') ? true : false;
        $blog->meta_title = $request->meta_title ?? $request->title;
        $blog->meta_description = $request->meta_description ?? $request->excerpt;

        // Handle tags
        if ($request->tags) {
            $blog->tags = array_map('trim', explode(',', $request->tags));
        }

        // Calculate reading time if not provided
        if ($request->reading_time) {
            $blog->reading_time = $request->reading_time;
        } else {
            $wordCount = str_word_count(strip_tags($request->blog_content));
            $blog->reading_time = max(1, ceil($wordCount / 200)); // 200 words per minute
        }

        // ✅ Handle thumbnail
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads/blogs/thumbnails', 'public');
            $blog->image = $path;
        }

        // ✅ Handle feature image
        if ($request->hasFile('feature_image')) {
            $path = $request->file('feature_image')->store('uploads/blogs/feature_images', 'public');
            $blog->feature_image = $path;
        }

        $blog->save();

        return redirect()->route('blogs')->with('success', 'Blog created successfully!');
    }

    /**
     * Update the specified blog.
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title'             => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:blogs,slug,' . $blog->id,
            'blog_category_id'  => 'required|exists:blog_categories,id',
            'blog_content'      => 'required|string',
            'excerpt'           => 'nullable|string|max:500',
            'tags'              => 'nullable|string',
            'meta_title'        => 'nullable|string|max:60',
            'meta_description'  => 'nullable|string|max:160',
            'reading_time'      => 'nullable|integer|min:1',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'feature_image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'published_at'      => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ Auto-generate slug if not provided
        $slug = $request->slug ?? Str::slug($request->title);
        if (Blog::where('slug', $slug)->where('id', '!=', $blog->id)->exists()) {
            $slug .= '-' . time();
        }

        $blog->title = $request->title;
        $blog->slug = $slug;
        $blog->blog_category_id = $request->blog_category_id;
        $blog->content = $request->blog_content;
        $blog->excerpt = $request->excerpt;
        $blog->published_at = $request->published_at;
        $blog->user_id = Auth::user()->id;

        // Master admin can publish directly, others need approval
        $roleKey = Auth::user()->role->role_key;
        if ($roleKey === 'master_admin') {
            $blog->status = $request->has('status') && $request->status == 'active' ? 'published' : 'draft';
        } else {
            // Non-admin users' blogs go to unapproved status when activated
            $blog->status = $request->has('status') && $request->status == 'active' ? 'unapproved' : 'draft';
        }

        $blog->is_featured = $request->has('is_featured') ? true : false;
        $blog->meta_title = $request->meta_title ?? $request->title;
        $blog->meta_description = $request->meta_description ?? $request->excerpt;

        // Handle tags
        if ($request->tags) {
            $blog->tags = array_map('trim', explode(',', $request->tags));
        }

        // Calculate reading time if not provided
        if ($request->reading_time) {
            $blog->reading_time = $request->reading_time;
        } else {
            $wordCount = str_word_count(strip_tags($request->blog_content));
            $blog->reading_time = max(1, ceil($wordCount / 200)); // 200 words per minute
        }

        // ✅ Handle thumbnail update
        if ($request->hasFile('image')) {
            if ($blog->image && Storage::disk('public')->exists($blog->image)) {
                Storage::disk('public')->delete($blog->image);
            }
            $path = $request->file('image')->store('uploads/blogs/thumbnails', 'public');
            $blog->image = $path;
        }

        // ✅ Handle feature image update
        if ($request->hasFile('feature_image')) {
            if ($blog->feature_image && Storage::disk('public')->exists($blog->feature_image)) {
                Storage::disk('public')->delete($blog->feature_image);
            }
            $path = $request->file('feature_image')->store('uploads/blogs/feature_images', 'public');
            $blog->feature_image = $path;
        }

        $blog->save();

        return redirect()->route('blogs')->with('success', 'Blog updated successfully!');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->image && Storage::disk('public')->exists($blog->image)) {
            Storage::disk('public')->delete($blog->image);
        }
        if ($blog->feature_image && Storage::disk('public')->exists($blog->feature_image)) {
            Storage::disk('public')->delete($blog->feature_image);
        }
        $blog->delete();
        return redirect()->route('blogs')->with('success', 'Blog deleted successfully!');
    }

    /**
     * Approve a blog (master admin only)
     */
    public function approve($id)
    {
        // Only master admin can approve blogs
        if (Auth::user()->role->role_key !== 'master_admin') {
            abort(403, 'Unauthorized action.');
        }

        $blog = Blog::findOrFail($id);
        $blog->status = 'published';
        $blog->save();

        return redirect()->back()->with('success', 'Blog approved and published successfully!');
    }

    /**
     * Reject a blog (master admin only)
     */
    public function reject($id)
    {
        // Only master admin can reject blogs
        if (Auth::user()->role->role_key !== 'master_admin') {
            abort(403, 'Unauthorized action.');
        }

        $blog = Blog::findOrFail($id);
        $blog->status = 'draft';
        $blog->save();

        return redirect()->back()->with('success', 'Blog rejected and moved to draft.');
    }

    public function showDashboard($id)
    {
        $blog = Blog::with(['category', 'user', 'allComments.user', 'allComments.allReplies.user'])
            ->withCount('allComments')
            ->findOrFail($id);

        $totalComments = $blog->allComments->count();
        $approvedComments = $blog->allComments->where('status', 'published')->count();
        $pendingComments = $blog->allComments->where('status', 'unapproved')->count();

        return view('dashboard.pages.blogs.show', compact('blog', 'totalComments', 'approvedComments', 'pendingComments'));
    }

    public function show($slug)
    {
        $blog = Blog::with(['category', 'user', 'comments.user', 'comments.replies.user'])->where('slug', $slug)->firstOrFail();

        // Increment views
        $blog->incrementViews();

        $categories = BlogCategory::all();
        $title = $blog->title;

        // Other blogs from the same category
        $relatedBlogs = Blog::where('blog_category_id', $blog->blog_category_id)
            ->where('id', '!=', $blog->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        // Recent blogs
        $recentBlogs = Blog::where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(5)
            ->get();

        return view('blog_details', compact('blog', 'categories', 'relatedBlogs', 'recentBlogs', 'title'));
    }

    public function postComment(Request $request, $slug)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $blog = Blog::where('slug', $slug)->firstOrFail();

        Comment::create([
            'user_id' => Auth::user()->id,
            'blog_id' => $blog->id,
            'parent_id' => $request->parent_id,
            'content' => $request->comment,
        ]);

        return back()->with('success', 'Your comment has been posted.');
    }
}
