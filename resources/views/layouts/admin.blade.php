<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - @yield('title')</title>

    <!-- Preload critical assets -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" as="style">

    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/global.css'])

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #2563eb;
            --primary-light: #3b82f6;
            --primary-dark: #1d4ed8;
            --primary-bg-subtle: #eff6ff;

            --success-color: #16a34a;
            --success-light: #22c55e;
            --success-bg-subtle: #f0fdf4;

            --warning-color: #d97706;
            --warning-light: #f59e0b;
            --warning-bg-subtle: #fffbeb;

            --danger-color: #dc2626;
            --danger-light: #ef4444;
            --danger-bg-subtle: #fef2f2;

            --info-color: #0891b2;
            --info-light: #06b6d4;
            --info-bg-subtle: #ecfeff;

            --secondary-color: #475569;
            --background-color: #f8fafc;
            --border-color: #e2e8f0;

            --font-family: 'Tajawal', sans-serif;
            --sidebar-width: 280px;
            --header-height: 70px;
            --transition-speed: 0.3s;

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);

            --border-radius-sm: 0.375rem;
            --border-radius: 0.5rem;
            --border-radius-lg: 0.75rem;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--background-color);
            overflow-x: hidden;
            color: #1e293b;
            line-height: 1.6;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all var(--transition-speed) ease;
            z-index: 1000;
        }

        .sidebar-brand {
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .sidebar-brand img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            transition: all var(--transition-speed) ease;
        }

        .sidebar-user {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--primary-light);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 600;
            margin-left: 1rem;
        }

        .sidebar .nav-link {
            padding: 0.8rem 1.5rem;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            border-radius: 8px;
            margin: 0.2rem 1rem;
            transition: all var(--transition-speed) ease;
        }

        .sidebar .nav-link:hover {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        .sidebar .nav-link.active {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
            font-weight: 500;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
            margin-left: 1rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            margin-right:16px;
            flex-grow: 1;
            min-height: 100vh;
            transition: all var(--transition-speed) ease;
            padding: 2rem;
        }

        /* Header Styles */
        .main-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        /* Notifications Styles */
        .notifications-dropdown {
            min-width: 360px;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0;
            margin-top: 0.5rem;
        }

        .notification-item {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            transition: background var(--transition-speed) ease;
        }

        .notification-item:hover {
            background: rgba(37, 99, 235, 0.05);
        }

        .notification-item.unread {
            background: rgba(37, 99, 235, 0.05);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1rem;
        }

        .notification-icon.primary {
            background: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        .notification-icon.success {
            background: rgba(34, 197, 94, 0.1);
            color: var(--success-color);
        }

        .notification-icon.danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow);
            transition: all var(--transition-speed) ease;
            background: #ffffff;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        .card-header h5, .card-header .h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: var(--border-color);
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 0.2rem var(--primary-bg-subtle);
        }

        .input-group-text {
            border-color: var(--border-color);
            background-color: var(--background-color);
        }

        /* Button Styles */
        .btn {
            border-radius: 8px;
            padding: 0.6rem 1.25rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all var(--transition-speed) ease;
        }

        .btn i {
            font-size: 1.1em;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-success {
            background: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-success:hover {
            background: var(--success-light);
            border-color: var(--success-light);
            transform: translateY(-1px);
        }

        /* Table Styles */
        .table {
            --bs-table-hover-bg: var(--primary-bg-subtle);
            --bs-table-hover-color: inherit;
            border-radius: 12px;
            overflow: hidden;
            margin: 0;
        }

        .table thead th {
            background: var(--background-color);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem;
            font-weight: 600;
            color: var(--primary-color);
            white-space: nowrap;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        /* Badge Styles */
        .badge {
            padding: 0.5em 0.8em;
            font-weight: 500;
            border-radius: 6px;
        }

        .badge.bg-primary {
            background-color: var(--primary-bg-subtle) !important;
            color: var(--primary-color);
        }

        .badge.bg-success {
            background-color: var(--success-bg-subtle) !important;
            color: var(--success-color);
        }

        .badge.bg-danger {
            background-color: var(--danger-bg-subtle) !important;
            color: var(--danger-color);
        }

        .badge.bg-warning {
            background-color: var(--warning-bg-subtle) !important;
            color: var(--warning-color);
        }

        .badge.bg-info {
            background-color: var(--info-bg-subtle) !important;
            color: var(--info-color);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: var(--secondary-color);
            transition: all var(--transition-speed) ease;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .btn-action.btn-view:hover {
            background: var(--info-bg-subtle);
            color: var(--info-color);
        }

        .btn-action.btn-edit:hover {
            background: var(--primary-bg-subtle);
            color: var(--primary-color);
        }

        .btn-action.btn-delete:hover {
            background: var(--danger-bg-subtle);
            color: var(--danger-color);
        }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.8rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-badge.active {
            background: var(--success-bg-subtle);
            color: var(--success-color);
        }

        .status-badge.inactive {
            background: var(--danger-bg-subtle);
            color: var(--danger-color);
        }

        .status-badge i {
            font-size: 0.8em;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(var(--sidebar-width));
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-right: 0;
            }
        }

        /* Animation Classes */
        .fade-enter {
            opacity: 0;
        }

        .fade-enter-active {
            opacity: 1;
            transition: opacity var(--transition-speed) ease;
        }

        .fade-exit {
            opacity: 1;
        }

        .fade-exit-active {
            opacity: 0;
            transition: opacity var(--transition-speed  ) ease;
        }

        .nav-section-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-link .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
            margin-left: 1rem;
            opacity: 0.8;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                right: -100%;
                height: 100vh;
                transition: right var(--transition-speed) ease;
                z-index: 1050;
            }

            .sidebar.show {
                right: 0;
            }

            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                visibility: hidden;
                transition: all var(--transition-speed) ease;
                z-index: 1040;
            }

            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }

            .main-content {
                margin-right: 0;
                padding: 1rem;
            }

            .navbar-toggler {
                display: block;
            }

            .card {
                margin-bottom: 1rem;
            }

            .table-responsive {
                margin: 0 -1rem;
                padding: 0 1rem;
                width: calc(100% + 2rem);
            }
        }

        /* Enhanced Sidebar */
        .sidebar-brand {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            background: var(--primary-bg-subtle);
        }

        .nav-section {
            margin: 1.5rem 0;
        }

        .nav-section:first-child {
            margin-top: 0;
        }

        .nav-section-title {
            color: var(--secondary-color);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0 1.5rem;
            margin-bottom: 0.5rem;
        }

        .sidebar .nav-link {
            padding: 0.7rem 1.5rem;
            color: var(--secondary-color);
            border-radius: 8px;
            margin: 0.2rem 1rem;
            transition: all var(--transition-speed) ease;
            position: relative;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link i {
            width: 1.5rem;
            height: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 0.75rem;
            font-size: 1.1rem;
            transition: all var(--transition-speed) ease;
        }

        .sidebar .nav-link:hover i {
            transform: translateX(-3px);
        }

        .sidebar .nav-link.active {
            background: var(--primary-bg-subtle);
            color: var(--primary-color);
            font-weight: 500;
        }

        .sidebar .nav-link .badge {
            padding: 0.35em 0.65em;
            font-size: 0.75rem;
            font-weight: 500;
        }

        /* Loading States */
        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: inherit;
            backdrop-filter: blur(2px);
        }

        .loading-spinner {
            width: 40px;
            height: 40px;
            border: 3px solid var(--border-color);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spinner 0.6s linear infinite;
        }

        .loading-skeleton {
            background: linear-gradient(90deg,
                var(--border-color) 25%,
                var(--background-color) 50%,
                var(--border-color) 75%);
            background-size: 200% 100%;
            animation: skeleton 1.5s infinite;
            border-radius: 4px;
            height: 1rem;
            opacity: 0.5;
        }

        /* Data Visualization Cards */
        .stat-card {
            position: relative;
            overflow: hidden;
            border: none;
            background: white;
            transition: transform var(--transition-speed) ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-color);
            border-radius: 4px 4px 0 0;
        }

        .stat-card.success::before {
            background: var(--success-color);
        }

        .stat-card.warning::before {
            background: var(--warning-color);
        }

        .stat-card.danger::before {
            background: var(--danger-color);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            transition: all var(--transition-speed) ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }

        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--primary-color);
        }

        .stat-card .stat-label {
            color: var(--secondary-color);
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .stat-card .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .stat-card .stat-change.positive {
            color: var(--success-color);
        }

        .stat-card .stat-change.negative {
            color: var(--danger-color);
        }

        /* Chart Containers */
        .chart-container {
            position: relative;
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            height: 100%;
            min-height: 400px;
            display: flex;
            flex-direction: column;
        }

        .chart-container .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-container .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        .chart-container .chart-body {
            flex-grow: 1;
            position: relative;
        }

        /* Data Tables */
        .data-table th {
            font-weight: 600;
            color: var(--primary-color);
            background: var(--primary-bg-subtle);
            border-bottom-width: 1px;
        }

        .data-table td {
            vertical-align: middle;
        }

        .data-table tr:hover {
            background-color: var(--primary-bg-subtle);
        }

        /* Animation Keyframes */
        @keyframes spinner {
            to { transform: rotate(360deg); }
        }

        @keyframes skeleton {
            from { background-position: 200% 0; }
            to { background-position: -200% 0; }
        }

        /* Empty States */
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
            color: var(--secondary-color);
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state-text {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .empty-state-subtext {
            font-size: 0.875rem;
            opacity: 0.75;
        }

        /* Form Validation States */
        .form-control.is-invalid {
            border-color: var(--danger-color);
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .form-control.is-valid {
            border-color: var(--success-color);
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: var(--danger-color);
        }

        .was-validated .form-control:invalid ~ .invalid-feedback,
        .form-control.is-invalid ~ .invalid-feedback {
            display: block;
        }
    </style>
</head>

<body>
    <!-- Add mobile menu toggle button -->
    <button class="navbar-toggler d-md-none position-fixed start-0 top-0 m-3 z-3 bg-white rounded-circle shadow-sm border-0 p-2" type="button">
        <i class="bi bi-list fs-4"></i>
    </button>

    <div class="wrapper d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand py-3">
                <img src="{{ asset('/favicon.ico') }}" alt="Logo" class="img-fluid" style="width:120px;height: 120px;" />
            </div>

            <div class="sidebar-user">
                <div class="d-flex align-items-center">
                    <div class="user-avatar">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div>
                        <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                </div>
            </div>

            <ul class="nav flex-column">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}"
                        href="{{ route('dashboard.index') }}">
                        <i class="bi bi-speedometer2"></i>
                        لوحة التحكم
                    </a>
                </li>

                <!-- Users Management -->
                <li class="nav-section mt-3">
                    <h6 class="nav-section-title text-muted px-4 mb-2">إدارة المستخدمين</h6>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <i class="bi bi-people"></i>
                            المستخدمين
                            {{-- <span class="badge bg-primary rounded-pill ms-auto">{{ \App\Models\User::count() }}</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}"
                            href="{{ route('doctors.index') }}">
                            <i class="bi bi-person-badge"></i>
                            الأطباء
                            {{-- <span class="badge bg-info rounded-pill ms-auto">{{ \App\Models\Doctor::count() }}</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
                            href="{{ route('patients.index') }}">
                            <i class="bi bi-person"></i>
                            المرضى
                            {{-- <span class="badge bg-success rounded-pill ms-auto">{{ \App\Models\Patient::count() }}</span> --}}
                        </a>
                    </li>
                </li>

                <!-- Medical Management -->
                <li class="nav-section mt-3">
                    <h6 class="nav-section-title text-muted px-4 mb-2">إدارة العيادة</h6>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"
                            href="{{ route('appointments.index') }}">
                            <i class="bi bi-calendar-check"></i>
                            المواعيد
                            {{-- <span class="badge bg-warning rounded-pill ms-auto">{{ \App\Models\Appointment::whereDate('scheduled_at', '>=', now())->count() }}</span> --}}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('specialties.*') ? 'active' : '' }}"
                            href="{{ route('specialties.index') }}">
                            <i class="bi bi-list-check"></i>
                            التخصصات
                            {{-- <span class="badge bg-secondary rounded-pill ms-auto">{{ \App\Models\Category::count() }}</span> --}}
                        </a>
                    </li>
                </li>

                <!-- Quick Actions -->
                <li class="nav-section mt-3">
                    <h6 class="nav-section-title text-muted px-4 mb-2">اختصارات سريعة</h6>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('appointments.create') }}">
                            <i class="bi bi-plus-circle"></i>
                            موعد جديد
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('doctors.create') }}">
                            <i class="bi bi-person-plus"></i>
                            إضافة طبيب
                        </a>
                    </li>
                </li>

                <!-- System -->
                <li class="nav-section mt-3">
                    <h6 class="nav-section-title text-muted px-4 mb-2">النظام</h6>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}" target="_blank">
                            <i class="bi bi-globe"></i>
                            زيارة الموقع
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            تسجيل الخروج
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </li>
            </ul>
        </nav>

        <!-- Add overlay for mobile -->
        <div class="sidebar-overlay d-md-none"></div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="main-header mb-4">
                <div class="ms-auto">
                    <div class="dropdown">
                        <button class="btn btn-link position-relative" type="button" id="notificationsDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notifications-count">
                                0
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end notifications-dropdown">
                            <div class="p-3 border-bottom">
                                <h6 class="mb-0">الإشعارات</h6>
                            </div>
                            <div class="notifications-list" style="max-height: 400px; overflow-y: auto;">
                                <!-- Notifications will be inserted here -->
                            </div>
                            <div class="p-3 border-top text-center">
                                <button class="btn btn-light btn-sm w-100 mark-all-read">
                                    <i class="bi bi-check2-all me-2"></i>
                                    تعليم الكل كمقروء
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Header -->
            @hasSection('title')
            <div class="page-header mb-4">
                <div class="card">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h1 class="h3 mb-0 text-primary fw-bold">@yield('title')</h1>
                        <div class="actions">
                            @yield('actions')
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Alerts -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Main Content Area -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5'
        });

        // Initialize tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Mobile sidebar toggle
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // Notifications functionality
        const notificationsCount = document.querySelector('.notifications-count');
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        const markAllReadButton = document.querySelector('.mark-all-read');
        const notificationsList = document.querySelector('.notifications-list');

        function updateNotificationsCount() {
            fetch('/admin/notifications/count')
                .then(response => response.json())
                .then(data => {
                    notificationsCount.textContent = data.count;
                    if (data.count > 0) {
                        notificationsCount.classList.remove('d-none');
                    } else {
                        notificationsCount.classList.add('d-none');
                    }
                });
        }

        function getNotificationIcon(type) {
            const icons = {
                'App\\Notifications\\NewAppointment': {
                    icon: 'bi-calendar-plus',
                    class: 'primary'
                },
                'App\\Notifications\\AppointmentCancelled': {
                    icon: 'bi-calendar-x',
                    class: 'danger'
                },
                'App\\Notifications\\AppointmentCompleted': {
                    icon: 'bi-calendar-check',
                    class: 'success'
                }
            };
            return icons[type] || { icon: 'bi-bell', class: 'primary' };
        }

        function formatTimeAgo(date) {
            const now = new Date();
            const diff = now - new Date(date);
            const minutes = Math.floor(diff / 60000);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);

            if (days > 0) return `منذ ${days} يوم`;
            if (hours > 0) return `منذ ${hours} ساعة`;
            if (minutes > 0) return `منذ ${minutes} دقيقة`;
            return 'الآن';
        }

        if (notificationsDropdown) {
            notificationsDropdown.addEventListener('show.bs.dropdown', function () {
                fetch('/admin/notifications')
                    .then(response => response.json())
                    .then(data => {
                        if (data.notifications.length === 0) {
                            notificationsList.innerHTML = `
                                <div class="text-center p-4 text-muted">
                                    <i class="bi bi-bell-slash fs-2 mb-2"></i>
                                    <p class="mb-0">لا توجد إشعارات</p>
                                </div>
                            `;
                            return;
                        }

                        notificationsList.innerHTML = data.notifications.map(notification => {
                            const iconData = getNotificationIcon(notification.type);
                            return `
                                <div class="notification-item ${notification.read_at ? '' : 'unread'}">
                                    <div class="d-flex align-items-center">
                                        <div class="notification-icon ${iconData.class}">
                                            <i class="bi ${iconData.icon}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <p class="mb-1">${notification.data.message}</p>
                                            <small class="text-muted">${formatTimeAgo(notification.created_at)}</small>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    });
            });
        }

        if (markAllReadButton) {
            markAllReadButton.addEventListener('click', function() {
                fetch('/admin/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(() => {
                    updateNotificationsCount();
                    document.querySelectorAll('.notification-item').forEach(item => {
                        item.classList.remove('unread');
                    });
                });
            });
        }

        // Initial notifications count update
        updateNotificationsCount();

        // Update notifications count every minute
        setInterval(updateNotificationsCount, 60000);

        // Add this to your existing DOMContentLoaded event
        const navbarToggler = document.querySelector('.navbar-toggler');
        const sidebarOverlay = document.querySelector('.sidebar-overlay');

        if (navbarToggler) {
            navbarToggler.addEventListener('click', () => {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }
    });
    </script>

    @stack('scripts')
</body>
</html>
