<div class="notification-item {{ !$notification->is_read ? 'unread' : '' }} p-3 border-bottom d-flex align-items-start gap-3">
    {{-- Icon based on type --}}
    <div class="notification-icon mt-1">
        @php
            $iconColor = 'text-primary';
            $iconName = 'bell';

            if (str_contains(strtolower($notification->title ?? ''), 'booking')) {
                $iconName = 'calendar';
                $iconColor = 'text-success';
            } elseif (str_contains(strtolower($notification->title ?? ''), 'payment')) {
                $iconName = 'dollar-sign';
                $iconColor = 'text-warning';
            } elseif (str_contains(strtolower($notification->title ?? ''), 'message')) {
                $iconName = 'message-circle';
                $iconColor = 'text-info';
            } elseif (str_contains(strtolower($notification->title ?? ''), 'review')) {
                $iconName = 'star';
                $iconColor = 'text-warning';
            }
        @endphp
        <div class="rounded-circle bg-light p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
            <i data-lucide="{{ $iconName }}" class="{{ $iconColor }}"></i>
        </div>
    </div>

    {{-- Content --}}
    <div class="flex-grow-1">
        <div class="d-flex align-items-center mb-1">
            <h6 class="mb-0 fw-semibold">{{ $notification->title }}</h6>
            @if(!$notification->is_read)
                <span class="badge bg-primary ms-2" style="font-size: 10px;">NEW</span>
            @endif
        </div>
        <p class="mb-2 text-muted small">{{ $notification->message }}</p>
        <div class="d-flex align-items-center gap-3">
            <small class="text-muted">
                <i data-lucide="clock" style="width: 14px; height: 14px;"></i>
                {{ $notification->created_at->diffForHumans() }}
            </small>
            @if($notification->link)
                <a href="{{ $notification->link }}" class="small text-primary text-decoration-none">
                    <i data-lucide="external-link" style="width: 14px; height: 14px;"></i> View Details
                </a>
            @endif
        </div>
    </div>

    {{-- Actions --}}
    <div class="notification-actions d-flex gap-1">
        @if(!$notification->is_read)
            <form action="{{ route('notifications.read', $notification) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-sm btn-light" title="Mark as read">
                    <i data-lucide="check" style="width: 16px; height: 16px;"></i>
                </button>
            </form>
        @endif
        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-light text-danger" title="Delete" onclick="return confirm('Delete this notification?')">
                <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
            </button>
        </form>
    </div>
</div>
