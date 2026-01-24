@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="building-2" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage all companies and subscriptions</p>
        </div>

        <div class="text-end">
            <a href="{{ route('subscriptions.index') }}" class="btn btn-warning btn-sm me-2">
                <i class="ti ti-credit-card"></i> Manage Subscriptions
            </a>
            <a href="{{ route('company.create') }}" class="btn btn-primary btn-sm">
                <i class="ti ti-plus"></i> Add Company
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-primary-subtle rounded me-3">
                            <i data-lucide="building-2" class="text-primary"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                            <small class="text-muted">Total Companies</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-success-subtle rounded me-3">
                            <i data-lucide="check-circle" class="text-success"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['active'] ?? 0 }}</h3>
                            <small class="text-muted">Active</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-secondary-subtle rounded me-3">
                            <i data-lucide="x-circle" class="text-secondary"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['inactive'] ?? 0 }}</h3>
                            <small class="text-muted">Inactive</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-info-subtle rounded me-3">
                            <i data-lucide="credit-card" class="text-info"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['with_subscription'] ?? 0 }}</h3>
                            <small class="text-muted">With Subscription</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('companies') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i data-lucide="search" style="width: 16px;"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0"
                                   placeholder="Search by name, email, KVK..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="subscription" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">All Subscriptions</option>
                            <option value="active" {{ request('subscription') === 'active' ? 'selected' : '' }}>With Subscription</option>
                            <option value="inactive" {{ request('subscription') === 'inactive' ? 'selected' : '' }}>No Subscription</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="sort_by" class="form-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Registration Date</option>
                            <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name</option>
                            <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Status</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-primary">
                                <i data-lucide="filter" style="width: 16px;"></i> Filter
                            </button>
                            <a href="{{ route('companies') }}" class="btn btn-secondary">
                                <i data-lucide="x" style="width: 16px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Companies Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Companies List ({{ $companies->total() }})</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-light btn-sm" onclick="window.print()">
                    <i data-lucide="printer" style="width: 14px;"></i> Print
                </button>
                <button class="btn btn-light btn-sm" onclick="exportToCSV()">
                    <i data-lucide="download" style="width: 14px;"></i> Export
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Company</th>
                            <th>KVK Number</th>
                            <th>Contact</th>
                            <th>Artists</th>
                            <th>Bookings</th>
                            <th>Status</th>
                            <th>Subscription</th>
                            <th style="width: 200px;">Actions</th>
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
                                        <i data-lucide="users" style="width: 12px;"></i> {{ $company->artists_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">
                                        <i data-lucide="calendar" style="width: 12px;"></i> {{ $company->bookings_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $company->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($company->status) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $subscription = $company->activeSubscription;
                                    @endphp
                                    @if($subscription)
                                        <span class="badge bg-info-subtle text-info">
                                            <i data-lucide="check-circle" style="width: 12px;"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i data-lucide="x-circle" style="width: 12px;"></i> None
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btn">
                                        <a href="{{ route('subscription.create', $company) }}"
                                            class="btn btn-warning btn-sm" title="Manage Subscription">
                                            <i class="ti ti-settings"></i>
                                        </a>
                                        <a href="{{ route('company.show', $company) }}"
                                            class="btn btn-sm btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('company.edit', $company) }}"
                                            class="btn btn-sm btn-info" title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <form action="{{ route('company.destroy', $company) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this company?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="ti ti-trash text-white"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i data-lucide="inbox" class="mb-2"></i>
                                    <p class="text-muted mb-0">No companies found</p>
                                    <a href="{{ route('company.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i data-lucide="plus" class="me-1"></i>Add First Company
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($companies->hasPages())
            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Showing {{ $companies->firstItem() }} to {{ $companies->lastItem() }} of {{ $companies->total() }} companies
                </div>
                {{ $companies->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
    <script>
        lucide.createIcons();

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
