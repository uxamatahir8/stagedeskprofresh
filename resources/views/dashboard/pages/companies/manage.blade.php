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

                <li class="breadcrumb-item"><a href="{{ route('companies') }}">Companies</a></li>

                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('companies') }}" class="btn btn-primary">Companies List</a>
        </div>

        <div class="card-body">
            <form class="validate_form"
                action="{{ $mode == 'edit' ? route('company.update', $company->id) : route('company.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if($mode == 'edit')
                    @method('PUT')
                @endif

                <h4 class="fw-bold">Company Info</h4>
                <hr>

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="company_name" class="col-form-label">Company Name</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="company_name" name="name" class="form-control required"
                                    value="{{ old('name', $company->name ?? '') }}" placeholder="Company Name">
                            </div>
                        </div>
                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4">
                                <label for="company_website" class="col-form-label">Company Website</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="company_website" name="website" class="form-control valid_url"
                                    value="{{ old('website', $company->website ?? '') }}" placeholder="Company Website">
                            </div>
                        </div>
                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4">
                                <label for="kvk_number" class="col-form-label">Kvk Number</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control kvk_number required" id="kvk_number" name="kvk_number"
                                    value="{{ old('kvk_number', $company->kvk_number ?? '') }}" placeholder="KVK Number">
                            </div>
                        </div>
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="address" class="col-form-label">Address</label>
                            </div>
                            <div class="col-lg-8">
                                <textarea id="address" class="form-control" name="address" rows="2"
                                    placeholder="address">{{ old('address', $company->address ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="company_email" class="col-form-label">Comapny Email</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="email" class="form-control email required" id="company_email" name="email"
                                    value="{{ old('email', $company->email ?? '') }}" placeholder="Company Email">
                            </div>
                        </div>
                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4">
                                <label for="company_email" class="col-form-label">Comapny Phone</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control phone required" id="company_email" name="phone"
                                    value="{{ old('phone', $company->phone ?? '') }}" placeholder="Company Phone">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4">
                                <label for="status" class="col-form-label">Status</label>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-check form-switch form-check-secondary fs-xxl mb-2">
                                    <input type="checkbox" name="status" value="active" class="form-check-input mt-1"
                                        id="checkboxSize20" {{ ($mode == 'edit' && $company->status) == 'active' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <hr>
                <h4 class="fw-bold">Company Logo</h4>
                <hr>

                <div class="row g-lg-4 g-2 mt-2">
                    <div class="col-lg-4">
                        <label for="logo" class="col-form-label">Upload Logo</label>
                    </div>
                    <div class="col-lg-8">
                        <input type="file" name="logo" id="logo" class="form-control" accept="image/*">

                        <div class="mt-3 position-relative" id="logo-preview-container">
                            @if(isset($company) && $company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo" id="logo-preview"
                                    class="img-fluid rounded" style="max-height: 120px;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                    id="remove-logo-btn">&times;</button>
                            @else
                                <img src="" alt="Logo Preview" id="logo-preview" class="img-fluid rounded d-none"
                                    style="max-height: 120px;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 d-none"
                                    id="remove-logo-btn">&times;</button>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>
                <h4 class="fw-bold">Contact Info</h4>
                <hr>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="conatact_email" class="col-form-label">Contact Name</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="conatact_email" name="contact_name" class="form-control required"
                                    value="{{ old('contact_name', $company->contact_name ?? '') }}"
                                    placeholder="Contact Name">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4">
                                <label for="contact_phone" class="col-form-label">Contact Phone</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="contact_phone" name="contact_phone"
                                    class="form-control phone required"
                                    value="{{ old('contact_phone', $company->contact_phone ?? '') }}"
                                    placeholder="Contact Phone">
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="conatact_email" class="col-form-label">Contact Email</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="email" id="conatact_email" name="contact_email" class="form-control required"
                                    value="{{ old('conatact_email', $company->email ?? '') }}" placeholder="Contact Email">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h4 class="fw-bold">Social Info</h4>
                <hr>

                <div class="row">
                    @foreach(config('arrays.social_links') as $key => $social_link)
                        @php
    if ($mode == 'edit') {
        $existingLink = $company->socialLinks->firstWhere('handle', $key)->url ?? '';
    }
                        @endphp
                        <div class="col-lg-6 mt-2">
                            <div class="row g-lg-4 g-2">
                                <div class="col-lg-4">
                                    <label for="{{ $key }}_link" class="col-form-label">{{ ucfirst($key) }}</label>
                                </div>
                                <div class="col-lg-8">
                                    <input type="text" id="{{ $key }}_link" name="social_links[{{ $key }}]" class="form-control valid_url"
                                        value="{{ old('social_links.' . $key, $existingLink ?? '') }}" placeholder="{{ $social_link }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-{{ $mode == 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode == 'edit' ? 'Update' : 'Save' }} Company
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoInput = document.getElementById('logo');
            const preview = document.getElementById('logo-preview');
            const removeBtn = document.getElementById('remove-logo-btn');

            logoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                // ✅ Validate file type
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type! Only jpeg, png, jpg, gif, webp are allowed.');
                    logoInput.value = '';
                    return;
                }

                // ✅ Validate file size (max 2MB)
                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('File too large! Maximum size allowed is 2MB.');
                    logoInput.value = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    removeBtn.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });

            // Remove logo
            removeBtn.addEventListener('click', function () {
                logoInput.value = '';
                preview.src = '';
                preview.classList.add('d-none');
                removeBtn.classList.add('d-none');

                // Optional: flag to remove existing logo on server
                if (!document.getElementById('remove_logo_flag')) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'remove_logo';
                    input.id = 'remove_logo_flag';
                    input.value = '1';
                    logoInput.closest('form').appendChild(input);
                }
            });
        });
    </script>

@endsection
