@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="users" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Track your referrals and their activity</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ $stats['total_referrals'] }}</h2>
                    <p class="text-muted mb-0">Total Referrals</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ $stats['active_referrals'] }}</h2>
                    <p class="text-muted mb-0">Active</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ $stats['total_users'] }}</h2>
                    <p class="text-muted mb-0">User Referrals</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h2 class="mb-1">{{ $stats['total_companies'] }}</h2>
                    <p class="text-muted mb-0">Company Referrals</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button">
                User Referrals
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="companies-tab" data-bs-toggle="tab" data-bs-target="#companies" type="button">
                Company Referrals
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- User Referrals Tab -->
        <div class="tab-pane fade show active" id="users" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                    <th>Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($userReferrals as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($user->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td><strong>${{ number_format($user->commission_earned ?? 0, 2) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No user referrals yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($userReferrals->hasPages())
                <div class="card-footer bg-transparent border-top">
                    {{ $userReferrals->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- Company Referrals Tab -->
        <div class="tab-pane fade" id="companies" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Contact Email</th>
                                    <th>Joined</th>
                                    <th>Status</th>
                                    <th>Commission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companyReferrals as $company)
                                    <tr>
                                        <td>{{ $company->name }}</td>
                                        <td>{{ $company->email }}</td>
                                        <td>{{ $company->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $company->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($company->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td><strong>${{ number_format($company->commission_earned ?? 0, 2) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No company referrals yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($companyReferrals->hasPages())
                <div class="card-footer bg-transparent border-top">
                    {{ $companyReferrals->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
