@extends('layouts.web')

@section('content')
    <!-- BLOG HERO SECTION -->
    <div class="bg-dark text-light py-5"
        style="background: url({{ asset('landing/images/background/header2-bg.png') }}) center/cover no-repeat;">
        <div class="container text-center">
            <h1 class="fw-bold">{{ $blog->title }}</h1>
        </div>
    </div>

    <!-- BLOG DETAIL + SIDEBAR -->
    <div class="container my-5">
        <div class="row">

            <!-- LEFT: Blog Detail + Comments -->
            <div class="col-lg-8 mb-4">

                <!-- Blog Card -->
                <div class="card shadow-sm border-0 mb-5">
                    <img src="{{ asset('storage/' . ($blog->image ?? '')) }}" class="card-img-top rounded-top" alt="">
                    <div class="card-body">
                        {!! $blog->content !!}
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="mb-5">
                    <h4>Comments</h4>

                    <!-- Show Login Prompt if Not Auth -->
                    @guest
                        <p class="text-muted">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a> to post a comment.
                        </p>
                    @endguest

                    <!-- Comment Form -->
                    @auth
                        <form action="{{ route('blogs.comment', $blog->slug) }}" method="POST" class="mb-4">
                            @csrf
                            <textarea name="comment" rows="3" class="form-control mb-2" placeholder="Write a comment..."
                                required></textarea>
                            <button class="btn btn-primary">Post Comment</button>
                        </form>
                    @endauth

                    <!-- Display Comments & Replies -->
                    @foreach($blog->comments as $comment)
                        <div class="mb-3 p-3 shadow-sm rounded bg-light">
                            <strong>{{ $comment->user->name }}</strong>
                            <p>{{ $comment->content }}</p>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>

                            @auth
                                <a href="javascript:void(0);" class="reply-toggle text-primary small"
                                    data-id="{{ $comment->id }}">Reply</a>

                                <form action="{{ route('blogs.comment', $blog->slug) }}" method="POST"
                                    class="reply-form mt-2 d-none" id="reply-form-{{ $comment->id }}">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                    <textarea name="comment" rows="2" class="form-control mb-2" placeholder="Reply..."
                                        required></textarea>
                                    <button class="btn btn-sm btn-secondary">Reply</button>
                                </form>
                            @endauth

                            @foreach($comment->replies as $reply)
                                <div class="ms-4 mt-2 p-2 border-start border-2">
                                    <strong>{{ $reply->user->name }}</strong>
                                    <p class="mb-0">{{ $reply->content }}</p>
                                    <small class="text-muted">{{ $reply->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- RIGHT: Sidebar -->
            <div class="col-lg-4">

                <!-- Categories -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Categories</h5>
                        <ul class="list-unstyled mb-0">
                            @foreach($categories as $cat)
                                <li class="mb-2">
                                    <a href="{{ url('blogs/' . strtolower($cat->name)) }}"
                                        class="{{ (isset($blog->category) && $blog->category->id === $cat->id) ? 'fw-bold text-primary' : 'text-dark' }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Related Blogs -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="mb-3">Related Blogs</h5>
                        @foreach($relatedBlogs as $related)
                            <div class="d-flex mb-3">
                                <img src="{{ asset('storage/' . ($related->feature_image ?? '')) }}" class="rounded me-2"
                                    style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <a href="{{ route('blog.details', $related->slug) }}"
                                        class="text-dark small fw-bold">{{ $related->title }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

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

    <script>
        document.querySelectorAll('.reply-toggle').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                document.getElementById('reply-form-' + id).classList.toggle('d-none');
            });
        });
    </script>
@endsection