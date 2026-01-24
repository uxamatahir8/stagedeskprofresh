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
                <a href="{{ route('support.tickets.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Create Ticket
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table data-tables="export-data-dropdown"
                       class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $c = 1; @endphp
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td>{{ $c++ }}</td>
                            <td>{{ $ticket->title }}</td>
                            <td>{{ ucfirst($ticket->type) }}</td>
                            <td>
                                    <span class="badge badge-label badge-soft-primary">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                            </td>
                            <td>{{ $ticket->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="action-btn">
                                    <a href="{{ route('support.tickets.edit', $ticket) }}"
                                        class="btn btn-sm btn-info" title="Edit">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <form action="{{ route('support.tickets.delete', $ticket) }}"
                                        method="POST" style="display: inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this ticket?');">
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
