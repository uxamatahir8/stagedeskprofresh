@extends('dashboard.layouts.dashboard')

@section('content')

    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0"><i data-lucide="settings" class="me-2"></i>{{ $title }}</h4>
            <p class="text-muted mb-0 mt-1">Configure your system settings and preferences</p>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i data-lucide="check-circle" class="me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" class="validate_form" method="POST" enctype="multipart/form-data">
        @csrf

        @php
            $fileFields = ['site_logo', 'site_favicon', 'seo_image'];
            $textAreaFields = ['custom_head_script', 'custom_body_script', 'seo_description', 'seo_keywords'];
            $timezoneFields = ['timezone'];

            // Group settings by category
            $generalSettings = [];
            $seoSettings = [];
            $emailSettings = [];
            $customScriptSettings = [];

            foreach($settings as $setting) {
                if (str_contains($setting->key, 'seo_')) {
                    $seoSettings[] = $setting;
                } elseif (str_contains($setting->key, 'email_')) {
                    $emailSettings[] = $setting;
                } elseif (str_contains($setting->key, 'script')) {
                    $customScriptSettings[] = $setting;
                } else {
                    $generalSettings[] = $setting;
                }
            }
        @endphp

        {{-- General Settings --}}
        @if(count($generalSettings) > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0"><i data-lucide="sliders" class="me-2"></i>General Settings</h5>
            </div>
            <div class="card-body">
                @foreach($generalSettings as $setting)
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
                            <label for="{{ $setting->key }}" class="col-form-label fw-semibold">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                        </div>
                        <div class="col-lg-8">

                            {{-- FILE FIELDS --}}
                            @if(in_array($setting->key, $fileFields))
                                @if(!empty($setting->value))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}" class="rounded border" height="60">
                                    </div>
                                @endif

                                <img id="{{ $setting->key }}_preview" src="" alt=""
                                    style="display:none; height:70px; margin-bottom:10px;" class="rounded border">

                                <input type="file" name="{{ $setting->key }}" id="{{ $setting->key }}"
                                    class="form-control {{ $extraClass }} image-preview-input" accept="image/*">
                                <small class="text-muted">Recommended: PNG or JPG format</small>

                            {{-- TEXTAREA FIELDS --}}
                            @elseif(in_array($setting->key, $textAreaFields))
                                <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="3"
                                    class="form-control {{ $extraClass }}"
                                    placeholder="{{ ucwords(str_replace('_', ' ', $setting->key)) }}">{{ old($setting->key, $setting->value) }}</textarea>

                            {{-- TIMEZONE SELECT --}}
                            @elseif(in_array($setting->key, $timezoneFields))
                                <select name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-select">
                                    <option value="">Select Timezone</option>
                                    @foreach($timezones as $timezone)
                                        <option value="{{ $timezone->timezone }}" {{ old($setting->key, $setting->value) == $timezone->timezone ? 'selected' : '' }}>
                                            {{ "(GMT" . ($timezone->gmt_offset > 0 ? "+" . $timezone->gmt_offset : $timezone->gmt_offset) . ":00) - $timezone->timezone" }}
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
            </div>
        </div>
        @endif

        {{-- SEO Settings --}}
        @if(count($seoSettings) > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0"><i data-lucide="search" class="me-2"></i>SEO Settings</h5>
            </div>
            <div class="card-body">
                @foreach($seoSettings as $setting)
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-lg-4">
                            <label for="{{ $setting->key }}" class="col-form-label fw-semibold">
                                {{ ucwords(str_replace(['_', 'seo '], [' ', 'SEO '], $setting->key)) }}
                            </label>
                        </div>
                        <div class="col-lg-8">
                            @if(in_array($setting->key, $fileFields))
                                @if(!empty($setting->value))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $setting->value) }}" alt="{{ $setting->key }}" class="rounded border" height="60">
                                    </div>
                                @endif
                                <img id="{{ $setting->key }}_preview" src="" alt="" style="display:none; height:70px; margin-bottom:10px;" class="rounded border">
                                <input type="file" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control image-preview-input" accept="image/*">
                                <small class="text-muted">Used for social sharing previews</small>
                            @elseif(in_array($setting->key, $textAreaFields))
                                <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="3" class="form-control" placeholder="{{ ucwords(str_replace('_', ' ', $setting->key)) }}">{{ old($setting->key, $setting->value) }}</textarea>
                            @else
                                <input type="text" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control" value="{{ old($setting->key, $setting->value) }}" placeholder="{{ ucwords(str_replace('_', ' ', $setting->key)) }}">
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Email Settings --}}
        @if(count($emailSettings) > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0"><i data-lucide="mail" class="me-2"></i>Email Settings</h5>
            </div>
            <div class="card-body">
                @foreach($emailSettings as $setting)
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-lg-4">
                            <label for="{{ $setting->key }}" class="col-form-label fw-semibold">
                                {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                            </label>
                        </div>
                        <div class="col-lg-8">
                            <input type="email" name="{{ $setting->key }}" id="{{ $setting->key }}" class="form-control email" value="{{ old($setting->key, $setting->value) }}" placeholder="{{ ucwords(str_replace('_', ' ', $setting->key)) }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Custom Scripts --}}
        @if(count($customScriptSettings) > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0"><i data-lucide="code" class="me-2"></i>Custom Scripts</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i data-lucide="alert-triangle" class="me-2"></i>
                    <strong>Warning:</strong> Only add trusted scripts. Invalid code may break your website.
                </div>
                @foreach($customScriptSettings as $setting)
                    <div class="mb-4">
                        <label for="{{ $setting->key }}" class="form-label fw-semibold">
                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                        </label>
                        <textarea name="{{ $setting->key }}" id="{{ $setting->key }}" rows="6" class="form-control font-monospace" placeholder="<script>Your code here</script>">{{ old($setting->key, $setting->value) }}</textarea>
                        <small class="text-muted">This script will be inserted in the {{ str_contains($setting->key, 'head') ? '<head>' : '<body>' }} section</small>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i data-lucide="x" class="me-1"></i>Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i data-lucide="save" class="me-1"></i>Update Settings
            </button>
        </div>

    </form>

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
