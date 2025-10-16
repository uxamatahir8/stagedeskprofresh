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
            <h4 class="card-title"> {{ $title }}</h4>
            <a href="{{ route('packages') }}" class="btn btn-primary">Packages List</a>
        </div>

        <div class="card-body">
            <form class="validate_form" action="" method="post">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="package_name" class="col-form-label">Package Name</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="package_name" name="name" class="form-control required"
                                    placeholder="Package Name">
                            </div>
                        </div>
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="duration_name" class="col-form-label">Duration Type</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control form-select required" name="duration_type">
                                    <option value="" selected> Select Duration Type </option>
                                    <option value="weekly">Weekly</option>
                                    <option value="weekly">Monthly</option>
                                    <option value="weekly">Yearly</option>
                                </select>
                            </div>
                        </div>
                    </div> <!-- end col -->

                    <div class="col-lg-6">
                        <!-- with Label Input -->
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Package Price</label>
                            </div>
                            <div class="col-lg-8">
                                <div>
                                    <input type="text" class="form-control required number" id="package_price" name="price"
                                        placeholder="Package Price">
                                </div>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div>
            </form>
        </div>
    </div>
@endsection
