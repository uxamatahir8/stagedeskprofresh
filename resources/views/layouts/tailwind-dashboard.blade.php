@php
    $siteName = function_exists('settings_get') ? settings_get('site_name') : config('app.name', 'StageDesk Pro');
    $siteName = $siteName ?: 'StageDesk Pro';
    $pageTitle = isset($title) ? $title . ' - ' . $siteName : $siteName;
    $notificationCount = $unreadNotificationCount ?? 0;
    $notifications = $topbarNotifications ?? collect();
    $breadcrumbs = $breadcrumbs ?? [];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.460.0/dist/umd/lucide.min.js" crossorigin="anonymous"></script>
    @stack('styles')
</head>
<body class="bg-surface-muted text-text-primary">
    <div class="min-h-screen lg:flex">
        <x-tw.navigation.dashboard-sidebar />

        <div class="fixed inset-0 z-50 hidden bg-slate-950/50 lg:hidden" data-tw-mobile-overlay data-tw-drawer-close></div>
        <div class="fixed inset-y-0 left-0 z-50 -translate-x-full transition-transform lg:hidden" data-tw-mobile-drawer>
            <x-tw.navigation.dashboard-sidebar mobile />
        </div>

        <div class="min-w-0 flex-1">
            <x-tw.navigation.dashboard-topbar :notifications="$notifications" :notification-count="$notificationCount" />

            <main class="px-4 py-6 lg:px-6">
                <div class="sd-dashboard-container">
                    <x-tw.breadcrumbs :items="$breadcrumbs" />

                    <div class="mb-6 space-y-4">
                        <x-tw.flash-messages />
                    </div>

                    @hasSection('page-header')
                        @yield('page-header')
                    @elseif(isset($title))
                        <x-tw.page-header :title="$title" />
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
