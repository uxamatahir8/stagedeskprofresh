@extends('layouts.web')

@section('content')
    <!--===== WELCOME STARTS =======-->
    <div class="about-welcome-section-area about2"
        style="background-image: url({{ asset('landing/images/background/header2-bg.png') }}); background-repeat: no-repeat; background-size: cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 m-auto">
                    <div class="about-welcome-header text-center heading3">
                        <h2 class="text-light">{{ $blog->title }}</h2>
                        <div class="space16"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===== WELCOME ENDS =======-->

    <!--===== BLOG AREA STARTS =======-->
    <div class="blog-right-details-area sp3">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 m-auto">
                    <div class="blog-right-area heading6 p-0">
                        <div class="img1-inner">
                            <img src="{{ asset('storage/' . $blog->image ?? '') }}" alt="">
                        </div>
                        <div class="space50"></div>
                        {!! $blog->content !!}
                        <div class="space50"></div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== BLOG PLAN AREA ENDS =======-->
    </div>
@endsection