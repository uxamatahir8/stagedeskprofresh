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
            <a href="{{ route('user.create') }}" class="btn btn-primary">
                Add User
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $c = 1;
                        @endphp
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $c++ }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->name }}</td>
                                <td>
                                    <span
                                        class="badge badge-label badge-soft-{{ config('arrays.status_colors')[$user->status]  }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('user.edit', $user) }}" class="btn btn-primary btn-sm">
                                        <i data-lucide="pencil"></i>
                                    </a>
                                    @if(Auth::user()->id != $user->id)
                                        <form action="{{ route('user.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this User?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i data-lucide="trash-2"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
