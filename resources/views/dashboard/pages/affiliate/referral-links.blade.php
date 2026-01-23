@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="link" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Create custom referral links for campaigns</p>
        </div>
    </div>

    <!-- Generate New Link -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Generate New Referral Link</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('affiliate.referral-links.generate') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Campaign Name <span class="text-danger">*</span></label>
                        <input type="text" name="campaign" class="form-control" required placeholder="e.g., Summer Promo 2026">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Target Type</label>
                        <select name="type" class="form-select">
                            <option value="general">General</option>
                            <option value="user">User Sign-up</option>
                            <option value="company">Company Registration</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="plus"></i> Generate Link
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Links -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Your Referral Links</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Link</th>
                            <th>Type</th>
                            <th>Clicks</th>
                            <th>Conversions</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($links as $link)
                            <tr>
                                <td><strong>{{ $link->campaign ?? 'N/A' }}</strong></td>
                                <td>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control form-control-sm" value="{{ $link->url }}" readonly id="link{{ $link->id }}">
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyLink('link{{ $link->id }}')">
                                            <i data-lucide="copy"></i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $link->type === 'general' ? 'secondary' : ($link->type === 'user' ? 'primary' : 'info') }}">
                                        {{ ucfirst($link->type ?? 'general') }}
                                    </span>
                                </td>
                                <td>{{ $link->clicks ?? 0 }}</td>
                                <td>{{ $link->conversions ?? 0 }}</td>
                                <td>{{ $link->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ $link->url }}" target="_blank" class="btn btn-sm btn-light">
                                        <i data-lucide="external-link"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i data-lucide="link" class="mb-2" style="width: 48px; height: 48px;"></i>
                                    <p>No referral links created yet</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($links->hasPages())
        <div class="card-footer bg-transparent border-top">
            {{ $links->links() }}
        </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function copyLink(elementId) {
        const input = document.getElementById(elementId);
        input.select();
        document.execCommand('copy');
        alert('Link copied to clipboard!');
    }
</script>
@endpush
