@extends('layouts.tailwind-public')

@section('meta_description', $blog->meta_description ?? $blog->excerpt ?? Str::limit(strip_tags($blog->content), 150))

@section('content')
@php
    $publishedDate = $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y');
@endphp

<section class="bg-sidebar py-14 text-white">
    <div class="sd-container max-w-4xl">
        @if ($blog->is_featured)
            <span class="sd-status sd-status-warning">Featured post</span>
        @endif
        <h1 class="mt-5 text-4xl font-bold leading-tight tracking-normal text-white">{{ $blog->title }}</h1>
        <div class="mt-5 flex flex-wrap gap-4 text-sm text-slate-300">
            <span class="inline-flex items-center gap-2"><i data-lucide="user" class="size-4"></i>{{ $blog->user->name ?? 'Admin' }}</span>
            <span class="inline-flex items-center gap-2"><i data-lucide="calendar" class="size-4"></i>{{ $publishedDate }}</span>
            <span class="inline-flex items-center gap-2"><i data-lucide="eye" class="size-4"></i>{{ $blog->views_count ?? 0 }} views</span>
            @if ($blog->reading_time)
                <span class="inline-flex items-center gap-2"><i data-lucide="clock" class="size-4"></i>{{ $blog->reading_time }} min read</span>
            @endif
            @if ($blog->category)
                <a href="{{ route('blogs', $blog->category->name) }}" class="inline-flex items-center gap-2 text-slate-200 hover:text-white"><i data-lucide="folder" class="size-4"></i>{{ $blog->category->name }}</a>
            @endif
        </div>
    </div>
</section>

