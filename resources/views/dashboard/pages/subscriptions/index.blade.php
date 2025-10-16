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

                <li class="breadcrumb-item"><a href="{{ route('companies') }}">Companies</a></li>

                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>

    </div>
    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            <a href="{{ route('subscription.create') }}" class="btn btn-primary">
                Add Subscription
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Package Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Auto Renewal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $c = 1;
                        @endphp
                        @foreach ($companySubscription as $subscription)
                            <tr>
                                <td>{{ $c }}</td>
                                <td>{{ $subscription->company->name }}</td>
                                <td>{{ $subscription->package->name }}</td>
                                <td>{{ date('D, d M Y', strtotime($subscription->start_date)) }}</td>
                                <td>{{ date('D, d M Y', strtotime($subscription->end_date)) }}</td>
                                <td>{{ $subscription->auto_renew == 1 ? 'Yes' : 'No' }}</td>
                                <td>
                                    <span
                                        class="badge badge-label badge-soft-{{ config('arrays.status_colors')[$subscription->status]  }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                            </tr>
                            @php
                                $c++;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
