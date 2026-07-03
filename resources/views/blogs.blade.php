@extends('layouts.tailwind-public')

@section('meta_description', 'Read StageDesk Pro articles about event-service company operations, bookings, subscriptions, artists, and affiliate growth.')

@section('content')
<section class="bg-sidebar py-14 text-white">
    <div class="sd-container">
        <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-sm font-semibold text-slate-200">
            <i data-lucide="newspaper" class="size-4"></i>
            Public blog
        </p>
        <h1 class="mt-5 text-4xl font-bold tracking-normal text-white">{{ $title }}</h1>
        <p class="mt-4 max-w-2xl text-base leading-7 text-slate-300">
            Practical articles for event-service companies managing subscriptions, artists, customer booking requests, payment verification, and growth.
        </p>
    </div>
</section>

<section class="sd-section bg-surface-muted">
    <div class="sd-container">
        @if (isset($category))
            <div class="mb-6 flex flex-col gap-3 rounded-card border border-accent/20 bg-accent-soft p-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm font-semibold text-primary">Showing category: {{ $category->name }}</p>
                <x-tw.button :href="route('blogs')" variant="outline" size="sm" icon="x">View all blogs</x-tw.button>
            </div>
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            @forelse ($blogs as $blog)
                <article class="sd-card overflow-hidden">
                    @if ($blog->feature_image || $blog->image)
                        <img src="{{ asset('storage/' . ($blog->feature_image ?: $blog->image)) }}" alt="{{ $blog->title }}" class="h-64 w-full object-cover">
                    @endif
                    <div class="sd-card-pad">
                        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-text-muted">
                            @if ($blog->category)
                                <a href="{{ route('blogs', $blog->category->name) }}" class="rounded-full bg-primary-soft px-3 py-1 text-primary hover:bg-accent-soft">{{ $blog->category->name }}</a>
                            @endif
                            <span>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                            @if ($blog->reading_time)
                                <span>{{ $blog->reading_time }} min read</span>
                            @endif
                            <span>{{ $blog->views_count ?? 0 }} views</span>
                            <span>{{ $blog->approved_comments_count ?? 0 }} comments</span>
                        </div>
                        @if ($blog->is_featured)
                            <span class="sd-status sd-status-warning mt-4">Featured</span>
                        @endif
                        <h2 class="mt-4 text-xl font-bold leading-snug text-text-primary">
                            <a href="{{ route('blog-front.details', $blog->slug) }}" class="hover:text-primary">{{ $blog->title }}</a>
                        </h2>
                        <p class="sd-text-body mt-3">{{ $blog->excerpt ?: Str::limit(strip_tags($blog->content), 160) }}</p>
                        @if ($blog->tags && count($blog->tags) > 0)
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach (array_slice($blog->tags, 0, 3) as $tag)
                                    <span class="rounded-full bg-surface-muted px-3 py-1 text-xs font-semibold text-text-muted">{{ $tag }}</span>
                                @endforeach
                            </div>
                        @endif
                        <x-tw.button :href="route('blog-front.details', $blog->slug)" variant="link" icon="arrow-right" class="mt-5">Read article</x-tw.button>
                    </div>
                </article>
            @empty
                <div class="sd-card sd-card-pad md:col-span-2">
                    @if (isset($category))
                        <h2 class="sd-title-card">No blogs found in {{ $category->name ?? 'this category' }}</h2>
                        <p class="sd-text-body mt-2">Try viewing all public articles.</p>
                        <x-tw.button :href="route('blogs')" class="mt-4">View all blogs</x-tw.button>
                    @else
                        <h2 class="sd-title-card">No blogs found</h2>
                        <p class="sd-text-body mt-2">Public articles will appear here once they are published.</p>
                    @endif
                </div>
            @endforelse
        </div>

        @if ($blogs->hasPages())
            <nav class="mt-10 flex flex-wrap items-center justify-center gap-2" aria-label="Blog list pagination">
                @if ($blogs->onFirstPage())
                    <span class="rounded-field border border-border bg-surface px-3 py-2 text-sm text-text-muted">Previous</span>
                @else
                    <a href="{{ $blogs->previousPageUrl() }}" class="rounded-field border border-border bg-surface px-3 py-2 text-sm font-semibold text-text-primary hover:bg-surface-muted">Previous</a>
                @endif

                @foreach ($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="rounded-field border px-3 py-2 text-sm font-semibold {{ $page === $blogs->currentPage() ? 'border-primary bg-primary text-white' : 'border-border bg-surface text-text-primary hover:bg-surface-muted' }}">{{ $page }}</a>
                @endforeach

                @if ($blogs->hasMorePages())
                    <a href="{{ $blogs->nextPageUrl() }}" class="rounded-field border border-border bg-surface px-3 py-2 text-sm font-semibold text-text-primary hover:bg-surface-muted">Next</a>
                @else
                    <span class="rounded-field border border-border bg-surface px-3 py-2 text-sm text-text-muted">Next</span>
                @endif
            </nav>
        @endif
    </div>
</section>
@endsection
