<head>
    <meta charset="utf-8">
    <title>{{ $title }} - {{ settings_get('site_name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="UBold is a modern, responsive admin dashboard available on ThemeForest. Ideal for building CRM, CMS, project management tools, and custom web applications with a clean UI, flexible layouts, and rich features.">
    <meta name="keywords"
        content="UBold, admin dashboard, ThemeForest, Bootstrap 5 admin, responsive admin, CRM dashboard, CMS admin, web app UI, admin theme, premium admin template">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    {{-- Datatables css --}}
    <link href="{{ asset('plugins/datatables/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/datatables/buttons.bootstrap5.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Theme Config Js -->
    <script src="{{ asset('js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('css/vendors.min.css') }}" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .sidenav-menu .logo {
            background-color: #fff !important;
            border-bottom: 1px solid #e4e4e4 !important;
        }

        /* Fix z-index hierarchy to prevent sidebar overlap */
        .app-topbar {
            z-index: 999 !important;
        }

        .app-topbar .dropdown-menu {
            z-index: 1000 !important;
        }

        /* Ensure sidebar is always above dropdowns */
        .sidenav-menu {
            z-index: 1051 !important;
        }

        /* Ensure dropdown closes properly and doesn't persist */
        .topbar-item .dropdown-menu {
            max-width: 100%;
            overflow: hidden;
        }
    </style>
</head>
