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

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @php
                    // Fields with file upload
                    $fileFields = ['site_logo', 'site_favicon', 'seo_image'];
                    $text_area_fields = ['custom_head_script', 'custom_body_script', 'seo_description', 'seo_keywords'];
                @endphp

                @foreach($settings as $key => $value)
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-lg-4">
                            <label for="{{ $value->key }}"
                                class="col-form-label">{{ ucwords(str_replace('_', ' ', $value->key)) }}</label>
                        </div>
                        <div class="col-lg-8">
                            @if(in_array($value->key, $fileFields))
                                @if($value)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $value->value) }}" alt="{{ $value->key }}" height="50">
                                    </div>
                                @endif
                                <input type="file" name="{{ $value->key }}" id="{{ $value->key }}" class="form-control">
                            @elseif(in_array($value->key, $text_area_fields))
                                <textarea name="{{ $value->key }}" id="{{ $value->key }}" class="form-control" rows="4"
                                    placeholder="{{ ucwords(str_replace('_', ' ', $value->key)) }}">{{ old($value->key, $value->value) }}</textarea>
                            @else
                                <input type="text" name="{{ $value->key }}" id="{{ $value->key }}" class="form-control"
                                    value="{{ old($value->key, $value->value) }}"
                                    placeholder="{{ ucwords(str_replace('_', ' ', $value->key)) }}">
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

@endsection