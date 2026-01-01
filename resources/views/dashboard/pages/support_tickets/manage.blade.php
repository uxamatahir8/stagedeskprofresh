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
                    <a href="{{ route('support.tickets') }}">Support Tickets</a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('support.tickets') }}" class="btn btn-primary">Tickets List</a>
        </div>

        <div class="card-body">
            <form class="validate_form"
                  action="{{ $mode == 'edit'
                    ? route('support.tickets.update', $ticket)
                    : route('support.tickets.store') }}"
                  method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    {{-- LEFT SIDE --}}
                    <div class="col-lg-6">

                        {{-- Ticket Title --}}
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Ticket Title:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" name="title" class="form-control required"
                                       value="{{ old('title', $ticket->title ?? '') }}"
                                       placeholder="Enter ticket title">
                            </div>
                        </div>

                        {{-- Ticket Type --}}
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Ticket Type:</label>
                            </div>
                            <div class="col-lg-8">
                                <select name="type" class="form-control form-select required">
                                    <option value="">Select Type</option>
                                    @foreach (['issue','complaint','dispute','suggestion','other'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $ticket->type ?? '') == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Attachments --}}
                        <div class="row g-lg-4 g-2 mt-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Attachments:</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="file" name="attachments[]" class="form-control"
                                       multiple accept=".jpg,.png,.pdf,.doc,.docx">
                                <small class="text-muted">Max 5 files (2MB each)</small>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SIDE --}}
                    <div class="col-lg-6">
                        {{-- Description --}}
                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label class="col-form-label">Description:</label>
                            </div>
                            <div class="col-lg-8">
                                <textarea id="content-editor" name="description" class="form-control required"
                                          rows="8">{{ old('description', $ticket->description ?? '') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <button type="submit"
                            class="btn btn-{{ $mode == 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode == 'edit' ? 'Update Ticket' : 'Create Ticket' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================== SCRIPT SECTION ================== --}}
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

    <script>
        tinymce.init({
            selector: '#content-editor',
            height: 400,
            menubar: true,
            branding: false,
            plugins: [
                'advlist autolink lists link charmap preview',
                'searchreplace visualblocks code fullscreen'
            ],
            toolbar:
                'undo redo | blocks | bold italic underline | bullist numlist | alignleft aligncenter alignright | code preview fullscreen',
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true
        });
    </script>
@endsection
