# Quick Reference: Notifications & Dashboard Filters

## 🔔 Notification System

### Create a Notification
```php
use App\Models\Notification;

Notification::create([
    'user_id' => $userId,
    'title' => 'Notification Title',
    'message' => 'Your notification message here',
    'link' => route('some.route'),  // Optional
    'is_read' => false
]);
```

### Notification Icons (Auto-assigned)
| Title Contains | Icon |
|----------------|------|
| "booking" | calendar |
| "payment" | dollar-sign |
| "message" | message-circle |
| "review" | star |
| Other | bell |

### API Endpoints
```bash
GET    /notifications                    # View all notifications
GET    /notifications/refresh            # Poll for updates (JSON)
GET    /notifications/unread             # Get unread (JSON)
PATCH  /notifications/{id}/read          # Mark as read
PATCH  /notifications/mark-all-read      # Mark all as read
DELETE /notifications/{id}               # Delete single
DELETE /notifications                    # Clear all
```

### Test Notification
```bash
# Visit (only in debug mode):
http://your-domain/test/create-notification
```

---

## 📊 Dashboard Filters

### Using Filters in URLs

**Predefined Filters:**
```bash
/dashboard?filter=today          # Today's data
/dashboard?filter=this_week      # This week
/dashboard?filter=this_month     # This month (default)
/dashboard?filter=this_year      # This year
```

**Custom Date Range:**
```bash
/dashboard?filter=custom&start_date=2024-01-01&end_date=2024-01-31
```

### Backend Usage
```php
// In your controller
$dateRange = $this->getDateRange($filter, $startDate, $endDate);

// Use in queries
$bookings = Booking::query()
    ->when($dateRange, function($query) use ($dateRange) {
        $query->whereBetween('created_at', [
            $dateRange['start'], 
            $dateRange['end']
        ]);
    })
    ->get();
```

### Frontend Usage
```blade
{{-- Filter dropdown --}}
<a href="{{ route('dashboard', ['filter' => 'today']) }}">Today</a>
<a href="{{ route('dashboard', ['filter' => 'this_week']) }}">This Week</a>

{{-- Custom date form --}}
<form action="{{ route('dashboard') }}" method="GET">
    <input type="hidden" name="filter" value="custom">
    <input type="date" name="start_date" required>
    <input type="date" name="end_date" required>
    <button type="submit">Apply</button>
</form>

{{-- Show active filter --}}
@if(request('filter'))
    <span class="badge">{{ ucwords(str_replace('_', ' ', request('filter'))) }}</span>
@endif
```

---

## 🎯 Common Tasks

### Mark Notification as Read (AJAX)
```javascript
fetch(`/notifications/${notificationId}/read`, {
    method: 'PATCH',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
    }
}).then(res => res.json());
```

### Get Unread Count
```javascript
fetch('/notifications/refresh')
    .then(res => res.json())
    .then(data => {
        console.log('Unread:', data.count);
    });
```

### Filter Dashboard Statistics
```php
// Get stats for last 7 days
$dateRange = [
    'start' => now()->subDays(7)->startOfDay(),
    'end' => now()->endOfDay()
];

$stats = $this->getStats(Auth::user()->role->role_key, $dateRange);
```

---

## ⚙️ Configuration

### JavaScript Polling Interval
File: `public/js/notifications.js`
```javascript
startPolling() {
    setInterval(() => {
        this.fetchUnreadNotifications();
    }, 30000);  // Change 30000 to desired milliseconds
}
```

### Pagination Limit
File: `app/Http/Controllers/NotificationController.php`
```php
$notifications = Notification::where('user_id', Auth::user()->id)
    ->paginate(20);  // Change 20 to desired limit
```

### Date Filter Default
File: `app/Http/Controllers/DashboardController.php`
```php
$filter = $request->get('filter', 'this_month');  // Change default here
```

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Notifications not updating | Check `/notifications/refresh` route is accessible |
| Badge not showing | Verify CSRF token in meta tag |
| Icons not displaying | Ensure Lucide JS is loaded and `lucide.createIcons()` is called |
| Filter not working | Check date format is `YYYY-MM-DD` |
| Empty notifications | Create test notification via `/test/create-notification` |

---

## 📝 Files Modified

```
✅ app/Http/Controllers/NotificationController.php
✅ app/Http/Controllers/DashboardController.php
✅ public/js/notifications.js
✅ resources/views/dashboard/pages/notifications/index.blade.php
✅ resources/views/dashboard/pages/notifications/partials/notification-item.blade.php (NEW)
✅ resources/views/dashboard/pages/notifications/partials/empty-state.blade.php (NEW)
✅ resources/views/dashboard/pages/index_enhanced.blade.php
✅ routes/notifications.php
✅ routes/test.php (NEW)
✅ routes/web.php
```

---

## 🚀 Quick Start

1. **Test Notifications:**
   - Visit `/test/create-notification` (debug mode only)
   - Check topbar bell icon for badge
   - Click bell to see dropdown
   - Visit `/notifications` for full list

2. **Test Dashboard Filters:**
   - Go to `/dashboard`
   - Click filter dropdown in header
   - Select "Today" or "This Week"
   - Try custom date range
   - Verify statistics update

3. **Test Real-time Updates:**
   - Open dashboard in one tab
   - Create notification via test route in another tab
   - Wait 30 seconds or refresh
   - Badge should update automatically

---

## 🎨 Customization Tips

### Change Notification Icon Logic
```php
// In NotificationController.php
private function getNotificationIcon($title)
{
    $title = strtolower($title ?? '');
    
    // Add your custom logic
    if (str_contains($title, 'urgent')) {
        return 'alert-circle';
    }
    // ... existing logic
}
```

### Add New Filter Option
```blade
{{-- In index_enhanced.blade.php --}}
<li>
    <a class="dropdown-item" href="{{ route('dashboard', ['filter' => 'yesterday']) }}">
        Yesterday
    </a>
</li>
```

```php
// In DashboardController.php getDateRange()
case 'yesterday':
    return [
        'start' => now()->subDay()->startOfDay(),
        'end' => now()->subDay()->endOfDay()
    ];
```

### Change Polling Frequency
```javascript
// In notifications.js
setInterval(() => {
    this.fetchUnreadNotifications();
}, 60000);  // Poll every 60 seconds instead of 30
```

---

**Last Updated:** 2024
**Status:** ✅ Production Ready
