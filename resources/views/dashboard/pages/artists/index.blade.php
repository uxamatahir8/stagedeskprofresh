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
                <a href="{{ route('artists.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Add Artist
                </a>
            </div>
        </div>

        <div class="card-body">
            @if ($artists->count() > 0)
                <div class="table-responsive">
                    <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                        <thead class="thead-sm text-uppercase fs-xxs">
                            <tr>
                                <th>#</th>
                                <th>Stage Name</th>
                                <th>User</th>
                                <th>Company</th>
                                <th>Experience</th>
                                <th>Rating</th>
                                <th>Genres</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($artists as $artist)
                                <tr>
                                    <td>
                                        <strong>#{{ $artist->id }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($artist->image)
                                                <img src="{{ asset('storage/' . $artist->image) }}" alt="{{ $artist->stage_name }}"
                                                     class="avatar-sm rounded-circle me-2">
                                            @else
                                                <div class="avatar-sm flex-shrink-0 me-2">
                                                    <span class="avatar-title rounded-circle bg-primary-light">
                                                        {{ substr($artist->stage_name, 0, 1) }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="mb-0 fw-bold">{{ $artist->stage_name }}</p>
                                                <small class="text-muted">{{ $artist->specialization }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $artist->user->name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-info">{{ $artist->company->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        {{ $artist->experience_years }} years
                                    </td>
                                    <td>
                                        @if($artist->rating > 0)
                                            <span class="badge badge-soft-success">
                                                <i data-lucide="star" class="fill-current" style="width: 16px; height: 16px;"></i> {{ $artist->rating }}
                                            </span>
                                        @else
                                            <span class="badge badge-soft-secondary">No rating</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ substr($artist->genres, 0, 30) }}...</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('artists.show', $artist) }}"
                                                class="btn btn-sm btn-primary" title="View">
                                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <a href="{{ route('artists.edit', $artist) }}"
                                                class="btn btn-sm btn-info" title="Edit">
                                                <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <form action="{{ route('artists.destroy', $artist) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $artists->firstItem() }} to {{ $artists->lastItem() }} of {{ $artists->total() }} results
                    </div>
                    <div>
                        {{ $artists->links() }}
                    </div>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i data-lucide="info" style="width: 16px; height: 16px;"></i> No artists found. <a href="{{ route('artists.create') }}">Add your first artist</a>
                </div>
            @endif
        </div>
    </div>
@endsection
