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
                                            <div class="images">
                                                <img src="{{ asset('storage/' . $blog->feature_image ?? '') }}"
                                                    alt="">
                                            </div>
                                            <div class="space24"></div>
                                            <div class="content tags">
                                                <a href="{{ url('blogs/' . strtolower($blog->category->name)) }}"
                                                    class="text-decoration-none">
                                                    <span>{{ $blog->category->name }}</span>
                                                </a>

                                                <div class="space16"></div>
                                                <a href="{{ route('blog.details', $blog->slug) }}">{{ $blog->title }}</a>
                                                <div class="space16"></div>
                                                <p>{!! Str::words($blog->content, 11, '...') !!}</p>
                                                <div class="space24"></div>
                                                <a href="{{ route('blog.details', $blog->slug) }}" class="readmore">Learn
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
