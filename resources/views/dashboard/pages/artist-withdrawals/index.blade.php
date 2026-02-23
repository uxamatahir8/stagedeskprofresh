@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card">
        <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Requests</h5>
            <div>
                <a href="?status=pending" class="btn btn-sm btn-outline-primary me-1">Pending</a>
                <a href="{{ route('artist-withdrawals.index') }}" class="btn btn-sm btn-outline-secondary">All</a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Artist</th>
                            <th>Company</th>
                            <th>Amount</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $w)
                            <tr>
                                <td>{{ $w->artist->user->name ?? 'N/A' }}</td>
                                <td>{{ $w->company->name ?? 'N/A' }}</td>
                                <td>€{{ number_format($w->amount, 2) }}</td>
                                <td>{{ Str::limit($w->artist_notes, 30) }}</td>
                                <td>
                                    <span class="badge bg-{{ $w->status === 'paid' ? 'success' : ($w->status === 'approved' ? 'info' : ($w->status === 'rejected' ? 'danger' : 'warning')) }}">
                                        {{ ucfirst($w->status) }}
                                    </span>
                                </td>
                                <td>{{ $w->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($w->status === 'pending')
                                        <form action="{{ route('artist-withdrawals.approve', $w) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $w->id }}">Reject</button>
                                        <div class="modal fade" id="rejectModal{{ $w->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('artist-withdrawals.reject', $w) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Withdrawal Request</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <label class="form-label">Reason (optional)</label>
                                                            <textarea name="admin_notes" class="form-control" rows="2" placeholder="Optional note for the artist"></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($w->status === 'approved')
                                        <form action="{{ route('artist-withdrawals.mark-paid', $w) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Mark Paid</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No withdrawal requests</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($withdrawals->hasPages())
        <div class="card-footer bg-transparent border-top">
            {{ $withdrawals->links() }}
        </div>
        @endif
    </div>
@endsection
