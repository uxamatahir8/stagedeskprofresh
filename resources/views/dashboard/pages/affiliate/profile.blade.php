@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="user" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage your affiliate profile</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('affiliate.profile.update') }}" method="POST">
                @csrf

                <!-- Personal Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Personal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', Auth::user()->phone) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country', Auth::user()->country) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Referral Code -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Your Referral Code</h5>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" value="{{ Auth::user()->referral_code ?? 'N/A' }}" readonly id="referralCode">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyReferralCode()">
                                <i data-lucide="copy"></i> Copy
                            </button>
                        </div>
                        <small class="text-muted">Share this code with potential referrals</small>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select">
                                    <option value="bank_transfer" {{ old('payment_method', Auth::user()->payment_method) === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                    <option value="paypal" {{ old('payment_method', Auth::user()->payment_method) === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                    <option value="stripe" {{ old('payment_method', Auth::user()->payment_method) === 'stripe' ? 'selected' : '' }}>Stripe</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">PayPal Email / Bank Account</label>
                                <input type="text" name="payment_details" class="form-control" value="{{ old('payment_details', Auth::user()->payment_details) }}" placeholder="Enter your payment details">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Minimum Payout Amount ($)</label>
                                <input type="number" name="min_payout" class="form-control" value="{{ old('min_payout', Auth::user()->min_payout ?? 50) }}" min="50" step="10">
                                <small class="text-muted">Minimum: $50</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Marketing Preferences -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Marketing Preferences</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="email_notifications" id="emailNotif" {{ old('email_notifications', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="emailNotif">
                                Receive email notifications about new commissions
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="marketing_updates" id="marketingUpdates" {{ old('marketing_updates', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="marketingUpdates">
                                Receive marketing updates and tips
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="performance_reports" id="perfReports" {{ old('performance_reports', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perfReports">
                                Receive monthly performance reports
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save"></i> Save Changes
                        </button>
                        <a href="{{ route('affiliate.dashboard') }}" class="btn btn-light ms-2">
                            <i data-lucide="x"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function copyReferralCode() {
        const input = document.getElementById('referralCode');
        input.select();
        document.execCommand('copy');
        alert('Referral code copied to clipboard!');
    }
</script>
@endpush
