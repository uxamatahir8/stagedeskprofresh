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
    <meta name="description" content="@yield('meta_description', $siteName . ' helps event-service companies manage subscriptions, artists, booking requests, payments, and affiliate growth.')">
    <title>{{ $pageTitle }}</title>
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.460.0/dist/umd/lucide.min.js" crossorigin="anonymous"></script>
    @stack('styles')
</head>
<body class="sd-page-shell bg-surface">
    <header class="sticky top-0 z-40 border-b border-border bg-white/90 backdrop-blur">
        <div class="sd-container flex h-[var(--sd-header-height)] items-center justify-between gap-4">
            <x-tw.app-logo size="lg" />

            <nav class="hidden items-center gap-6 text-sm font-semibold text-text-secondary lg:flex" aria-label="Public navigation">
                <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
                <a href="{{ route('home') }}#works" class="hover:text-primary">How it works</a>
                <a href="{{ route('home') }}#features" class="hover:text-primary">Features</a>
                <a href="{{ route('home') }}#pricing" class="hover:text-primary">Plans</a>
                <a href="{{ route('blogs') }}" class="hover:text-primary">Blog</a>
            </nav>

            <div class="hidden items-center gap-2 lg:flex">
                @guest
                    <x-tw.button :href="route('login')" variant="ghost">Login</x-tw.button>
                    <x-tw.button :href="route('register')">Register company</x-tw.button>
                @else
                    <x-tw.button :href="route('dashboard')">Dashboard</x-tw.button>
                @endguest
            </div>

            <button type="button" class="inline-flex size-10 items-center justify-center rounded-field text-text-secondary hover:bg-surface-muted lg:hidden" data-tw-drawer-open aria-label="Open navigation">
                <i data-lucide="menu" class="size-5"></i>
            </button>
        </div>
    </header>

    <div class="fixed inset-0 z-50 hidden bg-slate-950/50 lg:hidden" data-tw-mobile-overlay data-tw-drawer-close></div>
    <aside class="fixed inset-y-0 left-0 z-50 w-80 max-w-[86vw] -translate-x-full bg-sidebar text-white transition-transform lg:hidden" data-tw-mobile-drawer>
        <div class="flex h-[var(--sd-header-height)] items-center justify-between border-b border-white/10 px-5">
            <x-tw.app-logo variant="light" size="md" />
            <button type="button" class="inline-flex size-10 items-center justify-center rounded-field text-slate-300 hover:bg-white/10 hover:text-white" data-tw-drawer-close aria-label="Close navigation">
                <i data-lucide="x" class="size-5"></i>
            </button>
        </div>
        <nav class="space-y-1 p-4 text-sm font-semibold" aria-label="Mobile public navigation">
            <a href="{{ route('home') }}" class="block rounded-field px-3 py-2 text-slate-200 hover:bg-white/10">Home</a>
            <a href="{{ route('home') }}#works" class="block rounded-field px-3 py-2 text-slate-200 hover:bg-white/10">How it works</a>
            <a href="{{ route('home') }}#features" class="block rounded-field px-3 py-2 text-slate-200 hover:bg-white/10">Features</a>
            <a href="{{ route('home') }}#pricing" class="block rounded-field px-3 py-2 text-slate-200 hover:bg-white/10">Plans</a>
            <a href="{{ route('blogs') }}" class="block rounded-field px-3 py-2 text-slate-200 hover:bg-white/10">Blog</a>
            <div class="mt-4 border-t border-white/10 pt-4">
                @guest
                    <a href="{{ route('login') }}" class="block rounded-field px-3 py-2 text-slate-200 hover:bg-white/10">Login</a>
                    <a href="{{ route('register') }}" class="mt-2 block rounded-field bg-primary px-3 py-2 text-center text-white">Register company</a>
                @else
                    <a href="{{ route('dashboard') }}" class="block rounded-field bg-primary px-3 py-2 text-center text-white">Dashboard</a>
                @endguest
            </div>
        </nav>
    </aside>

    <main>
        <div class="sd-container pt-6">
            <x-tw.flash-messages />
        </div>
        @yield('content')
    </main>

    <footer class="border-t border-border bg-sidebar py-10 text-slate-300">
        <div class="sd-container flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
            <div>
                <x-tw.app-logo variant="light" size="lg" />
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-400">
                    SaaS operations for company subscriptions, artist assignments, booking requests, customer payments, and affiliate growth.
                </p>
            </div>
            <div class="flex flex-wrap gap-4 text-sm font-semibold">
                <a href="{{ route('home') }}" class="hover:text-white">Home</a>
                <a href="{{ route('blogs') }}" class="hover:text-white">Blog</a>
                <a href="{{ route('login') }}" class="hover:text-white">Login</a>
                <a href="{{ route('register') }}" class="hover:text-white">Register</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
