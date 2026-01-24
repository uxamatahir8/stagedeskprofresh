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
            <div class="title">
                <h4 class="card-title mb-0">{{ $title }}</h4>
            </div>
            <div class="action-btns">
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> New Payment
                </a>
            </div>
        </div>

        <div class="card-body">
            @if ($payments->count() > 0)
                <div class="table-responsive">
                    <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                        <thead class="thead-sm text-uppercase fs-xxs">
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Transaction ID</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>
                                        <strong>#{{ $payment->id }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-{{ $payment->type === 'booking' ? 'primary' : 'info' }}">
                                            {{ ucfirst($payment->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <h6 class="mb-0">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</h6>
                                    </td>
                                    <td>
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                                    </td>
                                    <td>
                                        @if($payment->status === 'completed')
                                            <span class="badge badge-soft-success">Completed</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge badge-soft-warning">Pending</span>
                                        @else
                                            <span class="badge badge-soft-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $payment->transaction_id ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $payment->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="action-btn">
                                            <a href="{{ route('payments.show', $payment) }}"
                                                class="btn btn-sm btn-primary" title="View">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            @if($payment->status === 'pending')
                                                <a href="{{ route('payments.edit', $payment) }}"
                                                    class="btn btn-sm btn-info" title="Edit">
                                                    <i class="ti ti-pencil"></i>
                                                </a>
                                                <form action="{{ route('payments.destroy', $payment) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                        onclick="return confirm('Are you sure?')">
                                                        <i class="ti ti-trash text-white"></i>
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

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $payments->firstItem() }} to {{ $payments->lastItem() }} of {{ $payments->total() }} payments
                    </div>
                    <div>
                        {{ $payments->links() }}
                    </div>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="ti ti-info-circle"></i> No payments yet. <a href="{{ route('payments.create') }}">Create your first payment</a>
                </div>
            @endif
        </div>
    </div>
@endsection
