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
                @if($unreadCount > 0)
                    <small class="text-danger">{{ $unreadCount }} unread</small>
                @endif
            </div>
            <div class="action-btns">
                @if($unreadCount > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="ti ti-check"></i> Mark All as Read
                        </button>
                    </form>
                @endif
                @if($notifications->count() > 0)
                    <form action="{{ route('notifications.destroy-all') }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete all notifications?')">
                            <i class="ti ti-trash"></i> Clear All
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="card-body">
            @if ($notifications->count() > 0)
                <div class="notification-list">
                    @foreach ($notifications as $notification)
                        <div class="notification-item {{ !$notification->is_read ? 'unread bg-light' : '' }} p-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold">{{ $notification->title }}</h6>
                                        @if(!$notification->is_read)
                                            <span class="badge bg-danger ms-2">New</span>
                                        @endif
                                    </div>
                                    <p class="mb-1 text-muted">{{ $notification->message }}</p>
                                    <small class="text-muted">
                                        <i class="ti ti-clock"></i> {{ $notification->created_at->diffForHumans() }}
                                    </small>
                                    @if($notification->link)
                                        <div class="mt-2">
                                            <a href="{{ $notification->link }}" class="btn btn-sm btn-primary">
                                                View Details
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="ms-2">
                                    @if(!$notification->is_read)
                                        <form action="{{ route('notifications.read', $notification) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-light" title="Mark as read">
                                                <i class="ti ti-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('notifications.destroy', $notification) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
                    </div>
                    <div>
                        {{ $notifications->links() }}
                    </div>
                </div>
            @else
                <div class="alert alert-info mb-0">
                    <i class="ti ti-info-circle"></i> No notifications yet. You're all caught up!
                </div>
            @endif
        </div>
    </div>

    <style>
        .notification-list {
            max-height: 600px;
            overflow-y: auto;
        }

        .notification-item.unread {
            border-left: 4px solid #0066cc;
        }

        .notification-item {
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            background-color: #f8f9fa !important;
        }
    </style>
@endsection
