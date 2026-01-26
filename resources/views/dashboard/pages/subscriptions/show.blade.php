@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">Subscription Details</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i class="ti ti-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('subscriptions.index') }}">Subscriptions</a>
                </li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Subscription #{{ $subscription->id }}</h4>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Company</label>
                            <p class="mb-0 fw-bold">
                                <a href="{{ route('company.show', $subscription->company->id) }}">
                                    {{ $subscription->company->company_name }}
                                </a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Package</label>
                            <p class="mb-0 fw-bold">
                                {{ $subscription->package->package_name }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Price</label>
                            <p class="mb-0 fs-5">
                                ${{ number_format($subscription->package->price, 2) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Duration</label>
                            <p class="mb-0">
                                {{ ucfirst($subscription->package->duration_type) }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Start Date</label>
                            <p class="mb-0">
                                {{ $subscription->start_date->format('M d, Y h:i A') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">End Date</label>
                            <p class="mb-0">
                                {{ $subscription->end_date->format('M d, Y h:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Status</label>
                            <p class="mb-0">
                                @php
                                    $statusClass = match($subscription->status) {
                                        'active' => 'success',
                                        'pending' => 'warning',
                                        'expired' => 'danger',
                                        'canceled' => 'secondary',
                                        default => 'info'
                                    };
                                @endphp
                                <span class="badge badge-{{ $statusClass }}">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted mb-1">Auto Renew</label>
                            <p class="mb-0">
                                <span class="badge badge-{{ $subscription->auto_renew ? 'success' : 'secondary' }}">
                                    {{ $subscription->auto_renew ? 'Enabled' : 'Disabled' }}
                                </span>
                            </p>
                        </div>
                    </div>

                    @php
                        $daysRemaining = now()->diffInDays($subscription->end_date, false);
                    @endphp

                    <div class="alert alert-{{ $daysRemaining < 7 ? 'warning' : 'info' }}" role="alert">
                        <i class="ti ti-info-circle"></i>
                        @if($daysRemaining > 0)
                            <strong>{{ $daysRemaining }} days remaining</strong> on this subscription.
                        @elseif($daysRemaining === 0)
                            <strong>Expires today!</strong> Please renew to maintain service.
                        @else
                            <strong>Expired</strong> - {{ abs($daysRemaining) }} days ago.
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Actions</h4>
                </div>

                <div class="card-body">
                    @if($subscription->status === 'active')
                        <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-warning w-100 mb-2">
                            <i class="ti ti-pencil"></i> Edit Subscription
                        </a>

                        <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to cancel this subscription?')">
                                <i class="ti ti-trash"></i> Cancel Subscription
                            </button>
                        </form>
                    @elseif($subscription->status === 'canceled')
                        <a href="{{ route('subscriptions.create') }}" class="btn btn-primary w-100">
                            <i class="ti ti-plus"></i> Create New Subscription
                        </a>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="ti ti-info-circle"></i>
                            <strong>{{ ucfirst($subscription->status) }} Subscription</strong>
                            <p class="mb-0 mt-2">This subscription cannot be modified in its current state.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Subscription Features</h4>
                </div>

                <div class="card-body">
                    @if($subscription->package->features && $subscription->package->features->count() > 0)
                        <ul class="list-unstyled">
                            @foreach($subscription->package->features as $feature)
                                <li class="mb-2">
                                    <i class="ti ti-check text-success"></i>
                                    {{ $feature->feature_description }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No features defined for this package.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Information</h4>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted mb-1">Subscription ID</label>
                        <p class="mb-0">
                            <code>#{{ $subscription->id }}</code>
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted mb-1">Created</label>
                        <p class="mb-0">
                            {{ $subscription->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted mb-1">Last Updated</label>
                        <p class="mb-0">
                            {{ $subscription->updated_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Back</h4>
                </div>

                <div class="card-body">
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-primary w-100">
                        <i class="ti ti-arrow-left"></i> Back to Subscriptions
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
