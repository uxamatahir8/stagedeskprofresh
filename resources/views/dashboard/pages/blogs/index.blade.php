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

    {{-- Stats Cards --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-3">
                                    <i data-lucide="file-text"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-xs text-muted mb-1">Total Blogs</p>
                            <h4 class="mb-0">{{ $stats['total'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-success-subtle text-success rounded-circle fs-3">
                                    <i data-lucide="check-circle"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-xs text-muted mb-1">Published</p>
                            <h4 class="mb-0">{{ $stats['published'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-warning-subtle text-warning rounded-circle fs-3">
                                    <i data-lucide="edit"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-xs text-muted mb-1">Drafts</p>
                            <h4 class="mb-0">{{ $stats['draft'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-sm-6">
            <div class="card card-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm">
                                <span class="avatar-title bg-info-subtle text-info rounded-circle fs-3">
                                    <i data-lucide="message-circle"></i>
                                </span>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="text-uppercase fw-semibold fs-xs text-muted mb-1">Unapproved Comments</p>
                            <h4 class="mb-0">{{ $stats['unapproved'] ?? 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header justify-content-between d-flex align-items-center">
            <div class="title">
                <h4 class="card-title mb-0">{{ $title }}</h4>
            </div>
            <div class="action-btns d-flex gap-2">
                <a href="{{ route('comments.index') }}" class="btn btn-outline-primary">
                    <i class="ti ti-message-circle"></i> Manage Comments
                </a>
                <a href="{{ route('blog.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus"></i> Add Blog
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
                            <th>Category</th>
                            <th>Author</th>
                            <th>Stats</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Published At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blogs as $index => $blog)
                            <tr>
                                <td>{{ $blogs->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if($blog->is_featured)
                                            <span class="badge bg-warning-subtle text-warning" title="Featured">
                                                <i data-lucide="star" class="icon-xs"></i>
                                            </span>
                                        @endif
                                        <div>
                                            <strong>{{ Str::limit($blog->title, 50) }}</strong>
                                            @if($blog->excerpt)
                                                <br><small class="text-muted">{{ Str::limit($blog->excerpt, 60) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ optional($blog->category)->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td>{{ optional($blog->user)->name ?? 'Unknown' }}</td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge bg-info-subtle text-info" title="Views">
                                            <i data-lucide="eye" class="icon-xs"></i> {{ $blog->views_count ?? 0 }}
                                        </span>
                                        <span class="badge bg-success-subtle text-success" title="Comments">
                                            <i data-lucide="message-circle" class="icon-xs"></i> {{ $blog->comments_count ?? 0 }}
                                        </span>
                                        @if($blog->reading_time)
                                            <span class="badge bg-secondary-subtle text-secondary" title="Reading Time">
                                                <i data-lucide="clock" class="icon-xs"></i> {{ $blog->reading_time }} min
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($blog->image)
                                        <img src="{{ asset('storage/' . $blog->image) }}" alt="Image" width="60" height="60"
                                            class="rounded">
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
                                <td>
                                    <div class="action-btn">
                                        <a href="{{ route('blog.show', $blog->slug) }}"
                                            class="btn btn-success btn-sm" title="View" target="_blank">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        <a href="{{ route('blog.comments', $blog->id) }}"
                                            class="btn btn-sm btn-primary" title="Comments">
                                            <i class="ti ti-message-circle"></i>
                                        </a>
                                        <a href="{{ route('blog.edit', $blog->id) }}"
                                            class="btn btn-sm btn-info" title="Edit">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <form action="{{ route('blog.destroy', $blog->id) }}" method="POST"
                                            style="display: inline;"
                                            onsubmit="return confirm('Are you sure you want to delete this blog?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="ti ti-trash text-white"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i data-lucide="file-text" class="text-muted mb-3" style="width: 48px; height: 48px;"></i>
                                        <p class="text-muted mb-0">No blogs found</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($blogs->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $blogs->firstItem() }} to {{ $blogs->lastItem() }} of {{ $blogs->total() }} blogs
                    </div>
                    <div>
                        {{ $blogs->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
                </table>
            </div>
        </div>
    </div>
@endsection
