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
                <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Request New Booking
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($bookings->count() > 0)
                <div class="table-responsive">
                    <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                        <thead class="thead-sm text-uppercase fs-xxs">
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Event Type</th>
                                <th>Event Date</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bookings as $booking)
                                <tr>
                                    <td>
                                        <strong>#{{ $booking->id }}</strong>
                                    </td>
                                    <td>
                                        {{ $booking->name }} {{ $booking->surname }}
                                    </td>
                                    <td>
                                        {{ $booking->eventType->event_type ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $booking->event_date }}
                                    </td>
                                    <td>
                                        {{ $booking->email }}
                                    </td>
                                    <td>
                                        {{ $booking->phone }}
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $booking->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="action-btn">
                                            <a href="{{ route('bookings.show', $booking) }}"
                                                class="btn btn-sm btn-primary"title="View">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('bookings.edit', $booking) }}"
                                                class="btn btn-sm btn-info"title="Edit">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete"
                                                    onclick="return confirm('Are you sure?')">
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
            @else
                <div class="alert alert-info mb-0">
                    <i class="ti ti-info-circle"></i> No bookings found. <a href="{{ route('bookings.create') }}">Create a
                        new booking</a>
                </div>
            @endif
        </div>
    </div>
@endsection
