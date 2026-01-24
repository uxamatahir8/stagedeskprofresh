@extends('layouts.web')

@section('content')

    <style>
        .pagination .page-link.active {
            background-color: #000;
            color: #fff;
            border-color: #000;
        }
    </style>
    <!--===== WELCOME STARTS =======-->
    <div class="about-welcome-section-area about2"
        style="background-image: url({{ asset('landing/images/background/header2-bg.png') }}); background-repeat: no-repeat; background-size: cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 m-auto">
                    <div class="about-welcome-header text-center heading3">
                        <h1>{{ $title }}</h1>
                        <div class="space16"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===== WELCOME ENDS =======-->

    <!--===== BLOG INNER STARTS =======-->
    <div class="blog-inner-section inner-section2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 m-auto">
                    <div class="blog-inner-content">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="blog-inner-header heading6">
                                    <!-- ✅ Updated subtitle -->
                                    <span>{{ $title }}</span>
                                    <div class="space16"></div>

                                    <!-- ✅ Replaced headline with StageDesk Pro–specific title -->
                                    <h2>Latest Articles on Event Management & Company Growth</h2>
                                </div>
                            </div>

                            <div class="col-lg-2"></div>

                            <div class="col-lg-5">
                                <div class="content-area heading6">
                                    <!-- ✅ Updated paragraph to describe StageDesk Pro’s content theme -->
                                    <p>
                                        Discover expert tips, industry trends, and success stories from event companies,
                                        DJs,
                                        and affiliates using StageDesk Pro. Stay ahead with guidance on efficient bookings,
                                        automated workflows, and insights that help your entertainment business grow.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="space60"></div>
                        <div class="row">
                            @if (!$blogs->isEmpty())
                                @if (isset($category))
                                    <div class="text-center mb-4">
                                        <h4>Showing blogs in category: <strong>{{ $category->name }}</strong></h4>
                                        <a href="{{ route('blogs') }}" class="header-btn7 mt-3">View All
                                            Blogs</a>
                                    </div>
                                @endif
                                @foreach ($blogs as $blog)
                                    <div class="col-lg-6 col-md-6">
                                        <div class="blog-inner-boxarea">
                                            <div class="images position-relative">
                                                <img src="{{ asset('storage/' . ($blog->feature_image ?? $blog->image ?? '')) }}"
                                                    alt="{{ $blog->title }}" style="width: 100%; height: 300px; object-fit: cover;">

                                                @if($blog->is_featured)
                                                    <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-3">
                                                        <i class="fa-solid fa-star"></i> Featured
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="space24"></div>
                                            <div class="content tags">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <a href="{{ url('blogs?category=' . $blog->blog_category_id) }}"
                                                        class="text-decoration-none">
                                                        <span>{{ $blog->category->name }}</span>
                                                    </a>
                                                    <small class="text-muted">
                                                        @if($blog->reading_time)
                                                            <i class="fa-solid fa-clock"></i> {{ $blog->reading_time }} min read
                                                        @endif
                                                    </small>
                                                </div>

                                                <div class="space8"></div>
                                                <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>

                                                @if($blog->excerpt)
                                                    <div class="space16"></div>
                                                    <p>{{ Str::limit($blog->excerpt, 120) }}</p>
                                                @else
                                                    <div class="space16"></div>
                                                    <p>{!! Str::words(strip_tags($blog->content), 15, '...') !!}</p>
                                                @endif

                                                <div class="space16"></div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-muted small">
                                                        <i class="fa-solid fa-eye"></i> {{ $blog->views_count ?? 0 }} views
                                                        <span class="mx-2">|</span>
                                                        <i class="fa-solid fa-comment"></i> {{ $blog->approvedComments->count() }} comments
                                                    </div>
                                                    <small class="text-muted">
                                                        <i class="fa-solid fa-calendar"></i>
                                                        {{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('M d, Y') : \Carbon\Carbon::parse($blog->created_at)->format('M d, Y') }}
                                                    </small>
                                                </div>

                                                @if($blog->tags && count($blog->tags) > 0)
                                                    <div class="space16"></div>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach(array_slice($blog->tags, 0, 3) as $tag)
                                                            <span class="badge bg-secondary">{{ $tag }}</span>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <div class="space24"></div>
                                                <a href="{{ route('blog.show', $blog->slug) }}" class="readmore">Learn
                                                    More <i class="fa-solid fa-arrow-right"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-lg-12">
                                    <div class="space20"></div>
                                    <div class="pagination-area">
                                        <nav aria-label="Page navigation example">
                                            <ul class="pagination justify-content-center">

                                                {{-- Previous Page Link --}}
                                                @if ($blogs->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#" aria-label="Previous">
                                                            <span aria-hidden="true"><i
                                                                    class="fa-solid fa-angle-left"></i></span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $blogs->previousPageUrl() }}"
                                                            aria-label="Previous">
                                                            <span aria-hidden="true"><i
                                                                    class="fa-solid fa-angle-left"></i></span>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach ($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                                                    @if ($page == $blogs->currentPage())
                                                        <li class="page-item"><a class="page-link active"
                                                                href="#">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                                        </li>
                                                    @else
                                                        <li class="page-item"><a class="page-link"
                                                                href="{{ $url }}">{{ str_pad($page, 2, '0', STR_PAD_LEFT) }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                {{-- Next Page Link --}}
                                                @if ($blogs->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $blogs->nextPageUrl() }}"
                                                            aria-label="Next">
                                                            <span aria-hidden="true"><i
                                                                    class="fa-solid fa-angle-right"></i></span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled">
                                                        <a class="page-link" href="#" aria-label="Next">
                                                            <span aria-hidden="true"><i
                                                                    class="fa-solid fa-angle-right"></i></span>
                                                        </a>
                                                    </li>
                                                @endif

                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    @if (isset($category))
                                        <h4>No blogs found in category: <strong>{{ $category->name ?? '' }}</strong></h4>
                                        <a href="{{ route('blogs') }}" class="header-btn7 mt-3">View All
                                            Blogs</a>
                                    @else
                                        <h4>No blogs found</strong></h4>
                                        <a href="{{ route('home') }}" class="header-btn7 mt-3">Home</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===== BLOG INNER ENDS =======-->

@endsection
