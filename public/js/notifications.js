// Notification System with Real-time Updates
(function() {
    'use strict';

    const NotificationSystem = {
        init() {
            this.bindEvents();
            this.startPolling();
        },

        bindEvents() {
            // Mark single notification as read
            document.querySelectorAll('[data-notification-read]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const notificationId = btn.dataset.notificationRead;
                    this.markAsRead(notificationId, btn);
                });
            });

            // Mark all as read
            const markAllBtn = document.getElementById('mark-all-read');
            if (markAllBtn) {
                markAllBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.markAllAsRead();
                });
            }

            // Clear all notifications
            const clearAllBtn = document.getElementById('clear-all-notifications');
            if (clearAllBtn) {
                clearAllBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (confirm('Are you sure you want to clear all notifications?')) {
                        this.clearAll();
                    }
                });
            }
        },

        async markAsRead(notificationId, button) {
            try {
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Update UI
                    const notificationItem = button.closest('.notification-item');
                    if (notificationItem) {
                        notificationItem.classList.remove('unread', 'bg-light');
                        button.remove();
                    }

                    // Update badge count
                    this.updateBadgeCount();
                    this.showToast('Notification marked as read', 'success');
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
                this.showToast('Failed to mark notification as read', 'error');
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('/notifications/mark-all-read', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    // Update UI
                    document.querySelectorAll('.notification-item.unread').forEach(item => {
                        item.classList.remove('unread', 'bg-light');
                        item.querySelector('[data-notification-read]')?.remove();
                    });

                    // Update badge count
                    this.updateBadgeCount();
                    this.showToast('All notifications marked as read', 'success');
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
                this.showToast('Failed to mark all notifications as read', 'error');
            }
        },

        async clearAll() {
            try {
                const response = await fetch('/notifications/destroy-all', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    window.location.reload();
                }
            } catch (error) {
                console.error('Error clearing notifications:', error);
                this.showToast('Failed to clear notifications', 'error');
            }
        },

        async fetchUnreadNotifications() {
            try {
                const response = await fetch('/notifications/refresh', {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        this.updateNotificationDropdown(data.notifications);
                        this.updateBadgeCount(data.count);
                    }
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        },

        updateNotificationDropdown(notifications) {
            const dropdown = document.querySelector('[data-simplebar]');
            if (!dropdown) return;

            if (notifications.length === 0) {
                dropdown.innerHTML = `
                    <div class="dropdown-item py-3 text-center text-muted">
                        <p class="mb-0 small">No new notifications</p>
                    </div>
                `;
                return;
            }

            dropdown.innerHTML = notifications.map(notification => `
                <div class="dropdown-item notification-item py-2 text-wrap bg-light" id="notification-${notification.id}">
                    <span class="d-flex align-items-center gap-2">
                        <span class="flex-shrink-0 position-relative">
                            <div class="avatar-sm rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center">
                                <i data-lucide="${notification.icon || 'bell'}" class="text-primary"></i>
                            </div>
                            <span class="position-absolute rounded-pill bg-danger notification-badge" style="width: 8px; height: 8px; bottom: 0; right: 0;"></span>
                        </span>
                        <span class="flex-grow-1">
                            <span class="fw-medium text-body d-block">${notification.title}</span>
                            <span class="text-muted small">${this.truncate(notification.message, 40)}</span><br>
                            <span class="fs-xs text-muted"><i class="ti ti-clock"></i> ${notification.created_at}</span>
                        </span>
                        <form action="/notifications/${notification.id}/read" method="POST" class="d-inline">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="submit" class="flex-shrink-0 text-muted btn btn-link p-0" title="Mark as read">
                                <i class="ti ti-check fs-md"></i>
                            </button>
                        </form>
                    </span>
                </div>
            `).join('');

            // Reinitialize Lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

            // Rebind events
            this.bindEvents();
        },

        updateBadgeCount(count = null) {
            const badge = document.querySelector('.topbar-badge');

            if (count === null) {
                // Fetch current count from server
                fetch('/notifications/refresh', {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.updateBadgeDisplay(data.count);
                    }
                })
                .catch(err => console.error('Error fetching notification count:', err));
            } else {
                this.updateBadgeDisplay(count);
            }
        },

        updateBadgeDisplay(count) {
            const badge = document.querySelector('.topbar-badge');

            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                } else {
                    // Create badge if it doesn't exist
                    const bellIcon = document.querySelector('[data-lucide="bell"]');
                    if (bellIcon) {
                        const newBadge = document.createElement('span');
                        newBadge.className = 'badge text-bg-danger badge-circle topbar-badge';
                        newBadge.textContent = count;
                        bellIcon.parentElement.appendChild(newBadge);
                    }
                }
            } else {
                badge?.remove();
            }
        },

        startPolling() {
            // Poll for new notifications every 30 seconds
            setInterval(() => {
                this.fetchUnreadNotifications();
            }, 30000);
        },

        truncate(str, length) {
            return str.length > length ? str.substring(0, length) + '...' : str;
        },

        timeAgo(date) {
            const seconds = Math.floor((new Date() - new Date(date)) / 1000);

            let interval = seconds / 31536000;
            if (interval > 1) return Math.floor(interval) + ' years ago';

            interval = seconds / 2592000;
            if (interval > 1) return Math.floor(interval) + ' months ago';

            interval = seconds / 86400;
            if (interval > 1) return Math.floor(interval) + ' days ago';

            interval = seconds / 3600;
            if (interval > 1) return Math.floor(interval) + ' hours ago';

            interval = seconds / 60;
            if (interval > 1) return Math.floor(interval) + ' minutes ago';

            return Math.floor(seconds) + ' seconds ago';
        },

        showToast(message, type = 'info') {
            // Using simple alert for now, can be replaced with a toast library
            if (type === 'error') {
                console.error(message);
            }
        }
    };

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => NotificationSystem.init());
    } else {
        NotificationSystem.init();
    }
})();
