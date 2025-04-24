<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - @yield('title')</title>

    <!-- Preload critical assets -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" as="style">

    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="{{ asset('modules/admin/assets/css/tables.css') }}" rel="stylesheet">

    <!-- Defer non-critical CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" media="print" onload="this.media='all'">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.0/dist/sweetalert2.min.css" rel="stylesheet" media="print" onload="this.media='all'">

    <style>
        /* Critical CSS */
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #F7F8FA;
        }
        .sidebar {
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            z-index: 100;
            padding: 0;
        }
        .sidebar .nav-link {
            color: #4A5568;
            padding: .8rem 1.5rem;
            font-weight: 500;
        }
        .sidebar .nav-link.active {
            background-color: #EBF8FF;
            color: #3182CE;
        }
        .sidebar .nav-link i {
            font-size: 1.1rem;
        }
        main {
            margin-right: 16.666667%;
        }

        /* Notification Styles */
        .notifications-bar {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .notifications-dropdown {
            min-width: 320px;
            max-width: 400px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .notification-item {
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #e8f4ff;
        }

        .notification-item .notification-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
        }

        .notification-item .notification-icon.new {
            background-color: #cfe2ff;
            color: #0d6efd;
        }

        .notification-item .notification-icon.cancelled {
            background-color: #f8d7da;
            color: #dc3545;
        }

        .notification-item .notification-icon.completed {
            background-color: #d1e7dd;
            color: #198754;
        }

        .notifications-count {
            font-size: 0.75rem;
            padding: 0.25em 0.6em;
            transform: translate(25%, -25%);
        }

        .mark-all-read:hover {
            background-color: #e9ecef;
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-4">
                    <div class="text-center mb-4">
                        <h5 style="color: #2D3748; font-weight: 600;">نظام حجز العيادات</h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 ms-2"></i>
                                لوحة التحكم
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people ms-2"></i>
                                المستخدمين
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"
                                href="{{ route('doctors.index') }}">
                                <i class="bi bi-person-badge ms-2"></i>
                                الأطباء
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.patients.*') ? 'active' : '' }}"
                                href="{{ route('admin.patients.index') }}">
                                <i class="bi bi-people ms-2"></i>
                                المرضى
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}"
                                href="{{ route('admin.appointments.index') }}">
                                <i class="bi bi-calendar-check ms-2"></i>
                                المواعيد
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.specialties.*') ? 'active' : '' }}"
                                href="{{ route('admin.specialties.index') }}">
                                <i class="bi bi-list-check ms-2"></i>
                                التخصصات
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Notifications Bar -->
                <!-- <div class="notifications-bar py-2 px-4 bg-white border-bottom">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-link position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notifications-count">
                                    0
                                </span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end notifications-dropdown p-0" aria-labelledby="notificationsDropdown" style="width: 300px;">
                                <div class="p-3 border-bottom">
                                    <h6 class="mb-0">الإشعارات</h6>
                                </div>
                                <div class="notifications-list" style="max-height: 300px; overflow-y: auto;">
                                </div>
                                <div class="p-2 border-top text-center">
                                    <button class="btn btn-sm btn-light w-100 mark-all-read">
                                        تعليم الكل كمقروء
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                @hasSection('title')
                <div class="page-header position-relative my-4">
                    <div class="d-flex justify-content-between align-items-center bg-white rounded-3 shadow-sm p-4">
                        <div class="d-flex align-items-center">
                            <h1 class="h3 mb-0 text-primary fw-bold">@yield('title')</h1>
                        </div>
                        <div class="actions">
                            @yield('actions')
                        </div>
                    </div>
                </div>
                <style>
                .page-header {
                    transition: all 0.3s ease;
                }
                .page-header:hover {
                    transform: translateY(-2px);
                }
                .page-header h1 {
                    position: relative;
                    padding-right: 15px;
                }
                /* .page-header h1:before {
                    content: '';
                    position: absolute;
                    right: 0;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 4px;
                    height: 70%;
                    background: linear-gradient(to bottom, #3182CE, #63B3ED);
                    border-radius: 2px;
                } */
                </style>
                @endif


                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="p-4 ">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Load scripts at the end -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.0/dist/sweetalert2.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // تحديث عدد الإشعارات كل دقيقة
        function updateNotificationsCount() {
            fetch('/admin/notifications/count')
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.notifications-count').textContent = data.count;
                });
        }

        // تحميل الإشعارات عند فتح القائمة
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        notificationsDropdown.addEventListener('show.bs.dropdown', function () {
            fetch('/admin/notifications')
                .then(response => response.json())
                .then(data => {
                    const notificationsList = document.querySelector('.notifications-list');
                    notificationsList.innerHTML = data.notifications.map(notification => `
                        <div class="notification-item p-3 border-bottom ${notification.read_at ? '' : 'bg-light'}">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi ${getNotificationIcon(notification.type)} fs-4"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1">${notification.data.message}</p>
                                    <small class="text-muted">${formatDate(notification.created_at)}</small>
                                </div>
                            </div>
                        </div>
                    `).join('') || '<div class="p-3 text-center text-muted">لا توجد إشعارات</div>';
                });
        });

        // تعليم كل الإشعارات كمقروءة
        document.querySelector('.mark-all-read').addEventListener('click', function() {
            fetch('/admin/notifications/mark-all-read', { method: 'POST' })
                .then(() => {
                    updateNotificationsCount();
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.remove('bg-light');
                    });
                });
        });

        // Helper functions
        function getNotificationIcon(type) {
            const icons = {
                'App\\Notifications\\NewAppointment': 'bi-calendar-plus',
                'App\\Notifications\\AppointmentCancelled': 'bi-calendar-x',
                'App\\Notifications\\AppointmentCompleted': 'bi-calendar-check',
                'default': 'bi-bell'
            };
            return icons[type] || icons.default;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('ar-EG');
        }

        // Initial update and set interval
        updateNotificationsCount();
        setInterval(updateNotificationsCount, 60000);
    });
    </script>
    @stack('scripts')
</body>

</html>
