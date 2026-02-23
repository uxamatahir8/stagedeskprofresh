@extends('dashboard.layouts.dashboard')

@push('styles')
    <style>
        .activity-logs-pagination svg {
            width: 14px !important;
            height: 14px !important;
            max-width: 14px !important;
            vertical-align: middle;
        }

        .activity-logs-pagination .pagination {
            margin-bottom: 0;
        }
    </style>
@endpush

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
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            <span class="badge badge-soft-info">Retention: Permanent</span>
        </div>

        <div class="card-body">
            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            @if(isset($flowGroups) && $flowGroups->count() > 0)
                <div class="row g-3 mb-4">
                    @foreach($flowGroups->take(4) as $flow)
                        <div class="col-md-6 col-xl-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="small text-uppercase text-muted">Flow</strong>
                                    <span class="badge badge-soft-primary">{{ $flow['count'] }} events</span>
                                </div>
                                <div class="text-truncate small" title="{{ $flow['key'] }}">{{ $flow['key'] }}</div>
                                <small class="text-muted d-block mt-2">
                                    {{ optional($flow['first_at'])->diffForHumans() }} -> {{ optional($flow['last_at'])->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Filters -->
            <form method="GET" action="{{ route('activity-logs.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="action" class="form-label">Action</label>
                        <select name="action" id="action" class="form-select">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="category" id="category" class="form-select">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_from" class="form-label">Date From</label>
                        <input type="date" name="date_from" id="date_from" class="form-control"
                            value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_to" class="form-label">Date To</label>
                        <input type="date" name="date_to" id="date_to" class="form-control"
                            value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="severity" class="form-label">Severity</label>
                        <select name="severity" id="severity" class="form-select">
                            <option value="">All Severities</option>
                            @foreach($severities as $severity)
                                <option value="{{ $severity }}" {{ request('severity') == $severity ? 'selected' : '' }}>
                                    {{ ucfirst($severity) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="event_key" class="form-label">Event Key</label>
                        <input type="text" name="event_key" id="event_key" class="form-control" value="{{ request('event_key') }}" placeholder="e.g. auth.login.success">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Category</th>
                            <th>Severity</th>
                            <th>Action</th>
                            <th>Event Key</th>
                            <th>Status</th>
                            <th>Flow</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = ($logs->currentPage() - 1) * $logs->perPage() + 1; @endphp
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ optional($log->user)->name ?? 'System' }}</td>
                                <td><span class="badge badge-soft-secondary text-uppercase">{{ $log->category ?? 'general' }}</span></td>
                                <td>
                                    <span class="badge
                                        @if($log->severity === 'error') badge-soft-danger
                                        @elseif($log->severity === 'warning') badge-soft-warning
                                        @elseif($log->severity === 'success') badge-soft-success
                                        @else badge-soft-info
                                        @endif">
                                        {{ ucfirst($log->severity ?? 'info') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-label
                                        @if($log->action == 'created') badge-soft-success
                                        @elseif($log->action == 'updated') badge-soft-info
                                        @elseif($log->action == 'deleted') badge-soft-danger
                                        @elseif($log->action == 'login') badge-soft-primary
                                        @elseif($log->action == 'logout') badge-soft-warning
                                        @else badge-soft-secondary
                                        @endif">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td><small>{{ $log->event_key ?? 'N/A' }}</small></td>
                                <td><small>{{ ucfirst($log->status ?? 'N/A') }}</small></td>
                                <td><small>{{ $log->correlation_key ?? ('request:' . ($log->request_id ?? 'N/A')) }}</small></td>
                                <td>{{ Str::limit($log->description, 50) }}</td>
                                <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('activity-logs.show', $log) }}" class="btn btn-sm btn-primary" title="View Details">
                                            <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center">No activity logs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3 activity-logs-pagination">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
