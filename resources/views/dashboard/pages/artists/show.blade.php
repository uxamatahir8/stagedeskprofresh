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
                <li class="breadcrumb-item">
                    <a href="{{ route('artists.index') }}">Artists</a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-body text-center">
                    @if($artist->image)
                        <img src="{{ asset('storage/' . $artist->image) }}" alt="{{ $artist->stage_name }}"
                             class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="avatar-lg mb-3 mx-auto">
                            <span class="avatar-title rounded-circle bg-primary-light">
                                {{ substr($artist->stage_name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                    <h5 class="fw-bold">{{ $artist->stage_name }}</h5>
                    <p class="text-muted mb-2">{{ $artist->specialization }}</p>
                    @if($artist->rating > 0)
                        <div class="mb-2">
                            <span class="badge badge-soft-success">
                                <i class="ti ti-star-filled"></i> {{ $artist->rating }} Rating
                            </span>
                        </div>
                    @endif
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('artists.edit', $artist) }}" class="btn btn-sm btn-warning">
                            <i class="ti ti-pencil"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-3">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h4 class="card-title">Artist Details</h4>
                    <a href="{{ route('artists.index') }}" class="btn btn-primary btn-sm">Artists List</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <h6 class="mb-1">Company:</h6>
                            <p class="text-muted mb-0">{{ $artist->company->name ?? 'N/A' }}</p>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <h6 class="mb-1">User:</h6>
                            <p class="text-muted mb-0">{{ $artist->user->name ?? 'N/A' }}</p>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <h6 class="mb-1">Experience:</h6>
                            <p class="text-muted mb-0">{{ $artist->experience_years }} years</p>
                        </div>

                        <div class="col-sm-6 mb-3">
                            <h6 class="mb-1">Genres:</h6>
                            <p class="text-muted mb-0">{{ $artist->genres }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <h6 class="mb-1">Bio:</h6>
                            <p class="text-muted mb-0">{{ $artist->bio ?? 'No bio provided' }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <h6 class="mb-2">Joined:</h6>
                            <p class="text-muted mb-0">{{ $artist->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Services Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header justify-content-between d-flex align-items-center">
                    <h5 class="card-title mb-0">Services ({{ $artist->services->count() }})</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="ti ti-plus"></i> Add Service
                    </button>
                </div>

                <div class="card-body">
                    @if($artist->services->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Service Name</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($artist->services as $service)
                                        <tr>
                                            <td><strong>{{ $service->service_name }}</strong></td>
                                            <td>{{ substr($service->service_description, 0, 50) }}...</td>
                                            <td>${{ number_format($service->price, 2) }}</td>
                                            <td>{{ $service->duration }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            No services added yet. <a href="#" data-bs-toggle="modal" data-bs-target="#addServiceModal">Add one now</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="#" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Service Name</label>
                            <input type="text" class="form-control" name="service_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="service_description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Price</label>
                                <input type="number" class="form-control" name="price" step="0.01" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Duration</label>
                                <input type="text" class="form-control" name="duration" placeholder="e.g., 4 hours" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
