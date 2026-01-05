@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">{{ $title }}</h4>
        </div>
        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}"><i class="ti ti-home"></i></a>
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
                @if ($mode == 'edit')
                    @method('PUT')
                @endif

                <!-- Company Info -->
                <h4 class="fw-bold">Company Info</h4>
                <hr>
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4"><label for="company_name" class="col-form-label">Company Name</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="company_name" name="name" class="form-control required"
                                    value="{{ old('name', $company->name ?? '') }}" placeholder="Company Name">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4"><label for="company_website" class="col-form-label">Company
                                    Website</label></div>
                            <div class="col-lg-8">
                                <input type="text" id="company_website" name="website" class="form-control valid_url"
                                    value="{{ old('website', $company->website ?? '') }}" placeholder="Company Website">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4"><label for="kvk_number" class="col-form-label">Kvk Number</label></div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control kvk_number required" id="kvk_number"
                                    name="kvk_number" value="{{ old('kvk_number', $company->kvk_number ?? '') }}"
                                    placeholder="KVK Number">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4"><label for="address" class="col-form-label">Address</label></div>
                            <div class="col-lg-8">
                                <textarea id="address" class="form-control" name="address" rows="2" placeholder="Address">{{ old('address', $company->address ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4"><label for="company_email" class="col-form-label">Company Email</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="email" class="form-control email required" id="company_email" name="email"
                                    value="{{ old('email', $company->email ?? '') }}" placeholder="Company Email">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4"><label for="company_phone" class="col-form-label">Company Phone</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" class="form-control phone required" id="company_phone" name="phone"
                                    value="{{ old('phone', $company->phone ?? '') }}" placeholder="Company Phone">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4"><label for="status" class="col-form-label">Status</label></div>
                            <div class="col-lg-8">
                                <div class="form-check form-switch form-check-secondary fs-xxl mb-2">
                                    <input type="checkbox" name="status" value="active" class="form-check-input mt-1"
                                        id="checkboxSize20"
                                        {{ ($mode == 'edit' && $company->status) == 'active' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Logo -->
                <hr>
                <h4 class="fw-bold">Company Logo</h4>
                <hr>
                <div class="row g-lg-4 g-2 mt-2">
                    <div class="col-lg-4"><label for="logo" class="col-form-label">Upload Logo</label></div>
                    <div class="col-lg-8">
                        <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                        <div class="mt-3 position-relative" id="logo-preview-container">
                            @if (isset($company) && $company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo" id="logo-preview"
                                    class="img-fluid rounded" style="max-height: 120px;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                    id="remove-logo-btn">&times;</button>
                            @else
                                <img src="" alt="Logo Preview" id="logo-preview"
                                    class="img-fluid rounded d-none" style="max-height: 120px;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 d-none"
                                    id="remove-logo-btn">&times;</button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <hr>
                <h4 class="fw-bold">Contact Info</h4>
                <hr>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4"><label class="col-form-label">Contact Name</label></div>
                            <div class="col-lg-8">
                                <input type="text" name="contact_name" class="form-control required"
                                    value="{{ old('contact_name', $company->contact_name ?? '') }}"
                                    placeholder="Contact Name">
                            </div>
                        </div>
                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4"><label class="col-form-label">Contact Phone</label></div>
                            <div class="col-lg-8">
                                <input type="text" name="contact_phone" class="form-control phone required"
                                    value="{{ old('contact_phone', $company->contact_phone ?? '') }}"
                                    placeholder="Contact Phone">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4"><label class="col-form-label">Contact Email</label></div>
                            <div class="col-lg-8">
                                <input type="email" name="contact_email" id="contact_email"
                                    class="form-control required"
                                    value="{{ old('contact_email', $company->contact_email ?? '') }}"
                                    placeholder="Contact Email">
                            </div>
                        </div>
                    </div>
                </div>
                @if ($mode != 'edit')
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="form-check form-switch mb-2">
                                <input type="checkbox" name="is_admin" value="1" class="form-check-input"
                                    id="companyAdminSwitch">
                                <label class="form-check-label" for="companyAdminSwitch">Add this contact as Company
                                    Admin</label>
                            </div>
                        </div>
                    </div>
                @endif

                <div id="adminFields" style="display: none;">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row g-lg-4 g-2 mt-2">
                                <div class="col-lg-4"><label class="col-form-label">Password</label></div>
                                <div class="col-lg-8">
                                    <input type="password" name="password" class="form-control" id="password"
                                        placeholder="Password">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="row g-lg-4 g-2 mt-1">
                                <div class="col-lg-4"><label class="col-form-label">Confirm Password</label></div>
                                <div class="col-lg-8">
                                    <input type="password" name="password_confirmation" class="form-control match"
                                        id="confirm_password" data-match="password" placeholder="Confirm Password">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-lg-4"><label class="col-form-label">Country</label></div>
                                <div class="col-lg-8">
                                    <select name="admin_country_id" id="admin_country" class="form-select">
                                        <option value="">Select Country</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-lg-4"><label class="col-form-label">State</label></div>
                                <div class="col-lg-8">
                                    <select name="admin_state_id" id="admin_state" class="form-select">
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-lg-4"><label class="col-form-label">City</label></div>
                                <div class="col-lg-8">
                                    <select name="admin_city_id" id="admin_city" class="form-select">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg-4"><label class="col-form-label">Address</label></div>
                                <div class="col-lg-8">
                                    <textarea name="admin_address" class="form-control" rows="2" placeholder="Address"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-lg-4"><label class="col-form-label">Profile Image</label></div>
                                <div class="col-lg-8">
                                    <input type="file" name="admin_profile_image" id="admin_profile_image"
                                        class="form-control" accept="image/*">
                                    <img id="admin_profile_preview" class="img-fluid rounded mt-2 d-none"
                                        style="max-height: 100px;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <hr>
                <h4 class="fw-bold">Social Info</h4>
                <hr>
                <div class="row">
                    @foreach (config('arrays.social_links') as $key => $social_link)
                        @php
                            $existingLink =
                                $mode == 'edit' ? $company->socialLinks->firstWhere('handle', $key)->url ?? '' : '';
                        @endphp
                        <div class="col-lg-6 mt-2">
                            <div class="row g-lg-4 g-2">
                                <div class="col-lg-4"><label class="col-form-label">{{ ucfirst($key) }}</label></div>
                                <div class="col-lg-8">
                                    <input type="text" name="social_links[{{ $key }}]"
                                        class="form-control valid_url"
                                        value="{{ old('social_links.' . $key, $existingLink ?? '') }}"
                                        placeholder="{{ $social_link }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit -->
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-{{ $mode == 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode == 'edit' ? 'Update' : 'Save' }} Company
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle admin fields
            const adminSwitch = document.getElementById('companyAdminSwitch');
            const adminFields = document.getElementById('adminFields');
            adminSwitch.addEventListener('change', () => {
                const emailInput = document.getElementById('contact_email');

                adminFields.style.display = adminSwitch.checked ? 'block' : 'none';

                if (adminSwitch.checked) {
                    emailInput.classList.add('unique_email');
                } else {
                    emailInput.classList.remove('unique_email');
                }

                // Trigger validation-related events
                ['input', 'change', 'blur'].forEach(eventType => {
                    emailInput.dispatchEvent(new Event(eventType, {
                        bubbles: true
                    }));
                });
            });


            // Admin profile image preview
            const adminProfileInput = document.getElementById('admin_profile_image');
            const adminProfilePreview = document.getElementById('admin_profile_preview');
            adminProfileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    adminProfilePreview.src = e.target.result;
                    adminProfilePreview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });

            // Dynamic country/state/city for admin
            const countrySelect = document.getElementById('admin_country');
            const stateSelect = document.getElementById('admin_state');
            const citySelect = document.getElementById('admin_city');

            countrySelect.addEventListener('change', async function() {
                const countryId = this.value;
                stateSelect.innerHTML = '<option value="">Select State</option>';
                citySelect.innerHTML = '<option value="">Select City</option>';
                if (!countryId) return;
                const res = await fetch(`/states/${countryId}`);
                const states = await res.json();
                states.forEach(s => stateSelect.appendChild(new Option(s.name, s.id)));
            });

            stateSelect.addEventListener('change', async function() {
                const stateId = this.value;
                citySelect.innerHTML = '<option value="">Select City</option>';
                if (!stateId) return;
                const res = await fetch(`/cities/${stateId}`);
                const cities = await res.json();
                cities.forEach(c => citySelect.appendChild(new Option(c.name, c.id)));
            });

            // Company logo preview
            const logoInput = document.getElementById('logo');
            const logoPreview = document.getElementById('logo-preview');
            const removeBtn = document.getElementById('remove-logo-btn');
            logoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    logoPreview.src = e.target.result;
                    logoPreview.classList.remove('d-none');
                    removeBtn.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });

            removeBtn.addEventListener('click', function() {
                logoInput.value = '';
                logoPreview.src = '';
                logoPreview.classList.add('d-none');
                removeBtn.classList.add('d-none');
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
