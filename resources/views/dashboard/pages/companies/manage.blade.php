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
            </form>
        </div>
    </div>
@endsection
