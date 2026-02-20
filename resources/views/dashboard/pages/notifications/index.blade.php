@extends('dashboard.layouts.dashboard')

@section('content')
    <div class="page-title-head d-flex align-items-center justify-content-between mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-xl fw-bold m-0">
                <i data-lucide="bell" class="me-2"></i>{{ $title }}
            </h4>
            <p class="text-muted mb-0 mt-1">Manage your notifications and alerts</p>
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i data-lucide="check-circle" class="me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="title">
                <h5 class="card-title mb-0">
                    <i data-lucide="list" class="me-2"></i>All Notifications
                </h5>
                @if($unreadCount > 0)
                    <small class="text-danger">{{ $unreadCount }} unread</small>
                @endif
            </div>
            <div class="action-btns d-flex gap-2">
                @if($unreadCount > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i data-lucide="check" style="width: 16px; height: 16px;"></i> Mark All Read
                        </button>
                    </form>
                @endif
                @if($notifications->count() > 0)
                    <form action="{{ route('notifications.destroy-all') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete all notifications?')">
                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Clear All
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="card-header border-bottom-0 bg-light">
            <form method="GET" action="{{ route('notifications.index') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach(($categoryCounts ?? collect())->keys() as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="priority" class="form-select form-select-sm">
                        <option value="">All Priorities</option>
                        <option value="4" {{ request('priority') === '4' ? 'selected' : '' }}>High (4)</option>
                        <option value="3" {{ request('priority') === '3' ? 'selected' : '' }}>Medium (3)</option>
                        <option value="2" {{ request('priority') === '2' ? 'selected' : '' }}>Normal (2)</option>
                        <option value="1" {{ request('priority') === '1' ? 'selected' : '' }}>Low (1)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Apply</button>
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-light w-100">Reset</a>
                </div>
            </form>

            <ul class="nav nav-pills mb-0" id="notificationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab">
                        <i data-lucide="list" class="me-1"></i>All ({{ $notifications->total() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="unread-tab" data-bs-toggle="pill" data-bs-target="#unread" type="button" role="tab">
                        <i data-lucide="bell" class="me-1"></i>Unread ({{ $unreadCount }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="read-tab" data-bs-toggle="pill" data-bs-target="#read" type="button" role="tab">
                        <i data-lucide="check" class="me-1"></i>Read ({{ $notifications->total() - $unreadCount }})
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="notificationTabContent">
                {{-- All Notifications --}}
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    @if ($notifications->count() > 0)
                        <div class="notification-list">
                            @foreach ($notifications as $notification)
                                @include('dashboard.pages.notifications.partials.notification-item', ['notification' => $notification])
                            @endforeach
                        </div>
                    @else
                        @include('dashboard.pages.notifications.partials.empty-state')
                    @endif
                </div>

                {{-- Unread Notifications --}}
                <div class="tab-pane fade" id="unread" role="tabpanel">
                    @php
                        $unreadNotifications = $notifications->getCollection()->where('is_read', false);
                    @endphp
                    @if ($unreadNotifications->count() > 0)
                        <div class="notification-list">
                            @foreach ($unreadNotifications as $notification)
                                @include('dashboard.pages.notifications.partials.notification-item', ['notification' => $notification])
                            @endforeach
                        </div>
                    @else
                        @include('dashboard.pages.notifications.partials.empty-state', ['message' => 'No unread notifications'])
                    @endif
                </div>

                {{-- Read Notifications --}}
                <div class="tab-pane fade" id="read" role="tabpanel">
                    @php
                        $readNotifications = $notifications->getCollection()->where('is_read', true);
                    @endphp
                    @if ($readNotifications->count() > 0)
                        <div class="notification-list">
                            @foreach ($readNotifications as $notification)
                                @include('dashboard.pages.notifications.partials.notification-item', ['notification' => $notification])
                            @endforeach
                        </div>
                    @else
                        @include('dashboard.pages.notifications.partials.empty-state', ['message' => 'No read notifications'])
                    @endif
                </div>
            </div>
        </div>

        @if($notifications->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
                    </div>
                    <div>
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('styles')
    <style>
        .notification-list {
            max-height: 600px;
            overflow-y: auto;
        }

        .notification-item {
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background-color: #f8f9fa !important;
        }

        .notification-item.unread {
            border-left: 4px solid #6366f1;
        }
    </style>
    @endpush
@endsection
