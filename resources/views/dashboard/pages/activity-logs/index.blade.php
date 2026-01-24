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
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            @if(Auth::user()->role->role_key === 'master_admin')
                <form action="{{ route('activity-logs.clear-old') }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Are you sure you want to clear old activity logs?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Clear Old Logs (90+ days)</button>
                </form>
            @endif
        </div>

        <div class="card-body">
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
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>User Agent</th>
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
                                <td>{{ Str::limit($log->description, 50) }}</td>
                                <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                <td>{{ Str::limit($log->user_agent ?? 'N/A', 30) }}</td>
                                <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                <td>
                                    <div class="action-btn">
                                        <a href="{{ route('activity-logs.show', $log) }}" class="btn btn-sm btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No activity logs found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
