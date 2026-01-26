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
                    <a href="{{ route('support.tickets') }}">Support Tickets</a>
                </li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between">
            <h4 class="card-title">{{ $title }}</h4>
            <a href="{{ route('support.tickets') }}" class="btn btn-primary">
                Tickets List
            </a>
        </div>

        <div class="card-body">
            <form class="validate_form"
                action="{{ $mode === 'edit' ? route('support.tickets.update', $ticket) : route('support.tickets.store') }}"
                method="POST" enctype="multipart/form-data">

                @csrf
                @if ($mode === 'edit')
                    @method('PUT')
                @endif

                {{-- ================= BASIC INFO ================= --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <label class="col-form-label">Ticket Title:</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" name="title" class="form-control required"
                                    value="{{ old('title', $ticket->title ?? '') }}" placeholder="Enter ticket title">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <label class="col-form-label">Ticket Type:</label>
                            </div>
                            <div class="col-md-8">
                                <select name="type" class="form-select required">
                                    <option value="">Select Type</option>
                                    @foreach (['issue', 'complaint', 'dispute', 'suggestion', 'other'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('type', $ticket->type ?? '') === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= ATTACHMENTS ================= --}}
                <div class="row mb-3">
                    <div class="col-lg-4">
                        <label class="col-form-label">Attachments:</label>
                    </div>

                    <div class="col-lg-8">
                        <input type="file" name="attachments[]" id="attachmentsInput" class="form-control" multiple
                            accept="image/*">

                        <small class="text-muted d-block mt-1">
                            Max 5 images (2MB each)
                        </small>

                        {{-- New uploads preview --}}
                        <div id="attachmentsPreview" class="row g-2 mt-2"></div>

                        {{-- Existing attachments (edit mode) --}}
                        @if ($mode === 'edit' && $ticket->attachments->count())
                            <hr>
                            <div class="fw-semibold mb-2">Existing Attachments</div>
                            <div class="row g-2">
                                @foreach ($ticket->attachments as $attachment)
                                    <div class="col-3">
                                        <div class="border rounded p-1 text-center">
                                            <img src="{{ asset('storage/' . $attachment->file_path) }}"
                                                class="img-fluid rounded" style="height: 90px; object-fit: cover;">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ================= DESCRIPTION ================= --}}
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="col-form-label">Description:</label>
                    </div>
                    <div class="col-12">
                        <textarea id="content-editor" name="description" class="form-control required" rows="8">{{ old('description', $ticket->description ?? '') }}</textarea>
                    </div>
                </div>

                <hr>

                {{-- ================= ACTIONS ================= --}}
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-{{ $mode === 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode === 'edit' ? 'Update Ticket' : 'Create Ticket' }}
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- ================= SCRIPTS ================= --}}
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
            toolbar: 'undo redo | blocks | bold italic underline | ' +
                'bullist numlist | alignleft aligncenter alignright | ' +
                'code preview fullscreen'
        });
    </script>

    <script>
        const input = document.getElementById('attachmentsInput');
        const preview = document.getElementById('attachmentsPreview');
        const MAX_FILES = 5;

        input.addEventListener('change', function() {
            preview.innerHTML = '';

            if (this.files.length > MAX_FILES) {
                alert(`Maximum ${MAX_FILES} images allowed.`);
                this.value = '';
                return;
            }

            Array.from(this.files).forEach(file => {
                if (!file.type.startsWith('image/')) {
                    return;
                }

                const reader = new FileReader();

                reader.onload = e => {
                    const col = document.createElement('div');
                    col.classList.add('col-3');

                    col.innerHTML = `
                    <div class="border rounded p-1 text-center">
                        <img src="${e.target.result}"
                             class="img-fluid rounded"
                             style="height: 90px; object-fit: cover;">
                    </div>
                `;

                    preview.appendChild(col);
                };

                reader.readAsDataURL(file);
            });
        });
    </script>

@endsection
