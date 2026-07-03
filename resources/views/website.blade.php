@extends('layouts.tailwind-public')

@section('meta_description', 'StageDesk Pro is a SaaS operations platform for event-service companies managing subscriptions, artists, bookings, payment proof, and affiliate growth.')

@section('content')
@php
    $formatLimit = fn ($value, $unit = '') => is_null($value) ? 'Included' : number_format((float) $value) . $unit;
    $workflow = [
        ['icon' => 'building-2', 'title' => 'Register company', 'body' => 'Create the company profile, admin account, and operating details used throughout the portal.'],
        ['icon' => 'badge-dollar-sign', 'title' => 'Choose a plan', 'body' => 'Select a monthly or yearly subscription package based on artist, request, and response limits.'],
        ['icon' => 'file-check-2', 'title' => 'Submit payment proof', 'body' => 'Upload proof for manual verification before subscription access is activated.'],
        ['icon' => 'calendar-check-2', 'title' => 'Run operations', 'body' => 'Manage users, artists, customer booking requests, assignments, earnings, and referrals.'],
    ];
    $capabilities = [
        ['icon' => 'users', 'title' => 'Company management', 'body' => 'Maintain company users, artist records, profiles, and role-specific access.'],
        ['icon' => 'refresh-cw', 'title' => 'Subscription management', 'body' => 'Track package limits, subscription state, renewal windows, and payment verification.'],
        ['icon' => 'clipboard-list', 'title' => 'Booking operations', 'body' => 'Receive customer booking requests and coordinate company responses from the dashboard.'],
        ['icon' => 'shield-check', 'title' => 'Manual payment verification', 'body' => 'Keep submitted proof visible while admins review and mark payment status.'],
        ['icon' => 'user-check', 'title' => 'Artist assignment', 'body' => 'Assign artists to paid bookings and track response or assignment progress separately.'],
        ['icon' => 'wallet-cards', 'title' => 'Earnings and withdrawals', 'body' => 'Review artist earnings and withdrawal requests through the operational workflow.'],
        ['icon' => 'share-2', 'title' => 'Referral growth', 'body' => 'Support affiliate referrals, commissions, and performance visibility for growth programs.'],
    ];
    $roles = [
        ['icon' => 'briefcase-business', 'title' => 'Company Admin', 'items' => ['Register company details', 'Manage artists and users', 'Review booking and payment statuses', 'Assign artists to paid bookings']],
        ['icon' => 'mic-2', 'title' => 'Artist', 'items' => ['Maintain artist profile', 'Review assigned work', 'Track earnings', 'Request withdrawals where enabled']],
        ['icon' => 'calendar-days', 'title' => 'Customer', 'items' => ['Submit booking requests', 'Share event requirements', 'Follow request and payment status']],
        ['icon' => 'network', 'title' => 'Affiliate', 'items' => ['Share referral links', 'Track referred companies', 'Review commission performance']],
    ];
    $statusRows = [
        ['label' => 'Booking status', 'value' => 'Pending -> Confirmed -> Completed', 'icon' => 'calendar-clock'],
        ['label' => 'Payment status', 'value' => 'Awaiting proof -> Submitted -> Verified', 'icon' => 'receipt-text'],
        ['label' => 'Subscription status', 'value' => 'Selected -> Pending verification -> Active', 'icon' => 'badge-check'],
        ['label' => 'Artist response', 'value' => 'Unassigned -> Assigned -> Accepted or rejected', 'icon' => 'user-round-check'],
    ];
    $faqs = [
        ['q' => 'How does a company start using StageDesk Pro?', 'a' => 'A company registers an account, selects a package, and follows the subscription payment proof flow before using company operations.'],
        ['q' => 'Are customer bookings and subscription payments the same workflow?', 'a' => 'No. Booking status, payment status, subscription status, and artist assignment status are tracked as separate workflows.'],
        ['q' => 'Does StageDesk Pro automatically match artists to customers?', 'a' => 'No. Companies manage artists and assign them to bookings through the dashboard workflow.'],
        ['q' => 'How are payment proofs handled?', 'a' => 'Submitted payment proof is retained for manual review and verification inside the platform.'],
    ];
