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
                <li class="breadcrumb-item">
                    <a href="{{ route('artists.index') }}">Artists</a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="mb-2">Please fix the following errors:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('artists.index') }}" class="btn btn-primary">Artists List</a>
        </div>

        <div class="card-body">
            <form class="validate_form"
                action="{{ $mode === 'edit' ? route('artists.update', $artist) : route('artists.store') }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                @if ($mode === 'edit')
                    @method('PUT')
                @endif

                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Company <span class="text-danger">*</span></label>
                        <select name="company_id" class="form-control form-select required" id="company_id">
                            <option value="">Select Company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ old('company_id', $preselectedUser->company_id ?? $artist->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">User (Artist) <span class="text-danger">*</span></label>
                        <select name="user_id" class="form-control form-select required" id="user_id">
                            <option value="">Select Artist User</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('user_id', $preselectedUser->id ?? $artist->user_id ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @if(isset($preselectedUser))
                            <small class="text-info mt-1 d-block">
                                <i data-lucide="info" style="width: 16px; height: 16px;"></i> User "{{ $preselectedUser->name }}" was just created and is pre-selected.
                            </small>
                        @endif
                        @error('user_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Stage Name <span class="text-danger">*</span></label>
                        <input type="text" name="stage_name" class="form-control required" placeholder="Enter stage name"
                            value="{{ old('stage_name', $artist->stage_name ?? '') }}">
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Years of Experience <span class="text-danger">*</span></label>
                        <input type="number" name="experience_years" class="form-control required" min="0" placeholder="Enter years"
                            value="{{ old('experience_years', $artist->experience_years ?? 0) }}">
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Genres <span class="text-danger">*</span></label>
                        <input type="text" name="genres" class="form-control required" placeholder="e.g., House, Techno, Pop"
                            value="{{ old('genres', $artist->genres ?? '') }}">
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Specialization <span class="text-danger">*</span></label>
                        <input type="text" name="specialization" class="form-control required" placeholder="e.g., Wedding DJ, Club DJ"
                            value="{{ old('specialization', $artist->specialization ?? '') }}">
                    </div>

                    <div class="col-lg-12 mb-3">
                        <label class="col-form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="4" placeholder="Enter artist bio">{{ old('bio', $artist->bio ?? '') }}</textarea>
                    </div>

                    <div class="col-lg-6 mb-3">
                        <label class="col-form-label">Profile Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @if($mode === 'edit' && $artist->image)
                            <small class="text-muted">Current: <a href="{{ asset('storage/' . $artist->image) }}" target="_blank">View Image</a></small>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('artists.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-{{ $mode === 'edit' ? 'warning' : 'primary' }}">
                        <i class="ti ti-{{ $mode === 'edit' ? 'pencil' : 'plus' }}"></i>
                        {{ $mode === 'edit' ? 'Update' : 'Save' }} Artist
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
