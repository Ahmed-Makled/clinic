<nav class="navbar navbar-expand-lg navbar-dark bg-primary position-fixed w-100" style="direction: ltr; z-index: 1030">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.jpg') }}" class="rounded-pill" height="40" width="40" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('login') ? 'active' : '' }}" style="border: 1px solid #FFF;" href="{{ route('login') }}">تسجيل الدخول</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false"> حسابى
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            @if(auth()->user()->hasRole('Administrator'))
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard.index') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>لوحة التحكم
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="bi bi-person me-2"></i>حسابى
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="specialtiesDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false"> التخصصات
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="specialtiesDropdown">
                        @if(!empty($categories) && count($categories))
                            @foreach($categories as $c)
                                <li><a class="dropdown-item" href="{{ route('categories.show', $c->id) }}">{{ $c->name }}</a></li>
                            @endforeach
                        @else
                            <li><span class="dropdown-item text-muted">لا يوجد أقسام.</span></li>
                        @endif
                    </ul>
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
                        <span class="visually-hidden">(current)</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
