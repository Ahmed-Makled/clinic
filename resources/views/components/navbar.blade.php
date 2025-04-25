<nav class="navbar navbar-expand-lg navbar-dark bg-primary position-fixed w-100" style="direction: ltr; z-index: 1030">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.jpg') }}" class="rounded-pill" height="40" width="40" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" style="border: 1px solid #FFF;" href="{{ route('login') }}">تسجيل الدخول</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> حسابى
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <!-- @if(auth()->check())
                                @php
                                    $user = auth()->user();
                                    $roles = $user->roles()->pluck('name')->toArray();
                                @endphp
                                <div class="dropdown-item text-muted">
                                    Roles: {{ implode(', ', $roles) }}
                                </div>
                                <div class="dropdown-divider"></div>
                            @endif -->
                            @if(auth()->user()->hasRole('Administrator'))
                                <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                    <i class="bi bi-speedometer2 me-2"></i>لوحة التحكم
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif
                            <a class="dropdown-item" href="{{ route('profile') }}">
                                <i class="bi bi-person me-2"></i>حسابى
                            </a>

                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
                                </button>
                            </form>
                        </div>
                    </li>
                @endguest

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> التخصصات
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @if(!empty($categories) && count($categories))
                            @foreach($categories as $c)
                                <a class="dropdown-item" href="{{ route('categories.show', $c->id) }}">{{ $c->name }}</a>
                            @endforeach
                        @else
                            <span class="dropdown-item text-muted">لا يوجد أقسام.</span>
                        @endif
                    </div>
                </li>

                <li class="nav-item {{ request()->is('doctors') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('doctors.index') }}">الأطباء</a>
                </li>
                <li class="nav-item {{ request()->is('contact') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('contact') }} ">إتصل بنا</a>
                </li>
                <li class="nav-item {{ request()->is('about') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('about') }}">عن الموقع</a>
                </li>
                <li class="nav-item {{ request()->is('welcome') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">الصفحة الرئيسية
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