<section class="sd-section bg-surface-muted">
    <div class="sd-container grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:items-start">
        <article class="min-w-0">
            <div class="sd-card overflow-hidden">
                @if ($blog->feature_image || $blog->image)
                    <img src="{{ asset('storage/' . ($blog->feature_image ?: $blog->image)) }}" class="max-h-[460px] w-full object-cover" alt="{{ $blog->title }}">
                @endif
                <div class="sd-card-pad">
                    @if ($blog->excerpt)
                        <div class="mb-6 rounded-card border border-info/20 bg-info-soft p-4">
                            <p class="text-sm font-semibold text-sky-900">Overview</p>
                            <p class="mt-1 text-sm leading-6 text-sky-900">{{ $blog->excerpt }}</p>
                        </div>
                    @endif

                    <div class="sd-rich-content">
                        {!! $blog->content !!}
                    </div>

                    @if ($blog->tags && count($blog->tags) > 0)
                        <div class="mt-8 border-t border-border pt-5">
                            <p class="sd-label">Tags</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach ($blog->tags as $tag)
                                    <span class="rounded-full bg-surface-muted px-3 py-1 text-xs font-semibold text-text-muted">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <section class="mt-6 sd-card sd-card-pad" id="comments">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="text-xl font-bold text-text-primary">Comments ({{ $blog->approvedComments->count() }})</h2>
                    <i data-lucide="message-square" class="size-5 text-text-muted"></i>
                </div>

                @guest
                    <div class="mt-5 rounded-card border border-info/20 bg-info-soft p-4 text-sm leading-6 text-sky-900">
                        <a href="{{ route('login') }}" class="font-bold text-primary hover:text-primary-hover">Login</a>
                        or
                        <a href="{{ route('register') }}" class="font-bold text-primary hover:text-primary-hover">register</a>
                        to post a comment.
                    </div>
                @endguest

                @auth
                    <form action="{{ route('comment.store') }}" method="POST" class="mt-5 rounded-card border border-border bg-surface-muted p-4">
                        @csrf
                        <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                        <label for="comment-content" class="sd-label">Write a comment</label>
                        <textarea id="comment-content" name="content" rows="4" class="sd-form-field mt-2" placeholder="Share your comment..." required>{{ old('content') }}</textarea>
                        @error('content')
                            <p class="sd-error-text mt-2">{{ $message }}</p>
                        @enderror
                        <x-tw.button type="submit" class="mt-3" icon="send">Post comment</x-tw.button>
                    </form>
                @endauth

                <div class="mt-6 space-y-4">
                    @forelse ($blog->approvedComments->whereNull('parent_id') as $comment)
                        <div class="rounded-card border border-border bg-surface p-4" id="comment-{{ $comment->id }}">
                            <div class="flex gap-3">
                                <img src="{{ $comment->user->avatar ?? asset('images/default-avatar.png') }}" class="size-11 rounded-full object-cover" alt="{{ $comment->user->name }}">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-bold text-text-primary">{{ $comment->user->name }}</p>
                                            <p class="text-xs text-text-muted">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                        <button type="button" class="inline-flex items-center gap-1 rounded-field border border-border bg-surface px-2 py-1 text-xs font-semibold text-text-secondary hover:bg-surface-muted" data-comment-like="{{ $comment->id }}">
                                            <i data-lucide="thumbs-up" class="size-3.5"></i>
                                            <span data-like-count>{{ $comment->likes_count ?? 0 }}</span>
                                        </button>
                                    </div>
                                    <p class="mt-3 text-sm leading-6 text-text-secondary">{{ $comment->content }}</p>
                                    @auth
                                        <button type="button" class="mt-3 text-sm font-semibold text-primary hover:text-primary-hover" data-reply-toggle="{{ $comment->id }}">Reply</button>
                                        <form action="{{ route('comment.store') }}" method="POST" class="mt-3 hidden rounded-card border border-border bg-surface-muted p-3" data-reply-form="{{ $comment->id }}">
                                            @csrf
                                            <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <textarea name="content" rows="3" class="sd-form-field" placeholder="Write a reply..." required></textarea>
                                            <x-tw.button type="submit" size="sm" class="mt-2" icon="send">Post reply</x-tw.button>
                                        </form>
                                    @endauth

                                    @if ($comment->replies->count() > 0)
                                        <div class="mt-4 space-y-3 border-l border-border pl-4">
                                            @foreach ($comment->replies as $reply)
                                                <div class="rounded-card bg-surface-muted p-3">
                                                    <div class="flex gap-3">
                                                        <img src="{{ $reply->user->avatar ?? asset('images/default-avatar.png') }}" class="size-9 rounded-full object-cover" alt="{{ $reply->user->name }}">
                                                        <div class="min-w-0 flex-1">
                                                            <div class="flex flex-wrap items-start justify-between gap-3">
                                                                <div>
                                                                    <p class="text-sm font-bold text-text-primary">{{ $reply->user->name }}</p>
                                                                    <p class="text-xs text-text-muted">{{ $reply->created_at->diffForHumans() }}</p>
                                                                </div>
                                                                <button type="button" class="inline-flex items-center gap-1 rounded-field border border-border bg-surface px-2 py-1 text-xs font-semibold text-text-secondary hover:bg-surface-muted" data-comment-like="{{ $reply->id }}">
                                                                    <i data-lucide="thumbs-up" class="size-3.5"></i>
                                                                    <span data-like-count>{{ $reply->likes_count ?? 0 }}</span>
                                                                </button>
                                                            </div>
                                                            <p class="mt-2 text-sm leading-6 text-text-secondary">{{ $reply->content }}</p>
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
                        <div class="rounded-card border border-border bg-surface-muted p-4">
                            <p class="sd-text-body">No comments yet. Be the first to comment.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </article>

        <aside class="space-y-4">
            @if ($blog->user)
                <div class="sd-card sd-card-pad text-center">
                    <img src="{{ $blog->user->avatar ?? asset('images/default-avatar.png') }}" class="mx-auto size-16 rounded-full object-cover" alt="{{ $blog->user->name }}">
                    <h2 class="sd-title-card mt-3">{{ $blog->user->name }}</h2>
                    <p class="sd-text-muted">Author</p>
                </div>
            @endif

            <div class="sd-card sd-card-pad">
                <h2 class="sd-title-card">Categories</h2>
                <div class="mt-4 space-y-2">
                    @foreach ($categories as $cat)
                        <a href="{{ route('blogs', $cat->name) }}" class="flex items-center justify-between rounded-field px-3 py-2 text-sm font-semibold {{ (isset($blog->category) && $blog->category->id === $cat->id) ? 'bg-primary-soft text-primary' : 'text-text-secondary hover:bg-surface-muted' }}">
                            <span>{{ $cat->name }}</span>
                            <i data-lucide="chevron-right" class="size-4"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            @if (isset($recentBlogs) && $recentBlogs->count() > 0)
                <div class="sd-card sd-card-pad">
                    <h2 class="sd-title-card">Recent posts</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($recentBlogs as $recent)
                            <a href="{{ route('blog-front.details', $recent->slug) }}" class="flex gap-3 rounded-field p-2 hover:bg-surface-muted">
                                @if ($recent->feature_image || $recent->image)
                                    <img src="{{ asset('storage/' . ($recent->feature_image ?: $recent->image)) }}" class="size-14 rounded-field object-cover" alt="{{ $recent->title }}">
                                @endif
                                <span class="text-sm font-semibold leading-5 text-text-primary">{{ Str::limit($recent->title, 56) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (isset($relatedBlogs) && $relatedBlogs->count() > 0)
                <div class="sd-card sd-card-pad">
                    <h2 class="sd-title-card">Related posts</h2>
                    <div class="mt-4 space-y-4">
                        @foreach ($relatedBlogs as $related)
                            <a href="{{ route('blog-front.details', $related->slug) }}" class="flex gap-3 rounded-field p-2 hover:bg-surface-muted">
                                @if ($related->feature_image || $related->image)
                                    <img src="{{ asset('storage/' . ($related->feature_image ?: $related->image)) }}" class="size-14 rounded-field object-cover" alt="{{ $related->title }}">
                                @endif
                                <span class="text-sm font-semibold leading-5 text-text-primary">{{ Str::limit($related->title, 56) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-reply-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelector(`[data-reply-form="${button.dataset.replyToggle}"]`)?.classList.toggle('hidden');
        });
    });

    document.querySelectorAll('[data-comment-like]').forEach((button) => {
        button.addEventListener('click', () => {
            fetch(`/comment/like/${button.dataset.commentLike}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        button.querySelector('[data-like-count]').textContent = data.likes_count;
                        button.classList.add('border-primary', 'text-primary');
                    }
                });
        });
    });
</script>
@endpush
