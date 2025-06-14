@extends('layouts.app')

@section('content')
    <header class="header position-relative vh-100">
        <div class="overlay position-absolute w-100 h-100 d-flex align-items-center">
            <div class="container mt-5 py-5 text-white">
                <div class="row justify-content-center">
                    <div class="col-md-9 text-center">
                        <h1 class="display-3 fw-bold mb-4 animate__animated animate__fadeInDown">
                            ✨ <span class="text-primary position-relative text-white">Clinic Master</span> ✨
                        </h1>
                        <p class="lead mb-3 animate__animated animate__fadeInUp opacity-90" style="animation-delay: 0.3s;">
                            ابحث عن طبيبك المناسب واحجز موعدك بسهولة
                        </p>
                        <p class="h5 mb-5 animate__animated animate__fadeInUp opacity-85" style="animation-delay: 0.5s;">
                            منصة شاملة تربط بين المرضى والأطباء لتوفير أفضل رعاية صحية مع تجربة استخدام سهلة وآمنة
                        </p>
                    </div>
                </div>

                <div class="buttons-group text-center mb-5 animate__animated animate__fadeInUp">
                    <a class="btn btn-primary btn-lg rounded-pill px-5 py-3 mx-2 shadow-lg hover-scale-lg"
                        href="{{ route('search') }}">
                        <i class="bi bi-calendar-check me-2"></i> إحجز الآن
                    </a>
                    <a class="btn btn-light btn-lg rounded-pill px-5 py-3 mx-2 shadow-lg hover-scale-lg"
                        href="{{ route('contact') }}">
                        <i class="bi bi-headset me-2"></i> اتصل بنا
                    </a>
                </div>

                <div class="tabs shadow bg-white text-dark rounded-xl animate__animated animate__fadeInUp">
                    <div class="p-4">
                        <div class="text-center mb-4">
                            <h4 class="fw-bold text-primary mb-2">ابحث عن طبيبك المثالي</h4>
                            <p class="text-muted">استخدم الفلتر أدناه للعثور على الطبيب المناسب لك</p>
                        </div>

                        <form id="search" action="{{ route('search') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-6 col-lg-3">
                                    <div class="search-box-container">
                                        <label class="form-label fw-bold text-dark mb-2">
                                            <i class="fas fa-stethoscope text-primary me-2"></i>
                                            التخصص
                                        </label>
                                        <select name="category" id="category" class="form-select form-select-lg search-box">
                                            <option value="">اختر التخصص</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="search-box-container">
                                        <label class="form-label fw-bold text-dark mb-2">
                                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                                            المحافظة
                                        </label>
                                        <select name="governorate_id" id="governorate_id"
                                            class="form-select form-select-lg search-box">
                                            <option value="">اختر المحافظة</option>
                                            @foreach($governorates as $governorate)
                                                <option value="{{ $governorate->id }}" {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                                    {{ $governorate->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="search-box-container">
                                        <label class="form-label fw-bold text-dark mb-2">
                                            <i class="fas fa-city text-info me-2"></i>
                                            المدينة
                                        </label>
                                        <select name="city_id" id="city_id" class="form-select form-select-lg search-box">
                                            <option value="">اختر المدينة</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 col-lg-3">
                                    <div class="search-box-container">
                                        <label class="form-label fw-bold text-dark mb-2">
                                            <i class="fas fa-user-md text-purple me-2"></i>
                                            الأطباء
                                        </label>
                                        <select name="doctors" id="doctors" class="form-select form-select-lg search-box">
                                            <option value="">اختر الطبيب</option>
                                            @foreach($doctors as $doctor)
                                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit"
                                    class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-sm hover-scale-lg">
                                    <i class="fas fa-search me-2"></i>
                                    ابحث عن طبيب الآن
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div id="mySlide" class="carousel slide carousel-fade overflow-hidden h-100" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mySlide" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#mySlide" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#mySlide" data-bs-slide-to="2"></button>
            </div>

            <div class="carousel-inner h-100">
                <div class="carousel-item h-100 active">
                    <img src="{{ asset('images/slide3.jpg') }}" class="d-block w-100 h-100 object-fit-cover"
                        alt="Medical Slide 1" />
                </div>
                <div class="carousel-item h-100">
                    <img src="{{ asset('images/slide1.jpg') }}" class="d-block w-100 h-100 object-fit-cover"
                        alt="Medical Slide 2" />
                </div>
                <div class="carousel-item h-100">
                    <img src="{{ asset('images/slide2.jpg') }}" class="d-block w-100 h-100 object-fit-cover"
                        alt="Medical Slide 3" />
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#mySlide" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mySlide" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </header>

    <section class="about py-5">
        <div class="container">
            <div class="section-header text-center mb-5 position-relative">
                <h2 class="display-5 fw-bold mb-3 animate__animated animate__fadeInDown position-relative d-inline-block">
                    إزاى تحجز معانا
                    <span class="section-underline"></span>
                </h2>
                <p class="text-muted w-75 mx-auto animate__animated animate__fadeInUp">اتبع الخطوات البسيطة دي عشان تحجز مع
                    افضل دكتور</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="booking-step shadow rounded-4 bg-white text-center p-4 h-100 position-relative animate__animated animate__fadeInUp"
                        data-wow-delay="0.1s">
                        <div class="step-number">1</div>
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-search fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">إبحث على دكتور</h4>
                        <p class="text-muted mb-3">حدد التخصص والمنطقة والتأمين وسعر الكشف المناسب ليك</p>
                        <div class="step-features">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted small">تخصصات متنوعة</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted small">بحث متقدم</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="booking-step shadow rounded-4 bg-white text-center p-4 h-100 position-relative animate__animated animate__fadeInUp"
                        data-wow-delay="0.3s">
                        <div class="step-number">2</div>
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-star fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">قارن واختار</h4>
                        <p class="text-muted mb-3">شوف تقييمات المرضى السابقين واختار الدكتور المناسب لحالتك</p>
                        <div class="step-features">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted small">تقييمات حقيقية</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted small">معلومات مفصلة</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="booking-step shadow rounded-4 bg-white text-center p-4 h-100 position-relative animate__animated animate__fadeInUp"
                        data-wow-delay="0.5s">
                        <div class="step-number">3</div>
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-calendar-check fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h4 class="mb-3">احجز موعدك</h4>
                        <p class="text-muted mb-3">احجز ميعادك اونلاين وهنأكدلك الحجز فورًا عبر الرسائل النصية</p>
                        <div class="step-features">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted small">تأكيد فوري</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted small">تذكير قبل الميعاد</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 animate__animated animate__fadeInUp">
                <a href="{{ route('search') }}" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-sm">
                    <i class="bi bi-calendar-plus me-2"></i> ابدأ الحجز الآن
                </a>
            </div>
        </div>
    </section>



    <!-- سيكشن "ليه تختار منصتنا؟" محسن ومطور -->
    <section class="features py-5">
        <div class="container">
            <div class="section-header text-center mb-5 position-relative">
                <h2 class="display-5 fw-bold mb-3 animate__animated animate__fadeInDown position-relative d-inline-block">
                    ليه تختار منصتنا؟
                    <span class="section-underline"></span>
                </h2>
                <p class="text-muted w-75 mx-auto animate__animated animate__fadeInUp">نقدم لك خدمات طبية متكاملة من خلال
                    نخبة من أفضل الأطباء والمستشفيات</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card bg-white text-center p-4 rounded-4 shadow-sm h-100 animate__animated animate__fadeInUp"
                        data-delay="0.1s">
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-shield-check fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">دكاترة معتمدين</h5>
                        <p class="text-muted mb-3">نضمن لك التعامل مع أطباء مُرخصين ومعتمدين من وزارة الصحة، بخبرة لا تقل عن
                            3 سنوات في مجالهم</p>
                        <div class="feature-details">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">الترخيص الطبي</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">الخبرة الكافية</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card bg-white text-center p-4 rounded-4 shadow-sm h-100 animate__animated animate__fadeInUp"
                        data-delay="0.3s">
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-clock-history fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">حجز سريع</h5>
                        <p class="text-muted mb-3">احجز موعدك في أقل من دقيقة واحدة، وتلقى تأكيداً فورياً عبر الرسائل النصية
                            والبريد الإلكتروني</p>
                        <div class="feature-details">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">حجز خلال دقيقة</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">تأكيد فوري</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card bg-white text-center p-4 rounded-4 shadow-sm h-100 animate__animated animate__fadeInUp"
                        data-delay="0.5s">
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-geo-alt fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">عيادات في كل مكان</h5>
                        <p class="text-muted mb-3">نمتلك شبكة واسعة من العيادات والمستشفيات في جميع محافظات مصر، اختر الأقرب
                            لك بكل سهولة</p>
                        <div class="feature-details">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">تغطية كل المحافظات</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">سهولة الوصول</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="feature-card bg-white text-center p-4 rounded-4 shadow-sm h-100 animate__animated animate__fadeInUp"
                        data-delay="0.7s">
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-headset fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">دعم على مدار اليوم</h5>
                        <p class="text-muted mb-3">فريق دعم متخصص متاح 24/7 للإجابة على استفساراتك ومساعدتك في أي وقت تحتاج
                            فيه للمساعدة</p>
                        <div class="feature-details">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">متاح 24/7</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">فريق متخصص</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- صف إضافي للميزات -->
            <div class="row g-4 mt-3">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card bg-white text-center p-4 rounded-4 shadow-sm h-100 animate__animated animate__fadeInUp"
                        data-delay="0.8s">
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-star fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">تقييمات حقيقية</h5>
                        <p class="text-muted mb-3">تقييمات واقعية من مرضى حقيقيين تساعدك على اختيار الطبيب المناسب بثقة
                            وشفافية</p>
                        <div class="feature-details">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">تقييمات موثقة</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">نظام تقييم دقيق</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card bg-white text-center p-4 rounded-4 shadow-sm ه-100 animate__animated animate__fadeInUp"
                        data-delay="1s">
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-wallet2 fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">أسعار تنافسية</h5>
                        <p class="text-muted mb-3">أسعار مناسبة وشفافة لجميع الخدمات الطبية، مع إمكانية الدفع بطرق متعددة
                            وخصومات دورية</p>
                        <div class="feature-details">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">أفضل الأسعار</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">خصومات مستمرة</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card bg-white text-center p-4 rounded-4 shadow-sm ه-100 animate__animated animate__fadeInUp"
                        data-delay="1.2s">
                        <div class="icon-box mb-4 mx-auto">
                            <div class="icon-circle">
                                <i class="bi bi-journal-medical fs-1 text-primary"></i>
                            </div>
                        </div>
                        <h5 class="mb-3 fw-bold">ملفات طبية آمنة</h5>
                        <p class="text-muted mb-3">احتفظ بملفك الطبي بشكل آمن ومشفر، وتتبع تاريخ الزيارات والعلاجات بسهولة
                        </p>
                        <div class="feature-details">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">خصوصية عالية</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                <span class="text-muted">متابعة مستمرة</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- زر الحجز -->
            <div class="text-center mt-5 animate__animated animate__fadeInUp">
                <a href="{{ route('search') }}"
                    class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-sm hover-scale-lg">
                    <i class="bi bi-calendar-check me-2"></i> احجز موعدك الآن
                </a>
            </div>

        </div>
    </section>

    <!-- شركاؤنا - تصميم محسن -->
    <section class="partners py-5 bg-white">
        <div class="container">
            <div class="section-header text-center mb-5 position-relative">
                <h2 class="display-5 fw-bold mb-3 animate__animated animate__fadeInDown position-relative d-inline-block">
                    شركاؤنا
                    <span class="section-underline"></span>
                </h2>
                <p class="text-muted w-75 mx-auto animate__animated animate__fadeInUp">نتعاون مع أفضل المستشفيات والمراكز
                    الطبية في مصر والعالم العربي</p>
            </div>

            <!-- للشاشات المتوسطة والكبيرة -->
            <div class="d-none d-md-block">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/4e73df/ffffff?text=Medlife" alt="Medlife Hospital"
                                class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/20c997/ffffff?text=HealthPlus" alt="HealthPlus"
                                class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/fd7e14/ffffff?text=MediCare" alt="MediCare"
                                class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/e83e8c/ffffff?text=AlShifa" alt="AlShifa Hospital"
                                class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/6f42c1/ffffff?text=MediTech" alt="MediTech"
                                class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/dc3545/ffffff?text=NileCare" alt="NileCare"
                                class="img-fluid">
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center align-items-center mt-4">
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/6610f2/ffffff?text=AlAmal" alt="AlAmal Center"
                                class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/198754/ffffff?text=EgyptMed" alt="EgyptMed"
                                class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/0d6efd/ffffff?text=CairoHealth" alt="CairoHealth"
                                class="img-fluid">
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-4 mb-4">
                        <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                            <img src="https://placehold.co/200x100/fd7e14/ffffff?text=SmartCare" alt="SmartCare"
                                class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>

            <!-- كاروسيل للشاشات الصغيرة -->
            <div class="d-md-none">
                <div id="partnersCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/4e73df/ffffff?text=Medlife"
                                            alt="Medlife Hospital" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/20c997/ffffff?text=HealthPlus"
                                            alt="HealthPlus" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/fd7e14/ffffff?text=MediCare" alt="MediCare"
                                            class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/e83e8c/ffffff?text=AlShifa"
                                            alt="AlShifa Hospital" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/6f42c1/ffffff?text=MediTech" alt="MediTech"
                                            class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/dc3545/ffffff?text=NileCare" alt="NileCare"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/6610f2/ffffff?text=AlAmal"
                                            alt="AlAmal Center" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/198754/ffffff?text=EgyptMed" alt="EgyptMed"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/0d6efd/ffffff?text=CairoHealth"
                                            alt="CairoHealth" class="img-fluid">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="partner-logo p-3 text-center shadow-hover rounded-3">
                                        <img src="https://placehold.co/200x100/fd7e14/ffffff?text=SmartCare" alt="SmartCare"
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-indicators position-relative mt-3">
                        <button type="button" data-bs-target="#partnersCarousel" data-bs-slide-to="0"
                            class="active bg-primary" aria-current="true"></button>
                        <button type="button" data-bs-target="#partnersCarousel" data-bs-slide-to="1"
                            class="bg-primary"></button>
                        <button type="button" data-bs-target="#partnersCarousel" data-bs-slide-to="2"
                            class="bg-primary"></button>
                        <button type="button" data-bs-target="#partnersCarousel" data-bs-slide-to="3"
                            class="bg-primary"></button>
                        <button type="button" data-bs-target="#partnersCarousel" data-bs-slide-to="4"
                            class="bg-primary"></button>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- قسم آراء المرضى - تصميم محسن بسلايدر أفقي -->
    <section class="testimonials py-5">
        <div class="container">
            <div class="section-header text-center mb-5 position-relative">
                <h2 class="display-5 fw-bold mb-3 animate__animated animate__fadeInDown position-relative d-inline-block">
                    آراء المرضى
                    <span class="section-underline"></span>
                </h2>
                <p class="text-muted w-75 mx-auto animate__animated animate__fadeInUp">شاهد تجارب مرضانا مع أطبائنا المميزين
                </p>
            </div>

            <!-- تصميم جديد باستخدام سلايدر أفقي -->
            <div class="testimonials-slider position-relative">
                <!-- أزرار التنقل -->
                <div class="slider-nav">
                    <button class="slider-prev" aria-label="السابق">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                    <button class="slider-next" aria-label="التالي">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                </div>

                <!-- حاوية السلايدر -->
                <div class="testimonials-track-outer">
                    <div class="testimonials-track">
                        <!-- بطاقة تقييم 1 -->
                        <div class="testimonial-slide">
                            <div class="testimonial-card h-100">
                                <div class="testimonial-rating mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span class="ms-2 text-muted">(5.0)</span>
                                </div>
                                <div class="testimonial-text mb-4">
                                    <p class="mb-0">
                                        "تجربتي مع دكتور أحمد كانت ممتازة، كان متعاون جدًا ومتفهم لحالتي. استطاع تشخيص
                                        مشكلتي بدقة ووصف العلاج المناسب. موقع شركتكم سهل جدًا في الاستخدام وحجز الميعاد كان
                                        سهل وسريع."
                                    </p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="testimonial-img me-3">
                                        <img src="https://randomuser.me/api/portraits/men/45.jpg" class="rounded-circle"
                                            width="60" height="60" alt="مريض">
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold">محمد أحمد</h5>
                                        <p class="text-primary mb-0 small">قسم جراحة العظام</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- بطاقة تقييم 2 -->
                        <div class="testimonial-slide">
                            <div class="testimonial-card h-100">
                                <div class="testimonial-rating mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star text-warning"></i>
                                    <span class="ms-2 text-muted">(4.0)</span>
                                </div>
                                <div class="testimonial-text mb-4">
                                    <p class="mb-0">
                                        "أنا سعيدة جداً بخدمات العيادة، الدكتورة ليلى كانت محترمة جداً والعيادة نظيفة ومجهزة
                                        بأحدث الأجهزة. أنصح بشدة بالحجز من خلال موقعكم لسهولة الحجز ودقة الحجوزات."
                                    </p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="testimonial-img me-3">
                                        <img src="https://randomuser.me/api/portraits/women/32.jpg" class="rounded-circle"
                                            width="60" height="60" alt="مريضة">
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold">سارة محمود</h5>
                                        <p class="text-primary mb-0 small">قسم الأمراض الجلدية</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- بطاقة تقييم 3 -->
                        <div class="testimonial-slide">
                            <div class="testimonial-card h-100">
                                <div class="testimonial-rating mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                    <span class="ms-2 text-muted">(4.5)</span>
                                </div>
                                <div class="testimonial-text mb-4">
                                    <p class="mb-0">
                                        "تطبيقكم جميل جدًا وسهل الاستعمال، سهلتولي حجز ميعاد مع الدكتور بسهولة. الأطباء
                                        محترفين والحجوزات دقيقة وفي الوقت المحدد. شكرًا لكم على المجهود الرائع."
                                    </p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="testimonial-img me-3">
                                        <img src="https://randomuser.me/api/portraits/men/36.jpg" class="rounded-circle"
                                            width="60" height="60" alt="مريض">
                                    </div>
                                    <div>
                                        <h5 class="mb-0 fw-bold">خالد محمد</h5>
                                        <p class="text-primary mb-0 small">قسم طب الأطفال</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- مؤشرات السلايدر (النقاط) -->
                <div class="testimonials-indicators">
                    <div class="indicator active" data-index="0"></div>
                    <div class="indicator" data-index="1"></div>
                    <div class="indicator" data-index="2"></div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('search') }}"
                    class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-sm animate__animated animate__fadeInUp">
                    <i class="bi bi-calendar-check me-2"></i> احجز الآن وكن جزءًا من قصص النجاح
                </a>
            </div>
        </div>
    </section>

    @push('styles')
        <style>
            .header {
                overflow: hidden;
            }

            .header .carousel-item img {
                filter: brightness(0.8);
                transform: scale(1.1);
                transition: transform 6s ease-in-out;
            }

            .header .carousel-item.active img {
                transform: scale(1);
            }

            /* تحسينات عنوان القسم */
            .section-header h2 {
                position: relative;
                display: inline-block;
                margin-bottom: 1rem;
            }

            .section-underline {
                position: absolute;
                left: 50%;
                bottom: -10px;
                width: 80px;
                height: 4px;
                background-color: var(--bs-primary);
                transform: translateX(-50%);
                border-radius: 2px;
                content: "";
                display: block;
            }

            .section-header h2:after {
                content: "";
                position: absolute;
                left: 50%;
                bottom: -10px;
                width: 50px;
                height: 4px;
                background-color: rgba(13, 110, 253, 0.5);
                transform: translateX(-50%) translateX(65px);
                border-radius: 2px;
            }

            /* خطوات الحجز المحسنة */
            .section-header .badge {
                font-size: 0.9rem;
                letter-spacing: 1px;
            }

            .booking-step {
                border-top: 4px solid var(--bs-primary);
                transition: all 0.3s ease;
                overflow: hidden;
            }

            .booking-step:hover {
                transform: translateY(-10px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
            }

            .booking-step .step-number {
                position: absolute;
                top: 20px;
                right: 20px;
                background: var(--bs-primary);
                color: white;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: bold;
                font-size: 1.2rem;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .icon-circle {
                width: 80px;
                height: 80px;
                background-color: rgba(13, 110, 253, 0.1);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto;
                transition: all 0.3s ease;
            }

            .booking-step:hover .icon-circle {
                background-color: var(--bs-primary);
            }

            .booking-step:hover .icon-circle i {
                color: white !important;
            }

            .step-features {
                border-top: 1px dashed #ddd;
                padding-top: 15px;
                margin-top: 15px;
            }

            /* Utility classes */
            .hover-scale-lg {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .hover-scale-lg:hover {
                transform: scale(1.1);
                box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
            }

            .rounded-xl {
                border-radius: 1rem;
            }

            .text-glow {
                text-shadow: 0 0 10px rgba(13, 110, 253, 0.5);
            }

            /* Carousel controls */
            .carousel-indicators {
                z-index: 2;
            }

            .carousel-indicators button {
                width: 12px !important;
                height: 12px !important;
                border-radius: 50%;
                margin: 0 6px !important;
            }

            .carousel-control-prev,
            .carousel-control-next {
                z-index: 2;
                width: 10%;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .header:hover .carousel-control-prev,
            .header:hover .carousel-control-next {
                opacity: 1;
            }

            /* Line styles */
            .line {
                position: relative;
            }

            .line::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 3px;
                background-color: var(--bs-primary);
                transition: width 0.3s ease;
            }

            .line:hover::after,
            .line.active::after {
                width: 60%;
            }

            /* Search box styles */
            .search-box {
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .search-box::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 3px;
                height: 0;
                background-color: var(--bs-primary);
                transition: height 0.3s ease;
            }

            .search-box:hover::before {
                height: 100%;
            }

            .search-box:hover {
                border-color: var(--bs-primary) !important;
                box-shadow: 0 0 15px rgba(13, 110, 253, 0.2);
                transform: translateY(-2px);
            }

            /* تحسينات للـ header الجديد */
            .search-box-container {
                transition: all 0.3s ease;
            }

            .search-box-container:hover {
                transform: translateY(-2px);
            }

            .search-box {
                border: 2px solid #e9ecef;
                border-radius: 12px;
                padding: 12px 16px;
                font-size: 1rem;
                transition: all 0.3s ease;
                background-color: #f8f9fa;
            }

            .search-box:focus {
                border-color: var(--bs-primary);
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
                background-color: white;
                transform: translateY(-1px);
            }

            .search-box:hover {
                border-color: var(--bs-primary);
                background-color: white;
            }

            .form-label {
                font-size: 0.95rem;
                margin-bottom: 8px;
            }

            .form-label i {
                font-size: 1.1rem;
            }

            .text-purple {
                color: #6f42c1 !important;
            }

            /* تحسين العنوان الرئيسي */
            .display-3 {
                font-size: calc(1.525rem + 3.3vw);
                font-weight: 700;
                letter-spacing: -0.5px;
            }

            .text-glow {
                text-shadow: 0 0 20px rgba(13, 110, 253, 0.3);
            }

            /* تحسين نموذج البحث */
            .tabs {
                backdrop-filter: blur(10px);
                /* background: rgba(255, 255, 255, 0.95) !important; */
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            /* تحسين الأزرار */
            .btn-primary {
                background: linear-gradient(135deg, #0d6efd 0%, #0c63e4 100%);
                border: none;
                box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(13, 110, 253, 0.4);
                background: linear-gradient(135deg, #0c63e4 0%, #0b5ed7 100%);
            }

            /* تحسين التخطيط العام */
            .header .container {
                max-width: 1200px;
            }

            /* تحسين الريسبونسف */
            @media (max-width: 768px) {
                .display-3 {
                    font-size: 2.5rem;
                }

                .search-box-container {
                    margin-bottom: 1rem;
                }

                .btn-lg {
                    padding: 12px 30px;
                    font-size: 1rem;
                }
            }

            @media (max-width: 576px) {
                .display-3 {
                    font-size: 2rem;
                }

                .tabs {
                    margin: 0 -15px;
                    border-radius: 0;
                }
            }

            /* تصميمات سيكشن "ليه تختار منصتنا؟" */
            .features {
                background-color: #f8f9fa;
                position: relative;
                overflow: hidden;
                padding: 80px 0;
            }

            .features::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('{{ asset("images/pattern-bg.png") }}') center/cover;
                opacity: 0.03;
                z-index: 0;
            }

            .features .container {
                position: relative;
                z-index: 1;
            }

            .feature-card {
                transition: all 0.35s ease;
                border-top: 4px solid transparent;
                overflow: hidden;
            }

            .feature-card:hover {
                transform: translateY(-10px);
                border-top: 4px solid var(--bs-primary);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1) !important;
            }

            .icon-circle {
                width: 90px;
                height: 90px;
                background-color: rgba(13, 110, 253, 0.1);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto;
                transition: all 0.3s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            }

            .feature-card:hover .icon-circle {
                background-color: var(--bs-primary);
                transform: scale(1.1) rotate(10deg);
                box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
            }

            .feature-card:hover .icon-circle i {
                color: white !important;
                transform: scale(1.2);
            }

            .icon-circle i {
                transition: all 0.3s ease;
            }

            .feature-details {
                border-top: 1px dashed #ddd;
                padding-top: 15px;
                margin-top: auto;
                text-align: right;
            }

            .feature-card h5 {
                position: relative;
                display: inline-block;
            }

            .feature-card:hover h5 {
                color: var(--bs-primary);
            }

            /* تصميم قسم الإحصائيات */
            .stats-bar {
                border-top: 1px solid rgba(0, 0, 0, 0.05);
                margin-top: 50px;
            }

            .stats-counter {
                padding: 20px 0;
            }

            .stats-icon {
                transition: all 0.3s ease;
            }

            .stats-data:hover .stats-icon {
                transform: scale(1.2);
            }

            .counter {
                font-size: 2.5rem;
                background: linear-gradient(45deg, var(--bs-primary), #0c63e4);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
                margin-bottom: 0;
            }

            .animate__animated[data-delay] {
                animation-delay: var(--delay);
            }

            @media (max-width: 768px) {
                .counter {
                    font-size: 2rem;
                }

                .feature-card {
                    margin-bottom: 15px;
                }
            }


            /* تصميمات شعارات الشركاء */
            .partner-logo {
                transition: all 0.3s ease;
            }

            .grayscale-hover img {
                filter: grayscale(100%);
                opacity: 0.7;
                transition: all 0.3s ease;
            }

            .grayscale-hover:hover img {
                filter: grayscale(0%);
                opacity: 1;
            }

            /* تصميمات آراء المرضى */
            .testimonial-card {
                transition: all 0.3s ease;
                position: relative;
            }

            .testimonial-card:before {
                content: '"';
                position: absolute;
                top: 10px;
                right: 20px;
                font-size: 3rem;
                font-family: Georgia, serif;
                color: rgba(13, 110, 253, 0.1);
            }

            .testimonial-text {
                line-height: 1.7;
            }

            .testimonial-img img {
                border: 3px solid var(--bs-primary);
            }

            /* شريط الإحصائيات */
            .stats-bar {
                position: relative;
                z-index: 1;
            }

            .counter {
                animation: fadeIn 1s ease-in-out;
            }

            /* Animation keyframes */
            @keyframes float {
                0% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-10px);
                }

                100% {
                    transform: translateY(0px);
                }
            }

            /* Animation durations */
            .animate__animated.animate__fadeInDown {
                animation-duration: 1.5s;
            }

            .animate__animated.animate__fadeInUp {
                animation-duration: 1.2s;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* تحسينات الريسبونسف */
            @media (max-width: 768px) {
                .stats-bar {
                    margin-top: 1rem;
                }

                .doc-image-container {
                    height: 160px;
                }

                .testimonial-card {
                    margin-bottom: 1rem;
                }

                .booking-step {
                    margin-bottom: 2rem;
                }
            }

            /* تحسينات جديدة لقسم الشركاء */
            .shadow-hover {
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            }

            .shadow-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            }

            .partner-logo img {
                transition: all 0.4s ease;
                filter: brightness(0.95);
            }

            .partner-logo:hover img {
                filter: brightness(1.05);
                transform: scale(1.08);
            }

            .hover-scale {
                transition: transform 0.3s ease;
            }

            .hover-scale:hover {
                transform: scale(1.05);
            }

            #partnersCarousel .carousel-indicators [data-bs-target] {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin: 0 5px;
            }

            .section-header h2.animate__fadeInDown {
                animation-delay: 0.2s;
            }

            .section-header p.animate__fadeInUp {
                animation-delay: 0.4s;
            }

            /* تصميمات آراء المرضى */
            .testimonials {
                /* background-color: #f8f9fa; */
                position: relative;
                overflow: hidden;
            }

            .testimonials::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('{{ asset("images/pattern-bg.png") }}') center/cover;
                opacity: 0.05;
                z-index: 0;
            }

            .testimonials .container {
                position: relative;
                z-index: 1;
            }

            .testimonial-card {
                transition: all 0.3s ease;
                position: relative;
                border-radius: 12px;
                border-top: 3px solid var(--bs-primary);
                overflow: hidden;
            }

            .testimonial-card::before {
                content: '\201C';
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 60px;
                font-family: Georgia, serif;
                color: rgba(13, 110, 253, 0.1);
                line-height: 1;
            }

            .testimonial-card:hover {
                transform: translateY(-5px);
            }

            .testimonial-text {
                line-height: 1.7;
                font-style: italic;
            }

            .testimonial-img img {
                border: 2px solid var(--bs-primary);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .testimonial-card:hover .testimonial-img img {
                transform: scale(1.1);
            }

            .carousel-controls button {
                width: 40px;
                height: 40px;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .section-header h2 {
                color: #333;
            }

            #testimonialsCarouselSm .carousel-indicators [data-bs-target] {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                margin: 0 5px;
                background-color: var(--bs-primary);
            }

            /* Animation styles for testimonials */
            .testimonial-card.animate__animated {
                animation-duration: 0.8s;
            }

            .testimonial-card .testimonial-img {
                animation-delay: 0.2s;
            }

            /* Styles for responsive design */
            @media (max-width: 768px) {
                .testimonial-card {
                    margin-bottom: 1rem;
                }

                .testimonials::before {
                    opacity: 0.02;
                }
            }

            /* تصميمات آراء المرضى المحسنة مع سلايدر أفقي */
            .testimonials {
                background-color: #f8f9fa;
                position: relative;
                overflow: hidden;
                padding: 80px 0;
            }

            .testimonials::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: url('{{ asset("images/pattern-bg.png") }}') center/cover;
                opacity: 0.05;
                z-index: 0;
            }

            .testimonials .container {
                position: relative;
                z-index: 1;
            }

            /* تنسيق السلايدر */
            .testimonials-slider {
                margin: 20px auto;
                overflow: hidden;
                position: relative;
            }

            .testimonials-track-outer {
                overflow: hidden;
                padding: 30px 0;
                margin: 0 -10px;
            }

            .testimonials-track {
                display: flex;
                transition: transform 0.5s ease;
                gap: 20px;
                padding: 10px;
            }

            .testimonial-slide {
                min-width: calc(33.333% - 20px);
                padding: 0;
                transition: all 0.3s ease;
            }

            /* تصميم البطاقة */
            .testimonial-card {
                background: white;
                border-radius: 15px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
                padding: 25px;
                transition: all 0.3s ease;
                position: relative;
                border-top: 4px solid var(--bs-primary);
                height: 100%;
            }

            .testimonial-card::before {
                content: '\201C';
                position: absolute;
                top: 15px;
                right: 20px;
                font-size: 60px;
                font-family: Georgia, serif;
                color: rgba(13, 110, 253, 0.1);
                line-height: 1;
            }

            .testimonial-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
            }

            .testimonial-text {
                font-style: italic;
                color: #555;
                line-height: 1.7;
                font-size: 15px;
            }

            /* صورة المريض */
            .testimonial-img img {
                width: 60px;
                height: 60px;
                border: 3px solid var(--bs-primary);
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                transition: all 0.3s ease;
            }

            .testimonial-card:hover .testimonial-img img {
                transform: scale(1.1);



            }

            /* أزرار التنقل */
            .slider-nav button {
                width: 45px;
                height: 45px;
                border-radius: 50%;
                background-color: white;
                color: var(--bs-primary);
                border: none;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
                font-size: 20px;
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .slider-nav button:hover {
                background-color: var(--bs-primary);
                color: white;
            }

            .slider-prev {
                left: -10px;
            }

            .slider-next {
                right: -10px;
            }

            /* مؤشرات السلايدر (النقاط) */
            .testimonials-indicators {
                display: flex;
                justify-content: center;
                margin-top: 30px;
                gap: 10px;
            }

            .indicator {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background-color: #ccc;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .indicator.active {
                background-color: var(--bs-primary);
                width: 25px;
                border-radius: 10px;
            }

            /* تصميمات للشاشات المختلفة */
            @media (max-width: 991px) {
                .testimonial-slide {
                    min-width: calc(50% - 20px);
                }

                .slider-prev {
                    left: 10px;
                }

                .slider-next {
                    right: 10px;

                }
            }

            @media (max-width: 767px) {
                .testimonial-slide {
                    min-width: calc(100% - 20px);
                }

                .testimonials-track {
                    gap: 15px;
                }

                .slider-prev {
                    left: 5px;
                }

                .slider-next {
                    right: 5px;

                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Function to load doctors based on filters
                function loadDoctors(governorateId = '', cityId = '', categoryId = '') {
                    const doctors = @json($doctors);
                    const doctorsSelect = $('#doctors');

                    // Clear previous options
                    doctorsSelect.empty().append('<option value="">اختر الطبيب</option>');

                    let filteredDoctors = doctors;

                    // Filter by category (specialty) first
                    if (categoryId) {
                        filteredDoctors = filteredDoctors.filter(doctor => {
                            return doctor.category_id == categoryId;
                        });
                    }

                    // Filter by governorate
                    if (governorateId) {
                        filteredDoctors = filteredDoctors.filter(doctor => {
                            return doctor.governorate_id == governorateId;
                        });
                    }

                    // Filter by city (independent of governorate selection)
                    if (cityId) {
                        filteredDoctors = filteredDoctors.filter(doctor => {
                            return doctor.city_id == cityId;
                        });
                    }

                    // Add filtered doctors to select
                    filteredDoctors.forEach(function (doctor) {
                        doctorsSelect.append(new Option(doctor.name, doctor.id));
                    });

                    // Debug info
                    console.log('Total doctors:', doctors.length);
                    console.log('Filtered doctors:', filteredDoctors.length);
                    console.log('Category ID:', categoryId);
                    console.log('Governorate ID:', governorateId);
                    console.log('City ID:', cityId);
                }

                // Function to load all cities
                function loadAllCities() {
                    const cities = @json($cities);
                    const citySelect = $('#city_id');

                    citySelect.empty().append('<option value="">اختر المدينة</option>');
                    cities.forEach(function (city) {
                        citySelect.append(new Option(city.name, city.id));
                    });
                }

                // Initial load of all cities
                loadAllCities();

                // إضافة كود التعامل مع المحافظات والمدن
                $('#governorate_id').on('change', function () {
                    const governorateId = $(this).val();
                    const governorates = @json($governorates);
                    const citySelect = $('#city_id');

                    // Clear cities dropdown
                    citySelect.empty().append('<option value="">اختر المدينة</option>');

                    if (governorateId) {
                        // Find selected governorate and load its cities
                        const selectedGovernorate = governorates.find(gov => gov.id == governorateId);
                        if (selectedGovernorate && selectedGovernorate.cities) {
                            selectedGovernorate.cities.forEach(function (city) {
                                citySelect.append(new Option(city.name, city.id));
                            });
                        }
                    } else {
                        // If no governorate selected, show all cities
                        loadAllCities();
                    }

                    // Reset city selection
                    citySelect.val('');

                    // Update doctors when governorate changes
                    loadDoctors(governorateId, '', $('#category').val());
                });

                // Update doctors when city changes
                $('#city_id').on('change', function () {
                    const governorateId = $('#governorate_id').val();
                    const cityId = $(this).val();
                    const categoryId = $('#category').val();

                    // If a city is selected, automatically update governorate if not already selected
                    if (cityId && !governorateId) {
                        const cities = @json($cities);
                        const governorates = @json($governorates);

                        // Find which governorate this city belongs to
                        let cityGovernorateId = null;
                        for (let gov of governorates) {
                            if (gov.cities && gov.cities.some(city => city.id == cityId)) {
                                cityGovernorateId = gov.id;
                                break;
                            }
                        }

                        // If we found the governorate, select it
                        if (cityGovernorateId) {
                            $('#governorate_id').val(cityGovernorateId);
                        }
                    }

                    loadDoctors(governorateId, cityId, categoryId);
                });

                // Update doctors when category (specialty) changes
                $('#category').on('change', function () {
                    const categoryId = $(this).val();
                    const governorateId = $('#governorate_id').val();
                    const cityId = $('#city_id').val();
                    loadDoctors(governorateId, cityId, categoryId);
                });

                // Initial load of all doctors
                loadDoctors('', '', '');

                // إذا كان هناك قيمة محفوظة للمحافظة، قم بتحميل مدنها
                const oldGovernorate = $('#governorate_id').val();
                if (oldGovernorate) {
                    $('#governorate_id').trigger('change');

                    // اختيار المدينة المحفوظة إن وجدت
                    const oldCity = "{{ old('city_id') }}";
                    if (oldCity) {
                        setTimeout(() => {
                            $('#city_id').val(oldCity);
                            $('#city_id').trigger('change');
                        }, 100);
                    }
                }

                // Tab switching functionality
                $('.line').on('click', function () {
                    const tabName = $(this).data('tab-name');

                    // Remove active class from all tabs
                    $('.line').removeClass('active');
                    // Add active class to clicked tab
                    $(this).addClass('active');

                    // Hide all tab contents
                    $('.tabs-container > div').hide();
                    // Show selected tab content
                    $('#' + tabName).show();
                });

                // تأثير العداد للإحصائيات
                function animateCounters() {
                    $('.counter').each(function () {
                        const $this = $(this);
                        const countTo = $this.text().replace(/\D/g, '');

                        $({ countNum: 0 }).animate({
                            countNum: countTo
                        }, {
                            duration: 2000,
                            easing: 'swing',
                            step: function () {
                                $this.text(Math.floor(this.countNum).toLocaleString() + '+');
                            }
                        });
                    });
                }

                // بدء تشغيل التأثير عند رؤية العناصر
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCounters();
                            observer.unobserve(entry.target);
                        }
                    });
                });

                $('.stats-bar').each(function () {
                    observer.observe(this);
                });

                // فلترة الأطباء بحسب التخصص
                $('.doctors-filter-buttons button').on('click', function () {
                    const filter = $(this).data('filter');

                    // تنشيط الزر المختار
                    $('.doctors-filter-buttons button').removeClass('active');
                    $(this).addClass('active');

                    if (filter === 'all') {
                        // عرض جميع الأطباء
                        $('.doctor-item').fadeIn(300);
                    } else {
                        // إخفاء جميع الأطباء
                        $('.doctor-item').hide();
                        // عرض الأطباء بحسب التخصص المختار
                        $('.filter-cat-' + filter).fadeIn(300);
                    }
                });

                // تأثير الظهور المتدرج للأطباء عند التمرير
                const doctorsObserver = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const element = entry.target;
                            element.classList.add('animate__fadeInUp', 'animate__animated');
                            doctorsObserver.unobserve(element);
                        }
                    });
                }, {
                    threshold: 0.1
                });

                $('.doctor-card').each(function () {
                    doctorsObserver.observe(this);
                });
            });

            // سكريبت السلايدر الأفقي للتقييمات
            document.addEventListener('DOMContentLoaded', function () {
                const track = document.querySelector('.testimonials-track');
                const slides = document.querySelectorAll('.testimonial-slide');
                const prevButton = document.querySelector('.slider-prev');
                const nextButton = document.querySelector('.slider-next');
                const indicators = document.querySelectorAll('.indicator');

                if (!track || slides.length === 0) return;

                let currentSlide = 0;
                let slideWidth = 0;
                let slidesToShow = 3; // عدد البطاقات المعروضة في الشاشة الكبيرة

                // تحديث عدد البطاقات المعروضة بناءً على عرض الشاشة
                function updateSlidesToShow() {
                    if (window.innerWidth < 768) {
                        slidesToShow = 1;
                    } else if (window.innerWidth < 992) {
                        slidesToShow = 2;
                    } else {
                        slidesToShow = 3;
                    }

                    slideWidth = track.clientWidth / slidesToShow;
                    slides.forEach(slide => {
                        slide.style.minWidth = `calc(${100 / slidesToShow}% - 20px)`;
                    });
                }

                // حركة السلايدر إلى الشريحة المحددة
                function moveToSlide(index) {
                    if (index < 0) {
                        index = slides.length - slidesToShow;
                    } else if (index > slides.length - slidesToShow) {
                        index = 0;
                    }

                    currentSlide = index;
                    const offset = -currentSlide * (slideWidth + 20); // إضافة الفجوة (gap)
                    track.style.transform = `translateX(${offset}px)`;

                    // تحديث مؤشرات السلايدر
                    indicators.forEach((ind, i) => {
                        ind.classList.remove('active');
                    });

                    // تنشيط المؤشر المناسب
                    const indicatorIndex = Math.floor(currentSlide % indicators.length);
                    indicators[indicatorIndex].classList.add('active');
                }

                // تحديث السلايدر عند تغيير حجم النافذة
                window.addEventListener('resize', function () {
                    updateSlidesToShow();
                    moveToSlide(currentSlide);
                });

                // تهيئة السلايدر
                updateSlidesToShow();

                // أزرار التنقل
                if (prevButton) {
                    prevButton.addEventListener('click', function () {
                        moveToSlide(currentSlide - 1);
                    });
                }

                if (nextButton) {
                    nextButton.addEventListener('click', function () {
                        moveToSlide(currentSlide + 1);
                    });
                }

                // مؤشرات السلايدر
                indicators.forEach((indicator, index) => {
                    indicator.addEventListener('click', function () {
                        moveToSlide(index);
                    });
                });

                // التنقل باستخدام السحب (للجوال)
                let isDragging = false;
                let startPosition = 0;
                let currentTranslate = 0;

                track.addEventListener('mousedown', dragStart);
                track.addEventListener('touchstart', dragStart);
                track.addEventListener('mouseup', dragEnd);
                track.addEventListener('touchend', dragEnd);
                track.addEventListener('mouseleave', dragEnd);
                track.addEventListener('mousemove', drag);
                track.addEventListener('touchmove', drag);

                function dragStart(e) {
                    isDragging = true;
                    startPosition = getPositionX(e);
                    currentTranslate = -currentSlide * (slideWidth + 20);
                }

                function drag(e) {
                    if (isDragging) {
                        const currentPosition = getPositionX(e);
                        const diff = currentPosition - startPosition;
                        const translate = currentTranslate + diff;
                        track.style.transform = `translateX(${translate}px)`;
                    }
                }

                function dragEnd(e) {
                    if (!isDragging) return;
                    isDragging = false;
                    const currentPosition = getPositionX(e);
                    const diff = currentPosition - startPosition;

                    // تحديد ما إذا كان يجب الانتقال للشريحة التالية أو السابقة
                    if (diff < -100) {
                        moveToSlide(currentSlide + 1);
                    } else if (diff > 100) {
                        moveToSlide(currentSlide - 1);
                    } else {
                        moveToSlide(currentSlide);
                    }
                }

                function getPositionX(e) {
                    return e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
                }

                // تلقائي التنقل كل 5 ثوانٍ
                setInterval(function () {
                    if (!isDragging) {
                        moveToSlide(currentSlide + 1);
                    }
                }, 5000);
            });
        </script>
    @endpush

@endsection
