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
            <a href="{{ route('package.create') }}" class="btn btn-primary">
                Add Package
            </a>
        </div>

        <div class="card-body">
            <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                <thead class="thead-sm text-uppercase fs-xxs">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th class="text-center">Users Allowed</th>
                        <th class="text-center">Requests Allowed</th>
                        <th class="text-center">Responses Allowed</th>
                        <th>Status</th>
                        <th>Control</th>
                    </tr>
                </thead>
                <tbody>
                    @php $c = 1; @endphp
                    @foreach($packages as $package)
                        <tr>
                            <td>{{ $c }}</td>
                            <td>{{ $package->name }}</td>
                            <td>{{ ucfirst($package->duration_type) }}</td>
                            <td>{{ $package->price == 0 ? 'Free' : $package->price . '$' }}</td>
                            <td class="text-center">{{ $package->max_users_allowed }}</td>
                            <td class="text-center">{{ $package->max_requests_allowed }}</td>
                            <td class="text-center">{{ $package->max_responses_allowed }}</td>
                            <td>
                                <span
                                    class="badge badge-label badge-soft-{{ config('arrays.status_colors')[$package->status]  }}">
                                    {{ ucfirst($package->status) }}
                                </span>
                            </td>
                            <td class="d-flex gap-2">
                                <!-- Edit Button -->
                                <a href="{{ route('package.edit', $package) }}" class="btn btn-info btn-sm" title="Edit">
                                    <i data-lucide="pencil"></i>
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('package.destroy', $package) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this package?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                        <i data-lucide="trash-2"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @php $c++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div> <!-- end card-body -->
    </div> <!-- end card -->
@endsection
