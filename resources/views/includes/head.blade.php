<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!--=====TITLE=======-->
    <title>{{ $title }} - {{ settings_get('site_name') }}</title>

    <!--=====FAV ICON=======-->
    <link rel="shortcut icon" href="{{ asset('landing/images/logo/logo6.png') }}">

    <!--=====CSS=======-->
    <link rel="stylesheet" href="{{ asset('landing/css/plugins/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/plugins/swiper.bundle.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/plugins/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/plugins/mobile.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/plugins/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/plugins/slick-slider.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/plugins/owlcarousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/plugins/aos.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/master.css') }}">

    <!--=====JQUERY=======-->
    <script src="{{ asset('landing/js/plugins/jquery-3-6-0.min.js') }}"></script>
    <style>
        img,
        svg,
        video,
        iframe {
            max-width: 100%;
            height: auto;
        }

        @media (max-width: 991.98px) {
            .header-elements {
                flex-wrap: wrap;
                gap: 0.75rem;
            }

            .header-elements .btn-area {
                width: 100%;
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }

        @media (max-width: 399.98px) {
            .mobile-header {
                padding: 12px 0;
            }

            .mobile-sidebar {
                padding: 28px 16px;
            }

            .mobile-nav li a {
                font-size: 16px;
                line-height: 22px;
                padding: 8px 0;
            }
        }
    </style>
</head>