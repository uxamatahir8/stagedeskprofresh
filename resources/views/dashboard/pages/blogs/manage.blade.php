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
                <li class="breadcrumb-item"><a href="{{ route('blogs') }}">Blogs</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <h4 class="card-title mb-0">{{ $title }}</h4>
            <a href="{{ route('blogs') }}" class="btn btn-primary">Blogs List</a>
        </div>

        <div class="card-body">
            <form class="validate_form"
                action="{{ $mode == 'edit' ? route('blog.update', $blog->id) : route('blog.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @if ($mode == 'edit')
                    @method('PUT')
                @endif

                <h4 class="fw-bold">Blog Information</h4>
                <hr>

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2 mb-2">
                            <div class="col-lg-4">
                                <label for="title" class="col-form-label">Title</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="title" name="title" class="form-control required"
                                    value="{{ old('title', $blog->title ?? '') }}" placeholder="Enter Blog Title">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mb-2">
                            <div class="col-lg-4">
                                <label for="slug" class="col-form-label">Slug</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="slug" name="slug" class="form-control required"
                                    value="{{ old('slug', $blog->slug ?? '') }}" placeholder="Auto-generated slug">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mb-2">
                            <div class="col-lg-4">
                                <label for="excerpt" class="col-form-label">Excerpt</label>
                            </div>
                            <div class="col-lg-8">
                                <textarea id="excerpt" name="excerpt" class="form-control" rows="3"
                                    placeholder="Short summary of the blog post">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                                <small class="text-muted">Brief description shown in blog listings</small>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mb-2">
                            <div class="col-lg-4">
                                <label for="blog_category_id" class="col-form-label">Category</label>
                            </div>
                            <div class="col-lg-8">
                                <select name="blog_category_id" id="blog_category_id" class="form-select required">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('blog_category_id', $blog->blog_category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mb-2">
                            <div class="col-lg-4">
                                <label for="tags" class="col-form-label">Tags</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="text" id="tags" name="tags" class="form-control"
                                    value="{{ old('tags', isset($blog->tags) ? implode(',', $blog->tags) : '') }}"
                                    placeholder="e.g., Laravel, PHP, Web Development">
                                <small class="text-muted">Separate tags with commas</small>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mb-2">
                            <div class="col-lg-4">
                                <label for="published_at" class="col-form-label">Publish Date</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="date" id="published_at" name="published_at" class="form-control"
                                    value="{{ old('published_at', isset($blog->published_at) ? \Carbon\Carbon::parse($blog->published_at)->format('Y-m-d') : '') }}">
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2 mb-2">
                            <div class="col-lg-4">
                                <label for="is_featured" class="col-form-label">Featured Post</label>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-check form-switch form-check-warning fs-xxl">
                                    <input type="checkbox" name="is_featured" value="1" class="form-check-input mt-1"
                                        id="is_featured" {{ old('is_featured', $blog->is_featured ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Show on featured section</label>
                                </div>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="status" class="col-form-label">Status</label>
                            </div>
                            <div class="col-lg-8">
                                <div class="form-check form-switch form-check-secondary fs-xxl">
                                    <input type="checkbox" name="status" value="active" class="form-check-input mt-1"
                                        id="status" {{ ($mode == 'edit' && $blog->status == 'active') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Publish immediately</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-lg-6">
                        <div class="row g-lg-4 g-2 mb-2">
                            <div class="col-lg-4">
                                <label for="image" class="col-form-label">Thumbnail</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                <div class="mt-3 position-relative" id="image-preview-container">
                                    @if (isset($blog) && $blog->image)
                                        <img src="{{ asset('storage/' . $blog->image) }}" alt="Blog Image" id="image-preview"
                                            class="img-fluid rounded" style="max-height: 120px;">
                                    @else
                                        <img src="" alt="Image Preview" id="image-preview" class="img-fluid rounded d-none"
                                            style="max-height: 120px;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row g-lg-4 g-2">
                            <div class="col-lg-4">
                                <label for="feature_image" class="col-form-label">Feature Image</label>
                            </div>
                            <div class="col-lg-8">
                                <input type="file" name="feature_image" id="feature_image" class="form-control"
                                    accept="image/*">
                                <div class="mt-3 position-relative" id="feature-image-preview-container">
                                    @if (isset($blog) && $blog->feature_image)
                                        <img src="{{ asset('storage/' . $blog->feature_image) }}" alt="Feature Image"
                                            id="feature-image-preview" class="img-fluid rounded" style="max-height: 120px;">
                                    @else
                                        <img src="" alt="Feature Preview" id="feature-image-preview"
                                            class="img-fluid rounded d-none" style="max-height: 120px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <h4 class="fw-bold">Blog Content</h4>
                <hr>

                <div class="row mt-2">
                    <div class="col-12">
                        <textarea name="blog_content" id="content-editor" class="form-control"
                            rows="10">{{ old('content', $blog->content ?? '') }}</textarea>
                    </div>
                </div>

                <hr class="mt-4">
                <h4 class="fw-bold">SEO Settings</h4>
                <hr>

                <div class="row g-3">
                    <div class="col-lg-6">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" id="meta_title" name="meta_title" class="form-control"
                            value="{{ old('meta_title', $blog->meta_title ?? '') }}"
                            placeholder="SEO optimized title (max 60 chars)" maxlength="60">
                        <small class="text-muted">Leave empty to use blog title</small>
                    </div>

                    <div class="col-lg-6">
                        <label for="reading_time" class="form-label">Reading Time (minutes)</label>
                        <input type="number" id="reading_time" name="reading_time" class="form-control"
                            value="{{ old('reading_time', $blog->reading_time ?? '') }}"
                            placeholder="Auto-calculated if left empty" min="1">
                    </div>

                    <div class="col-12">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea id="meta_description" name="meta_description" class="form-control" rows="3"
                            placeholder="SEO optimized description (max 160 chars)" maxlength="160">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
                        <small class="text-muted">Leave empty to use excerpt</small>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-{{ $mode == 'edit' ? 'warning' : 'primary' }}">
                        {{ $mode == 'edit' ? 'Update' : 'Save' }} Blog
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ✅ JS for Slug and Image Preview --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            titleInput.addEventListener('input', function () {
                if (!slugInput.dataset.changed) {
                    slugInput.value = titleInput.value
                        .toLowerCase()
                        .replace(/[^\w ]+/g, '')
                        .replace(/ +/g, '-');
                }
            });

            slugInput.addEventListener('input', function () {
                slugInput.dataset.changed = true;
            });

            function previewImage(input, previewId) {
                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const preview = document.getElementById(previewId);
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                });
            }

            previewImage(document.getElementById('image'), 'image-preview');
            previewImage(document.getElementById('feature_image'), 'feature-image-preview');
        });
    </script>

    {{-- ✅ TinyMCE WYSIWYG Editor --}}
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

    <script>
        tinymce.init({
            selector: '#content-editor',
            height: 500,
            menubar: true,
            branding: false,
            plugins: [
                'advlist autolink lists link image charmap preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table code help wordcount'
            ],
            toolbar:
                'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | code preview fullscreen',

            /* ✅ Optional but cleaner URLs */
            relative_urls: false,
            remove_script_host: false,
            convert_urls: true,

            /* ✅ Image upload handler for Laravel */
            images_upload_url: '{{ route('blogs.uploadImage') }}',
            automatic_uploads: true,
            file_picker_types: 'image',

            file_picker_callback: function (cb, value, meta) {
                let input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function () {
                    let file = this.files[0];
                    let reader = new FileReader();
                    reader.onload = function () {
                        let id = 'blobid' + (new Date()).getTime();
                        let blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        let base64 = reader.result.split(',')[1];
                        let blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };
                input.click();
            },

            /* ✅ Backend Upload Handler */
            images_upload_handler: function (blobInfo, success, failure) {
                let xhr = new XMLHttpRequest();
                xhr.open('POST', '{{ route('blogs.uploadImage') }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                xhr.onload = function () {
                    if (xhr.status !== 200) {
                        failure('HTTP Error: ' + xhr.status);
                        return;
                    }
                    const json = JSON.parse(xhr.responseText);
                    if (!json || typeof json.location != 'string') {
                        failure('Invalid JSON: ' + xhr.responseText);
                        return;
                    }
                    success(json.location);
                };

                let formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            }
        });
    </script>
@endsection
