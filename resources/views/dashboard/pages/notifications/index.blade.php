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
                        <i class="ti ti-home"></i>
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
                            <i class="ti ti-check"></i> Mark All Read
                        </button>
                    </form>
                @endif
                @if($notifications->count() > 0)
                    <form action="{{ route('notifications.destroy-all') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete all notifications?')">
                            <i class="ti ti-trash"></i> Clear All
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Filter Tabs --}}
        <div class="card-header border-bottom-0 bg-light">
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
                        $unreadNotifications = $notifications->where('is_read', false);
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
                        $readNotifications = $notifications->where('is_read', true);
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
