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
                                href="{{ route('admin.doctors.index') }}">
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

                @hasSection('title')
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('title')</h1>
                    @yield('actions')
                </div>
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
    @stack('scripts')
</body>

</html>
