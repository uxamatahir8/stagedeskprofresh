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
        $blogs = Blog::with('category')->get();
        return view('dashboard.pages.blogs.index', compact('title', 'blogs'));
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
            'blog_content'           => 'required|string',
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
        $blog->published_at = $request->published_at;
        $blog->user_id = Auth::user()->id;
        $blog->status = Auth::user()->role->role_key == 'master_admin' ? 'published' : 'unapproved';

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
            'blog_content'           => 'required|string',
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
        $blog->published_at = $request->published_at;
        $blog->user_id = Auth::user()->id;
        $blog->status = Auth::user()->role->role_key == 'master_admin' ? 'published' : 'unapproved';

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

    public function show($slug)
    {
        $blog = Blog::with(['category', 'comments.user', 'comments.replies.user'])->where('slug', $slug)->firstOrFail();

        $categories = BlogCategory::all();
        $title = $blog->title;

        // Other blogs from the same category
        $relatedBlogs = Blog::where('blog_category_id', $blog->category_id)
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(3)
            ->get();

        return view('blog_details', compact('blog', 'categories', 'relatedBlogs', 'title'));
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
