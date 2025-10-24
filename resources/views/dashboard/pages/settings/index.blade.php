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

            <form action="{{ route('settings.update') }}" method="POST">
                @csrf

                @foreach($settings as $key => $value)
                    <div class="row g-3 align-items-center mb-3">
                        <div class="col-lg-4">
                            <label for="{{ $key }}" class="col-form-label">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                        </div>
                        <div class="col-lg-8">
                            <input type="text" name="{{ $key }}" id="{{ $key }}" class="form-control"
                                value="{{ old($key, $value) }}" placeholder="{{ ucwords(str_replace('_', ' ', $key)) }}">
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