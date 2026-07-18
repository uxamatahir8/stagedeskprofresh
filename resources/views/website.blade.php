@extends('layouts.tailwind-public')

@section('meta_description', 'StageDesk Pro is a premium SaaS operating platform for event-service companies managing subscriptions, artists, bookings, payment proof, and affiliate growth.')

@section('content')
@php
    $monthlyPreview = $monthly_packages->take(3);
    $yearlyPreview = $yearly_packages->take(3);
    $testimonialPreview = $testimonials->take(3);
    $blogPreview = $blogs->take(3);
    $availablePlans = $monthly_packages->count() + $yearly_packages->count();

    $metrics = [
        ['value' => number_format((float) ($landingStats['active_companies'] ?? 0)), 'label' => 'Active companies'],
        ['value' => number_format((float) ($landingStats['active_artists'] ?? 0)), 'label' => 'Active artists'],
        ['value' => number_format((float) ($landingStats['total_bookings'] ?? 0)), 'label' => 'Booking requests managed'],
        ['value' => number_format($availablePlans), 'label' => 'Subscription plans available'],
    ];

    $capabilities = [
        ['icon' => 'building-2', 'title' => 'Company workspace', 'body' => 'Manage company details, users, artists, requests, and subscription access from one organized workspace.'],
        ['icon' => 'calendar-check-2', 'title' => 'Booking operations', 'body' => 'Track customer requests, company responses, artist assignments, and the work that still needs attention.'],
        ['icon' => 'receipt-text', 'title' => 'Payment visibility', 'body' => 'Keep payment proof, verification, and booking payment status visible without mixing them into one signal.'],
        ['icon' => 'share-2', 'title' => 'Growth channels', 'body' => 'Support affiliate referrals and business growth while keeping operational roles clearly separated.'],
    ];

    $process = [
        ['icon' => 'building-2', 'step' => '01', 'title' => 'Register your company', 'body' => 'Create your workspace and add the details of your event-service business.'],
        ['icon' => 'badge-dollar-sign', 'step' => '02', 'title' => 'Choose a plan', 'body' => 'Select the subscription package that fits your operational needs.'],
        ['icon' => 'file-check-2', 'step' => '03', 'title' => 'Submit payment proof', 'body' => 'Share your payment proof for verification and subscription activation.'],
        ['icon' => 'workflow', 'step' => '04', 'title' => 'Run your operations', 'body' => 'Manage artists, bookings, customer requests, and daily workflows in one place.'],
    ];

    $statusLanes = [
        ['title' => 'Booking', 'icon' => 'calendar-clock', 'states' => [
            ['label' => 'Pending', 'tone' => 'neutral'],
            ['label' => 'Confirmed', 'tone' => 'success'],
            ['label' => 'Completed', 'tone' => 'success'],
            ['label' => 'Cancelled', 'tone' => 'danger'],
        ]],
        ['title' => 'Payment', 'icon' => 'receipt', 'states' => [
            ['label' => 'Unpaid', 'tone' => 'neutral'],
            ['label' => 'Pending', 'tone' => 'warning'],
            ['label' => 'Completed', 'tone' => 'success'],
            ['label' => 'Failed', 'tone' => 'danger'],
        ]],
        ['title' => 'Subscription', 'icon' => 'badge-check', 'states' => [
            ['label' => 'Pending verification', 'tone' => 'warning'],
            ['label' => 'Active', 'tone' => 'success'],
            ['label' => 'Paused', 'tone' => 'neutral'],
            ['label' => 'Expired', 'tone' => 'muted'],
        ]],
        ['title' => 'Artist response', 'icon' => 'user-round-check', 'states' => [
            ['label' => 'Awaiting response', 'tone' => 'warning'],
            ['label' => 'Accepted', 'tone' => 'success'],
            ['label' => 'Rejected', 'tone' => 'danger'],
            ['label' => 'Reassigned', 'tone' => 'neutral'],
        ]],
    ];

    $roles = [
        ['title' => 'Company admins', 'body' => 'Run the business workspace, manage artists, review bookings, and keep subscription access visible.'],
        ['title' => 'Artists', 'body' => 'Receive assigned work, respond to booking requests, and track earnings where enabled.'],
        ['title' => 'Customers', 'body' => 'Submit booking requests and follow the status of their event workflow.'],
        ['title' => 'Affiliates', 'body' => 'Refer companies and monitor referral performance through dedicated tools.'],
    ];

    $faqs = [
        ['q' => 'Who is StageDesk Pro built for?', 'a' => 'StageDesk Pro is built for event-service businesses that need a structured way to manage company subscriptions, artists, bookings, payments, and referrals.'],
        ['q' => 'How does a company get started?', 'a' => 'A company registers, chooses a subscription package, submits payment proof, and begins operating from its workspace after verification.'],
        ['q' => 'Does StageDesk Pro automatically match artists?', 'a' => 'No. Companies manage their artists and assign them to bookings through the platform workflow.'],
        ['q' => 'Are booking, payment, and subscription statuses separate?', 'a' => 'Yes. Each workflow has its own status so teams can understand what needs attention.'],
        ['q' => 'Can customers and affiliates use the platform?', 'a' => 'Yes. The platform supports customer booking requests and affiliate referral workflows alongside company and artist operations.'],
    ];

    $chipTone = [
        'success' => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        'warning' => 'border-amber-200 bg-amber-50 text-amber-800',
        'danger' => 'border-rose-200 bg-rose-50 text-rose-700',
        'muted' => 'border-slate-200 bg-slate-100 text-slate-500',
        'neutral' => 'border-slate-200 bg-white text-slate-700',
    ];
