@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="image" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Download promotional materials for your campaigns</p>
        </div>
    </div>

    <!-- Banners -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Banners & Graphics</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach(['728x90', '300x250', '160x600', '468x60'] as $size)
                    <div class="col-md-6 col-lg-3">
                        <div class="card border h-100">
                            <div class="card-body text-center">
                                <div class="mb-3 p-3 bg-light rounded">
                                    <i data-lucide="image" style="width: 64px; height: 64px;"></i>
                                </div>
                                <h6 class="mb-2">{{ $size }} Banner</h6>
                                <p class="text-muted small mb-3">Standard display ad</p>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i data-lucide="download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Email Templates -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Email Templates</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i data-lucide="mail" style="width: 48px; height: 48px;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Welcome Email</h6>
                                    <p class="text-muted small mb-2">Introduction email template</p>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i data-lucide="download"></i> Download HTML
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i data-lucide="mail" style="width: 48px; height: 48px;"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">Promotional Email</h6>
                                    <p class="text-muted small mb-2">Marketing campaign template</p>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i data-lucide="download"></i> Download HTML
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Media -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Social Media Assets</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach(['Facebook Post', 'Instagram Story', 'Twitter Banner', 'LinkedIn Post'] as $asset)
                    <div class="col-md-6 col-lg-3">
                        <div class="card border h-100">
                            <div class="card-body text-center">
                                <div class="mb-3 p-3 bg-light rounded">
                                    <i data-lucide="share-2" style="width: 48px; height: 48px;"></i>
                                </div>
                                <h6 class="mb-2">{{ $asset }}</h6>
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i data-lucide="download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Text & Copy -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom">
            <h5 class="card-title mb-0">Pre-written Copy</h5>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h6>Short Description</h6>
                <div class="bg-light p-3 rounded mb-2">
                    <p class="mb-0">Transform your events with StageDesk Pro! Book professional DJs and artists for any occasion. Sign up today and get 10% off your first booking!</p>
                </div>
                <button class="btn btn-sm btn-outline-secondary" onclick="copyText(this.previousElementSibling.querySelector('p').innerText)">
                    <i data-lucide="copy"></i> Copy
                </button>
            </div>

            <div class="mb-4">
                <h6>Long Description</h6>
                <div class="bg-light p-3 rounded mb-2">
                    <p class="mb-0">Looking for the perfect entertainment for your next event? StageDesk Pro connects you with top-rated DJs and artists in your area. Whether it's a wedding, corporate event, or private party, we make booking easy and stress-free. Browse profiles, read reviews, and book your perfect artist in minutes. Join thousands of satisfied customers - sign up now using my referral link and save 10% on your first booking!</p>
                </div>
                <button class="btn btn-sm btn-outline-secondary" onclick="copyText(this.previousElementSibling.querySelector('p').innerText)">
                    <i data-lucide="copy"></i> Copy
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function copyText(text) {
        navigator.clipboard.writeText(text);
        alert('Text copied to clipboard!');
    }
</script>
@endpush
