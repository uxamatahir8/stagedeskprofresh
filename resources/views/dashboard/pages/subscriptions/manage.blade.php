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
                        <i class="ti ti-home"></i>
                    </a>
                </li>

                <li class="breadcrumb-item"><a href="{{ route('subscriptions') }}">Subscriptions</a></li>

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
                        <a href="{{ route('subscriptions') }}" class="btn btn-danger">
                            View Subscriptions
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('subscription.store') }}" class="validate_form" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_id" class="form-label">Company Name</label>
                                    <select name="company_id" id="company_id" class="form-control required form-select">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" {{ $company->id == $id ? 'selected' : '' }}>
                                                {{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="package_id" class="form-label">Package Name</label>
                                    <select name="package_id" id="package_id" class="form-control required form-select">
                                        <option value="">Select Package</option>
                                        @foreach ($packages as $package)
                                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            {{-- Save Button --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        Save Subscription
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
