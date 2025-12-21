@extends('auth.layouts.auth')

@section('content')
    <form method="POST" class="validate_form" action="{{ route('user_register') }}" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div class="row d-felx justify-content-start align-items-center">
            <div class="col-md-6">
                <!-- Register As Toggle Buttons -->
                <label class="form-label fw-bold d-block mb-2 text-start">Register As:</label>
                <div class="text-center mb-4">
                    <div class="btn-group d-flex" role="group" aria-label="Register As">
                        @foreach (config('arrays.registerable_roles') as $role_id => $role)
                            <input type="radio" class="btn-check" data-value="{{ strtolower($role) }}" name="register_as"
                                id="as_{{ strtolower($role) }}" value="{{ $role_id }}" {{ strtolower($role) == 'affiliate' ? 'checked' : '' }} autocomplete="off">
                            <label class="btn btn-outline-primary fw-semibold w-100"
                                for="as_{{ strtolower($role) }}">{{ $role }}</label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <!-- Common User Info -->
        <div id="user-fields">
            <h5 class="fw-bold mb-3">User Information</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control required" placeholder="Your Full Name"
                            value="{{ old('name') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control required" placeholder="you@example.com"
                            value="{{ old('email') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control required" placeholder="Phone Number"
                            value="{{ old('phone') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control" placeholder="Address"
                            value="{{ old('address') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Country</label>
                    <select name="country_id" id="country" class="form-select">
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">State</label>
                    <select name="state_id" id="state" class="form-select">
                        <option value="">Select State</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">City</label>
                    <select name="city_id" id="city" class="form-select">
                        <option value="">Select City</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Zip Code</label>
                        <input type="text" name="zipcode" class="form-control" placeholder="Zip Code">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="profile_image" id="profile_image" class="form-control" accept="image/*">
                        <img id="profile_preview" class="img-fluid rounded mt-2 d-none" style="max-height: 100px;">
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control required" placeholder="••••••••">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control required"
                            placeholder="••••••••">
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Section -->
        <div id="company-fields" class="d-none mt-4">
            <hr>
            <h5 class="fw-bold mb-3">Company Information</h5>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" placeholder="Company Name">
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Company Website</label>
                        <input type="text" name="company_website" class="form-control valid_url"
                            placeholder="https://example.com">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">KVK Number</label>
                        <input type="text" name="kvk_number" class="form-control kvk_number" placeholder="KVK Number">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Company Email</label>
                        <input type="email" name="company_email" class="form-control" placeholder="Company Email">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Company Phone</label>
                        <input type="text" name="company_phone" class="form-control" placeholder="Company Phone">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Upload Company Logo</label>
                        <input type="file" name="company_logo" id="company_logo" class="form-control" accept="image/*">
                        <img id="logo_preview" class="img-fluid rounded mt-2 d-none" style="max-height: 100px;">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Company Address</label>
                <textarea name="company_address" class="form-control" rows="2" placeholder="Address"></textarea>
            </div>
        </div>

        <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary fw-semibold py-2">Register Account</button>
        </div>

        <p class="text-muted text-center mt-4 mb-0">
            Already have an account?
            <a href="{{ route('login') }}" class="text-decoration-underline fw-semibold">Login Here</a>
        </p>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const companyFields = document.getElementById('company-fields');
            const radios = document.querySelectorAll('input[name="register_as"]');

            radios.forEach(radio => {
                radio.addEventListener('change', () => {
                    if (radio.getAttribute('data-value') === 'company') {
                        companyFields.classList.remove('d-none');
                    } else {
                        companyFields.classList.add('d-none');
                    }
                });
            });

            // Logo preview
            document.getElementById('company_logo').addEventListener('change', function (e) {
                const file = e.target.files[0];
                const preview = document.getElementById('logo_preview');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Profile preview
            document.getElementById('profile_image').addEventListener('change', function (e) {
                const file = e.target.files[0];
                const preview = document.getElementById('profile_preview');
                if (file) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Dynamic location loading
            const countrySelect = document.getElementById('country');
            const stateSelect = document.getElementById('state');
            const citySelect = document.getElementById('city');

            countrySelect.addEventListener('change', async function () {
                const countryId = this.value;
                stateSelect.innerHTML = '<option value="">Select State</option>';
                citySelect.innerHTML = '<option value="">Select City</option>';
                if (!countryId) return;
                const res = await fetch(`/states/${countryId}`);
                const states = await res.json();
                states.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.name;
                    stateSelect.appendChild(opt);
                });
            });

            stateSelect.addEventListener('change', async function () {
                const stateId = this.value;
                citySelect.innerHTML = '<option value="">Select City</option>';
                if (!stateId) return;
                const res = await fetch(`/cities/${stateId}`);
                const cities = await res.json();
                cities.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.name;
                    citySelect.appendChild(opt);
                });
            });
        });
    </script>
@endsection
