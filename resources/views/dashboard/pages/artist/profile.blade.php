@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="user" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage your artist profile</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="{{ route('artist.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Profile Image -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Profile Photo</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            @if($artist->profile_image)
                                <img src="{{ Storage::url($artist->profile_image) }}" alt="Profile" class="rounded-circle me-3" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="avatar-lg me-3">
                                    <span class="avatar-title rounded-circle bg-primary fs-1">
                                        {{ substr($artist->user->name ?? 'A', 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <input type="file" name="profile_image" class="form-control" accept="image/*">
                                <small class="text-muted">JPG, PNG or GIF. Max 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Professional Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Bio</label>
                                <textarea name="bio" class="form-control" rows="4">{{ old('bio', $artist->bio) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Experience (years)</label>
                                <input type="number" name="experience_years" class="form-control" value="{{ old('experience_years', $artist->experience_years) }}" min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hourly Rate ($)</label>
                                <input type="number" name="hourly_rate" class="form-control" value="{{ old('hourly_rate', $artist->hourly_rate) }}" min="0" step="0.01">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Genres (JSON)</label>
                                <textarea name="genres" class="form-control" rows="3">{{ old('genres', $artist->genres) }}</textarea>
                                <small class="text-muted">Format: ["Hip Hop", "R&B", "Pop"]</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Availability</label>
                                <select name="availability" class="form-select">
                                    <option value="available" {{ old('availability', $artist->availability) === 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="busy" {{ old('availability', $artist->availability) === 'busy' ? 'selected' : '' }}>Busy</option>
                                    <option value="unavailable" {{ old('availability', $artist->availability) === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Portfolio Images -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-bottom">
                        <h5 class="card-title mb-0">Portfolio Images</h5>
                    </div>
                    <div class="card-body">
                        <input type="file" name="portfolio_images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Upload multiple images of your work, equipment, or performances</small>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="save" class="me-1"></i> Save Changes
                        </button>
                        <a href="{{ route('artist.dashboard') }}" class="btn btn-light ms-2">
                            <i data-lucide="x" class="me-1"></i> Cancel
                        </a>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0"><i data-lucide="lock" class="me-2"></i>Change Password</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info border-0">
                            <i data-lucide="info" class="me-2"></i>
                            Leave these fields blank if you don't want to change your password
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Current Password</label>
                                <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Enter new password">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top">
                        <button type="submit" class="btn btn-primary">
                            <i data-lucide="shield-check" class="me-1"></i> Update Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
