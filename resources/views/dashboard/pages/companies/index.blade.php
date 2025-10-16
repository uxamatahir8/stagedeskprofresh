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
                            <th>KVK Number</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $c = 1;
                        @endphp
                        @foreach ($companies as $company)
                            <tr>
                                <td>{{ $c }}</td>
                                <td>{{ $company->kvk_number }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->email }}</td>
                                <td>{{ $company->phone }}</td>
                                <td>
                                    <span
                                        class="badge badge-label badge-soft-{{ $company->status == 'active' ? 'primary' : 'danger' }}">
                                        {{ ucfirst($company->status) }}
                                    </span>
                                </td>
                                <td class="d-flex gap-2">

                                    <a href="{{ route('company.show', $company) }}" class="btn btn-dark btn-sm">
                                        <i data-lucide="eye"></i>
                                    </a>


                                    <a href="{{ route('company.edit', $company) }}" class="btn btn-info btn-sm">
                                        <i data-lucide="pencil"></i>
                                    </a>
                                    <!-- Delete Button -->
                                    <form action="{{ route('company.destroy', $company) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this company?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @php
                                $c++
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
