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
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form class="validate_form"
                        action="{{ $mode == 'edit' ? route('category.update', $category) : route('category.store') }}"
                        method="post">
                        @csrf
                        @if($mode == 'edit') @method('PUT') @endif
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control required"
                                value="{{ old('name', $category->name ?? '') }}" name="name" id="name">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">{{ $mode == 'edit' ? 'Update' : 'Save' }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                            <thead class="thead-sm text-uppercase fs-xxs">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td class="d-flex gap-2">
                                            <a href="{{ route('category.edit', $category) }}" class="btn btn-sm btn-primary">
                                                <i data-lucide="pencil"></i>
                                            </a>

                                            <form action="{{ route('category.destroy', $category) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this Category?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i data-lucide="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
