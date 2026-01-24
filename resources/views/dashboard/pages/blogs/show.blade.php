@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">Blog Details</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('blogs') }}">Blogs</a></li>
                <li class="breadcrumb-item active">{{ $blog->title }}</li>
            </ol>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                    <i data-lucide="eye"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-xs text-muted mb-1">Views</p>
                            <h4 class="mb-0">{{ $blog->views_count ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                    <i data-lucide="message-circle"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-xs text-muted mb-1">Total Comments</p>
                            <h4 class="mb-0">{{ $totalComments }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                    <i data-lucide="check-circle"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-xs text-muted mb-1">Approved Comments</p>
                            <h4 class="mb-0">{{ $approvedComments }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3">
                                    <i data-lucide="clock"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-xs text-muted mb-1">Pending Comments</p>
                            <h4 class="mb-0">{{ $pendingComments }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Blog Content --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Blog Content</h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-sm btn-success" target="_blank">
                            <i data-lucide="external-link" class="icon-sm me-1"></i> View Live
                        </a>
                        <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-sm btn-warning">
                            <i data-lucide="pencil" class="icon-sm me-1"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Featured Badge --}}
                    @if($blog->is_featured)
                        <span class="badge bg-warning text-dark mb-3">
                            <i data-lucide="star" class="icon-xs"></i> Featured Post
                        </span>
                    @endif

                    {{-- Title --}}
                    <h2 class="mb-3">{{ $blog->title }}</h2>

                    {{-- Meta Info --}}
                    <div class="d-flex flex-wrap gap-3 mb-4 text-muted">
                        <span><i data-lucide="user" class="icon-sm"></i> {{ $blog->user->name ?? 'Unknown' }}</span>
                        <span><i data-lucide="folder" class="icon-sm"></i> {{ $blog->category->name ?? 'Uncategorized' }}</span>
                        <span><i data-lucide="calendar" class="icon-sm"></i> {{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : 'Not published' }}</span>
                        @if($blog->reading_time)
                            <span><i data-lucide="clock" class="icon-sm"></i> {{ $blog->reading_time }} min read</span>
                        @endif
                    </div>

                    {{-- Excerpt --}}
                    @if($blog->excerpt)
                        <div class="alert alert-info mb-4">
                            <strong>Excerpt:</strong> {{ $blog->excerpt }}
                        </div>
                    @endif

                    {{-- Featured Image --}}
                    @if($blog->feature_image)
                        <img src="{{ asset('storage/' . $blog->feature_image) }}" class="img-fluid rounded mb-4" alt="{{ $blog->title }}">
                    @elseif($blog->image)
                        <img src="{{ asset('storage/' . $blog->image) }}" class="img-fluid rounded mb-4" alt="{{ $blog->title }}">
                    @endif

                    {{-- Content --}}
                    <div class="blog-content">
                        {!! $blog->content !!}
                    </div>

                    {{-- Tags --}}
                    @if($blog->tags && count($blog->tags) > 0)
                        <hr class="my-4">
                        <div class="d-flex flex-wrap gap-2">
                            <strong>Tags:</strong>
                            @foreach($blog->tags as $tag)
                                <span class="badge bg-secondary">{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Comments Section --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i data-lucide="message-circle"></i> Comments
                    </h4>
                </div>
                <div class="card-body">
                    @forelse($blog->allComments->whereNull('parent_id') as $comment)
                        <div class="comment-item mb-4 p-3 border rounded {{ $comment->status == 'unapproved' ? 'bg-warning-subtle' : '' }}">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong>{{ $comment->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i data-lucide="clock" class="icon-xs"></i> {{ $comment->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            @if($comment->status == 'unapproved')
                                                <form action="{{ route('comment.approve', $comment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                        <i data-lucide="check"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('comment.unapprove', $comment->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Unapprove">
                                                        <i data-lucide="x"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('comment.destroy', $comment->id) }}" method="POST"
                                                onsubmit="return confirm('Delete this comment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i data-lucide="trash-2"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <p class="mb-2">{{ $comment->content }}</p>

                                    @if($comment->status == 'unapproved')
                                        <span class="badge bg-warning text-dark">Pending Approval</span>
                                    @else
                                        <span class="badge bg-success">Published</span>
                                    @endif

                                    <span class="badge bg-info ms-2">
                                        <i data-lucide="thumbs-up" class="icon-xs"></i> {{ $comment->likes_count ?? 0 }}
                                    </span>

                                    {{-- Replies --}}
                                    @if($comment->allReplies->count() > 0)
                                        <div class="replies-section ms-4 mt-3">
                                            @foreach($comment->allReplies as $reply)
                                                <div class="reply-item mb-3 p-2 border-start border-primary border-3 ps-3 {{ $reply->status == 'unapproved' ? 'bg-warning-subtle' : '' }}">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <strong>{{ $reply->user->name }}</strong>
                                                            <br>
                                                            <small class="text-muted">
                                                                <i data-lucide="clock" class="icon-xs"></i> {{ $reply->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                        <div class="d-flex gap-2">
                                                            @if($reply->status == 'unapproved')
                                                                <form action="{{ route('comment.approve', $reply->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                                        <i data-lucide="check"></i>
                                                                    </button>
                                                                </form>
                                                            @else
                                                                <form action="{{ route('comment.unapprove', $reply->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-warning" title="Unapprove">
                                                                        <i data-lucide="x"></i>
                                                                    </button>
                                                                </form>
                                                            @endif

                                                            <form action="{{ route('comment.destroy', $reply->id) }}" method="POST"
                                                                onsubmit="return confirm('Delete this reply?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                                    <i data-lucide="trash-2"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <p class="mb-2">{{ $reply->content }}</p>
                                                    @if($reply->status == 'unapproved')
                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                    @else
                                                        <span class="badge bg-success">Published</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i data-lucide="message-circle" style="width: 48px; height: 48px;"></i>
                            <p class="mt-2">No comments yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- SEO Info --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-lucide="search"></i> SEO Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Title:</label>
                        <p class="text-muted">{{ $blog->meta_title ?? $blog->title }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Description:</label>
                        <p class="text-muted">{{ $blog->meta_description ?? $blog->excerpt ?? 'Not set' }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Slug:</label>
                        <code>{{ $blog->slug }}</code>
                    </div>
                </div>
            </div>

            {{-- Status Card --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-lucide="info"></i> Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Current Status:</label>
                        <br>
                        <span class="badge badge-label badge-soft-{{ config('arrays.status_colors')[$blog->status] ?? 'secondary' }}">
                            {{ ucfirst($blog->status) }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Created:</label>
                        <p class="text-muted mb-0">{{ $blog->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Last Updated:</label>
                        <p class="text-muted mb-0">{{ $blog->updated_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-warning">
                            <i data-lucide="pencil" class="icon-sm me-1"></i> Edit Blog
                        </a>
                        <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-success" target="_blank">
                            <i data-lucide="external-link" class="icon-sm me-1"></i> View Live
                        </a>
                        <a href="{{ route('blog.comments', $blog->id) }}" class="btn btn-info">
                            <i data-lucide="message-circle" class="icon-sm me-1"></i> All Comments
                        </a>
                        <form action="{{ route('blog.destroy', $blog->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this blog?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i data-lucide="trash-2" class="icon-sm me-1"></i> Delete Blog
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }
    </style>
@endsection
