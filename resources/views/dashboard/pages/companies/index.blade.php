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
        <div class="card-header justify-content-between d-flex align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            <a href="{{ route('company.create') }}" class="btn btn-primary">
                Add Company
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <a href="{{ route('company.edit', $company->id) }}" class="btn btn-primary">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
