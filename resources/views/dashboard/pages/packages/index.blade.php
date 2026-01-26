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
                        <i data-lucide="home" style="width: 14px; height: 14px;"></i>
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
                <a href="{{ route('package.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Add Package
                </a>
            </div>
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
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('package.edit', $package) }}" class="btn btn-info btn-sm" title="Edit">
                                        <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                    </a>
                                    <form action="{{ route('package.destroy', $package) }}" method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this package?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @php $c++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div> <!-- end card-body -->
    </div> <!-- end card -->
@endsection
