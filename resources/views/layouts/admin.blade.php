<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة التحكم - @yield('title')</title>

    <!-- Preload critical assets -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap"
        as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" as="style">

    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/global.css'])

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

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
            --sidebar-width: 240px;
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

        /* Enhanced Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            min-width: var(--sidebar-width);
            background: #fff;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            transition: width var(--transition-speed) ease;
            z-index: 1000;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-brand {
            padding: 1.5rem;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            background: white;
            justify-content: center;
        }

        .sidebar-brand img {
            height: 40px;
            width: auto;
            transition: all var(--transition-speed) ease;
        }

        .sidebar.collapsed .sidebar-brand img {
            transform: scale(0.8);
        }

        .sidebar-user {
            padding: 1.25rem;
            border-bottom: 1px solid var(--border-color);
            background: linear-gradient(to left, var(--primary-bg-subtle), white);
        }

        .user-info {
            margin-right: 1rem;
            transition: opacity var(--transition-speed) ease;
        }

        .sidebar.collapsed .user-info {
            opacity: 0;
            width: 0;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            font-weight: 600;
            flex-shrink: 0;
            transition: all var(--transition-speed) ease;
        }

        .sidebar.collapsed .user-avatar {
            margin: 0 auto;
        }

        /* Navigation Section Styles */
        .nav-section {
            margin: 1.5rem 0;
        }

        .nav-section-title {
            padding: 0.5rem 1.5rem;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all var(--transition-speed) ease;
        }

        .sidebar.collapsed .nav-section-title {
            opacity: 0;
        }

        /* Enhanced Nav Links */
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: var(--secondary-color);
            border-radius: 8px;
            margin: 0.2rem 1rem;
            position: relative;
            transition: all var(--transition-speed) ease;
            white-space: nowrap;
        }

        .nav-link i {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin-left: 1rem;
            opacity: 0.8;
            transition: all var(--transition-speed) ease;
        }

        .nav-link span {
            transition: opacity var(--transition-speed) ease;
        }

        .sidebar.collapsed .nav-link span {
            opacity: 0;
            width: 0;
        }

        .nav-link:hover {
            color: var(--primary-color);
            background: var(--primary-bg-subtle);
        }

        .nav-link:hover i {
            transform: translateX(-3px);
            opacity: 1;
        }

        .nav-link.active {
            color: var(--primary-color);
            background: var(--primary-bg-subtle);
            font-weight: 500;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            right: -1rem;
            top: 50%;
            transform: translateY(-50%);
            height: 60%;
            width: 3px;
            background: var(--primary-color);
            border-radius: 4px;
        }

        .nav-link .badge {
            margin-right: auto;
            transition: all var(--transition-speed) ease;
        }

        .sidebar.collapsed .nav-link .badge {
            opacity: 0;
            transform: scale(0);
        }

        /* Section Collapse Button */
        .section-collapse {
            padding: 0.5rem;
            margin: 0 1rem;
            border: none;
            background: transparent;
            color: var(--secondary-color);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: calc(100% - 2rem);
            border-radius: var(--border-radius);
            transition: all var(--transition-speed) ease;
        }

        .section-collapse:hover {
            background: var(--primary-bg-subtle);
            color: var(--primary-color);
        }

        .section-collapse i {
            transition: transform var(--transition-speed) ease;
        }

        .section-collapse.collapsed i {
            transform: rotate(-90deg);
        }

        /* Enhanced Tooltips for Collapsed State */
        .sidebar.collapsed .nav-link {
            position: relative;
            width: 40px;
            margin: 0.2rem auto;
            padding: 0.75rem 0;
            justify-content: center;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0;
        }

        /* Main Content Styles */
        .page {
            padding: 1rem;
        }

        .main-content {
            flex-grow: 1;
            min-height: 100vh;
            transition: all var(--transition-speed) ease;

        }

        /* Enhanced Main Header Styles */
        .main-header {
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-speed) ease;
        }

        .main-header .btn-icon {
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius);
            transition: all var(--transition-speed) ease;
            position: relative;
            border: 1px solid var(--border-color);
        }

        .main-header .btn-icon:hover {
            background: var(--primary-bg-subtle);
            color: var(--primary-color);
            transform: translateY(-1px);
        }

        .main-header .btn-icon i {
            font-size: 1.25rem;
        }

        .main-header .user-avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 600;
        }

        .main-header .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: var(--border-radius);
            margin-top: 0.5rem;
        }

        .main-header .notifications-dropdown {
            width: 360px;
            padding: 0;
        }

        .main-header .notifications-dropdown .dropdown-header {
            background: var(--primary-bg-subtle);
            color: var(--primary-color);
            font-weight: 600;
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        @media (max-width: 768px) {
            .main-header {
                padding: 0 1rem;
            }

            .main-header .notifications-dropdown {
                width: 300px;
            }
        }

        /* Notifications Styles */
        .notifications-dropdown {
            min-width: 380px;
            border: none;
            box-shadow: var(--shadow-lg);
            border-radius: var(--border-radius);
            padding: 0;
            margin-top: 0.75rem;
        }

        .notification-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
        }

        .notification-header h6 {
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-color);
        }

        .notifications-list {
            max-height: 400px;
            overflow-y: auto;
        }

        .notification-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            transition: all var(--transition-speed) ease;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .notification-item:hover {
            background: var(--primary-bg-subtle);
        }

        .notification-item.unread {
            background: var(--primary-bg-subtle);
        }

        .notification-item.unread:hover {
            background: rgba(37, 99, 235, 0.1);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .notification-content {
            flex-grow: 1;
        }

        .notification-content p {
            margin-bottom: 0.25rem;
            color: var (--secondary-color);
            font-size: 0.925rem;
        }

        .notification-content .time {
            font-size: 0.75rem;
            color: #64748b;
        }

        .notification-footer {
            padding: 0.75rem;
            text-align: center;
            border-top: 1px solid var(--border-color);
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

        .card-header h5,
        .card-header .h5 {
            color: var(--primary-color);
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }



        /* Table Styles */

        /* Badge Styles */

        .badge.bg-danger.notifications-count {
            background-color: var(--danger-bg-subtle) !important;
            color: var(--danger-color);
        }



        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(var(--sidebar-width));
            }

            .sidebar.show {
                transform: translateX(0);
            }


        }

        /* Animation Classes */


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



        /* Animation Keyframes */
        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes skeleton {
            from {
                background-position: 200% 0;
            }

            to {
                background-position: -200% 0;
            }
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
            border-color: var (--success-color);
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

        .was-validated .form-control:invalid~.invalid-feedback,
        .form-control.is-invalid~.invalid-feedback {
            display: block;
        }

        /* Enhanced Notification Button Styles */
        .notification-btn {
            position: relative;
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: var(--primary-bg-subtle);
            color: var(--primary-color);
            border: none;
            transition: all var(--transition-speed) ease;
        }

        .notification-btn:hover {
            transform: translateY(-2px);
            background: var(--primary-color);
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 10px;
            background-color: var(--danger-color);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced Notification Dropdown Styles */
        .notifications-dropdown {
            margin-top: 0.75rem !important;
            border: none !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            border-radius: 16px !important;
            padding: 0 !important;
            min-width: 320px !important;
        }

        .notification-header {
            background: var(--primary-bg-subtle);
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            border-radius: 16px 16px 0 0;
        }

        .notification-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            transition: all var(--transition-speed) ease;
            background: white;
        }

        .notification-item:hover {
            background: var(--primary-bg-subtle);
        }

        .notification-item.unread {
            background: rgba(37, 99, 235, 0.05);
        }

        .notification-item.unread:hover {
            background: rgba(37, 99, 235, 0.1);
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            background: var (--primary-bg-subtle);
            color: var(--primary-color);
        }
    </style>
    <!-- Additional CSS -->
    @stack('styles')
</head>

<body>
    <!-- Add mobile menu toggle button -->
    <button
        class="navbar-toggler d-md-none position-fixed start-0 top-0 m-3 z-3 bg-white rounded-circle shadow-sm border-0 p-2"
        type="button">
        <i class="bi bi-list fs-4"></i>
    </button>

    <div class="wrapper d-flex">
        <!-- Enhanced Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('/favicon.ico') }}" alt="Logo" class="img-fluid" style="width:120px;height:120px" />

            </div>



            <!-- Main Navigation -->
            <div class="nav-wrapper mt-3">
                <ul class="nav flex-column">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard.*') ? 'active' : '' }}"
                            href="{{ route('dashboard.index') }}" data-bs-toggle="tooltip" data-bs-placement="left"
                            data-bs-title="لوحة التحكم">
                            <i class="bi bi-speedometer2"></i>
                            <span>لوحة التحكم</span>
                        </a>
                    </li>

                    <!-- Users Management Section -->
                    <li class="nav-section">
                        <div class="nav-section-title">إدارة المستخدمين</div>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                                    href="{{ route('users.index') }}" data-bs-toggle="tooltip" data-bs-placement="left"
                                    data-bs-title="المستخدمين">
                                    <i class="bi bi-people"></i>
                                    <span>المستخدمين</span>

                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}"
                                    href="{{ route('doctors.index') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="الأطباء">
                                    <i class="bi bi-person-badge"></i>
                                    <span>الأطباء</span>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('doctors.incomplete_profiles') ? 'active' : '' }}"
                                    href="{{ route('doctors.incomplete_profiles') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="ملفات غير مكتملة">
                                    <i class="bi bi-exclamation-triangle text-warning"></i>
                                    <span>ملفات غير مكتملة</span>
                                    <span class="badge bg-warning text-dark">جديد</span>
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
                                    href="{{ route('patients.index') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="المرضى">
                                    <i class="bi bi-person"></i>
                                    <span>المرضى</span>

                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Medical Management Section -->
                    <li class="nav-section">
                        <div class="nav-section-title">إدارة العيادة</div>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"
                                    href="{{ route('appointments.index') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="الحجوزات">
                                    <i class="bi bi-calendar-check"></i>
                                    <span>الحجوزات</span>

                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}"
                                    href="{{ route('admin.payments.index') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="المدفوعات">
                                    <i class="bi bi-cash-coin"></i>
                                    <span>المدفوعات</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('specialties.*') ? 'active' : '' }}"
                                    href="{{ route('specialties.index') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="التخصصات">
                                    <i class="bi bi-list-check"></i>
                                    <span>التخصصات</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Quick Actions Section -->
                    <li class="nav-section">
                        <div class="nav-section-title">اختصارات سريعة</div>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('appointments.create') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="حجز جديد">
                                    <i class="bi bi-plus-circle"></i>
                                    <span>حجز جديد</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('doctors.create') }}" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="إضافة طبيب">
                                    <i class="bi bi-person-plus"></i>
                                    <span>إضافة طبيب</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- System Section -->
                    <li class="nav-section">
                        <div class="nav-section-title">النظام</div>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}"
                                   href="{{ route('admin.contacts.index') }}" data-bs-toggle="tooltip"
                                   data-bs-placement="left" data-bs-title="رسائل التواصل">
                                    <i class="bi bi-envelope"></i>
                                    <span>رسائل التواصل</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/') }}" target="_blank" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-title="زيارة الموقع">
                                    <i class="bi bi-globe"></i>
                                    <span>زيارة الموقع</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="تسجيل الخروج">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>تسجيل الخروج</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Add overlay for mobile -->
        <div class="sidebar-overlay d-md-none"></div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Main Header -->
            <header class="main-header">
                <div class="d-flex align-items-center  w-100">
                    <!-- Right Side (Actions) -->
                    <div class="d-flex align-items-center  ms-auto">
                        <!-- Search Toggle (Mobile) -->
                        <button class="btn btn-icon btn-light d-lg-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#mobileSearch">
                            <i class="bi bi-search"></i>
                        </button>



                        <!-- Notifications -->
                        {{-- <div class="dropdown">
                            <button class="btn notification-btn" type="button" data-bs-toggle="dropdown"
                                data-bs-auto-close="outside" aria-label="الإشعارات">
                                <i class="bi bi-bell"></i>
                                <span class="notification-badge d-none">0</span>
                            </button>
                            <div class="dropdown-menu notifications-dropdown">
                                <div
                                    class="notifications-header d-flex align-items-center justify-content-between p-3 border-bottom">
                                    <h6 class="mb-0">الإشعارات</h6>
                                    <button class="btn btn-sm btn-light mark-all-read" title="تعليم الكل كمقروء">
                                        <i class="bi bi-check2-all"></i>
                                    </button>
                                </div>
                                <div class="notifications-list">
                                    <!-- Notifications will be inserted here -->
                                </div>
                            </div>
                        </div> --}}

                        <!-- User Profile -->
                        <div class="dropdown">
                            <a class="nav-link  d-flex align-items-center gap-2 p-0" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar ?? asset('images/user-avatar.avif') }}"
                                    class="rounded-circle" width="32" height="32" alt="User Avatar">
                                <span>{{ auth()->user()->name }}</span>
                            </a>

                        </div>
                    </div>
                </div>


            </header>

            <!-- Secondary Header (Context Navigation) -->

            <div class="page-header p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>

                        @hasSection('breadcrumbs')
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0 mt-1">
                                    @yield('breadcrumbs')
                                </ol>
                            </nav>
                        @endif
                    </div>
                    <div class="page-actions">
                        @yield('actions')
                    </div>
                </div>
            </div>


                        @if (session('success'))
                        <div class="alert-card success mb-4">
                            <div class="alert-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="alert-content">
                                <h6 class="alert-heading">تمت العملية بنجاح!</h6>
                                <p class="mb-0">{!! session('success') !!}</p>
                            </div>
                            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    @endif
                    {{-- @if (session('error'))
                        <div class="alert-card error mb-4">
                            <div class="alert-icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="alert-content">
                                <h6 class="alert-heading">حدث خطأ!</h6>
                                <p class="mb-0">{!! session('error') !!}</p>
                            </div>
                            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    @endif --}}
                    @if (session('info'))
                        <div class="alert-card info mb-4">
                            <div class="alert-icon">
                                <i class="bi bi-info-circle"></i>
                            </div>
                            <div class="alert-content">
                                <h6 class="alert-heading">معلومات!</h6>
                                <p class="mb-0">{!! session('info') !!}</p>
                            </div>
                            <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    @endif

            @if($errors->any())
                 <!-- إضافة مكون عرض الأخطاء -->
                 <x-validation-errors/>

            @endif

            <div class="content px-3 pb-3">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Initialize notifications functionality
        function getNotificationIcon(type) {
            const icons = {
                'App\\Notifications\\NewAppointmentNotification': {
                    icon: 'bi-calendar-plus',
                    class: 'primary'
                },
                'App\\Notifications\\AppointmentCancelledNotification': {
                    icon: 'bi-calendar-x',
                    class: 'danger'
                },
                'App\\Notifications\\AppointmentCompletedNotification': {
                    icon: 'bi-calendar-check',
                    class: 'success'
                },
                'App\\Notifications\\NewDoctorNotification': {
                    icon: 'bi-person-plus',
                    class: 'primary'
                },
                'App\\Notifications\\DoctorUpdatedNotification': {
                    icon: 'bi-person-gear   ',
                    class: 'info'
                },
                'App\\Notifications\\DoctorDeletedNotification': {
                    icon: 'bi-person-x',
                    class: 'danger'
                },
                'App\\Notifications\\NewPatientNotification': {
                    icon: 'bi-person-add',
                    class: 'primary'
                },
                'App\\Notifications\\PatientUpdatedNotification': {
                    icon: 'bi-person-gear   ',
                    class: 'info'
                },
                'App\\Notifications\\PatientDeletedNotification': {
                    icon: 'bi-person-x',
                    class: 'danger'
                }
            };
            return icons[type] || { icon: 'bi-bell', class: 'primary' };
        }

        function formatRelativeTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);
            const diffInMinutes = Math.floor(diffInSeconds / 60);
            const diffInHours = Math.floor(diffInMinutes / 60);
            const diffInDays = Math.floor(diffInHours / 24);
            const diffInMonths = Math.floor(diffInDays / 30);
            const diffInYears = Math.floor(diffInMonths / 12);

            if (diffInSeconds < 60) {
                return 'منذ لحظات';
            } else if (diffInMinutes < 60) {
                return `منذ ${diffInMinutes} ${diffInMinutes === 1 ? 'دقيقة' : 'دقائق'}`;
            } else if (diffInHours < 24) {
                return `منذ ${diffInHours} ${diffInHours === 1 ? 'ساعة' : 'ساعات'}`;
            } else if (diffInDays < 30) {
                return `منذ ${diffInDays} ${diffInDays === 1 ? 'يوم' : 'أيام'}`;
            } else if (diffInMonths < 12) {
                return `منذ ${diffInMonths} ${diffInMonths === 1 ? 'شهر' : 'أشهر'}`;
            } else {
                return `منذ ${diffInYears} ${diffInYears === 1 ? 'سنة' : 'سنوات'}`;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5'
            });

            // Enhanced Sidebar Toggle
            const sidebar = document.querySelector('.sidebar');
            const sidebarCollapseBtn = document.querySelector('.sidebar-collapse-btn');
            const tooltips = [];

            // Initialize tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                try {
                    if (el) {
                        tooltips.push(new bootstrap.Tooltip(el, {
                            delay: { show: 300, hide: 100 },
                            animation: true,
                            container: 'body',
                            trigger: 'hover'
                        }));
                    }
                } catch (error) {
                    console.warn('Error initializing tooltip:', error);
                }
            });

            // Notifications functionality
            const notificationsButton = document.querySelector('.notification-btn');
            const notificationsCount = document.querySelector('.notification-badge');
            const notificationsList = document.querySelector('.notifications-list');
            const markAllReadButton = document.querySelector('.mark-all-read');

            function updateNotificationsCount() {
                if (!notificationsCount) return;

                fetch('/admin/notifications/count')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (notificationsCount) {
                            notificationsCount.textContent = data.count;
                            notificationsCount.classList.toggle('d-none', data.count === 0);
                        }
                    })
                    .catch(error => console.error('Error updating notifications:', error));
            }

            // Initialize dropdown if notification button exists
            if (notificationsButton) {
                const dropdown = new bootstrap.Dropdown(notificationsButton);

                notificationsButton.addEventListener('click', function () {
                    fetch('/admin/notifications')
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!notificationsList) return;

                            if (!data.notifications || data.notifications.length === 0) {
                                notificationsList.innerHTML = `
                                    <div class="text-center p-4 text-muted">
                                        <i class="bi bi-bell-slash fs-2 mb-2 d-block"></i>
                                        <p class="mb-0">لا توجد إشعارات</p>
                                    </div>
                                `;
                                return;
                            }

                            notificationsList.innerHTML = data.notifications.map(notification => {
                                const iconData = getNotificationIcon(notification.type);
                                return `
                                    <div class="notification-item ${notification.read_at ? '' : 'unread'}" data-id="${notification.id}">
                                        <div class="d-flex align-items-center">
                                            <div class="notification-icon bg-${iconData.class}-subtle text-${iconData.class}">
                                                <i class="bi ${iconData.icon}"></i>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <p class="mb-1">${notification.data.message}</p>
                                                <small class="text-muted">${formatRelativeTime(notification.created_at)}</small>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }).join('');

                            // Add click handlers for individual notifications
                            notificationsList.querySelectorAll('.notification-item').forEach(item => {
                                item.addEventListener('click', () => {
                                    const id = item.dataset.id;
                                    fetch(`/admin/notifications/${id}/mark-as-read`, {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        }
                                    })
                                    .then(() => {
                                        item.classList.remove('unread');
                                        updateNotificationsCount();
                                    })
                                    .catch(error => console.error('Error marking notification as read:', error));
                                });
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching notifications:', error);
                            if (notificationsList) {
                                notificationsList.innerHTML = `
                                    <div class="text-center p-4 text-danger">
                                        <i class="bi bi-exclamation-circle fs-2 mb-2 d-block"></i>
                                        <p class="mb-0">حدث خطأ في تحميل الإشعارات</p>
                                    </div>
                                `;
                            }
                        });
                });
            }

            // Mark all as read functionality
            if (markAllReadButton) {
                markAllReadButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation(); // Prevent dropdown from closing

                    fetch('/admin/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(() => {
                        updateNotificationsCount();
                        document.querySelectorAll('.notification-item').forEach(item => {
                            item.classList.remove('unread');
                        });
                    })
                    .catch(error => console.error('Error marking notifications as read:', error));
                });
            }

            // Initial count update and periodic refresh
            updateNotificationsCount();
            setInterval(updateNotificationsCount, 60000); // Update every minute

            // Mobile navigation
            const navbarToggler = document.querySelector('.navbar-toggler');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');

            if (navbarToggler && sidebarOverlay) {
                navbarToggler.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                    document.body.classList.toggle('sidebar-active');
                });

                sidebarOverlay.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.classList.remove('sidebar-active');
                });
            }

            // Handle sidebar collapse
            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('collapsed');
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));

                    const icon = sidebarCollapseBtn.querySelector('i');
                    icon.classList.toggle('bi-chevron-right');
                    icon.classList.toggle('bi-chevron-left');

                    // Handle tooltips
                    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                        const tooltip = bootstrap.Tooltip.getInstance(el);
                        if (tooltip) {
                            tooltip[sidebar.classList.contains('collapsed') ? 'enable' : 'disable']();
                        }
                    });
                });

                // Restore sidebar state
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    sidebar.classList.add('collapsed');
                    const icon = sidebarCollapseBtn.querySelector('i');
                    icon.classList.remove('bi-chevron-right');
                    icon.classList.add('bi-chevron-left');
                }
            }

            // Mobile swipe gestures
            let touchStartX = 0;
            let touchEndX = 0;

            document.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            }, false);

            document.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchEndX - touchStartX;
                const SWIPE_THRESHOLD = 50;

                if (Math.abs(diff) > SWIPE_THRESHOLD) {
                    if (diff > 0) { // Right swipe
                        sidebar.classList.add('show');
                        sidebarOverlay.classList.add('show');
                        document.body.classList.add('sidebar-active');
                    } else { // Left swipe
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        document.body.classList.remove('sidebar-active');
                    }
                }
            }, false);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Enhanced Sidebar Toggle
            const sidebar = document.querySelector('.sidebar');
            const sidebarCollapseBtn = document.querySelector('.sidebar-collapse-btn');
            const tooltips = [];

            // Initialize all tooltips with enhanced options
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                tooltips.push(new bootstrap.Tooltip(el, {
                    delay: { show: 300, hide: 100 },
                    animation: true,
                    container: 'body'
                }));
            });

            // Handle sidebar collapse
            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', () => {
                    sidebar.classList.toggle('collapsed');

                    // Store preference
                    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));

                    // Update collapse button icon
                    const icon = sidebarCollapseBtn.querySelector('i');
                    icon.classList.toggle('bi-chevron-right');
                    icon.classList.toggle('bi-chevron-left');

                    // Handle tooltips
                    if (sidebar.classList.contains('collapsed')) {
                        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                            const tooltip = bootstrap.Tooltip.getInstance(el);
                            if (tooltip) {
                                tooltip.enable();
                            }
                        });
                    } else {
                        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                            const tooltip = bootstrap.Tooltip.getInstance(el);
                            if (tooltip) {
                                tooltip.disable();
                            }
                        });
                    }
                });

                // Restore sidebar state
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    sidebar.classList.add('collapsed');
                    const icon = sidebarCollapseBtn.querySelector('i');
                    icon.classList.remove('bi-chevron-right');
                    icon.classList.add('bi-chevron-left');
                }
            }

            // Enhanced Mobile Navigation
            const mobileToggle = document.querySelector('.navbar-toggler');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');

            if (mobileToggle && sidebarOverlay) {
                mobileToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                    document.body.classList.toggle('sidebar-active');
                });

                sidebarOverlay.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.classList.remove('sidebar-active');
                });
            }

            // Add swipe gesture support for mobile
            let touchStartX = 0;
            let touchEndX = 0;

            document.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            }, false);

            document.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, false);

            function handleSwipe() {
                const SWIPE_THRESHOLD = 50;
                const diff = touchEndX - touchStartX;

                if (Math.abs(diff) > SWIPE_THRESHOLD) {
                    if (diff > 0) { // Right swipe
                        sidebar.classList.add('show');
                        sidebarOverlay.classList.add('show');
                        document.body.classList.add('sidebar-active');
                    } else { // Left swipe
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        document.body.classList.remove('sidebar-active');
                    }
                }
            }
        });
    </script>

    <script>
        // Global error handler for CSRF token issues (419 errors)
        document.addEventListener('DOMContentLoaded', function() {
            // Intercept all fetch requests to handle 419 errors globally
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                return originalFetch.apply(this, args)
                    .then(response => {
                        if (response.status === 419) {
                            // CSRF token expired, redirect to login
                            window.location.href = '{{ route("login") }}';
                            return Promise.reject(new Error('CSRF token expired'));
                        }
                        return response;
                    });
            };

            // Handle XMLHttpRequest errors (for jQuery AJAX)
            $(document).ajaxError(function(event, xhr, settings) {
                if (xhr.status === 419) {
                    window.location.href = '{{ route("login") }}';
                }
            });

            // Also handle it for forms that might submit directly
            $(document).on('submit', 'form', function(e) {
                // Check if CSRF token exists
                const token = $('meta[name="csrf-token"]').attr('content');
                const formToken = $(this).find('input[name="_token"]').val();

                if (!token && !formToken) {
                    e.preventDefault();
                    window.location.href = '{{ route("login") }}';
                }
            });
        });
    </script>

    @stack('scripts')

</body>

</html>
