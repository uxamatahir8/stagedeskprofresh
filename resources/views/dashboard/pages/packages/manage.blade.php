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
                <li class="breadcrumb-item"><a href="{{ route('packages') }}">Packages</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('packages') }}" class="btn btn-primary">Packages List</a>
        </div>

        <div class="card-body">
            <form class="validate_form"
                action="{{ $mode == 'edit' ? route('package.update', $package) : route('package.store') }}" method="post">
                @csrf
                @if($mode == 'edit')
                    @method('PUT')
                @endif

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="package_name" class="col-form-label">Package Name</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="package_name" name="name" class="form-control required"
                                    value="{{ old('name', $package->name ?? '') }}" placeholder="Package Name" required>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="duration_type" class="col-form-label">Duration Type</label>
                            </div>
                            <div class="col-lg-8">
                                <select id="duration_type" class="form-control form-select required" name="duration_type"
                                    required>
                                    <option value="">Select Duration Type</option>
                                    @foreach (config('arrays.duration_types') as $key => $duration_type)
                                        <option value="{{ $key }}" {{ old('duration_type', $package->duration_type ?? '') == $key ? 'selected' : '' }}>
                                            {{ $duration_type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="description" class="col-form-label">Description</label>
                            </div>
                            <div class="col-lg-8">
                                <textarea id="description" class="form-control" name="description" rows="2"
                                    placeholder="Description">{{ old('description', $package->description ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="status" class="col-form-label">Status</label>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-check form-switch form-check-secondary fs-xxl mb-2">
                                    <input type="checkbox" name="status" value="active" class="form-check-input mt-1"
                                        id="checkboxSize20" {{ $mode == 'edit' && $package->status == 'active' ? 'checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="package_price" class="col-form-label">Package Price</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="number" class="form-control required" id="package_price" name="price"
                                    value="{{ old('price', $package->price ?? '') }}" placeholder="Package Price" required>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="max_users_allowed" class="col-form-label">Users Allowed</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="number" class="form-control required" id="max_users_allowed"
                                    name="max_users_allowed"
                                    value="{{ old('max_users_allowed', $package->max_users_allowed ?? '') }}"
                                    placeholder="Max Users Allowed (e.g. 8)" required>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="max_requests_allowed" class="col-form-label">Requests Allowed</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="number" class="form-control required" id="max_requests_allowed"
                                    name="max_requests_allowed"
                                    value="{{ old('max_requests_allowed', $package->max_requests_allowed ?? '') }}"
                                    placeholder="Max Requests Allowed (e.g. 20)" required>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="max_responses_allowed" class="col-form-label">Responses Allowed</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="number" class="form-control required" id="max_responses_allowed"
                                    name="max_responses_allowed"
                                    value="{{ old('max_responses_allowed', $package->max_responses_allowed ?? '') }}"
                                    placeholder="Max Responses Allowed (e.g. 5)" required>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Package Features -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <h5 class="mb-3">Package Features</h5>
                        <div id="feature-wrapper">
                            @if($mode == 'edit' && isset($package->features))
                                @foreach($package->features as $feature)
                                    <div class="input-group mb-2 feature-item">
                                        <input type="text" name="features[]" value="{{ $feature->feature_description }}"
                                            class="form-control" placeholder="Enter feature description">
                                        <button type="button" class="btn btn-danger remove-feature">×</button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2 feature-item">
                                    <input type="text" name="features[]" class="form-control required"
                                        placeholder="Enter feature description">
                                    <button type="button" class="btn btn-danger remove-feature">×</button>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="add-feature" class="btn btn-secondary btn-sm mt-2">+ Add Feature</button>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-{{ $mode == 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode == 'edit' ? 'Update' : 'Save' }} Package
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('add-feature').addEventListener('click', function () {
            const wrapper = document.getElementById('feature-wrapper');
            const featureItems = wrapper.querySelectorAll('.feature-item');
            const total = featureItems.length;

            // Max limit check
            if (total >= 6) {
                alert('You can add a maximum of 6 features only.');
                return;
            }

            // Check if last input is filled
            const lastInput = featureItems[featureItems.length - 1]?.querySelector('input');
            if (lastInput && lastInput.value.trim() === '') {
                alert('Please fill the previous feature before adding a new one.');
                lastInput.focus();
                return;
            }

            // Create new input group
            const div = document.createElement('div');
            div.classList.add('input-group', 'mb-2', 'feature-item');
            div.innerHTML = `
            <input type="text" name="features[]" class="form-control required" placeholder="Enter feature description">
            <button type="button" class="btn btn-danger remove-feature">×</button>
        `;
            wrapper.appendChild(div);
        });

        // Handle remove feature
        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-feature')) {
                const wrapper = document.getElementById('feature-wrapper');
                const total = wrapper.querySelectorAll('.feature-item').length;

                // Prevent removing last one
                if (total <= 1) {
                    alert('At least one feature is required.');
                    return;
                }

                e.target.closest('.feature-item').remove();
            }
        });
    </script>


@endsection
