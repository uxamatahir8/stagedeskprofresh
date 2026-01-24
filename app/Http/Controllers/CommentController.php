<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of all comments (dashboard)
     */
    public function index()
    {
        $title = 'Comments Management';
        $comments = Comment::with(['user', 'blog'])->latest()->paginate(20);

        $stats = [
            'total' => Comment::count(),
            'published' => Comment::where('status', 'published')->count(),
            'unapproved' => Comment::where('status', 'unapproved')->count(),
            'replies' => Comment::whereNotNull('parent_id')->count(),
        ];

        return view('dashboard.pages.blogs.comments.index', compact('title', 'comments', 'stats'));
    }

    /**
     * Store a new comment (frontend)
     */
    public function store(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|exists:blogs,id',
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'blog_id' => $request->blog_id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
            'status' => 'unapproved', // Requires approval
        ]);

        return back()->with('success', 'Your comment has been submitted and is pending approval.');
    }

    /**
     * Approve a comment
     */
    public function approve(Comment $comment)
    {
        $comment->update(['status' => 'published']);

        return back()->with('success', 'Comment approved successfully.');
    }

    /**
     * Reject/Unapprove a comment
     */
    public function unapprove(Comment $comment)
    {
        $comment->update(['status' => 'unapproved']);

        return back()->with('success', 'Comment unapproved.');
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment)
    {
        // Check if user has permission
        if (Auth::id() !== $comment->user_id && !Auth::user()->role || !in_array(Auth::user()->role->role_key, ['master_admin', 'company_admin'])) {
            return back()->with('error', 'You do not have permission to delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }

    /**
     * Like a comment
     */
    public function like(Comment $comment)
    {
        $comment->increment('likes_count');

        return response()->json([
            'success' => true,
            'likes_count' => $comment->likes_count
        ]);
    }

    /**
     * Show comments for a specific blog
     */
    public function blogComments(Blog $blog)
    {
        $title = "Comments for: {$blog->title}";
        $comments = $blog->allComments()->with(['user', 'parent'])->latest()->paginate(20);

        return view('dashboard.pages.blogs.comments.blog-comments', compact('title', 'comments', 'blog'));
    }
}