@endphp

<section class="relative overflow-hidden bg-[#120f1d] text-white">
    <div class="absolute inset-0 opacity-60" aria-hidden="true">
        <div class="absolute left-1/2 top-0 h-[560px] w-[560px] -translate-x-1/3 rounded-full bg-primary/30 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-[420px] w-[420px] rounded-full bg-accent/20 blur-3xl"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(255,255,255,.045)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,.045)_1px,transparent_1px)] bg-[size:72px_72px]"></div>
    </div>

    <div class="sd-container relative grid min-h-[690px] gap-12 py-20 lg:grid-cols-[1.02fr_0.98fr] lg:items-center lg:py-24">
        <div class="max-w-3xl">
            <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-semibold text-slate-200">
                <i data-lucide="sparkles" class="size-4"></i>
                Premium SaaS operations for event-service companies
            </p>
            <h1 class="mt-7 text-[2.9rem] font-bold leading-[1.02] tracking-normal text-white md:text-[4rem]">
                Run bookings, artists, subscriptions, and payments with calm control.
            </h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-slate-300 md:text-xl">
                StageDesk Pro gives event-service businesses a structured workspace for company onboarding, booking requests, artist coordination, payment proof, and subscription access.
            </p>
            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                <x-tw.button :href="route('register')" size="lg" icon="building-2" class="min-h-12 px-6">Register your company</x-tw.button>
                <x-tw.button :href="route('login')" variant="outline" size="lg" icon="log-in" class="min-h-12 border-white/20 bg-white/5 px-6 text-white hover:bg-white/10">Sign in</x-tw.button>
            </div>
            <p class="mt-5 text-sm font-medium text-slate-400">
                Built for companies that need operational clarity from first request to fulfilled booking.
            </p>
        </div>

        <div class="relative mx-auto w-full max-w-[560px]" aria-label="StageDesk Pro product visual">
            <div class="absolute -inset-6 rounded-[2rem] bg-gradient-to-br from-primary/30 via-accent/20 to-white/5 blur-2xl"></div>
            <div class="relative overflow-hidden rounded-[1.75rem] border border-white/15 bg-white/[0.08] p-6 shadow-2xl backdrop-blur">
                <div class="flex items-center justify-between border-b border-white/10 pb-5">
                    <div class="flex items-center gap-3">
                        <span class="flex size-11 items-center justify-center rounded-xl bg-white text-primary">
                            <i data-lucide="layout-dashboard" class="size-5"></i>
                        </span>
                        <div>
                            <p class="text-base font-bold text-white">StageDesk workspace</p>
                            <p class="text-sm text-slate-400">Operating layers in one system</p>
                        </div>
                    </div>
                    <span class="rounded-full border border-emerald-300/30 bg-emerald-400/10 px-3 py-1 text-sm font-semibold text-emerald-200">Organized</span>
                </div>

                <div class="grid gap-4 py-6 sm:grid-cols-2">
                    @foreach ([['Company', 'building-2'], ['Bookings', 'calendar-check-2'], ['Artists', 'mic-2'], ['Payments', 'receipt-text']] as [$label, $icon])
                        <div class="rounded-2xl border border-white/10 bg-white/[0.06] p-5">
                            <i data-lucide="{{ $icon }}" class="size-6 text-slate-200"></i>
                            <p class="mt-5 text-lg font-bold text-white">{{ $label }}</p>
                            <div class="mt-4 h-2 rounded-full bg-white/10">
                                <div class="h-2 w-2/3 rounded-full bg-gradient-to-r from-primary to-accent"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="rounded-2xl border border-white/10 bg-slate-950/35 p-5">
                    <div class="flex flex-wrap gap-2">
                        <span class="rounded-full bg-white/10 px-3 py-1 text-sm font-semibold text-slate-200">Company onboarding</span>
                        <span class="rounded-full bg-white/10 px-3 py-1 text-sm font-semibold text-slate-200">Subscription access</span>
                        <span class="rounded-full bg-white/10 px-3 py-1 text-sm font-semibold text-slate-200">Artist coordination</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-border bg-white" id="metrics-ribbon">
    <div class="sd-container grid divide-y divide-border py-7 sm:grid-cols-2 sm:divide-x sm:divide-y-0 lg:grid-cols-4">
        @foreach ($metrics as $metric)
            <div class="px-0 py-5 first:pt-0 last:pb-0 sm:px-6 sm:py-3 sm:first:pt-3 sm:last:pb-3">
                <p class="text-3xl font-bold tracking-normal text-text-primary md:text-4xl">{{ $metric['value'] }}</p>
                <p class="mt-1 text-sm font-semibold uppercase tracking-wide text-text-muted">{{ $metric['label'] }}</p>
            </div>
        @endforeach
    </div>
