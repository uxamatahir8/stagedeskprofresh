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

                <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>

                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('users') }}" class="btn btn-primary">Users List</a>
        </div>
        <div class="card-body">
            <form class="validate_form" action="{{ $mode == 'edit' ? route('user.update', $user) : route('user.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if($mode == 'edit')
                    @method('PUT')
                @endif
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="name" class="col-form-label">Select User Role:</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control form-select required" name="role_id">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-lg-4 g-2 mt-2 ">
                            <div class="col-lg-4">
                                <label for="name" class="col-form-label">Full Name:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="name" name="name" class="form-control required"
                                    value="{{ old('name', $user->name ?? '') }}" placeholder="Full Name">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mt-2 ">
                            <div class="col-lg-4">
                                <label for="phone" class="col-form-label">Phone:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="phone" name="phone" class="form-control phone required"
                                    value="{{ old('phone', $user->phone ?? '') }}" placeholder="Phone">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div id="company_admin" class="row g-lg-4 g-2 d-none">
                            <div class="col-lg-4">
                                <label for="company_name" class="col-form-label">Select Company:</label>
                            </div>
                            <div class="col-lg-8">
                                <select class="form-control form-select" name="company_id" id="company_id">
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id', $user->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="email-div" class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="email" class="col-form-label">Email:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="email" id="email" name="email" class="form-control required"
                                    value="{{ old('email', $user->email ?? '') }}" placeholder="Email">
                            </div>
                        </div>
                        <div id="email-div" class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="email" class="col-form-label">Password:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="password" id="password" name="password"
                                    class="form-control {{ $mode == 'create' ? 'required' : '' }}"
                                    placeholder="Enter Password">
                                @if($mode == 'edit')
                                    <small class="ms-2 text text-info">
                                        * Leave blank if you don't want to change password
                                    </small>
                                @endif

                            </div>
                        </div>
                        <div id="email-div" class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label for="email" class="col-form-label">Confirm Password:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="password" data-match="password" id="confirm_password" name="confirm_password"
                                    class="form-control match {{ $mode == 'create' ? 'required' : '' }}"
                                    placeholder="Confirm Password">
                                @if($mode == 'edit')
                                    <small class="ms-2 text text-info">
                                        * Leave blank if you don't want to change password
                                    </small>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.querySelector('select[name="role_id"]').addEventListener('change', (e) => {
            const select = e.target;
            const role_key = select.options[select.selectedIndex].text;


            const company_roles_key = ['Artist', 'Company Admin']

            if (company_roles_key.includes(role_key)) {
                document.querySelector('#company_admin').classList.remove('d-none');
                document.querySelector('#company_id').classList.add('required');
                document.querySelector('#email-div').classList.add('mt-2');
            } else {
                document.querySelector('#company_admin').classList.add('d-none');
                document.querySelector('#company_id').classList.remove('required');
                document.querySelector('#company_id').value = '';
                document.querySelector('#email-div').classList.remove('mt-2');
            }

        });
    </script>
@endsection
