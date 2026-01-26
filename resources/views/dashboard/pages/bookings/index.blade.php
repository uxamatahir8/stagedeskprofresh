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
                <a href="{{ route('bookings.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width: 16px; height: 16px;"></i> Request New Booking
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
                                @if(in_array(auth()->user()->role->role_key, ['master_admin', 'company_admin']))
                                <th>Assigned Artist</th>
                                @endif
                                <th>Status</th>
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
                                    @if(in_array(auth()->user()->role->role_key, ['master_admin', 'company_admin']))
                                    <td>
                                        @if($booking->assignedArtist)
                                            <span class="badge bg-info">{{ $booking->assignedArtist->user->name }}</span>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#assignArtistModal{{ $booking->id }}">
                                                <i data-lucide="user-plus" style="width: 16px; height: 16px;"></i> Assign
                                            </button>
                                        @endif
                                    </td>
                                    @endif
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'confirmed' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                            ];
                                            $color = $statusColors[$booking->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $booking->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('bookings.show', $booking) }}"
                                                class="btn btn-sm btn-primary"title="View">
                                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <a href="{{ route('bookings.edit', $booking) }}"
                                                class="btn btn-sm btn-info"title="Edit">
                                                <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                            </a>
                                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST"
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

                                {{-- Assign Artist Modal --}}
                                @if(in_array(auth()->user()->role->role_key, ['master_admin', 'company_admin']) && !$booking->assignedArtist)
                                <div class="modal fade" id="assignArtistModal{{ $booking->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('bookings.assign-artist', $booking->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Assign Artist to Booking #{{ $booking->id }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Select Artist</label>
                                                        <select name="artist_id" class="form-select artist-select" required data-booking-company="{{ $booking->company_id }}">
                                                            <option value="">Choose an artist...</option>
                                                            @foreach($artists ?? [] as $artist)
                                                                @php
                                                                    $companyMatch = auth()->user()->role->role_key === 'master_admin' ?
                                                                        ($booking->company_id == $artist->company_id) : true;
                                                                @endphp
                                                                <option value="{{ $artist->id }}"
                                                                    data-company-id="{{ $artist->company_id }}"
                                                                    @if(!$companyMatch && auth()->user()->role->role_key === 'master_admin') style="display:none;" @endif>
                                                                    {{ $artist->user->name }} - {{ $artist->specialization ?? 'DJ' }}
                                                                    @if(auth()->user()->role->role_key === 'master_admin' && $artist->company)
                                                                        ({{ $artist->company->name }})
                                                                    @endif
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if(auth()->user()->role->role_key === 'master_admin')
                                                            <small class="text-muted">Only artists from {{ $booking->company->name ?? 'the selected company' }} are shown</small>
                                                        @endif
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Company Notes (Optional)</label>
                                                        <textarea name="company_notes" class="form-control" rows="3" placeholder="Add any special instructions for the artist..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Assign Artist</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i data-lucide="info" style="width: 16px; height: 16px;"></i> No bookings found. <a href="{{ route('bookings.create') }}">Create a
                        new booking</a>
                </div>
            @endif
        </div>
    </div>
@endsection
