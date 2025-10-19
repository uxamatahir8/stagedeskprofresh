<!DOCTYPE html>
<html lang="en">

@include('includes.head')

<body>
    <!--===== PRELOADER STARTS =======-->
    <div id="preloader">
        <div class="preloader4">
            <span></span>
            <span></span>
        </div>
    </div>
    <!--===== PRELOADER ENDS =======-->

    <!--===== PAGE PROGRESS START=======-->
    <div class="paginacontainer">
        <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
        </div>
    </div>
    <!--===== PAGE PROGRESS END=======-->

    @include('includes.header')


    @include('includes.mobile_header')

    <!--===== WELCOME STARTS =======-->
    <div class="welcome4-section-area" style="background-image: url({{ asset('landing/images/background/header4-bg.png') }});
        background-position: center; background-size: cover; width: 100%; height: 100%;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="welcome4-header heading5">
                        <span data-aos="fade-up" data-aos-duration="700"><img
                                src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Social Media Schedule
                            Platform.</span>
                        <div class="space20"></div>
                        <h1 data-aos="fade-up" data-aos-duration="1000">Success Social Media Management & Scheduling
                        </h1>
                        <div class="space16"></div>
                        <p data-aos="fade-up" data-aos-duration="1000">Welcome to the ultimate hub for social media
                            mastery. Our platform offers comprehensive solutions for managing and scheduling your social
                            media presence with ease and efficiency.</p>
                        <div class="space32"></div>
                        <div class="btn-area" data-aos="fade-up" data-aos-duration="1200">
                            <a href="javascript:void(0)" class="header-btn7">Schedule a Consultation</a>
                            <a href="https://www.youtube.com/watch?v=Y8XpQpW5OVY" class="header-btn6 popup-youtube"> <i
                                    class="fa-solid fa-play"></i>Watch Demo Video</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="welcome4-images-area" data-aos="zoom-out" data-aos-duration="1000">
                        <div class="main-img">
                            <img src="{{ asset('landing/images/all-images/header4-main-img.png') }}" alt="">
                            <img src="{{ asset('landing/images/background/header4-bg2.png') }}" alt=""
                                class="header4-bg2 aniamtion-key-5">
                        </div>
                        <img src="{{ asset('landing/images/all-images/header4-img1.png') }}" alt=""
                            class="header4-img1 aniamtion-key-2">
                        <img src="{{ asset('landing/images/all-images/header4-img2.png') }}" alt=""
                            class="header4-img2 aniamtion-key-2">
                        <img src="{{ asset('landing/images/elements/header4-elements.jpg') }}" alt=""
                            class="header4-elements keyframe5">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===== WELCOME ENDS =======-->
    <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%"
        data-bs-smooth-scroll="true" class="scrollspy-example bg-body-tertiary p-3 rounded-2" tabindex="0">

        <!--===== WORKS AREA STARTS =======-->
        <div class="works4-section-area sp3" id="works">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-auto">
                        <div class="works4-header text-center heading6 tags2">
                            <span data-aos="fade-up" data-aos-duration="800"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">How It Works</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000">How Social Media Management & Scheduling
                                Works</h2>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="sticky-section-area">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="images">
                                        <img src="{{ asset('landing/images/all-images/scroll-img1.png') }}" alt="">
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="sticky-text text2 heading6">
                                        <h3>01</h3>
                                        <div class="space16"></div>
                                        <h2>Scheduling and Posting</h2>
                                        <div class="space24"></div>
                                        <p>Utilizing advanced scheduling tools, we'll schedule posts at optimal times to
                                            maximize reach and engagement</p>
                                    </div>

                                    <div class="sticky-text heading6">
                                        <h3>02</h3>
                                        <div class="space16"></div>
                                        <h2>Community Engagement</h2>
                                        <div class="space24"></div>
                                        <p>We actively engage with your audience by responding to comments, messages,
                                            and mentions in a timely and authentic manner.</p>
                                    </div>

                                    <div class="sticky-text heading6">
                                        <h3>03</h3>
                                        <div class="space16"></div>
                                        <h2>Analytics & Engagement</h2>
                                        <div class="space24"></div>
                                        <p>Performance Tracking: Comprehensive analytics and reporting to track the
                                            success your social media campaigns initiatives.</p>
                                    </div>

                                    <div class="sticky-text heading6">
                                        <h3>04</h3>
                                        <div class="space16"></div>
                                        <h2>Content Creation</h2>
                                        <div class="space24"></div>
                                        <p>Compelling Visuals Professionally designed graphics, images, and videos to
                                            captivate your audience.</p>
                                    </div>
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== WORKS AREA ENDS =======-->

        <!--===== BRAND AREA STARTS =======-->
        <div class="brand4-section-area sp3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-auto">
                        <div class="brand4-header tags2 heading6 text-center">
                            <span data-aos="fade-up" data-aos-duration="800"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Our Trusted
                                Brand</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000">Trusted by Thousands Our Brand's Legacy of
                                Excellence</h2>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="brand2-logos">
                            <div class="brand2-logo" data-aos="fade-right" data-aos-duration="1000">
                                <img src="{{ asset('landing/images/elements/brand2-logo1.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo2.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo3.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo4.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo5.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo1.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo2.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo3.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo4.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo5.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo1.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo2.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo3.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo4.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo5.png') }}" alt="">
                            </div>
                        </div>
                        <div class="space24"></div>
                        <div class="brand3-logos" data-aos="fade-left" data-aos-duration="800">
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo1.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo2.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo3.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo4.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-log5.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo1.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo2.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo3.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand2-logo4.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-log5.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo1.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo2.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo3.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-logo4.png') }}" alt="">
                            </div>
                            <div class="brand2-logo">
                                <img src="{{ asset('landing/images/elements/brand4-log5.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== BRAND AREA ENDS =======-->

        <!--===== SERVICE AREA STARTS =======-->
        <div class="marketing-section-area sp3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        <div class="marketing-header tags2 heading6 text-center">
                            <span data-aos="fade-up" data-aos-duration="800"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Social Media
                                Marketing</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000"> Our Proven Social Media Strategy Process
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="marketing-img" data-aos="flip-left" data-aos-duration="1000">
                            <img src="{{ asset('landing/images/all-images/marketing-img1.png') }}" alt="">
                            <img src="{{ asset('landing/images/background/marketing-bg.png') }}" alt=""
                                class="marketing-bg">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="tabs-text-area" data-aos="fade-up" data-aos-duration="800">
                            <div class="tabs-icon">
                                <img src="{{ asset('landing/images/icons/service-icon1.svg') }}" alt="">
                            </div>
                            <div class="tabs-text">
                                <a href="features.html">Choose The Right Platform</a>
                                <p>Tailor your content to fit the format and audience preferences of each platform.</p>
                            </div>
                        </div>
                        <div class="space20"> </div>
                        <div class="tabs-text-area" data-aos="fade-up" data-aos-duration="1000">
                            <div class="tabs-icon1">
                                <img src="{{ asset('landing/images/icons/service-icon2.svg') }}" alt="">
                            </div>
                            <div class="tabs-text">
                                <a href="features.html">Create Engaging Content</a>
                                <p>Use attention-grabbing visuals, such as images, videos, or infographics, make your
                                    content stand out.</p>
                            </div>
                        </div>
                        <div class="space20"></div>
                        <div class="tabs-text-area" data-aos="fade-up" data-aos-duration="1200">
                            <div class="tabs-icon2">
                                <img src="{{ asset('landing/images/icons/service-icon3.svg') }}" alt="">
                            </div>
                            <div class="tabs-text">
                                <a href="features.html">Utilise Hashtags & Keyword </a>
                                <p>Research relevant hashtags and keywords to increase the discoverability of your
                                    posts.</p>
                            </div>
                        </div>
                        <div class="space20"></div>
                        <div class="tabs-text-area" data-aos="fade-up" data-aos-duration="1200">
                            <div class="tabs-icon3">
                                <img src="{{ asset('landing/images/icons/service-icons4.svg') }}" alt="">
                            </div>
                            <div class="tabs-text">
                                <a href="features.html">Promote User Generated Content</a>
                                <p>Encourage your audience to create and share content related to your brand, products,
                                    or services.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== SERVICE AREA ENDS =======-->

        <!--===== OTHERS AREA STARTS =======-->
        <div class="others4-section-area sp3" id="features">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <div class="others-collaborate-area heading6 tags2 ">
                            <span data-aos="fade-right" data-aos-duration="600"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Collaborate</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-right" data-aos-duration="700">Collective Collaborative Social Media
                                Mastery</h2>
                            <div class="space32"></div>
                            <div class="listarea" data-aos="fade-right" data-aos-duration="800">
                                <div><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt=""></div>
                                <div>
                                    <p><span>Brainstorming Sessions: </span> We can schedule regular brainstorming
                                        sessions to generate creative ideas for social media content.</p>
                                </div>
                            </div>
                            <div class="space24"></div>
                            <div class="listarea" data-aos="fade-right" data-aos-duration="900">
                                <div><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt=""></div>
                                <div>
                                    <p><span>Content Creation:</span>We can collaborate on content creation by dividing
                                        tasks basedour on our strengths and areas of expertise. </p>
                                </div>
                            </div>
                            <div class="space24"></div>
                            <div class="listarea" data-aos="fade-right" data-aos-duration="1000">
                                <div><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt=""></div>
                                <div>
                                    <p><span>Data Analysis: </span>After launching campaigns, we can collaborate on
                                        analyzing performance data to measure the effectiveness.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="collaborate-img" data-aos="flip-right" data-aos-duration="1000">
                            <img src="{{ asset('landing/images/all-images/others-img5.png') }}" alt="">
                        </div>
                    </div>
                </div>
                <div class="space60 d-lg-block d-none"></div>
                <div class="row align-items-center">
                    <div class="col-lg-7">
                        <div class="collaborate-img img2" data-aos="flip-right" data-aos-duration="1000">
                            <img src="{{ asset('landing/images/all-images/others-img6.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="others-collaborate-area heading6 tags2 ">
                            <span data-aos="fade-right" data-aos-duration="600"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Collaborate</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-right" data-aos-duration="700">Elevating Social Media Strategies With
                                Quad</h2>
                            <div class="space32"></div>
                            <div class="listarea" data-aos="fade-right" data-aos-duration="800">
                                <div><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt=""></div>
                                <div>
                                    <p><span>Campaign Planning: </span>We can collaborate on planning and executing
                                        social media marketing campaigns. This includes setting goals.</p>
                                </div>
                            </div>
                            <div class="space24"></div>
                            <div class="listarea" data-aos="fade-right" data-aos-duration="900">
                                <div><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt=""></div>
                                <div>
                                    <p><span>Feedback & Iteration: </span>Collaboration also involves providing
                                        constructive feedback and iterating on our strategies.</p>
                                </div>
                            </div>
                            <div class="space24"></div>
                            <div class="listarea" data-aos="fade-right" data-aos-duration="1000">
                                <div><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt=""></div>
                                <div>
                                    <p><span>Data Analysis: </span>After launching campaigns, we can collaborate on
                                        analyzing performance data to measure the effectiveness.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== OTHERS AREA ENDS =======-->

        <!--===== PRICING PLAN AREA STARTS =======-->
        <div class="pricing-paln-section-area pricicng-paln3 sp4">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 m-auto">
                        <div class="pricing-header text-center heading6 tags2">
                            <span data-aos="fade-up" data-aos-duration="800"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Pricing Plan</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000" class="text-capitalize">Choose Your
                                Subscription</h2>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="pricing-plans">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="plan-toggle-wrap">
                                    <div class="toggle-inner" data-aos="zoom-out" data-aos-duration="1000">
                                        <input id="ce-toggle" checked="" type="checkbox">
                                        <span class="custom-toggle"></span>
                                        <span class="t-month">Monthly</span>
                                        <span class="t-year">Annual</span>
                                    </div>
                                </div>
                                <div class="space40"></div>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div id="monthly" style="display: block;">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4" data-aos="flip-right" data-aos-duration="1000">
                                        <div class="price-box">
                                            <div class="hadding">
                                                <div class="pricing-area">
                                                    <h3>Basic Plan</h3>
                                                    <div class="space24"></div>
                                                    <h2 class="pricing-heading">$9.9<span>/month</span></h2>
                                                    <div class="space16"></div>
                                                    <p>Ideal for individuals or small businesses looking to establish a
                                                        solid online presence.</p>
                                                    <div class="space24"></div>
                                                    <ul>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Unlimited posts</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">10
                                                            days planning period</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">2
                                                            account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            project</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            RSS-feed</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/red-exit.svg" alt="">You can
                                                            buy additional account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/red-exit.svg" alt="">DM&
                                                            comments</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/red-exit.svg"
                                                                alt="">Collaboration roles and tools</li>
                                                    </ul>
                                                    <div class="space32"></div>
                                                    <div>
                                                        <a href="pricing-plan.html" class="header-btn7">Quad Compare
                                                            plan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4" data-aos="flip-left" data-aos-duration="1200">
                                        <div class="price-box">
                                            <div class="hadding">
                                                <div class="pricing-area bg2">
                                                    <div class="popular">
                                                        <p>Most Popular</p>
                                                    </div>
                                                    <h3>Pro Plan</h3>
                                                    <div class="space24"></div>
                                                    <h2 class="pricing-heading">$12.9<span>/month</span></h2>
                                                    <div class="space16"></div>
                                                    <p>Ideal for individuals or small businesses looking to establish a
                                                        solid online presence.</p>
                                                    <div class="space24"></div>
                                                    <ul>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Unlimited posts</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">10
                                                            days planning period</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">2
                                                            account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            project</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            RSS-feed</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">You
                                                            can buy additional account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">DM&
                                                            comments</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Collaboration roles and tools</li>
                                                    </ul>
                                                    <div class="space32"></div>
                                                    <div>
                                                        <a href="pricing-plan.html" class="header-btn7">Quad Compare
                                                            plan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4" data-aos="flip-right" data-aos-duration="1400">
                                        <div class="price-box">
                                            <div class="hadding">
                                                <div class="pricing-area">
                                                    <h3>Bussiness Plan</h3>
                                                    <div class="space24"></div>
                                                    <h2 class="pricing-heading">$19.9<span>/month</span></h2>
                                                    <div class="space16"></div>
                                                    <p>Ideal for individuals or small businesses looking to establish a
                                                        solid online presence.</p>
                                                    <div class="space24"></div>
                                                    <ul>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Unlimited posts</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">10
                                                            days planning period</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">2
                                                            account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            project</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            RSS-feed</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">You
                                                            can buy additional account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">DM&
                                                            comments</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Collaboration roles and tools</li>
                                                    </ul>
                                                    <div class="space32"></div>
                                                    <div>
                                                        <a href="pricing-plan.html" class="header-btn7">Quad Compare
                                                            plan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="yearly" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6 col-lg-4" data-aos="flip-right" data-aos-duration="1000">
                                        <div class="price-box">
                                            <div class="hadding">
                                                <div class="pricing-area">
                                                    <h3>Basic Plan</h3>
                                                    <div class="space24"></div>
                                                    <h2 class="pricing-heading">$99.9<span>/Year</span></h2>
                                                    <div class="space16"></div>
                                                    <p>Ideal for individuals or small businesses looking to establish a
                                                        solid online presence.</p>
                                                    <div class="space24"></div>
                                                    <ul>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Unlimited posts</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">10
                                                            days planning period</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">2
                                                            account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            project</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            RSS-feed</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/red-exit.svg" alt="">You can
                                                            buy additional account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/red-exit.svg" alt="">DM&
                                                            comments</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/red-exit.svg"
                                                                alt="">Collaboration roles and tools</li>
                                                    </ul>
                                                    <div class="space32"></div>
                                                    <div>
                                                        <a href="pricing-plan.html" class="header-btn7">Quad Compare
                                                            plan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4" data-aos="flip-left" data-aos-duration="1200">
                                        <div class="price-box">
                                            <div class="hadding">
                                                <div class="pricing-area bg2">
                                                    <div class="popular">
                                                        <p>Most Popular</p>
                                                    </div>
                                                    <h3>Pro Plan</h3>
                                                    <div class="space24"></div>
                                                    <h2 class="pricing-heading">$199.9<span>/Year</span></h2>
                                                    <div class="space16"></div>
                                                    <p>Ideal for individuals or small businesses looking to establish a
                                                        solid online presence.</p>
                                                    <div class="space24"></div>
                                                    <ul>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Unlimited posts</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">10
                                                            days planning period</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">2
                                                            account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            project</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            RSS-feed</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">You
                                                            can buy additional account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">DM&
                                                            comments</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Collaboration roles and tools</li>
                                                    </ul>
                                                    <div class="space32"></div>
                                                    <div>
                                                        <a href="pricing-plan.html" class="header-btn7">Quad Compare
                                                            plan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4" data-aos="flip-right" data-aos-duration="1400">
                                        <div class="price-box">
                                            <div class="hadding">
                                                <div class="pricing-area">
                                                    <h3>Bussiness Plan</h3>
                                                    <div class="space24"></div>
                                                    <h2 class="pricing-heading">$39.9<span>/Year</span></h2>
                                                    <div class="space16"></div>
                                                    <p>Ideal for individuals or small businesses looking to establish a
                                                        solid online presence.</p>
                                                    <div class="space24"></div>
                                                    <ul>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Unlimited posts</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">10
                                                            days planning period</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">2
                                                            account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            project</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">1
                                                            RSS-feed</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">You
                                                            can buy additional account</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg" alt="">DM&
                                                            comments</li>
                                                        <li class="space16"></li>
                                                        <li><img src="assets/images/icons/check-green.svg"
                                                                alt="">Collaboration roles and tools</li>
                                                    </ul>
                                                    <div class="space32"></div>
                                                    <div>
                                                        <a href="pricing-plan.html" class="header-btn7">Quad Compare
                                                            plan</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== PRICING PLAN AREA ENDS =======-->

        <!--===== OTHERS AREA STARTS =======-->
        <div class="others5-section-area sp3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-auto">
                        <div class="social-area-header heading6 tags2 text-center">
                            <span data-aos="fade-up" data-aos-duration="800"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Integration</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000" class="text-capitalize">We’re a Meta
                                Business Partner</h2>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="all-social-area">
                            <div class="facebook" data-aos="zoom-in" data-aos-duration="800">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/facebook.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Facebook</a>
                            </div>
                            <div class="instagram" data-aos="zoom-in" data-aos-duration="900">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/instagram2.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Instagram</a>
                            </div>
                            <div class="behance" data-aos="zoom-in" data-aos-duration="900">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/behance.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Behance</a>
                            </div>
                            <div class="twitter" data-aos="zoom-in" data-aos-duration="1000">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/twitter.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Twitter</a>
                            </div>
                            <div class="telegram" data-aos="zoom-in" data-aos-duration="1100">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/telegram.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Telegram</a>
                            </div>
                            <div class="tiktok" data-aos="zoom-in" data-aos-duration="1200">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/tiktok.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Tiktok</a>
                            </div>
                            <div class="youtube" data-aos="zoom-in" data-aos-duration="800">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/youtube.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Youtube</a>
                            </div>
                            <div class="behance" data-aos="zoom-in" data-aos-duration="900">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/behance.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Behance</a>
                            </div>
                        </div>
                    </div>
                    <div class="space50"></div>
                    <div class="col-lg-12">
                        <div class="all-social-area">
                            <div class="pinterest" data-aos="zoom-in" data-aos-duration="1000">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/pinterest.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Pinterest</a>
                            </div>
                            <div class="linkedin" data-aos="zoom-in" data-aos-duration="1000">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/linkedin.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Linkedin</a>
                            </div>

                            <div class="instagram" data-aos="zoom-in" data-aos-duration="1100">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/massenger.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Messenger</a>
                            </div>
                            <div class="github" data-aos="zoom-out" data-aos-duration="1200">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/github.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Github</a>
                            </div>
                            <div class="gtp" data-aos="zoom-out" data-aos-duration="800">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/gpt.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Chat GPT</a>
                            </div>
                            <div class="reddit" data-aos="zoom-out" data-aos-duration="900">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/reddit.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Reddit</a>
                            </div>
                            <div class="paypal" data-aos="zoom-out" data-aos-duration="1000">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/paypal.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Paypal</a>
                            </div>
                            <div class="thumb" data-aos="zoom-out" data-aos-duration="1100">
                                <div class="icons1">
                                    <a href="#"><img src="assets/images/icons/thumb.svg" alt=""></a>
                                </div>
                                <div class="space20"></div>
                                <a class="link" href="#">Paypal</a>
                            </div>
                        </div>
                    </div>
                    <div class="space50"></div>
                    <div class="col-lg-12">
                        <div class="text-center" data-aos="fade-up" data-aos-duration="1200">
                            <a href="download.html" class="header-btn7">Browse All Integration</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== OTHERS AREA ENDS =======-->

        <!--===== TESTIMONIAL AREA STARTS =======-->
        <div class="testimonial-carousel-area sp3" id="testimonials">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <div class="tastemonial-box-area">
                            <img src="assets/images/icons/pinterest1.svg" alt="" class="pinterest1"
                                data-aos="fade-right" data-aos-duration="1000">
                            <img src="assets/images/background/testimonial.png" alt="" class="testimonial"
                                data-aos="zoom-out" data-aos-duration="800">
                            <div class="row">
                                <div class="col-lg-8 m-auto">
                                    <div class="testimonial-main-box heading6 owl-carousel">
                                        <div class="testimonial-content">
                                            <div class="img1 text-center">
                                                <img src="assets/images/icons/quito-img1.svg" alt="">
                                            </div>
                                            <div class="space16"></div>
                                            <h2 class="text-center">Client Success Stories </h2>
                                            <div class="space32"></div>
                                            <p class="text-center">"Working with Quad has been a game-changer for our
                                                social media presence. Their team's expertise and dedication have
                                                significantly boosted our engagement and brand visibility. Highly
                                                recommend their services!"</p>
                                            <div class="space32"></div>
                                            <div class="team-details">
                                                <div class="img1">
                                                    <img src="assets/images/all-images/testimonial4-img1.png" alt="">
                                                </div>
                                                <div class="content">
                                                    <a href="team.html">Adam Smith</a>
                                                    <p>Owner At Frenzy Design</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="testimonial-content">
                                            <div class="img1 text-center">
                                                <img src="assets/images/icons/quito-img1.svg" alt="">
                                            </div>
                                            <div class="space16"></div>
                                            <h2 class="text-center">Client Success Stories </h2>
                                            <div class="space32"></div>
                                            <p class="text-center">"Working with Quad has been a game-changer for our
                                                social media presence. Their team's expertise and dedication have
                                                significantly boosted our engagement and brand visibility. Highly
                                                recommend their services!"</p>
                                            <div class="space32"></div>
                                            <div class="team-details">
                                                <div class="img1">
                                                    <img src="assets/images/all-images/testimonial4-img1.png" alt="">
                                                </div>
                                                <div class="content">
                                                    <a href="team.html">Adam Smith</a>
                                                    <p>Owner At Frenzy Design</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="testimonial-content">
                                            <div class="img1 text-center">
                                                <img src="assets/images/icons/quito-img1.svg" alt="">
                                            </div>
                                            <div class="space16"></div>
                                            <h2 class="text-center">Client Success Stories </h2>
                                            <div class="space32"></div>
                                            <p class="text-center">"Working with Quad has been a game-changer for our
                                                social media presence. Their team's expertise and dedication have
                                                significantly boosted our engagement and brand visibility. Highly
                                                recommend their services!"</p>
                                            <div class="space32"></div>
                                            <div class="team-details">
                                                <div class="img1">
                                                    <img src="assets/images/all-images/testimonial4-img1.png" alt="">
                                                </div>
                                                <div class="content">
                                                    <a href="team.html">Adam Smith</a>
                                                    <p>Owner At Frenzy Design</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <img src="assets/images/icons/instagram3.svg" alt="" class="instagram3" data-aos="fade-left"
                                data-aos-duration="1000">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== TESTIMONIAL AREA ENDS =======-->

        <!--===== BLOG AREA STARTS =======-->
        <div class="blog4-section-area sp6" id="blog">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-auto">
                        <div class="blog4-header tags2 heading6 text-center">
                            <span data-aos="fade-up" data-aos-duration="800"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Blog</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000">Our Latest Blog & Articles</h2>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-6" data-aos="flip-left" data-aos-duration="800">
                        <div class="blog4-boxarea">
                            <div class="img1">
                                <img src="assets/images/all-images/blog4-img1.png" alt="">
                            </div>
                            <div class="blog-content">
                                <div class="date">
                                    <div class="social">
                                        <a href="blog-details.html">Social Media</a>
                                    </div>
                                    <div class="date-day">
                                        <a href="#"><img src="assets/images/icons/clock-img1.svg" alt="">Oct 15,2023</a>
                                    </div>
                                </div>
                                <div class="space16"></div>
                                <a href="blog-details.html">The Importance of Social Media Management for Businesses</a>
                                <div class="space16"></div>
                                <p>Discuss why businesses need to actively manage their social media accounts.</p>
                                <div class="space20"></div>
                                <a href="blog-details.html" class="readmore">Read More <i
                                        class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6" data-aos="flip-right" data-aos-duration="1000">
                        <div class="blog4-boxarea">
                            <div class="img1">
                                <img src="assets/images/all-images/blog4-img2.png" alt="">
                            </div>
                            <div class="blog-content">
                                <div class="date">
                                    <div class="social">
                                        <a href="#">Content</a>
                                    </div>
                                    <div class="date-day">
                                        <a href="#"><img src="assets/images/icons/clock-img1.svg" alt="">Oct 15,2023</a>
                                    </div>
                                </div>
                                <div class="space16"></div>
                                <a href="blog-details.html">Effective Strategies for Social Media Content Planning</a>
                                <div class="space16"></div>
                                <p>Discuss why businesses need to actively manage their social media accounts.</p>
                                <div class="space20"></div>
                                <a href="blog-details.html" class="readmore">Read More <i
                                        class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6" data-aos="flip-left" data-aos-duration="1200">
                        <div class="blog4-boxarea">
                            <div class="img1">
                                <img src="assets/images/all-images/blog4-img3.png" alt="">
                            </div>
                            <div class="blog-content">
                                <div class="date">
                                    <div class="social">
                                        <a href="blog-details.html">Engaging</a>
                                    </div>
                                    <div class="date-day">
                                        <a href="#"><img src="assets/images/icons/clock-img1.svg" alt="">Oct 15,2023</a>
                                    </div>
                                </div>
                                <div class="space16"></div>
                                <a href="blog-details.html">How to Create Engaging Social Media Content Management</a>
                                <div class="space16"></div>
                                <p>Discuss why businesses need to actively manage their social media accounts.</p>
                                <div class="space20"></div>
                                <a href="blog-details.html" class="readmore">Read More <i
                                        class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== BLOG AREA ENDS =======-->
    </div>
    <!--===== CTA AREA STARTS =======-->
    <div class="cta4-section-area sp3"
        style="background-image: url(assets/images/all-images/cta-btg2.png); background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cta-bg-area">
                        <div class="row">
                            <div class="col-lg-8 m-auto">
                                <div class="cta-content-area text-center heading6">
                                    <h2 data-aos="zoom-out" data-aos-duration="800">You Have Many Things to Do. Let Us
                                        Help You With Social Media</h2>
                                    <div class="space16"></div>
                                    <p data-aos="zoom-out" data-aos-duration="1000">Whether you're looking to boost
                                        engagement, increase brand visibility, or drive sales, our team is here to help
                                        you achieve your social media goals. Schedule a consultation today to learn how
                                        our tailored solutions can transform your online presence."</p>
                                    <div class="space32"></div>
                                    <div class="btn-area" data-aos="zoom-out" data-aos-duration="1200">
                                        <a href="contact.html" class="header-btn7">Get Started Now</a>
                                        <a href="pricing-plan.html" class="header-btn8">15 Days Free Trails</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===== CTA AREA ENDS =======-->


    <!--===== FOOTER AREA STARTS =======-->
    <div class="footer2-section-area footer4-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer2-last-section">
                        <div class="row">
                            <div class="col-lg col-md-6 col-12">
                                <div class="footer-auhtor-area">
                                    <img src="assets/images/logo/logo5.png" alt="">
                                    <p>Tailor our Project <br class="d-lg-block d-none"> Management Software to <br
                                            class="d-lg-block d-none"> fit your unique processes.</p>
                                    <ul class="social-links">
                                        <li><a href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-pinterest-p"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-youtube"></i></a></li>
                                        <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg col-md-6 col-12">
                                <div class="footer-auhtor-area">
                                    <h3>Features</h3>
                                    <ul>
                                        <li><a href="#">Docs</a></li>
                                        <li><a href="#">Integrations</a></li>
                                        <li><a href="#">Automations</a></li>
                                        <li><a href="#">Files</a></li>
                                        <li><a href="#">Dashboards</a></li>
                                        <li><a href="#">Kanban</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg col-md-6 col-12">
                                <div class="footer-auhtor-area">
                                    <h3>Use Cases</h3>
                                    <ul>
                                        <li><a href="#">Marketing</a></li>
                                        <li><a href="#">Project</a></li>
                                        <li><a href="#">Management</a></li>
                                        <li><a href="#">Sales</a></li>
                                        <li><a href="#">Developers</a></li>
                                        <li><a href="#">Construction</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg col-md-6 col-12">
                                <div class="footer-auhtor-area">
                                    <h3>Company</h3>
                                    <ul>
                                        <li><a href="about.html">About</a></li>
                                        <li><a href="#">Customer Stories</a></li>
                                        <li><a href="#">Become a Partner</a></li>
                                        <li><a href="#">Become a Partner</a></li>
                                        <li><a href="#">Emergency Response</a></li>
                                        <li><a href="#">Quad-U</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-lg col-md-6 col-12">
                                <div class="footer-auhtor-area">
                                    <h3>Resources</h3>
                                    <ul>
                                        <li><a href="#">Community</a></li>
                                        <li><a href="blog-details.html">Blog</a></li>
                                        <li><a href="#">Academy</a></li>
                                        <li><a href="#">App Development</a></li>
                                        <li><a href="#">Sass & Startup</a></li>
                                        <li><a href="#">Find a Partner</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="copyright-social-area">
                                    <ul>
                                        <li class="pera">
                                            <p>Copyright @2024 Quad. All Right Reserved</p>
                                        </li>
                                    </ul>
                                    <ul>
                                        <li><a>Your Privacy</a></li>
                                        <li class="pera"><a>Terms</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===== FOOTER ENDS =======-->

    <!--=====JS=======-->
    <script src="{{ asset('landing/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/swiper.bundle.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/mobilemenu.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/slick-slider.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/owlcarousel.min.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/counter.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/waypoints.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/aos.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/gsap.min.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/magnific-popup.js') }}"></script>
    <script src="{{ asset('landing/js/plugins/ScrollTrigger.min.js') }}"></script>
    <script src="{{ asset('landing/js/main.js') }}"></script>
</body>

</html>