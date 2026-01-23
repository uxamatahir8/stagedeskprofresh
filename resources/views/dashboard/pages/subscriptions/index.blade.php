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

                <li class="breadcrumb-item"><a href="{{ route('subscriptions.index') }}">Subscriptions</a></li>

                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>

    </div>
    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
                <i class="ti ti-plus"></i> Add Subscription
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>ID</th>
                            <th>Company Name</th>
                            <th>Package Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Auto Renewal</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companySubscription as $subscription)
                            <tr>
                                <td>#{{ $subscription->id }}</td>
                                <td>{{ $subscription->company->company_name }}</td>
                                <td>{{ $subscription->package->package_name }}</td>
                                <td>{{ $subscription->start_date->format('D, d M Y') }}</td>
                                <td>{{ $subscription->end_date->format('D, d M Y') }}</td>
                                <td>
                                    <span class="badge badge-{{ $subscription->auto_renew ? 'success' : 'secondary' }}">
                                        {{ $subscription->auto_renew ? 'Yes' : 'No' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $subscription->status === 'active' ? 'success' : ($subscription->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($subscription->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-icon-btn gap-2 d-flex">
                                        <a href="{{ route('subscriptions.show', $subscription) }}" class="btn btn-sm btn-info" title="View">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        @if($subscription->status === 'active')
                                            <a href="{{ route('subscriptions.edit', $subscription) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <form action="{{ route('subscriptions.destroy', $subscription) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Cancel" onclick="return confirm('Are you sure?')">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
