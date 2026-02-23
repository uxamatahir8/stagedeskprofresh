@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}"><i data-lucide="home" style="width: 14px; height: 14px;"></i></a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <p class="text-muted mb-4">Select a subscription package for your company. After selecting, you will be directed to complete payment. Access to the dashboard is granted once your payment is verified by the administrator.</p>
        </div>
        @forelse($packages as $package)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 border shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $package->name }}</h5>
                        @if($package->description)
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($package->description, 100) }}</p>
                        @endif
                        <p class="mb-2">
                            <span class="fs-4 fw-bold text-primary">{{ $package->price == 0 ? 'Free' : $package->price . ' €' }}</span>
                            <span class="text-muted">/ {{ ucfirst($package->duration_type ?? 'monthly') }}</span>
                        </p>
                        <ul class="list-unstyled small mb-3">
                            @if($package->max_users_allowed !== null)
                                <li><i data-lucide="users" style="width: 14px; height: 14px;" class="text-muted me-1"></i> {{ $package->max_users_allowed }} users</li>
                            @endif
                            @if($package->max_requests_allowed !== null)
                                <li><i data-lucide="calendar-check" style="width: 14px; height: 14px;" class="text-muted me-1"></i> {{ $package->max_requests_allowed }} requests</li>
                            @endif
                            @if($package->max_responses_allowed !== null)
                                <li><i data-lucide="music" style="width: 14px; height: 14px;" class="text-muted me-1"></i> {{ $package->max_responses_allowed }} artist responses</li>
                            @endif
                        </ul>
                        <form action="{{ route('packages.choose.store') }}" method="POST" class="mt-auto">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <button type="submit" class="btn btn-primary w-100">
                                <i data-lucide="check" style="width: 16px; height: 16px;"></i> Choose this package
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">No active packages are available at the moment. Please contact support.</div>
            </div>
        @endforelse
    </div>
@endsection
