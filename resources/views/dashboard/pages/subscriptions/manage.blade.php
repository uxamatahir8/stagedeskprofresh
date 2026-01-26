@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}">
                        <i data-lucide="home" style="width: 14px; height: 14px;"></i>
                    </a>
                </li>

                <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">Subscriptions</a></li>

                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>

    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header justify-content-between d-flex align-items-center">
                    <div class="title">
                        <h4 class="card-title mb-0">{{ $title }}</h4>
                    </div>
                    <div class="action-btns">
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-danger">
                            View Subscriptions
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ isset($subscription) ? route('subscriptions.update', $subscription) : route('subscriptions.store') }}" class="validate_form" method="post">
                        @csrf
                        @if(isset($subscription))
                            @method('PUT')
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_id" class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <select name="company_id" id="company_id" class="form-control required form-select">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id', $subscription->company_id ?? $id ?? '') == $company->id ? 'selected' : '' }}>
                                                {{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="package_id" class="form-label">Package Name <span class="text-danger">*</span></label>
                                    <select name="package_id" id="package_id" class="form-control required form-select">
                                        <option value="">Select Package</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package->id }}" {{ old('package_id', $subscription->package_id ?? '') == $package->id ? 'selected' : '' }}>
                                                {{ $package->package_name }} ({{ $package->price }}) - {{ ucfirst($package->duration_type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if(isset($subscription))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="text" id="start_date" class="form-control" disabled value="{{ $subscription->start_date->format('M d, Y') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="text" id="end_date" class="form-control" disabled value="{{ $subscription->end_date->format('M d, Y') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Auto Renew</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="auto_renew" id="autoRenew" value="1" {{ old('auto_renew', $subscription->auto_renew ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="autoRenew">
                                            Enable automatic renewal on expiry
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <p class="mb-0">
                                        <span class="badge badge-{{ $subscription->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($subscription->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <hr>
                        <div class="row">
                            {{-- Save Button --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-{{ isset($subscription) ? 'warning' : 'primary' }}">
                                        <i data-lucide="{{ isset($subscription) ? 'pencil' : 'plus' }}" style="width: 16px; height: 16px;"></i>
                                        {{ isset($subscription) ? 'Update' : 'Create' }} Subscription
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
