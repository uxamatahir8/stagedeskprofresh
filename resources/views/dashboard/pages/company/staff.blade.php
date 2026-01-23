@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="users" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage your company staff members</p>
        </div>
    </div>

    <!-- Staff List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Staff Members</h5>
            <span class="badge bg-primary">{{ $staff->total() }} Total</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($staff as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-2">
                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                {{ substr($member->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $member->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $member->email }}</td>
                                <td><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $member->role)) }}</span></td>
                                <td>{{ $member->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ $member->status === 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td>{{ $member->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i data-lucide="users" class="mb-2" style="width: 48px; height: 48px;"></i>
                                    <p>No staff members found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($staff->hasPages())
        <div class="card-footer bg-transparent border-top">
            {{ $staff->links() }}
        </div>
        @endif
    </div>
@endsection
