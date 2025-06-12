document.addEventListener('DOMContentLoaded', function () {
    // Search functionality enhancements
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        // Expand search on focus
        searchInput.addEventListener('focus', () => {
            searchInput.parentElement.style.transform = 'scale(1.02)';
        });

        searchInput.addEventListener('blur', () => {
            searchInput.parentElement.style.transform = 'scale(1)';
        });

        // Debounced search
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Implement your search logic here
                console.log('Searching for:', e.target.value);
            }, 300);
        });
    }

    // Enhanced Notifications System
    const notificationSystem = {
        count: document.querySelector('.notifications-count'),
        list: document.querySelector('.notifications-list'),
        markAllReadBtn: document.querySelector('.mark-all-read'),
        dropdown: document.querySelector('.notifications-dropdown'),

        updateCount(count) {
            if (this.count) {
                this.count.textContent = count;
                this.count.classList.toggle('d-none', count === 0);
            }
        },

        formatTimeAgo(date) {
            const now = new Date();
            const diff = now - new Date(date);
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);

            if (days > 0) return `منذ ${days} يوم`;
            if (hours > 0) return `منذ ${hours} ساعة`;
            if (minutes > 0) return `منذ ${minutes} دقيقة`;
            return 'الآن';
        },

        async loadNotifications() {
            try {
                const response = await fetch('/api/notifications');

                if (response.status === 419) {
                    window.location.href = '/login';
                    return;
                }

                const data = await response.json();

                if (!data.notifications.length) {
                    this.renderEmptyState();
                    return;
                }

                this.renderNotifications(data.notifications);
                this.updateCount(data.unreadCount);
            } catch (error) {
                console.error('Error loading notifications:', error);
                this.renderError();
            }
        },

        renderNotifications(notifications) {
            if (!this.list) return;

            this.list.innerHTML = notifications.map(notification => `
                <div class="notification-item ${notification.read_at ? '' : 'unread'}"
                     data-id="${notification.id}">
                    <div class="d-flex align-items-center gap-3">
                        <div class="notification-icon ${this.getIconClass(notification.type)}">
                            <i class="bi ${this.getIconName(notification.type)}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1">${notification.data.message}</p>
                            <small class="text-muted">
                                ${this.formatTimeAgo(notification.created_at)}
                            </small>
                        </div>
                    </div>
                </div>
            `).join('');

            // Add click handlers for individual notifications
            this.list.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', () => this.markAsRead(item.dataset.id));
            });
        },

        renderEmptyState() {
            if (!this.list) return;

            this.list.innerHTML = `
                <div class="text-center p-4">
                    <i class="bi bi-bell-slash fs-2 text-muted mb-2"></i>
                    <p class="text-muted mb-0">لا توجد إشعارات</p>
                </div>
            `;
        },

        renderError() {
            if (!this.list) return;

            this.list.innerHTML = `
                <div class="text-center p-4">
                    <i class="bi bi-exclamation-circle fs-2 text-danger mb-2"></i>
                    <p class="text-muted mb-0">حدث خطأ في تحميل الإشعارات</p>
                </div>
            `;
        },

        getIconName(type) {
            const icons = {
                'App\\Notifications\\NewAppointment': 'bi-calendar-plus',
                'App\\Notifications\\AppointmentCancelled': 'bi-calendar-x',
                'App\\Notifications\\AppointmentCompleted': 'bi-calendar-check'
            };
            return icons[type] || 'bi-bell';
        },

        getIconClass(type) {
            const classes = {
                'App\\Notifications\\NewAppointment': 'bg-primary-subtle text-primary',
                'App\\Notifications\\AppointmentCancelled': 'bg-danger-subtle text-danger',
                'App\\Notifications\\AppointmentCompleted': 'bg-success-subtle text-success'
            };
            return classes[type] || 'bg-primary-subtle text-primary';
        },

        async markAsRead(id) {
            try {
                const response = await fetch(`/api/notifications/${id}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.status === 419) {
                    window.location.href = '/login';
                    return;
                }

                await this.loadNotifications();
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('/api/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.status === 419) {
                    window.location.href = '/login';
                    return;
                }

                await this.loadNotifications();
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        },

        init() {
            // Load notifications when dropdown is opened
            if (this.dropdown) {
                this.dropdown.addEventListener('show.bs.dropdown', () => {
                    this.loadNotifications();
                });
            }

            // Mark all as read button handler
            if (this.markAllReadBtn) {
                this.markAllReadBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.markAllAsRead();
                });
            }

            // Initial load
            this.loadNotifications();

            // Set up periodic updates
            setInterval(() => this.loadNotifications(), 60000); // Update every minute
        }
    };

    // Initialize notification system
    notificationSystem.init();

    // Quick Actions dropdown animation
    const quickActions = document.querySelector('.dropdown-menu');
    if (quickActions) {
        quickActions.addEventListener('show.bs.dropdown', function () {
            const items = this.querySelectorAll('.dropdown-item');
            items.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.style.opacity = 0;
                item.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    item.style.opacity = 1;
                    item.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    }

    // Mobile search toggle animation
    const mobileSearchCollapse = document.getElementById('mobileSearch');
    if (mobileSearchCollapse) {
        mobileSearchCollapse.addEventListener('show.bs.collapse', function () {
            this.style.height = '0px';
            setTimeout(() => {
                this.style.height = this.scrollHeight + 'px';
            }, 0);
        });

        mobileSearchCollapse.addEventListener('hide.bs.collapse', function () {
            this.style.height = '0px';
        });
    }
});
