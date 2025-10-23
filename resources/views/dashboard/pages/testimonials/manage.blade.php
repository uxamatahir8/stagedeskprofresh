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

                <li class="breadcrumb-item"><a href="{{ route('testimonials') }}">Testimonials</a></li>

                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('testimonials') }}" class="btn btn-primary">Testimonials List</a>
        </div>

        <div class="card-body">
            <form class="validate_form"
                action="{{ $mode == 'edit' ? route('testimonials.update', $testimonial->id) : route('testimonials.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if ($mode == 'edit')
                    @method('PUT')
                @endif

                <h4 class="fw-bold">Testimonial Info</h4>
                <hr>

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="name" class="col-form-label">Name</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="name" name="name" class="form-control required"
                                    value="{{ old('name', $testimonial->name ?? '') }}" placeholder="Full Name">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="designation" class="col-form-label">Designation</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="designation" name="designation" class="form-control"
                                    value="{{ old('designation', $testimonial->designation ?? '') }}"
                                    placeholder="e.g. CEO, Marketing Head">
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="testimonial" class="col-form-label">Testimonial</label>
                            </div>
                            <div class="col-lg-8">
                                <textarea id="testimonial" name="testimonial" class="form-control required" rows="4"
                                    placeholder="Write testimonial here...">{{ old('testimonial', $testimonial->testimonial ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <h4 class="fw-bold">Avatar</h4>
                <hr>

                <div class="row g-lg-4 g-2 mt-2">
                    <div class="col-lg-4">
                        <label for="avatar" class="col-form-label">Upload Avatar</label>
                    </div>
                    <div class="col-lg-8">
                        <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">

                        <div class="mt-3 position-relative" id="avatar-preview-container">
                            @if (isset($testimonial) && $testimonial->avatar)
                                <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="Avatar" id="avatar-preview"
                                    class="img-fluid rounded" style="max-height: 120px;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                    id="remove-avatar-btn">&times;</button>
                            @else
                                <img src="" alt="Avatar Preview" id="avatar-preview" class="img-fluid rounded d-none"
                                    style="max-height: 120px;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 d-none"
                                    id="remove-avatar-btn">&times;</button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-{{ $mode == 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode == 'edit' ? 'Update' : 'Save' }} Testimonial
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatarInput = document.getElementById('avatar');
            const preview = document.getElementById('avatar-preview');
            const removeBtn = document.getElementById('remove-avatar-btn');

            avatarInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Invalid file type! Only jpeg, png, jpg, gif, webp are allowed.');
                    avatarInput.value = '';
                    return;
                }

                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    alert('File too large! Maximum size allowed is 2MB.');
                    avatarInput.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                    removeBtn.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });

            removeBtn.addEventListener('click', function () {
                avatarInput.value = '';
                preview.src = '';
                preview.classList.add('d-none');
                removeBtn.classList.add('d-none');

                if (!document.getElementById('remove_avatar_flag')) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'remove_avatar';
                    input.id = 'remove_avatar_flag';
                    input.value = '1';
                    avatarInput.closest('form').appendChild(input);
                }
            });
        });
    </script>
@endsection
