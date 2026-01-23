@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="activity" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">System Activity Logs & Audit Trail</p>
        </div>
        <div class="text-end">
            <button class="btn btn-light btn-sm me-2" onclick="location.reload()">
                <i data-lucide="refresh-cw"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-logs') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">User</label>
                        <input type="text" name="user" class="form-control" placeholder="Search by user name" value="{{ request('user') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Action</label>
                        <select name="action" class="form-select">
                            <option value="">All Actions</option>
                            <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>Created</option>
                            <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>Updated</option>
                            <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                            <option value="viewed" {{ request('action') === 'viewed' ? 'selected' : '' }}>Viewed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="filter"></i> Apply Filters
                        </button>
                        <a href="{{ route('admin.activity-logs') }}" class="btn btn-light">
                            <i data-lucide="x"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Activity Logs</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs ?? [] as $log)
                            <tr>
                                <td>#{{ $log->id }}</td>
                                <td>
                                    @if($log->user)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                    {{ substr($log->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fs-sm">{{ $log->user->name }}</h6>
                                                <small class="text-muted">{{ $log->user->email }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{
                                        $log->action === 'created' ? 'success' :
                                        ($log->action === 'updated' ? 'info' :
                                        ($log->action === 'deleted' ? 'danger' : 'secondary'))
                                    }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td><code>{{ class_basename($log->model_type ?? 'N/A') }}</code></td>
                                <td>{{ $log->description }}</td>
                                <td><small class="text-muted">{{ $log->ip_address ?? 'N/A' }}</small></td>
                                <td>
                                    <small class="text-muted">
                                        {{ $log->created_at->format('M d, Y H:i') }}<br>
                                        <span class="text-success">{{ $log->created_at->diffForHumans() }}</span>
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i data-lucide="inbox" class="mb-2" style="width: 48px; height: 48px;"></i>
                                    <p>No activity logs found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if(isset($logs) && $logs->hasPages())
        <div class="card-footer bg-transparent border-top">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
@endsection
