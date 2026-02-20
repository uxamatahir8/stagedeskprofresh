@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-lucide="home" style="width: 14px; height: 14px;"></i></a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            <a href="{{ route('payment-methods.create') }}" class="btn btn-primary">
                <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Add Method
            </a>
        </div>
        <div class="card-body">
            @if($methods->count())
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="thead-sm text-uppercase fs-xxs">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Account</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($methods as $method)
                                <tr>
                                    <td><strong>#{{ $method->id }}</strong></td>
                                    <td>{{ $method->display_name }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $method->method_type)) }}</td>
                                    <td>
                                        <small class="text-muted">{{ $method->account_name ?: ($method->wallet_email ?: 'N/A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-{{ $method->is_active ? 'success' : 'secondary' }}">
                                            {{ $method->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('payment-methods.edit', $method) }}" class="btn btn-sm btn-info" title="Edit">
                                                <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <form action="{{ route('payment-methods.destroy', $method) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this payment method?')" title="Delete">
                                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3 d-flex justify-content-end">
                    {{ $methods->links() }}
                </div>
            @else
                <div class="alert alert-info mb-0">No payment methods yet. Add your first method.</div>
            @endif
        </div>
    </div>
@endsection