</section>

<section id="features" class="sd-section bg-surface-muted">
    <div class="sd-container">
        <div class="max-w-3xl">
            <p class="sd-label text-primary">Core platform capabilities</p>
            <h2 class="mt-3 text-3xl font-bold leading-tight tracking-normal text-text-primary md:text-4xl">One operating layer for the work behind every event.</h2>
        </div>
        <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($capabilities as $feature)
                <article class="rounded-2xl border border-border bg-white p-7 shadow-soft">
                    <i data-lucide="{{ $feature['icon'] }}" class="size-7 text-primary"></i>
                    <h3 class="mt-6 text-xl font-bold tracking-normal text-text-primary">{{ $feature['title'] }}</h3>
                    <p class="mt-3 text-base leading-7 text-text-secondary">{{ $feature['body'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section id="works" class="sd-section bg-white">
    <div class="sd-container">
        <div class="mx-auto max-w-3xl text-center">
            <p class="sd-label justify-center text-primary">How it works</p>
            <h2 class="mt-3 text-3xl font-bold leading-tight tracking-normal text-text-primary md:text-4xl">A clear path from registration to daily operations.</h2>
        </div>

        <div class="relative mt-16 lg:mt-20">
            <div class="absolute left-8 top-0 hidden h-px w-[calc(100%-4rem)] bg-gradient-to-r from-primary/30 via-accent/35 to-primary/30 lg:block"></div>
            <div class="grid gap-10 lg:grid-cols-4">
                @foreach ($process as $step)
                    <article class="relative pl-16 lg:pl-0">
                        <div class="absolute left-5 top-0 h-full w-px bg-border lg:hidden"></div>
                        <div class="absolute left-0 top-0 flex size-10 items-center justify-center rounded-full border border-primary/20 bg-white text-sm font-bold text-primary shadow-soft lg:relative lg:left-auto lg:top-auto lg:size-16 lg:text-lg">
                            {{ $step['step'] }}
                        </div>
                        <div class="mt-0 lg:mt-7">
                            <i data-lucide="{{ $step['icon'] }}" class="size-7 text-accent"></i>
                            <h3 class="mt-5 text-xl font-bold tracking-normal text-text-primary">{{ $step['title'] }}</h3>
                            <p class="mt-3 text-base leading-7 text-text-secondary">{{ $step['body'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section id="pricing" class="sd-section bg-surface-muted">
    <div class="sd-container">
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div class="max-w-3xl">
                <p class="sd-label text-primary">Subscription package preview</p>
                <h2 class="mt-3 text-3xl font-bold leading-tight tracking-normal text-text-primary md:text-4xl">Choose a plan that fits how your company operates.</h2>
            </div>
            <x-tw.button :href="route('register')" icon="arrow-right">Register company</x-tw.button>
        </div>

        <div class="mt-10 grid gap-6 lg:grid-cols-2">
            @foreach ([['Monthly', $monthlyPreview, 'monthly'], ['Yearly', $yearlyPreview, 'yearly']] as [$label, $packages, $duration])
                <div class="rounded-2xl border border-border bg-white p-6 shadow-soft">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-text-primary">{{ $label }} plans</h3>
                        <span class="rounded-full bg-primary-soft px-3 py-1 text-sm font-semibold text-primary">{{ $packages->count() }} shown</span>
                    </div>
                    <div class="mt-5 space-y-4">
                        @forelse ($packages as $package)
                            <article class="border-t border-border pt-4 first:border-t-0 first:pt-0">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <h4 class="text-lg font-bold text-text-primary">{{ $package->name }}</h4>
                                        <p class="mt-1 text-sm leading-6 text-text-secondary">{{ $package->description }}</p>
                                    </div>
                                    <p class="shrink-0 text-2xl font-bold text-primary">${{ number_format((float) $package->price, 2) }}</p>
                                </div>
                                @if ($package->features && $package->features->count())
                                    <ul class="mt-4 grid gap-2 sm:grid-cols-2">
                                        @foreach ($package->features->take(6) as $feature)
                                            <li class="flex gap-2 text-sm leading-6 text-text-secondary">
                                                <i data-lucide="check" class="mt-1 size-4 shrink-0 text-success"></i>
                                                <span>{{ $feature->feature_description }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                                <x-tw.button :href="route('register') . '?package_id=' . $package->id" variant="link" icon="arrow-right" class="mt-3">Select {{ $package->name }}</x-tw.button>
                            </article>
                        @empty
                            <p class="text-base leading-7 text-text-secondary">No {{ strtolower($label) }} packages are available right now.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="status-visibility" class="sd-section bg-white">
    <div class="sd-container grid gap-12 lg:grid-cols-[0.78fr_1.22fr] lg:items-center">
        <div>
            <p class="sd-label text-primary">Operational status visibility</p>
            <h2 class="mt-3 text-3xl font-bold leading-tight tracking-normal text-text-primary md:text-4xl">Keep every operational status clear.</h2>
            <p class="mt-5 text-lg leading-8 text-text-secondary">
                Bookings, payment verification, subscription access, and artist responses move independently. StageDesk Pro keeps each stage visible so your team knows what needs attention.
            </p>
        </div>

        <div class="rounded-3xl border border-border bg-surface-muted p-5 shadow-soft">
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($statusLanes as $lane)
                    <div class="rounded-2xl bg-white p-5">
                        <div class="flex items-center gap-3">
                            <span class="flex size-10 items-center justify-center rounded-xl bg-primary-soft text-primary">
                                <i data-lucide="{{ $lane['icon'] }}" class="size-5"></i>
                            </span>
                            <h3 class="text-lg font-bold text-text-primary">{{ $lane['title'] }}</h3>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-2">
                            @foreach ($lane['states'] as $state)
                                <span class="rounded-full border px-3 py-1.5 text-sm font-semibold {{ $chipTone[$state['tone']] }}">
                                    {{ $state['label'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="sd-section bg-surface-muted">
    <div class="sd-container">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
            <div>
                <p class="sd-label text-primary">Built for every participant</p>
                <h2 class="mt-3 text-3xl font-bold leading-tight tracking-normal text-text-primary md:text-4xl">One platform, distinct roles, cleaner handoffs.</h2>
                <p class="mt-4 text-lg leading-8 text-text-secondary">Each team or user type works from the part of the platform that matches their responsibilities.</p>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($roles as $role)
                    <article class="rounded-2xl border border-border bg-white p-6 shadow-soft">
                        <h3 class="text-xl font-bold text-text-primary">{{ $role['title'] }}</h3>
                        <p class="mt-3 text-base leading-7 text-text-secondary">{{ $role['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section id="testimonials" class="sd-section bg-white">
    <div class="sd-container">
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="sd-label text-primary">Testimonials</p>
                <h2 class="mt-3 text-3xl font-bold leading-tight tracking-normal text-text-primary md:text-4xl">What teams say about StageDesk Pro.</h2>
            </div>
        </div>
        <div class="mt-10 grid gap-5 md:grid-cols-3">
            @forelse ($testimonialPreview as $testimonial)
                <article class="rounded-2xl border border-border bg-surface-muted p-7">
                    <i data-lucide="quote" class="size-7 text-primary"></i>
                    <p class="mt-5 text-base leading-8 text-text-secondary">{{ $testimonial->testimonial }}</p>
                    <div class="mt-6 flex items-center gap-3">
                        <img src="{{ $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : asset('images/default.jpg') }}" alt="{{ $testimonial->name }}" class="size-12 rounded-full object-cover">
                        <div>
                            <p class="font-bold text-text-primary">{{ $testimonial->name }}</p>
                            <p class="text-sm text-text-muted">{{ $testimonial->designation }}</p>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-border bg-surface-muted p-7 md:col-span-3">
                    <p class="text-base leading-7 text-text-secondary">Testimonials will appear here once they are added.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section id="blog" class="sd-section bg-surface-muted">
    <div class="sd-container">
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="sd-label text-primary">Latest articles</p>
                <h2 class="mt-3 text-3xl font-bold leading-tight tracking-normal text-text-primary md:text-4xl">Ideas for cleaner event operations.</h2>
            </div>
            <x-tw.button :href="route('blogs')" variant="outline" icon="newspaper">View all articles</x-tw.button>
        </div>
        <div class="mt-10 grid gap-5 md:grid-cols-3">
            @forelse ($blogPreview as $blog)
                <article class="overflow-hidden rounded-2xl border border-border bg-white shadow-soft">
                    @if ($blog->feature_image || $blog->image)
                        <img src="{{ asset('storage/' . ($blog->feature_image ?: $blog->image)) }}" alt="{{ $blog->title }}" class="h-52 w-full object-cover">
                    @endif
                    <div class="p-6">
                        <p class="text-sm font-semibold text-text-muted">{{ optional($blog->category)->name ?? 'Uncategorized' }} | {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</p>
                        <h3 class="mt-3 text-xl font-bold leading-snug text-text-primary">
                            <a href="{{ route('blog-front.details', $blog->slug) }}" class="hover:text-primary">{{ $blog->title }}</a>
                        </h3>
                        <p class="mt-3 text-base leading-7 text-text-secondary">{{ $blog->excerpt ?: Str::limit(strip_tags($blog->content), 120) }}</p>
                        <x-tw.button :href="route('blog-front.details', $blog->slug)" variant="link" icon="arrow-right" class="mt-4">Read article</x-tw.button>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-border bg-white p-7 md:col-span-3">
                    <p class="text-base leading-7 text-text-secondary">No articles are available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="sd-section bg-white">
    <div class="sd-container">
        <div class="max-w-3xl">
            <p class="sd-label text-primary">FAQ</p>
            <h2 class="mt-3 text-3xl font-bold leading-tight tracking-normal text-text-primary md:text-4xl">Questions companies ask before getting started.</h2>
        </div>
        <div class="mt-10 grid gap-4 md:grid-cols-2">
            @foreach ($faqs as $faq)
                <article class="rounded-2xl border border-border bg-surface-muted p-6">
                    <h3 class="text-lg font-bold text-text-primary">{{ $faq['q'] }}</h3>
                    <p class="mt-3 text-base leading-7 text-text-secondary">{{ $faq['a'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-[#120f1d] py-16 text-white">
    <div class="sd-container flex flex-col gap-7 md:flex-row md:items-center md:justify-between">
        <div class="max-w-2xl">
            <h2 class="text-3xl font-bold tracking-normal text-white md:text-4xl">Create your company workspace.</h2>
            <p class="mt-3 text-base leading-7 text-slate-300">Start with registration, choose the package that fits your business, and bring bookings and artist coordination into one operating system.</p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row">
            <x-tw.button :href="route('register')" icon="building-2">Register company</x-tw.button>
            <x-tw.button :href="route('login')" variant="outline" icon="log-in" class="border-white/20 bg-white/5 text-white hover:bg-white/10">Login</x-tw.button>
        </div>
    </div>
</section>
@endsection
