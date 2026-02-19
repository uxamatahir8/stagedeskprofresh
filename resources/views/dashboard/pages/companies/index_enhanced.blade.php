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
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <div class="title">
                <h4 class="card-title mb-0">{{ $title }}</h4>
            </div>
            <div class="action-btns d-flex gap-2">
                <a href="{{ route('subscriptions.index') }}" class="btn btn-warning btn-sm">
                    <i data-lucide="credit-card" style="width: 16px; height: 16px;"></i> Subscriptions
                </a>
                <a href="{{ route('company.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Add Company
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- Filters --}}
            <form method="GET" action="{{ route('companies') }}" id="filterForm" class="row g-2 mb-3">
                <div class="col-md-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i data-lucide="search" style="width: 14px; height: 14px;"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="subscription" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                        <option value="">Subscription</option>
                        <option value="active" {{ request('subscription') === 'active' ? 'selected' : '' }}>With Subscription</option>
                        <option value="inactive" {{ request('subscription') === 'inactive' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort_by" class="form-select form-select-sm" onchange="document.getElementById('filterForm').submit()">
                        <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name</option>
                        <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary"><i data-lucide="filter" style="width: 14px; height: 14px;"></i></button>
                    <a href="{{ route('companies') }}" class="btn btn-sm btn-secondary"><i data-lucide="x" style="width: 14px; height: 14px;"></i></a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>#</th>
                            <th>Company</th>
                            <th>KVK</th>
                            <th>Contact</th>
                            <th>Artists</th>
                            <th>Bookings</th>
                            <th>Status</th>
                            <th>Subscription</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($companies as $index => $company)
                            <tr>
                                <td>{{ $companies->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($company->logo)
                                            <img src="{{ asset('storage/' . $company->logo) }}" class="rounded me-2" width="32" height="32" style="object-fit: cover;">
                                        @else
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title rounded bg-primary">{{ substr($company->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <strong>{{ $company->name }}</strong>
                                            @if($company->website)
                                                <br><small class="text-muted"><a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a></small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $company->kvk_number ?? '-' }}</td>
                                <td>
                                    <div>{{ $company->email }}</div>
                                    <small class="text-muted">{{ $company->phone }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i data-lucide="users" style="width: 12px; height: 12px;"></i> {{ $company->artists_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">
                                        <i data-lucide="calendar" style="width: 12px; height: 12px;"></i> {{ $company->bookings_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-label badge-soft-{{ config('arrays.status_colors')[$company->status] ?? 'secondary' }}">
                                        {{ ucfirst($company->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $subscription = $company->activeSubscription;
                                    @endphp
                                    @if($subscription)
                                        <span class="badge bg-info-subtle text-info">
                                            <i data-lucide="check-circle" style="width: 12px; height: 12px;"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i data-lucide="x-circle" style="width: 12px; height: 12px;"></i> None
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('subscription.create', $company) }}" class="btn btn-warning btn-sm" title="Subscription">
                                            <i data-lucide="credit-card" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <a href="{{ route('company.show', $company) }}" class="btn btn-sm btn-primary" title="View">
                                            <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <a href="{{ route('company.edit', $company) }}" class="btn btn-sm btn-info" title="Edit">
                                            <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                        </a>
                                        <form action="{{ route('company.destroy', $company) }}" method="POST" style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this company?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i data-lucide="inbox" style="width: 32px; height: 32px;" class="text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No companies found</p>
                                    <a href="{{ route('company.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i data-lucide="plus" style="width: 14px; height: 14px;" class="me-1"></i> Add Company
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($companies->hasPages())
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $companies->firstItem() }} to {{ $companies->lastItem() }} of {{ $companies->total() }}
                </div>
                {{ $companies->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        if (typeof lucide !== 'undefined') lucide.createIcons();

        function exportToCSV() {
            const table = document.querySelector('table');
            let csv = [];
            const rows = table.querySelectorAll('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = [], cols = rows[i].querySelectorAll('td, th');

                for (let j = 0; j < cols.length - 1; j++) { // Exclude actions column
                    row.push(cols[j].innerText);
                }
                csv.push(row.join(','));
            }

            const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
            const downloadLink = document.createElement('a');
            downloadLink.download = 'companies_' + new Date().toISOString().slice(0,10) + '.csv';
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
    @endpush
@endsection
