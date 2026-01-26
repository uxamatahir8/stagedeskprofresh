@extends('layouts.web')

@section('content')


    <!--===== WELCOME STARTS =======-->
    <div class="welcome4-section-area"
        style="background-image: url({{ asset('landing/images/background/header4-bg.png') }}); background-position: center; background-size: cover; width: 100%; height: 100%;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="welcome4-header heading5">
                        <!-- Updated Title and Intro according to StageDesk Pro -->
                        <span data-aos="fade-up" data-aos-duration="700"><img
                                src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">StageDesk Pro
                            Platform</span>
                        <div class="space20"></div>
                        <h1 data-aos="fade-up" data-aos-duration="1000">Complete DJ Booking & Company Management Solution
                        </h1>
                        <div class="space16"></div>
                        <p data-aos="fade-up" data-aos-duration="1000">
                            StageDesk Pro is a full-fledged platform designed for event companies, DJs, and customers.
                            It streamlines company registrations, DJ management, bookings, and affiliate partnerships — all
                            under one secure, modern system.
                        </p>
                        <div class="space32"></div>
                        <div class="btn-area" data-aos="fade-up" data-aos-duration="1200">

                            <!-- Button for customers -->
                            <a href="{{ route('register') }}" class="header-btn6">Book Your DJ Now</a>

                            <!-- Button for companies -->
                            <a href="{{ route('register') }}" class="header-btn7">Register Your Company</a>

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

        <!--===== HOW IT WORKS AREA STARTS =======-->
        <div class="works4-section-area sp3" id="works">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 m-auto">
                        <div class="works4-header text-center heading6 tags2">
                            <span data-aos="fade-up" data-aos-duration="800">
                                <img src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">How It
                                Works</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000">How StageDesk Pro Streamlines the Booking
                                Process</h2>
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
                                    <!-- Updated step descriptions -->
                                    <div class="sticky-text heading6">
                                        <h3>01</h3>
                                        <div class="space16"></div>
                                        <h2>Company Registration</h2>
                                        <div class="space24"></div>
                                        <p>Register your company by selecting a plan — including a free tier with limited
                                            features. Create an admin login and start managing DJs and requests instantly.
                                        </p>
                                    </div>

                                    <div class="sticky-text heading6">
                                        <h3>02</h3>
                                        <div class="space16"></div>
                                        <h2>DJ Onboarding</h2>
                                        <div class="space24"></div>
                                        <p>Invite DJs to join your portal or register them manually. Each DJ has their own
                                            login, service list, and pricing options.</p>
                                    </div>

                                    <div class="sticky-text heading6">
                                        <h3>03</h3>
                                        <div class="space16"></div>
                                        <h2>Customer Booking</h2>
                                        <div class="space24"></div>
                                        <p>Customers can request DJs publicly. Companies can view and respond to bookings
                                            depending on their plan limitations.</p>
                                        <div class="space16"></div>
                                        <a href="{{ route('register') }}" class="header-btn7">Book Your DJ Now</a>
                                    </div>

                                    <div class="sticky-text heading6">
                                        <h3>04</h3>
                                        <div class="space16"></div>
                                        <h2>Subscription & Payment</h2>
                                        <div class="space24"></div>
                                        <p>Securely manage subscription renewals, payment methods, and plan upgrades.
                                            Automatic renewals ensure uninterrupted access.</p>
                                    </div>
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== HOW IT WORKS AREA ENDS =======-->

        <!--===== WHY CHOOSE US AREA STARTS =======-->
        <div class="brand4-section-area sp3" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        <div class="brand4-header tags2 heading6 text-center">
                            <span data-aos="fade-up" data-aos-duration="800">
                                <img src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Why Choose StageDesk Pro
                            </span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000">The Complete Solution for Event Entertainment Management</h2>
                            <div class="space24"></div>
                            <p data-aos="fade-up" data-aos-duration="1200">
                                StageDesk Pro revolutionizes how event companies manage their DJ bookings, streamline operations,
                                and grow their business. Our all-in-one platform eliminates the chaos of manual booking management.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="800">
                        <div class="text-center p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); height: 100%;">
                            <div class="mb-3">
                                <img src="{{ asset('landing/images/icons/service-icon1.svg') }}" alt="" style="width: 60px; height: 60px;">
                            </div>
                            <h4 class="mb-3">Centralized Management</h4>
                            <p>Manage all your DJs, bookings, customers, and payments from a single, intuitive dashboard. No more scattered spreadsheets or missed opportunities.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="1000">
                        <div class="text-center p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); height: 100%;">
                            <div class="mb-3">
                                <img src="{{ asset('landing/images/icons/service-icon2.svg') }}" alt="" style="width: 60px; height: 60px;">
                            </div>
                            <h4 class="mb-3">Automated Workflows</h4>
                            <p>Save hours with automated email notifications, booking confirmations, payment reminders, and status updates for all parties involved.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="1200">
                        <div class="text-center p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); height: 100%;">
                            <div class="mb-3">
                                <img src="{{ asset('landing/images/icons/service-icon3.svg') }}" alt="" style="width: 60px; height: 60px;">
                            </div>
                            <h4 class="mb-3">Flexible Pricing Plans</h4>
                            <p>Start free and scale as you grow. Choose monthly or yearly subscriptions with features tailored to your business size and needs.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="800">
                        <div class="text-center p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); height: 100%;">
                            <div class="mb-3">
                                <img src="{{ asset('landing/images/icons/service-icons4.svg') }}" alt="" style="width: 60px; height: 60px;">
                            </div>
                            <h4 class="mb-3">Secure Payments</h4>
                            <p>Integrated payment processing with industry-leading security. Accept bookings and process payments seamlessly through our platform.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="1000">
                        <div class="text-center p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); height: 100%;">
                            <div class="mb-3">
                                <img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 60px; height: 60px;">
                            </div>
                            <h4 class="mb-3">Real-Time Analytics</h4>
                            <p>Track your business performance with comprehensive analytics. Monitor bookings, revenue, DJ performance, and customer satisfaction metrics.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="1200">
                        <div class="text-center p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); height: 100%;">
                            <div class="mb-3">
                                <img src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="" style="width: 60px; height: 60px;">
                            </div>
                            <h4 class="mb-3">24/7 Accessibility</h4>
                            <p>Access your dashboard anytime, anywhere. Manage bookings on the go with our fully responsive platform that works on all devices.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== WHY CHOOSE US AREA ENDS =======-->

        <!--===== SERVICES AREA STARTS =======-->
        <div class="marketing-section-area sp3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        <div class="marketing-header tags2 heading6 text-center">
                            <span data-aos="fade-up" data-aos-duration="800"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Platform
                                Features</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000">Key Features of StageDesk Pro</h2>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="marketing-img" data-aos="flip-left" data-aos-duration="1000">
                            <img src="{{ asset('landing/images/all-images/marketing-img1.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- Updated features from project -->
                        <div class="tabs-text-area" data-aos="fade-up" data-aos-duration="800">
                            <div class="tabs-icon">
                                <img src="{{ asset('landing/images/icons/service-icon1.svg') }}" alt="">
                            </div>
                            <div class="tabs-text">
                                <a href="#">Multi-User Portals</a>
                                <p>Separate dashboards for Admin, Companies, DJs, Customers, and Affiliates — each with
                                    role-based access and permissions.</p>
                            </div>
                        </div>
                        <div class="space20"></div>
                        <div class="tabs-text-area" data-aos="fade-up" data-aos-duration="1000">
                            <div class="tabs-icon1">
                                <img src="{{ asset('landing/images/icons/service-icon2.svg') }}" alt="">
                            </div>
                            <div class="tabs-text">
                                <a href="#">Booking & Payment System</a>
                                <p>Integrated with secure payment gateways allowing customers to book and pay directly on
                                    the platform.</p>
                            </div>
                        </div>
                        <div class="space20"></div>
                        <div class="tabs-text-area" data-aos="fade-up" data-aos-duration="1200">
                            <div class="tabs-icon2">
                                <img src="{{ asset('landing/images/icons/service-icon3.svg') }}" alt="">
                            </div>
                            <div class="tabs-text">
                                <a href="#">Subscription Management</a>
                                <p>Companies can subscribe to plans — free, monthly, or yearly — and renew automatically
                                    with card-based payments.</p>
                            </div>
                        </div>
                        <div class="space20"></div>
                        <div class="tabs-text-area" data-aos="fade-up" data-aos-duration="1200">
                            <div class="tabs-icon3">
                                <img src="{{ asset('landing/images/icons/service-icons4.svg') }}" alt="">
                            </div>
                            <div class="tabs-text">
                                <a href="#">Affiliate System</a>
                                <p>Affiliates can promote StageDesk Pro, onboard companies, and earn commission from paid
                                    subscriptions.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== SERVICES AREA ENDS =======-->

        <!--===== COLLABORATION AREA STARTS =======-->
        <div class="others4-section-area sp3" id="features">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <div class="others-collaborate-area heading6 tags2 ">
                            <span data-aos="fade-right" data-aos-duration="600"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}"
                                    alt="">Collaboration</span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-right" data-aos-duration="700">Empowering Collaboration Between DJs &
                                Companies
                            </h2>
                            <div class="space32"></div>
                            <div class="listarea" data-aos="fade-right" data-aos-duration="800">
                                <div><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt=""></div>
                                <div>
                                    <p><span>Invite & Manage DJs:</span> Companies can easily invite DJs via email and
                                        manage
                                        their profiles, services, and assignments.</p>
                                </div>
                            </div>
                            <div class="space24"></div>
                            <div class="listarea" data-aos="fade-right" data-aos-duration="900">
                                <div><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt=""></div>
                                <div>
                                    <p><span>Smart Request Handling:</span> Companies can respond to customer requests and
                                        assign DJs in real-time.</p>
                                </div>
                            </div>
                            <div class="space24"></div>
                            <div class="listarea" data-aos="fade-right" data-aos-duration="1000">
                                <div><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt=""></div>
                                <div>
                                    <p><span>Automated Notifications:</span> Stay informed through automated notifications
                                        about
                                        bookings, renewals, and status updates.</p>
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
            </div>
        </div>
        <!--===== COLLABORATION AREA ENDS =======-->

        <!--===== PRICING PLAN AREA STARTS =======-->
        <div class="pricing-paln-section-area pricicng-paln3 sp4" id="pricing">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7 m-auto">
                        <div class="pricing-header text-center heading6 tags2">
                            <span data-aos="fade-up" data-aos-duration="800"><img
                                    src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Pricing
                                Plan</span>
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
                                    @foreach ($monthly_packages as $package)
                                        <div class="col-md-6 col-lg-4" data-aos="flip-right" data-aos-duration="1000">
                                            <div class="price-box">
                                                <div class="hadding">
                                                    <div class="pricing-area">
                                                        <h3>{{ $package->name }}</h3>
                                                        <div class="space24"></div>
                                                        <h2 class="pricing-heading">${{ $package->price }}
                                                            <span>/month</span>
                                                        </h2>
                                                        <div class="space16"></div>
                                                        <p>{{ $package->description }}</p>
                                                        <div class="space24"></div>
                                                        <ul>
                                                            <li>
                                                                <img src="{{ asset('landing/images/icons/check-green.svg') }}"
                                                                    alt="">
                                                                No. of Aritsts Allowed : {{ $package->max_users_allowed }}
                                                            </li>
                                                            <li class="space16"></li>
                                                            <li>
                                                                <img src="{{ asset('landing/images/icons/check-green.svg') }}"
                                                                    alt="">
                                                                Requests view Allowed :
                                                                {{ $package->max_requests_allowed }}/month
                                                            </li>
                                                            <li class="space16"></li>
                                                            <li>
                                                                <img src="{{ asset('landing/images/icons/check-green.svg') }}"
                                                                    alt="">
                                                                Responses Allowed:
                                                                {{ $package->max_responses_allowed }}/month
                                                            </li>
                                                            <li class="space16"></li>
                                                            @foreach ($package->features as $feature)
                                                                <li><img src="{{ asset('landing/images/icons/check-green.svg') }}"
                                                                        alt="">{{ $feature->feature_description }}
                                                                </li>
                                                                <li class="space16"></li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="space32"></div>
                                                        <div>
                                                            <a href="{{ route('register') }}?package_id={{ $package->id }}"
                                                                class="header-btn7">Get Started
                                                                with
                                                                {{ $package->name }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div id="yearly" style="display: none;">
                                <div class="row">
                                    @foreach ($yearly_packages as $package)
                                        <div class="col-md-6 col-lg-4" data-aos="flip-right" data-aos-duration="1000">
                                            <div class="price-box">
                                                <div class="hadding">
                                                    <div class="pricing-area">
                                                        <h3>{{ $package->name }}</h3>
                                                        <div class="space24"></div>
                                                        <h2 class="pricing-heading">${{ $package->price }}
                                                            <span>/year</span>
                                                        </h2>
                                                        <div class="space16"></div>
                                                        <p>{{ $package->description }}</p>
                                                        <div class="space24"></div>
                                                        <ul>
                                                            <li>
                                                                <img src="{{ asset('landing/images/icons/check-green.svg') }}"
                                                                    alt="">
                                                                No. of Aritsts Allowed : {{ $package->max_users_allowed }}
                                                            </li>
                                                            <li class="space16"></li>
                                                            <li>
                                                                <img src="{{ asset('landing/images/icons/check-green.svg') }}"
                                                                    alt="">
                                                                Requests view Allowed :
                                                                {{ $package->max_requests_allowed }}/year
                                                            </li>
                                                            <li class="space16"></li>
                                                            <li>
                                                                <img src="{{ asset('landing/images/icons/check-green.svg') }}"
                                                                    alt="">
                                                                Responses Allowed:
                                                                {{ $package->max_responses_allowed }}/year
                                                            </li>
                                                            <li class="space16"></li>
                                                            @foreach ($package->features as $feature)
                                                                <li><img src="{{ asset('landing/images/icons/check-green.svg') }}"
                                                                        alt="">{{ $feature->feature_description }}
                                                                </li>
                                                                <li class="space16"></li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="space32"></div>
                                                        <div>
                                                            <a href="{{ route('register') }}?package_id={{ $package->id }}"
                                                                class="header-btn7">
                                                                Get Started with {{ $package->name }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== PRICING PLAN AREA ENDS =======-->

        <!--===== BENEFITS FOR ALL USERS AREA STARTS =======-->
        <div class="benefits-section-area sp3" style="background: #f8f9fa;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        <div class="text-center heading6 tags2">
                            <span data-aos="fade-up" data-aos-duration="800">
                                <img src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">For Everyone
                            </span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000">Benefits for Every User Type</h2>
                            <div class="space24"></div>
                            <p data-aos="fade-up" data-aos-duration="1200">
                                Whether you're a company owner, DJ, customer, or affiliate, StageDesk Pro has features designed specifically for your needs.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="row">
                    <!-- For Companies -->
                    <div class="col-lg-6 mb-4" data-aos="fade-right" data-aos-duration="800">
                        <div class="p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); height: 100%;">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('landing/images/icons/service-icon1.svg') }}" alt="" style="width: 50px; height: 50px; margin-right: 15px;">
                                <h3 class="mb-0">For Event Companies</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Manage unlimited DJs and bookings</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Track revenue and performance analytics</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Automated booking assignments</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Customer relationship management</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Flexible subscription plans</li>
                            </ul>
                        </div>
                    </div>
                    <!-- For DJs -->
                    <div class="col-lg-6 mb-4" data-aos="fade-left" data-aos-duration="800">
                        <div class="p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); height: 100%;">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('landing/images/icons/service-icon2.svg') }}" alt="" style="width: 50px; height: 50px; margin-right: 15px;">
                                <h3 class="mb-0">For DJs & Artists</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Personal dashboard with booking calendar</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Accept or reject booking requests</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Track earnings and payment history</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Build your profile and showcase services</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Receive instant booking notifications</li>
                            </ul>
                        </div>
                    </div>
                    <!-- For Customers -->
                    <div class="col-lg-6 mb-4" data-aos="fade-right" data-aos-duration="1000">
                        <div class="p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); height: 100%;">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('landing/images/icons/service-icon3.svg') }}" alt="" style="width: 50px; height: 50px; margin-right: 15px;">
                                <h3 class="mb-0">For Customers</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Browse and book DJs instantly</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> View DJ profiles, reviews, and ratings</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Secure online payment processing</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Track booking status in real-time</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Leave reviews and feedback</li>
                            </ul>
                        </div>
                    </div>
                    <!-- For Affiliates -->
                    <div class="col-lg-6 mb-4" data-aos="fade-left" data-aos-duration="1000">
                        <div class="p-4" style="background: white; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.1); height: 100%;">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ asset('landing/images/icons/service-icons4.svg') }}" alt="" style="width: 50px; height: 50px; margin-right: 15px;">
                                <h3 class="mb-0">For Affiliates</h3>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Earn commissions on referrals</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Access to marketing materials</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Real-time commission tracking</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Detailed performance analytics</li>
                                <li class="mb-3"><img src="{{ asset('landing/images/icons/check-img7.svg') }}" alt="" style="width: 20px; margin-right: 10px;"> Automated payout management</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== BENEFITS FOR ALL USERS AREA ENDS =======-->

        <!--===== SUCCESS METRICS AREA STARTS =======-->
        <div class="metrics-section-area sp3">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 m-auto">
                        <div class="text-center heading6 tags2">
                            <span data-aos="fade-up" data-aos-duration="800">
                                <img src="{{ asset('landing/images/icons/clock-img2.svg') }}" alt="">Platform Impact
                            </span>
                            <div class="space16"></div>
                            <h2 data-aos="fade-up" data-aos-duration="1000">Growing Together, Achieving More</h2>
                            <div class="space24"></div>
                            <p data-aos="fade-up" data-aos-duration="1200">
                                Join thousands of event companies and DJs who have transformed their booking management with StageDesk Pro
                            </p>
                        </div>
                    </div>
                </div>
                <div class="space60"></div>
                <div class="row text-center">
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="800">
                        <div class="p-4">
                            <h1 class="mb-3" style="color: #6c5ce7; font-size: 3.5rem; font-weight: 700;">500+</h1>
                            <h5>Registered Companies</h5>
                            <p class="text-muted">Event companies trust our platform</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="1000">
                        <div class="p-4">
                            <h1 class="mb-3" style="color: #00b894; font-size: 3.5rem; font-weight: 700;">2,000+</h1>
                            <h5>Active DJs</h5>
                            <p class="text-muted">Professional artists on our platform</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="1200">
                        <div class="p-4">
                            <h1 class="mb-3" style="color: #fd79a8; font-size: 3.5rem; font-weight: 700;">15,000+</h1>
                            <h5>Bookings Completed</h5>
                            <p class="text-muted">Successful events managed</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4" data-aos="zoom-in" data-aos-duration="1400">
                        <div class="p-4">
                            <h1 class="mb-3" style="color: #fdcb6e; font-size: 3.5rem; font-weight: 700;">98%</h1>
                            <h5>Satisfaction Rate</h5>
                            <p class="text-muted">Happy customers and companies</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===== SUCCESS METRICS AREA ENDS =======-->


        <!--===== TESTIMONIAL AREA STARTS =======-->
        <div class="testimonial-carousel-area sp3" id="testimonials">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 m-auto">
                        <div class="tastemonial-box-area">
                            <img src="{{ asset('landing/images/icons/pinterest1.svg') }}" alt=""
                                class="pinterest1" data-aos="fade-right" data-aos-duration="1000">
                            <img src="{{ asset('landing/images/background/testimonial.png') }}" alt=""
                                class="testimonial" data-aos="zoom-out" data-aos-duration="800">
                            <div class="row">
                                <div class="col-lg-8 m-auto">
                                    <div class="testimonial-main-box heading6 owl-carousel">
                                        @foreach ($testimonials as $testimonial)
                                            <div class="testimonial-content">
                                                <div class="img1 text-center">
                                                    <img src="{{ asset('landing/images/icons/quito-img1.svg') }}"
                                                        alt="">
                                                </div>
                                                <div class="space16"></div>
                                                <h2 class="text-center">Client Success Stories </h2>
                                                <div class="space32"></div>
                                                <p class="text-center">" {{ $testimonial->testimonial }} "</p>
                                                <div class="space32"></div>
                                                <div class="team-details">
                                                    <div class="img1">
                                                        <img src="{{ $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : asset('images/default.jpg') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="content">
                                                        <a href="team.html">{{ $testimonial->name }}</a>
                                                        <p>{{ $testimonial->designation }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <img src="{{ asset('landing/images/icons/instagram3.svg') }}" alt=""
                                class="instagram3" data-aos="fade-left" data-aos-duration="1000">
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
                    @if (!$blogs->isEmpty())
                        @foreach ($blogs as $blog)
                            <div class="col-lg-4 col-md-6" data-aos="flip-left" data-aos-duration="1200">
                                <div class="blog4-boxarea">
                                    <div class=" img1">
                                        <img src="{{ asset('storage/' . $blog->feature_image ?? '') }}" alt="">
                                    </div>
                                    <div class="blog-content">
                                        <div class="date">
                                            <div class="social">
                                                <a
                                                    href="{{ route('blog.details', $blog->slug) }}">{{ $blog->category->name }}</a>
                                            </div>
                                            <div class="date-day">
                                                <a href="#"><img
                                                        src="{{ asset('landing/images/icons/clock-img1.svg') }}"
                                                        alt="">{{ date('d-M-Y', strtotime($blog->published_at)) }}</a>
                                            </div>
                                        </div>
                                        <div class="space16"></div>
                                        <a href="{{ route('blog.details', $blog->slug) }}">{{ $blog->title }}</a>
                                        <div class="space16"></div>
                                        <p>{!! Str::words($blog->content, 11, '...') !!}</p>
                                        <div class="space20"></div>
                                        <a href="{{ route('blog.details', $blog->slug) }}" class="readmore">Read More <i
                                                class="fa-solid fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-lg-12">
                            <div class="text-center">
                                <h4>No blogs available at the moment.</h4>
                            </div>
                        </div>
                    @endif
                </div>
                @if (!$blogs->isEmpty())
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <a href="{{ route('blogs') }}" class="header-btn7">View All Blogs</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!--===== BLOG AREA ENDS =======-->
    </div>
    <!--===== CTA AREA STARTS =======-->
    <div class="cta4-section-area sp3"
        style="background-image: url({{ asset('landing/images/all-images/cta-btg2.png') }}); background-position: center; background-repeat: no-repeat;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cta-bg-area">
                        <div class="row">
                            <div class="col-lg-8 m-auto">
                                <div class="cta-content-area text-center heading6">
                                    <!-- ✅ Updated headline to match StageDesk Pro purpose -->
                                    <h2 data-aos="zoom-out" data-aos-duration="800">
                                        Simplify Your DJ Bookings and Company Management with StageDesk Pro
                                    </h2>
                                    <div class="space16"></div>
                                    <!-- ✅ Updated CTA message: focused on event companies, DJs, and booking automation -->
                                    <p data-aos="zoom-out" data-aos-duration="1000">
                                        Manage your entertainment business seamlessly — register your company, onboard DJs,
                                        handle customer requests, and automate bookings. StageDesk Pro gives you the tools
                                        to grow faster with modern technology, integrated payments, and subscription
                                        control.
                                    </p>
                                    <div class="space32"></div>
                                    <div class="btn-area" data-aos="zoom-out" data-aos-duration="1200">
                                        <a href="{{ route('register') }}" class="header-btn5">Book Your DJ Now</a>
                                        <a href="{{ route('register') }}" class="header-btn7">Register Your Company</a>
                                        <a href="#pricing" class="header-btn8">Explore Plans</a>
                                    </div>
                                    <!-- ✅ Optional note for context -->
                                    <!-- The first button leads companies to register,
                                                                                                                            and the second one takes users to pricing/subscription packages -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===== CTA AREA ENDS =======-->


@endsection
