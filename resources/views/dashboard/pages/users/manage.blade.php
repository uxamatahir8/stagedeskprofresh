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
                <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('users') }}" class="btn btn-primary">Users List</a>
        </div>

        <div class="card-body">
            <form class="validate_form" action="{{ $mode == 'edit' ? route('user.update', $user) : route('user.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if($mode == 'edit') @method('PUT') @endif

                <div class="row">
                    <div class="col-lg-6">
                        {{-- Role --}}
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Select User Role:</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control form-select required" name="role_id">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Full Name --}}
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Full Name:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" name="name" class="form-control required"
                                    value="{{ old('name', $user->name ?? '') }}" placeholder="Full Name">
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Phone:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" name="phone" class="form-control required"
                                    value="{{ old('phone', $user->profile->phone ?? '') }}" placeholder="Phone">
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Address:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" name="address" class="form-control required"
                                    value="{{ old('address', $user->profile->address ?? '') }}" placeholder="Address">
                            </div>
                        </div>

                        {{-- Dynamic Country, State, City --}}
                        <div class="row g-lg-4 g-2 mt-3">
                            <div class="col-lg-4">
                                <label class="col-form-label">Country:</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control form-select required" name="country_id" id="country">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country_id', $user->profile->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">State:</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control form-select required" name="state_id" id="state">
                                    <option value="">Select State</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">City:</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control form-select required" name="city_id" id="city">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-1">
                            <div class="col-lg-4">
                                <label for="status" class="col-form-label">Status</label>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-check form-switch form-check-secondary fs-xxl mb-2">
                                    <input type="checkbox" name="status" value="active" class="form-check-input mt-1"
                                        id="checkboxSize20" {{ ($mode == 'edit' && $user->status) == 'active' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Right Side --}}
                    <div class="col-lg-6">
                        {{-- Company --}}
                        <div id="company_admin" class="row g-lg-4 g-2 d-none">
                            <div class="col-lg-4">
                                <label class="col-form-label">Select Company:</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control form-select" name="company_id" id="company_id">
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $user->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div id="email-div" class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Email:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="email" name="email" class="form-control required"
                                    value="{{ old('email', $user->email ?? '') }}" placeholder="Email">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Password:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="password" name="password"
                                    class="form-control {{ $mode == 'create' ? 'required' : '' }}" id="password"
                                    placeholder="Enter Password">
                                @if($mode == 'edit')
                                    <small class="text-info ms-2">Leave blank if unchanged</small>
                                @endif
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Confirm Password:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="password" name="confirm_password" id="confirm_password"
                                    class="form-control {{ $mode == 'create' ? 'required' : '' }} match" data-match="password"
                                    placeholder="Confirm Password">
                            </div>
                        </div>

                        {{-- Zip Code --}}
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Zip Code:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" name="zipcode" class="form-control required"
                                    value="{{ old('zipcode', $user->profile->zipcode ?? '') }}" placeholder="Zip Code">
                            </div>
                        </div>

                        {{-- About --}}
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">About:</label>
                            </div>
                            <div class="col-lg-8">
                                <textarea name="about" class="form-control" rows="5"
                                    placeholder="About">{{ old('about', $user->profile->about ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="logo" class="col-form-label">Upload Logo</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="file" name="logo" id="logo" class="form-control" accept="image/*">

                                <div class="mt-3 position-relative" id="logo-preview-container">
                                    @if(isset($user) && isset($user->profile) && $user->profile->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile->profile_image) }}" alt="Profile Picture"
                                            id="logo-preview" class="img-fluid rounded" style="max-height: 120px;">
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
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-{{ $mode == 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode == 'edit' ? 'Update' : 'Save' }} Company
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================== SCRIPT SECTION ================== --}}
    <script>


            document.querySelector('select[name="role_id"]').addEventListener('change', (e) => {
                const select = e.target;
                const role_key = select.options[select.selectedIndex].text;


                const company_roles_key = ['Artist', 'Company Admin']

                if (company_roles_key.includes(role_key)) {
                    document.querySelector('#company_admin').classList.remove('d-none');
                    document.querySelector('#company_id').classList.add('required');
                    document.querySelector('#email-div').classList.add('mt-2');
                } else {
                    document.querySelector('#company_admin').classList.add('d-none');
                    document.querySelector('#company_id').classList.remove('required');
                    document.querySelector('#company_id').value = '';
                    document.querySelector('#email-div').classList.remove('mt-2');
                }

            });
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
        document.addEventListener('DOMContentLoaded', () => {
            const countrySelect = document.getElementById('country');
            const stateSelect = document.getElementById('state');
            const citySelect = document.getElementById('city');

            // Load states when country changes
            countrySelect.addEventListener('change', async function () {
                const countryId = this.value;
                stateSelect.innerHTML = '<option value="">Select State</option>';
                citySelect.innerHTML = '<option value="">Select City</option>';
                if (!countryId) return;
                try {
                    const response = await fetch(`/states/${countryId}`);
                    const states = await response.json();
                    states.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.id;
                        option.textContent = state.name;
                        stateSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading states:', error);
                }
            });

            // Load cities when state changes
            stateSelect.addEventListener('change', async function () {
                const stateId = this.value;
                citySelect.innerHTML = '<option value="">Select City</option>';
                if (!stateId) return;
                try {
                    const response = await fetch(`/cities/${stateId}`);
                    const cities = await response.json();
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.id;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading cities:', error);
                }
            });

            // Auto-load for edit mode
            const oldCountry = "{{ old('country_id', $user->profile->country_id ?? '') }}";
            const oldState = "{{ old('state_id', $user->profile->state_id ?? '') }}";
            const oldCity = "{{ old('city_id', $user->profile->city_id ?? '') }}";

            if (oldCountry) {
                fetch(`/states/${oldCountry}`)
                    .then(res => res.json())
                    .then(states => {
                        states.forEach(state => {
                            const opt = document.createElement('option');
                            opt.value = state.id;
                            opt.textContent = state.name;
                            if (state.id == oldState) opt.selected = true;
                            stateSelect.appendChild(opt);
                        });
                        if (oldState) {
                            fetch(`/cities/${oldState}`)
                                .then(res => res.json())
                                .then(cities => {
                                    cities.forEach(city => {
                                        const opt = document.createElement('option');
                                        opt.value = city.id;
                                        opt.textContent = city.name;
                                        if (city.id == oldCity) opt.selected = true;
                                        citySelect.appendChild(opt);
                                    });
                                });
                        }
                    });
            }
        });
    </script>
@endsection
