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
        :root {
            --sd-bg: #f5f7fb;
            --sd-surface: #ffffff;
            --sd-primary: #5b2a86;
            --sd-text: #0f172a;
            --sd-muted: #475569;
        }

        img, svg, video, iframe { max-width: 100%; height: auto; }
        body { background: var(--sd-bg); color: var(--sd-text); }

        .modern-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(8px);
            background: rgba(15, 23, 42, 0.9);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .modern-nav {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .modern-brand img { width: 118px; height: auto; }
        .modern-links { list-style: none; display: flex; gap: 1.2rem; margin: 0; padding: 0; }
        .modern-links a { color: #e2e8f0; font-weight: 500; text-decoration: none; }
        .modern-links a:hover { color: #fff; }
        .modern-actions { display: flex; gap: .5rem; }

        .welcome4-section-area {
            border-radius: 0 0 28px 28px;
            overflow: hidden;
            background: linear-gradient(135deg, #111827 0%, #1f2937 60%, #312e81 100%) !important;
        }

        .welcome4-section-area h1 { color: #fff !important; letter-spacing: -0.03em; }
        .welcome4-section-area p, .welcome4-section-area span { color: #dbeafe !important; }

        .modern-footer {
            background: #0f172a;
            color: #cbd5e1;
            padding: 3rem 0 1.2rem;
            margin-top: 3rem;
        }
        .modern-footer-logo { width: 140px; height: auto; margin-bottom: .75rem; }
        .modern-footer-text { margin: 0; max-width: 520px; }
        .modern-footer-links { display: flex; flex-wrap: wrap; gap: .8rem 1rem; justify-content: flex-start; }
        .modern-footer-links a { color: #e2e8f0; text-decoration: none; }
        .modern-footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 1.5rem;
            padding-top: .9rem;
            display: flex;
            justify-content: space-between;
            font-size: .9rem;
        }

        @media (max-width: 991.98px) {
            .modern-footer-bottom { flex-direction: column; gap: .35rem; }
        }

        @media (max-width: 399.98px) {
            .mobile-header { padding: 12px 0; }
            .mobile-sidebar { padding: 28px 16px; }
            .mobile-nav li a { font-size: 16px; line-height: 22px; padding: 8px 0; }
        }
    </style>
</head>