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
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form class="validate_form"
                        action="{{ $mode == 'edit' ? route('event.update', $event) : route('event.store') }}"
                        method="post">
                        @csrf
                        @if($mode == 'edit') @method('PUT') @endif
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control required"
                                value="{{ old('name', $event->event_type ?? '') }}" name="event_type" id="event_type">
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
                                @foreach ($event_types as $event_type)
                                    <tr>
                                        <td>{{ $event_type->id }}</td>
                                        <td>{{ $event_type->event_type }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('event.edit', $event_type) }}" class="btn btn-sm btn-info"
                                                    title="Edit">
                                                    <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                                                </a>
                                                <form action="{{ route('event.destroy', $event_type) }}" method="POST"
                                                    style="display: inline;"
                                                    onsubmit="return confirm('Are you sure you want to delete this Event Type?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
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
                </div>
            </div>
        </div>
    </div>
@endsection
