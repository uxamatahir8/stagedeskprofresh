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
            <a href="{{ route('bookings.index') }}" class="btn btn-primary">Bookings Requests</a>
        </div>
        <div class="card-body">
            <form class="validate_form"
                action="{{ $mode == 'edit' ? route('bookings.update', $booking) : route('bookings.store') }}" method="post">
                @csrf
                @if ($mode == 'edit')
                    @method('PUT')
                @endif
            </form>
        </div>
    </div>
@endsection