@endphp

<section class="overflow-hidden bg-sidebar text-white">
    <div class="sd-container grid gap-12 py-16 lg:grid-cols-[1fr_0.92fr] lg:items-center lg:py-20">
        <div>
            <p class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-sm font-semibold text-slate-200">
                <i data-lucide="layout-dashboard" class="size-4"></i>
                SaaS operations for event-service companies
            </p>
            <h1 class="mt-6 max-w-3xl text-4xl font-bold leading-tight tracking-normal text-white md:text-5xl">
                Manage company subscriptions, artists, bookings, and payment verification from one portal.
            </h1>
            <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300">
                StageDesk Pro helps event-service businesses register companies, choose subscription packages, submit payment proof, manage artists, handle customer booking requests, and track every operational status clearly.
            </p>
            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <x-tw.button :href="route('register')" size="lg" icon="building-2">Register your company</x-tw.button>
                <x-tw.button :href="route('login')" variant="outline" size="lg" icon="log-in" class="border-white/20 bg-white/5 text-white hover:bg-white/10">Sign in</x-tw.button>
            </div>
        </div>

        <div class="rounded-card border border-white/10 bg-white/[0.06] p-4 shadow-panel">
            <div class="rounded-card bg-slate-950/60 p-4">
                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-300">Operations cockpit</p>
                        <p class="text-xs text-slate-500">Company subscription and booking view</p>
                    </div>
                    <span class="sd-status sd-status-active">Active plan</span>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    @foreach ([['Booking requests', $landingStats['total_bookings'] ?? 0, 'clipboard-list'], ['Active artists', $landingStats['active_artists'] ?? 0, 'mic-2'], ['Active companies', $landingStats['active_companies'] ?? 0, 'building-2'], ['Subscriptions', $landingStats['active_subscriptions'] ?? 0, 'badge-check']] as [$label, $value, $icon])
                        <div class="rounded-card border border-white/10 bg-white/[0.07] p-4">
                            <i data-lucide="{{ $icon }}" class="size-5 text-accent"></i>
                            <p class="mt-3 text-2xl font-bold text-white">{{ number_format((float) $value) }}</p>
                            <p class="text-sm text-slate-400">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 space-y-3">
                    @foreach ($statusRows as $row)
                        <div class="flex items-start gap-3 rounded-card border border-white/10 bg-white/[0.05] p-3">
                            <i data-lucide="{{ $row['icon'] }}" class="mt-0.5 size-5 text-slate-300"></i>
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $row['label'] }}</p>
                                <p class="text-xs text-slate-400">{{ $row['value'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section id="works" class="sd-section bg-surface">
    <div class="sd-container">
        <div class="max-w-2xl">
            <p class="sd-label text-primary">Core workflow</p>
            <h2 class="sd-title-section mt-3">From company registration to managed operations.</h2>
        </div>
        <div class="mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($workflow as $index => $step)
                <div class="sd-card sd-card-pad">
                    <span class="sd-status sd-status-info">Step {{ $index + 1 }}</span>
                    <i data-lucide="{{ $step['icon'] }}" class="mt-5 size-7 text-primary"></i>
                    <h3 class="sd-title-card mt-4">{{ $step['title'] }}</h3>
                    <p class="sd-text-body mt-2">{{ $step['body'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="features" class="sd-section bg-surface-muted">
    <div class="sd-container">
        <div class="grid gap-8 lg:grid-cols-[0.7fr_1.3fr] lg:items-start">
            <div>
                <p class="sd-label text-primary">Platform capabilities</p>
                <h2 class="sd-title-section mt-3">Built around the real operating model.</h2>
                <p class="sd-text-body mt-4">The public site now describes the workflows already represented in the application: manual verification, separated statuses, artists, companies, customers, and affiliates.</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($capabilities as $feature)
                    <div class="sd-card sd-card-pad">
                        <i data-lucide="{{ $feature['icon'] }}" class="size-6 text-accent"></i>
                        <h3 class="sd-title-card mt-4">{{ $feature['title'] }}</h3>
                        <p class="sd-text-body mt-2">{{ $feature['body'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="sd-section bg-surface">
    <div class="sd-container">
        <div class="max-w-2xl">
            <p class="sd-label text-primary">Role-based experience</p>
            <h2 class="sd-title-section mt-3">Each role sees the work that belongs to them.</h2>
        </div>
        <div class="mt-10 grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            @foreach ($roles as $role)
                <div class="sd-card sd-card-pad">
                    <i data-lucide="{{ $role['icon'] }}" class="size-6 text-primary"></i>
                    <h3 class="sd-title-card mt-4">{{ $role['title'] }}</h3>
                    <ul class="mt-4 space-y-2">
                        @foreach ($role['items'] as $item)
                            <li class="flex gap-2 text-sm leading-6 text-text-secondary">
                                <i data-lucide="check" class="mt-1 size-4 shrink-0 text-success"></i>
                                <span>{{ $item }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="pricing" class="sd-section bg-surface-muted">
    <div class="sd-container">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="sd-label text-primary">Packages</p>
                <h2 class="sd-title-section mt-3">Choose a monthly or yearly subscription package.</h2>
                <p class="sd-text-body mt-3 max-w-2xl">Package names, prices, limits, and features are loaded from the existing package records.</p>
            </div>
            <x-tw.button :href="route('register')" icon="arrow-right">Register company</x-tw.button>
        </div>

        @foreach ([['Monthly packages', $monthly_packages, 'monthly'], ['Yearly packages', $yearly_packages, 'yearly']] as [$heading, $packages, $duration])
            <div class="mt-10">
                <h3 class="text-lg font-bold text-text-primary">{{ $heading }}</h3>
                <div class="mt-4 grid gap-4 lg:grid-cols-3">
                    @forelse ($packages as $package)
                        <div class="sd-card sd-card-pad flex flex-col">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h4 class="text-xl font-bold text-text-primary">{{ $package->name }}</h4>
                                    <p class="sd-text-body mt-2">{{ $package->description }}</p>
                                </div>
                                <span class="sd-status sd-status-info">{{ ucfirst($duration) }}</span>
                            </div>
                            <p class="mt-6 text-3xl font-bold text-primary">${{ number_format((float) $package->price, 2) }}</p>
                            <div class="mt-5 grid gap-2 text-sm text-text-secondary">
                                <span>{{ $formatLimit($package->max_users_allowed) }} artists allowed</span>
                                <span>{{ $formatLimit($package->max_requests_allowed, '/' . $duration) }} booking requests</span>
                                <span>{{ $formatLimit($package->max_responses_allowed, '/' . $duration) }} artist responses</span>
                            </div>
                            @if ($package->features && $package->features->count())
                                <ul class="mt-5 space-y-2">
                                    @foreach ($package->features as $feature)
                                        <li class="flex gap-2 text-sm leading-6 text-text-secondary">
                                            <i data-lucide="check" class="mt-1 size-4 shrink-0 text-success"></i>
                                            <span>{{ $feature->feature_description }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            <x-tw.button :href="route('register') . '?package_id=' . $package->id" class="mt-6" icon="badge-dollar-sign">Select {{ $package->name }}</x-tw.button>
                        </div>
                    @empty
                        <div class="sd-card sd-card-pad lg:col-span-3">
                            <p class="sd-text-body">No {{ $duration }} packages are available right now.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="sd-section bg-surface">
    <div class="sd-container grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
        <div>
            <p class="sd-label text-primary">Status visibility</p>
            <h2 class="sd-title-section mt-3">Separate statuses keep the workflow understandable.</h2>
            <p class="sd-text-body mt-4">A booking can move through operations while payment proof, subscription access, and artist assignment each keep their own state.</p>
        </div>
        <div class="grid gap-3 sm:grid-cols-2">
            @foreach ($statusRows as $row)
                <div class="sd-card sd-card-pad">
                    <i data-lucide="{{ $row['icon'] }}" class="size-6 text-accent"></i>
                    <h3 class="sd-title-card mt-4">{{ $row['label'] }}</h3>
                    <p class="sd-text-body mt-2">{{ $row['value'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="testimonials" class="sd-section bg-surface-muted">
    <div class="sd-container">
        <p class="sd-label text-primary">Testimonials</p>
        <h2 class="sd-title-section mt-3">Customer notes from the platform.</h2>
        <div class="mt-10 grid gap-4 md:grid-cols-3">
            @forelse ($testimonials as $testimonial)
                <div class="sd-card sd-card-pad">
                    <i data-lucide="quote" class="size-6 text-primary"></i>
                    <p class="mt-4 text-sm leading-7 text-text-secondary">{{ $testimonial->testimonial }}</p>
                    <div class="mt-5 flex items-center gap-3">
                        <img src="{{ $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : asset('images/default.jpg') }}" alt="{{ $testimonial->name }}" class="size-11 rounded-full object-cover">
                        <div>
                            <p class="text-sm font-bold text-text-primary">{{ $testimonial->name }}</p>
                            <p class="text-xs text-text-muted">{{ $testimonial->designation }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="sd-card sd-card-pad md:col-span-3">
                    <p class="sd-text-body">Testimonials will appear here once they are added.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section id="blog" class="sd-section bg-surface">
    <div class="sd-container">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="sd-label text-primary">Blog highlights</p>
                <h2 class="sd-title-section mt-3">Latest public articles.</h2>
            </div>
            <x-tw.button :href="route('blogs')" variant="outline" icon="newspaper">View all blogs</x-tw.button>
        </div>
        <div class="mt-10 grid gap-4 md:grid-cols-3">
            @forelse ($blogs as $blog)
                <article class="sd-card overflow-hidden">
                    @if ($blog->feature_image || $blog->image)
                        <img src="{{ asset('storage/' . ($blog->feature_image ?: $blog->image)) }}" alt="{{ $blog->title }}" class="h-48 w-full object-cover">
                    @endif
                    <div class="sd-card-pad">
                        <p class="sd-meta">{{ optional($blog->category)->name ?? 'Uncategorized' }} | {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</p>
                        <h3 class="mt-3 text-lg font-bold leading-snug text-text-primary">
                            <a href="{{ route('blog-front.details', $blog->slug) }}" class="hover:text-primary">{{ $blog->title }}</a>
                        </h3>
                        <p class="sd-text-body mt-3">{{ $blog->excerpt ?: Str::limit(strip_tags($blog->content), 120) }}</p>
                        <x-tw.button :href="route('blog-front.details', $blog->slug)" variant="link" icon="arrow-right" class="mt-4">Read more</x-tw.button>
                    </div>
                </article>
            @empty
                <div class="sd-card sd-card-pad md:col-span-3">
                    <p class="sd-text-body">No blogs available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="sd-section bg-surface-muted">
    <div class="sd-container">
        <p class="sd-label text-primary">FAQ</p>
        <h2 class="sd-title-section mt-3">Common questions.</h2>
        <div class="mt-8 grid gap-4 md:grid-cols-2">
            @foreach ($faqs as $faq)
                <div class="sd-card sd-card-pad">
                    <h3 class="sd-title-card">{{ $faq['q'] }}</h3>
                    <p class="sd-text-body mt-2">{{ $faq['a'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-sidebar py-14 text-white">
    <div class="sd-container flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-white">Ready to organize company operations?</h2>
            <p class="mt-2 text-sm leading-6 text-slate-300">Register your company, choose a package, and move through the existing payment proof workflow.</p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row">
            <x-tw.button :href="route('register')" icon="building-2">Register company</x-tw.button>
            <x-tw.button :href="route('login')" variant="outline" icon="log-in" class="border-white/20 bg-white/5 text-white hover:bg-white/10">Login</x-tw.button>
        </div>
    </div>
</section>
@endsection
