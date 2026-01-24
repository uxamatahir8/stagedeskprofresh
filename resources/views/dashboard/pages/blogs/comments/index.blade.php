@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="message-circle" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage blog comments and replies</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-primary-subtle rounded me-3">
                            <i data-lucide="message-circle" class="text-primary"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                            <small class="text-muted">Total Comments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-success-subtle rounded me-3">
                            <i data-lucide="check-circle" class="text-success"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['published'] ?? 0 }}</h3>
                            <small class="text-muted">Published</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-warning-subtle rounded me-3">
                            <i data-lucide="clock" class="text-warning"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['unapproved'] ?? 0 }}</h3>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-info-subtle rounded me-3">
                            <i data-lucide="corner-down-right" class="text-info"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['replies'] ?? 0 }}</h3>
                            <small class="text-muted">Replies</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Comments Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
            <h5 class="card-title mb-0">All Comments ({{ $comments->total() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>User</th>
                            <th>Blog Post</th>
                            <th>Comment</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($comments as $index => $comment)
                            <tr>
                                <td>{{ $comments->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-primary">{{ substr($comment->user->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <strong>{{ $comment->user->name ?? 'Unknown' }}</strong>
                                            <br><small class="text-muted">{{ $comment->user->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('blog.details', $comment->blog->slug) }}" target="_blank">
                                        {{ Str::limit($comment->blog->title ?? 'N/A', 40) }}
                                    </a>
                                </td>
                                <td>
                                    <div style="max-width: 300px;">
                                        {{ Str::limit($comment->content, 100) }}
                                    </div>
                                </td>
                                <td>
                                    @if($comment->parent_id)
                                        <span class="badge bg-info-subtle text-info">
                                            <i data-lucide="corner-down-right" style="width: 12px;"></i> Reply
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary">
                                            <i data-lucide="message-circle" style="width: 12px;"></i> Comment
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $comment->status === 'published' ? 'success' : 'warning' }}">
                                        {{ ucfirst($comment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $comment->created_at->format('M d, Y') }}</small>
                                    <br><small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if($comment->status === 'unapproved')
                                            <form action="{{ route('comment.approve', $comment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                    <i data-lucide="check" style="width: 14px;"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('comment.unapprove', $comment) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm" title="Unapprove">
                                                    <i data-lucide="x" style="width: 14px;"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('blog.details', $comment->blog->slug) }}#comment-{{ $comment->id }}" target="_blank" class="btn btn-dark btn-sm" title="View">
                                            <i data-lucide="eye" style="width: 14px;"></i>
                                        </a>

                                        <form action="{{ route('comment.destroy', $comment) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i data-lucide="trash-2" style="width: 14px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i data-lucide="inbox" class="mb-2"></i>
                                    <p class="text-muted mb-0">No comments found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($comments->hasPages())
            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $comments->firstItem() }} to {{ $comments->lastItem() }} of {{ $comments->total() }} comments
                </div>
                {{ $comments->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        lucide.createIcons();
    </script>
    @endpush
@endsection
