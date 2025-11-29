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
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
        </div>

        <div class="card-body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('settings.update') }}" class="validate_form" method="POST" enctype="multipart/form-data">
                @csrf

                @php
                    $fileFields = ['site_logo', 'site_favicon', 'seo_image'];
                    $textAreaFields = ['custom_head_script', 'custom_body_script', 'seo_description', 'seo_keywords'];
                    $timezoneFields = ['timezone'];
                @endphp

                @foreach($settings as $key => $setting)
                    @php
                        $extraClass = '';
                        if (str_contains($setting->key, 'email')) {
                            $extraClass = 'email';
                        } elseif (str_contains($setting->key, 'phone')) {
                            $extraClass = 'phone';
                        } elseif (str_contains($setting->key, 'url')) {
                            $extraClass = 'valid_url';
                        }
                    @endphp

                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-lg-4">
                            <label for="{{ $setting->key }}" class="col-form-label">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                        </div>
                        <div class="col-lg-8">

                            {{-- FILE FIELDS --}}
                            @if(in_array($setting->key, $fileFields))
                                @if(!empty($setting->value))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}" height="50">
                                    </div>
                                @endif

                                {{-- NEW PREVIEW HOLDER --}}
                                <img id="{{ $setting->key }}_preview" src="" alt=""
                                    style="display:none; height:70px; margin-bottom:10px;">

                                <input type="file" name="{{ $setting->key }}" id="{{ $setting->key }}"
                                    class="form-control {{ $extraClass }} image-preview-input">

                                {{-- TEXTAREA FIELDS --}}
                            @elseif(in_array($setting->key, $textAreaFields))
                                <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="4"
                                    class="form-control {{ $extraClass }}"
                                    placeholder="{{ ucwords(str_replace('_', ' ', $setting->key)) }}">{{ old($setting->key, $setting->value) }}</textarea>

                                {{-- TIMEZONE SELECT --}}
                            @elseif(in_array($setting->key, $timezoneFields))
                                <select name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-select">
                                    <option value="">Select Timezone</option>
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone->timezone }}" {{ old($setting->key, $setting->value) == $timezone->timezone ? 'selected' : '' }}>
                                            {{ "$timezone->timezone - $timezone->offset" }}
                                        </option>
                                    @endforeach
                                </select>

                                {{-- TEXT & EMAIL INPUTS --}}
                            @else
                                <input type="{{ str_contains($setting->key, 'email') ? 'email' : 'text' }}"
                                    name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control {{ $extraClass }}"
                                    value="{{ old($setting->key, $setting->value) }}"
                                    placeholder="{{ ucwords(str_replace('_', ' ', $setting->key)) }}">
                            @endif

                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.image-preview-input').forEach(input => {
            input.addEventListener('change', function () {
                const preview = document.getElementById(this.id + '_preview');
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        preview.src = event.target.result;
                        preview.style.display = "block";
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>

@endsection