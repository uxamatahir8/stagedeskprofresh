@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="settings" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage company settings and preferences</p>
        </div>
    </div>

    <form action="{{ route('company.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Company Information -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="card-title mb-0">Company Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $company->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $company->phone) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Website</label>
                        <input type="url" name="website" class="form-control" value="{{ old('website', $company->website) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3">{{ old('address', $company->address) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $company->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Settings -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="card-title mb-0">Business Settings</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Business Hours</label>
                        <input type="text" name="business_hours" class="form-control" value="{{ old('business_hours', $company->business_hours) }}" placeholder="e.g., Mon-Fri: 9AM-6PM">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Service Areas</label>
                        <input type="text" name="service_areas" class="form-control" value="{{ old('service_areas', $company->service_areas) }}" placeholder="e.g., New York, Los Angeles">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $company->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $company->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Company Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if($company->logo)
                            <small class="text-muted">Current logo: <a href="{{ Storage::url($company->logo) }}" target="_blank">View</a></small>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="card-title mb-0">Social Media Links</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Facebook</label>
                        <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $company->facebook) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Instagram</label>
                        <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $company->instagram) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Twitter</label>
                        <input type="url" name="twitter" class="form-control" value="{{ old('twitter', $company->twitter) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">LinkedIn</label>
                        <input type="url" name="linkedin" class="form-control" value="{{ old('linkedin', $company->linkedin) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <button type="submit" class="btn btn-primary">
                    <i data-lucide="save"></i> Save Settings
                </button>
                <a href="{{ route('company.dashboard') }}" class="btn btn-light">
                    <i data-lucide="x"></i> Cancel
                </a>
            </div>
        </div>
    </form>
@endsection
