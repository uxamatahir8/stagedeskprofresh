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
                <li class="breadcrumb-item">
                    <a href="{{ route('activity-logs.index') }}">Activity Logs</a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            <a href="{{ route('activity-logs.index') }}" class="btn btn-primary">Back to List</a>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Activity Information</h5>

                        <div class="row mb-3">
                            <div class="col-4 fw-semibold">User:</div>
                            <div class="col-8">{{ optional($activityLog->user)->name ?? 'System' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4 fw-semibold">Action:</div>
                            <div class="col-8">
                                <span class="badge badge-label
                                    @if($activityLog->action == 'created') badge-soft-success
                                    @elseif($activityLog->action == 'updated') badge-soft-info
                                    @elseif($activityLog->action == 'deleted') badge-soft-danger
                                    @elseif($activityLog->action == 'login') badge-soft-primary
                                    @elseif($activityLog->action == 'logout') badge-soft-warning
                                    @else badge-soft-secondary
                                    @endif">
                                    {{ ucfirst($activityLog->action) }}
                                </span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4 fw-semibold">Description:</div>
                            <div class="col-8">{{ $activityLog->description }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4 fw-semibold">Created At:</div>
                            <div class="col-8">{{ $activityLog->created_at->format('l, F d, Y h:i:s A') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Request Information</h5>

                        <div class="row mb-3">
                            <div class="col-4 fw-semibold">IP Address:</div>
                            <div class="col-8">{{ $activityLog->ip_address ?? 'N/A' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-4 fw-semibold">User Agent:</div>
                            <div class="col-8">
                                <small class="text-break">{{ $activityLog->user_agent ?? 'N/A' }}</small>
                            </div>
                        </div>

                        @if($activityLog->properties)
                        <div class="row mb-3">
                            <div class="col-4 fw-semibold">Properties:</div>
                            <div class="col-8">
                                <pre class="bg-light p-3 rounded"><code>{{ json_encode(json_decode($activityLog->properties), JSON_PRETTY_PRINT) }}</code></pre>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($activityLog->user)
            <div class="row mt-4">
                <div class="col-12">
                    <h5 class="fw-bold mb-3">User Details</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">Name</th>
                                <td>{{ $activityLog->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $activityLog->user->email }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ optional($activityLog->user->role)->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge badge-label badge-soft-{{ config('arrays.status_colors')[$activityLog->user->status] ?? 'secondary' }}">
                                        {{ ucfirst($activityLog->user->status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
