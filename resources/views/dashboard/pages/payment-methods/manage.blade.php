@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i data-lucide="home" style="width: 14px; height: 14px;"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('payment-methods.index') }}">Payment Methods</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">Back</a>
        </div>
        <div class="card-body">
            <form action="{{ $mode === 'edit' ? route('payment-methods.update', $paymentMethod) : route('payment-methods.store') }}" method="POST">
                @csrf
                @if($mode === 'edit')
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Display Name <span class="text-danger">*</span></label>
                        <input type="text" name="display_name" class="form-control" value="{{ old('display_name', $paymentMethod->display_name ?? '') }}" required>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Method Type <span class="text-danger">*</span></label>
                        <select name="method_type" class="form-select" required>
                            @php $selected = old('method_type', $paymentMethod->method_type ?? 'bank_transfer'); @endphp
                            <option value="bank_transfer" {{ $selected === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="paypal" {{ $selected === 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="stripe" {{ $selected === 'stripe' ? 'selected' : '' }}>Stripe</option>
                            <option value="wise" {{ $selected === 'wise' ? 'selected' : '' }}>Wise</option>
                            <option value="other" {{ $selected === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Account Name</label>
                        <input type="text" name="account_name" class="form-control" value="{{ old('account_name', $paymentMethod->account_name ?? '') }}">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Account Number</label>
                        <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $paymentMethod->account_number ?? '') }}">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">IBAN</label>
                        <input type="text" name="iban" class="form-control" value="{{ old('iban', $paymentMethod->iban ?? '') }}">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">SWIFT Code</label>
                        <input type="text" name="swift_code" class="form-control" value="{{ old('swift_code', $paymentMethod->swift_code ?? '') }}">
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Wallet Email</label>
                        <input type="email" name="wallet_email" class="form-control" value="{{ old('wallet_email', $paymentMethod->wallet_email ?? '') }}">
                    </div>
                    <div class="col-lg-6 mb-3 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                {{ old('is_active', $paymentMethod->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label class="col-form-label">Instructions</label>
                        <textarea name="instructions" rows="4" class="form-control">{{ old('instructions', $paymentMethod->instructions ?? '') }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">{{ $mode === 'edit' ? 'Update' : 'Create' }} Method</button>
                </div>
            </form>
        </div>
    </div>
@endsection
