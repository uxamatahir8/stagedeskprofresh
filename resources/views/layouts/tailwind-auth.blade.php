@php
    $siteName = function_exists('settings_get') ? settings_get('site_name') : config('app.name', 'StageDesk Pro');
    $siteName = $siteName ?: 'StageDesk Pro';
    $pageTitle = isset($title) ? $title . ' - ' . $siteName : $siteName;
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
<body class="min-h-screen bg-surface-muted text-text-primary">
    <main class="grid min-h-screen lg:grid-cols-[1fr_minmax(440px,560px)]">
        <section class="hidden bg-sidebar text-white lg:flex lg:flex-col lg:justify-between lg:p-10">
            <x-tw.app-logo variant="light" size="xl" />
            <div class="max-w-xl">
                <p class="text-sm font-semibold uppercase tracking-wide text-slate-400">StageDesk Pro</p>
                <h1 class="mt-4 text-4xl font-bold leading-tight tracking-normal">Manage event-service operations with one secure workspace.</h1>
                <p class="mt-4 text-base leading-7 text-slate-300">
                    Access company subscriptions, bookings, manual payment verification, artist responses, and role-specific portals.
                </p>
            </div>
            <p class="text-sm text-slate-500">&copy; {{ date('Y') }} {{ $siteName }}</p>
        </section>

        <section class="flex min-h-screen items-center justify-center px-4 py-8">
            <div class="w-full max-w-xl">
                <div class="mb-8 lg:hidden">
                    <x-tw.app-logo size="xl" />
                </div>

                <div class="sd-card sd-card-pad">
                    <div class="mb-6">
                        <p class="sd-meta font-semibold uppercase tracking-wide text-primary">Secure access</p>
                        <h1 class="mt-2 text-2xl font-bold tracking-normal text-text-primary">{{ $title ?? 'Welcome back' }}</h1>
                        <p class="sd-text-muted mt-2">Use your current StageDesk Pro account flow. Request fields and security checks remain unchanged.</p>
                    </div>

                    <div class="space-y-4">
                        <x-tw.flash-messages />
                    </div>

                    <div class="mt-6">
                        @yield('content')
                    </div>
                </div>

                <p class="sd-meta mt-6 text-center">
                    &copy; {{ date('Y') }} {{ $siteName }} by Softring Solutions
                </p>
            </div>
        </section>
    </main>

    @stack('scripts')
</body>
</html>
