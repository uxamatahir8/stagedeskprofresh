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
                <a href="{{ route('blog.create') }}" class="btn btn-primary">
                    Add Blog
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table data-tables="export-data-dropdown" class="table table-striped align-middle mb-0">
                    <thead class="thead-sm text-uppercase fs-xxs">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Image</th>
                            <th>Feature Image</th>
                            <th>Status</th>
                            <th>Published At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($blogs as $index => $blog)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $blog->title }}</td>
                                <td>{{ $blog->slug }}</td>
                                <td>{{ optional($blog->category)->name ?? 'N/A' }}</td>
                                <td>{{ optional($blog->user)->name ?? 'N/A' }}</td>
                                <td>
                                    @if ($blog->image)
                                        <img src="{{ asset('storage/' . $blog->image) }}" alt="Image" width="60" height="60"
                                            class="rounded">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($blog->feature_image)
                                        <img src="{{ asset('storage/' . $blog->feature_image) }}" alt="Feature" width="60"
                                            height="60" class="rounded">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge badge-label badge-soft-{{ config('arrays.status_colors')[$blog->status] ?? 'secondary' }}">
                                        {{ ucfirst($blog->status) }}
                                    </span>
                                </td>
                                <td>{{ $blog->published_at ? \Carbon\Carbon::parse($blog->published_at)->format('d M, Y') : '—' }}
                                </td>
                                <td class="d-flex gap-2">


                                    <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-info btn-sm" title="Edit">
                                        <i data-lucide="pencil"></i>
                                    </a>

                                    <form action="{{ route('blog.destroy', $blog->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this blog?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i data-lucide="trash-2"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection