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
            <div class="title">
                <h4 class="card-title mb-0">{{ $title }}</h4>
            </div>
            <div class="action-btns">
                <a href="{{ route('testimonials.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Add Testimonial
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>#</th>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Testimonial</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($testimonials as $index => $testimonial)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    @if ($testimonial->avatar)
                                        <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="Avatar"
                                            class="rounded-circle" width="45" height="45">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                <td>{{ $testimonial->name }}</td>
                                <td>{{ $testimonial->designation }}</td>
                                <td>{{ Str::limit($testimonial->testimonial, 60) }}</td>
                                <td>{{ $testimonial->created_at->format('d M, Y') }}</td>

                                <td>
                                    <div class="action-btn">
                                        <a href="{{ route('testimonials.edit', $testimonial->id) }}" class="btn btn-sm btn-info"
                                            title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <form action="{{ route('testimonials.destroy', $testimonial->id) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this testimonial?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="ti ti-trash text-white"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
