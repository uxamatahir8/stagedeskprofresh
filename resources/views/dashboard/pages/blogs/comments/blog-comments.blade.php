@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="message-circle" style="width: 20px; height: 20px;" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">{{ Str::limit($blog->title, 60) }}</p>
        </div>
        <div class="text-end">
            <a href="{{ route('blog.details', $blog) }}" class="btn btn-secondary btn-sm me-2">
                <i data-lucide="arrow-left" style="width: 14px; height: 14px;" class="me-1"></i> Back to Post
            </a>
            <a href="{{ route('comments.index') }}" class="btn btn-light btn-sm">All Comments</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Comments ({{ $comments->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $index => $comment)
                            <tr>
                                <td>{{ $comments->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $comment->user->name ?? 'Unknown' }}</strong>
                                    <br><small class="text-muted">{{ $comment->user->email ?? '' }}</small>
                                </td>
                                <td>{{ Str::limit($comment->content, 120) }}</td>
                                <td>
                                    <span class="badge bg-{{ $comment->status === 'published' ? 'success' : 'warning' }}">{{ ucfirst($comment->status) }}</span>
                                </td>
                                <td><small>{{ $comment->created_at->format('M d, Y') }}</small></td>
                                <td>
                                    @if($comment->status === 'unapproved')
                                        <form action="{{ route('comment.approve', $comment) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                        </form>
                                    @endif
                                    <form action="{{ route('comment.destroy', $comment) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No comments for this post yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($comments->hasPages())
            <div class="card-footer">{{ $comments->links() }}</div>
        @endif
    </div>
@endsection
