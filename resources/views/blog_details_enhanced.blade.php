@extends('layouts.web')

@section('content')
    <!-- BLOG HERO SECTION -->
    <div class="bg-dark text-light py-5"
        style="background: url({{ asset('landing/images/background/header2-bg.png') }}) center/cover no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    @if($blog->is_featured)
                        <span class="badge bg-warning text-dark mb-3">
                            <i class="bi bi-star-fill"></i> Featured Post
                        </span>
                    @endif
                    <h1 class="fw-bold mb-3">{{ $blog->title }}</h1>
                    <div class="d-flex justify-content-center align-items-center gap-3 text-light">
                        <span><i class="bi bi-person"></i> {{ $blog->user->name ?? 'Admin' }}</span>
                        <span><i class="bi bi-calendar"></i> {{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}</span>
                        <span><i class="bi bi-eye"></i> {{ $blog->views_count ?? 0 }} views</span>
                        @if($blog->reading_time)
                            <span><i class="bi bi-clock"></i> {{ $blog->reading_time }} min read</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BLOG DETAIL + SIDEBAR -->
    <div class="container my-5">
        <div class="row">

            <!-- LEFT: Blog Detail + Comments -->
            <div class="col-lg-8 mb-4">

                <!-- Blog Card -->
                <div class="card shadow-sm border-0 mb-4">
                    @if($blog->feature_image)
                        <img src="{{ asset('storage/' . $blog->feature_image) }}" class="card-img-top rounded-top" alt="{{ $blog->title }}"
                             style="max-height: 450px; object-fit: cover;">
                    @elseif($blog->image)
                        <img src="{{ asset('storage/' . $blog->image) }}" class="card-img-top rounded-top" alt="{{ $blog->title }}"
                             style="max-height: 450px; object-fit: cover;">
                    @endif

                    <div class="card-body p-4">
                        @if($blog->excerpt)
                            <div class="alert alert-info mb-4">
                                <strong>Overview:</strong> {{ $blog->excerpt }}
                            </div>
                        @endif

                        <div class="blog-content">
                            {!! $blog->content !!}
                        </div>

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

                <!-- Comments Section -->
                <div class="mb-5">
                    <h4 class="mb-4">
                        <i class="bi bi-chat-left-text"></i>
                        Comments ({{ $blog->approvedComments->count() }})
                    </h4>

                    <!-- Show Login Prompt if Not Auth -->
                    @guest
                        <div class="alert alert-info">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a> or
                            <a href="{{ route('artist.register') }}">Register</a> to post a comment.
                        </div>
                    @endguest

                    <!-- Comment Form -->
                    @auth
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <form action="{{ route('comment.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                    <textarea name="content" rows="3" class="form-control mb-3"
                                              placeholder="Write a comment..." required></textarea>
                                    <button class="btn btn-primary">
                                        <i class="bi bi-send"></i> Post Comment
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth

                    <!-- Display Comments & Threaded Replies -->
                    @forelse($blog->approvedComments->whereNull('parent_id') as $comment)
                        <div class="comment-item mb-4 p-3 shadow-sm rounded bg-light border" id="comment-{{ $comment->id }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <img src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}"
                                         class="rounded-circle" alt="{{ $comment->user->name }}"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $comment->user->name }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                <i class="bi bi-clock"></i> {{ $comment->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary like-btn"
                                                    data-comment-id="{{ $comment->id }}">
                                                <i class="bi bi-hand-thumbs-up"></i>
                                                <span class="like-count">{{ $comment->likes_count ?? 0 }}</span>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="mt-2 mb-2">{{ $comment->content }}</p>
                                    @auth
                                        <a href="javascript:void(0);" class="reply-toggle text-primary small"
                                            data-id="{{ $comment->id }}">
                                            <i class="bi bi-reply"></i> Reply
                                        </a>

                                        <form action="{{ route('comment.store') }}" method="POST"
                                            class="reply-form mt-3 d-none" id="reply-form-{{ $comment->id }}">
                                            @csrf
                                            <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <textarea name="content" rows="2" class="form-control mb-2"
                                                      placeholder="Write a reply..." required></textarea>
                                            <button class="btn btn-sm btn-primary">
                                                <i class="bi bi-send"></i> Post Reply
                                            </button>
                                        </form>
                                    @endauth

                                    <!-- Display Nested Replies -->
                                    @if($comment->replies->count() > 0)
                                        <div class="replies-section mt-3">
                                            @foreach($comment->replies as $reply)
                                                <div class="reply-item ms-4 mt-3 p-3 border-start border-primary border-3 bg-white rounded">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0">
                                                            <img src="{{ $reply->user->avatar ?? asset('images/default-avatar.png') }}"
                                                                 class="rounded-circle" alt="{{ $reply->user->name }}"
                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <div class="d-flex justify-content-between align-items-start">
                                                                <div>
                                                                    <strong>{{ $reply->user->name }}</strong>
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        <i class="bi bi-clock"></i> {{ $reply->created_at->diffForHumans() }}
                                                                    </small>
                                                                </div>
                                                                <div>
                                                                    <button class="btn btn-sm btn-outline-primary like-btn"
                                                                            data-comment-id="{{ $reply->id }}">
                                                                        <i class="bi bi-hand-thumbs-up"></i>
                                                                        <span class="like-count">{{ $reply->likes_count ?? 0 }}</span>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <p class="mt-2 mb-0">{{ $reply->content }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-secondary">
                            <i class="bi bi-chat"></i> No comments yet. Be the first to comment!
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- RIGHT: Sidebar -->
            <div class="col-lg-4">

                <!-- Author Info -->
                @if($blog->user)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body text-center">
                        <img src="{{ $blog->user->avatar ?? asset('images/default-avatar.png') }}"
                             class="rounded-circle mb-3" alt="{{ $blog->user->name }}"
                             style="width: 80px; height: 80px; object-fit: cover;">
                        <h5 class="mb-1">{{ $blog->user->name }}</h5>
                        <p class="text-muted small">Author</p>
                    </div>
                </div>
                @endif

                <!-- Categories -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="bi bi-folder"></i> Categories</h5>
                        <ul class="list-unstyled mb-0">
                            @foreach($categories as $cat)
                                <li class="mb-2">
                                    <a href="{{ url('blogs?category=' . $cat->id) }}"
                                        class="text-decoration-none {{ (isset($blog->category) && $blog->category->id === $cat->id) ? 'fw-bold text-primary' : 'text-dark' }}">
                                        <i class="bi bi-arrow-right-short"></i> {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Recent Blogs -->
                @if(isset($recentBlogs) && $recentBlogs->count() > 0)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="bi bi-clock-history"></i> Recent Posts</h5>
                        @foreach($recentBlogs as $recent)
                            <div class="d-flex mb-3 pb-3 border-bottom">
                                @if($recent->image)
                                    <img src="{{ asset('storage/' . $recent->image) }}" class="rounded me-3"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                                <div>
                                    <a href="{{ route('blog.show', $recent->slug) }}"
                                        class="text-dark small fw-bold text-decoration-none">
                                        {{ Str::limit($recent->title, 50) }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i>
                                        {{ \Carbon\Carbon::parse($recent->created_at)->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Related Blogs -->
                @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="bi bi-link-45deg"></i> Related Posts</h5>
                        @foreach($relatedBlogs as $related)
                            <div class="d-flex mb-3 pb-3 border-bottom">
                                @if($related->image)
                                    <img src="{{ asset('storage/' . $related->image) }}" class="rounded me-3"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                                <div>
                                    <a href="{{ route('blog.show', $related->slug) }}"
                                        class="text-dark small fw-bold text-decoration-none">
                                        {{ Str::limit($related->title, 50) }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        <i class="bi bi-eye"></i> {{ $related->views_count ?? 0 }} views
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- LOGIN MODAL -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('user_login') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Login to Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        @if($errors->has('email'))
                            <p class="text-danger mt-2">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .blog-content {
            line-height: 1.8;
            font-size: 1.05rem;
        }
        .blog-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }
        .blog-content h2, .blog-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .comment-item, .reply-item {
            transition: all 0.2s;
        }
        .comment-item:hover, .reply-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }
    </style>

    <script>
        // Toggle reply form
        document.querySelectorAll('.reply-toggle').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                document.getElementById('reply-form-' + id).classList.toggle('d-none');
            });
        });

        // AJAX Like functionality
        document.querySelectorAll('.like-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const commentId = this.getAttribute('data-comment-id');
                const likeCountSpan = this.querySelector('.like-count');

                fetch(`/comment/like/${commentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        likeCountSpan.textContent = data.likes_count;
                        this.classList.add('btn-primary');
                        this.classList.remove('btn-outline-primary');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection
