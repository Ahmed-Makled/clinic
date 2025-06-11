<nav class="navbar navbar-expand-lg position-fixed w-100 top-0 py-1">
    <div class="container">
        <a class="navbar-brand fw-bold ms-0 me-4" href="{{ route('home') }}">
            <img src="{{ asset('images/logo-clinic.png') }}" class="logo-img" alt="Logo" />
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Main Navigation -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house me-1"></i>الرئيسية
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contact*') ? 'active' : '' }}" href="{{ route('contact') }}">
                        <i class="bi bi-envelope me-1"></i>اتصل بنا
                    </a>
                </li>
            </ul>

            <!-- Auth Buttons -->
            <div class="nav-auth d-flex align-items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-light px-4">تسجيل الدخول</a>
                @else
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar ?? asset('images/user-avatar.avif') }}"
                                 class="rounded-circle"
                                 width="32"
                                 height="32"
                                 alt="User Avatar">
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(auth()->user()->hasRole('Admin'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>لوحة التحكم
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                @if(auth()->user()->hasRole('Patient'))
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person me-2"></i>حسابي
                                    </a>
                                @elseif(auth()->user()->hasRole('Doctor'))
                                    <a class="dropdown-item" href="{{ route('doctors.profile') }}">
                                        <i class="bi bi-person-badge me-2"></i>الملف الشخصي
                                    </a>
                                @else
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person me-2"></i>حسابي
                                    </a>
                                @endif
                            </li>

                            @if(auth()->user()->hasRole('Patient'))
                            <li>
                                <a class="dropdown-item" href="{{ route('profile') }}#appointments" id="navbar-appointments-link">
                                    <i class="bi bi-calendar2-check me-2"></i>حجوزاتي
                                </a>
                            </li>
                            @elseif(auth()->user()->hasRole('Doctor'))
                            <li>
                                <a class="dropdown-item" href="{{ route('doctors.appointments') }}">
                                    <i class="bi bi-calendar2-check me-2"></i>إدارة الحجوزات
                                </a>
                            </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>

<style>
    .navbar {
        background:#0d6efd;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        box-shadow: 0 1px 10px rgba(0,0,0,0.1);
        z-index: 1030;
    }

    .navbar-brand {
        padding: 0;
    }

    .logo-img {
        height: 45px;
        width: auto;
        border-radius: 8px;
    }

    .nav-link {
        color: rgba(255,255,255,0.9);
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .nav-link:hover, .nav-link.active {
        color: #fff;
        background: rgba(255,255,255,0.1);
    }

    .nav-link i {
        font-size: 0.875rem;
    }

    /* Auth Buttons */
    .nav-auth .btn {
        border-radius: 0.5rem;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
        transition: all 0.2s ease;
    }

    .btn-outline-light:hover {
        background: rgba(255,255,255,0.1);
        border-color: transparent;
    }

    /* Dropdown Styles */
    .dropdown-menu {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 0.5rem;
    }

    .dropdown-item {
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .dropdown-item i {
        font-size: 0.875rem;
        width: 1.25rem;
    }

    /* Mobile Responsiveness */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: white;
            margin: 1rem -1rem -0.75rem;
            padding: 1rem;
            border-radius: 1rem;
        }

        .nav-link {
            color: #2d3748;
        }

        .nav-link:hover, .nav-link.active {
            color: #0d6efd;
            background: #f8f9fa;
        }

        .nav-auth {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }
    }
</style>
