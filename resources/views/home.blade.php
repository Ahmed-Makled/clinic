@extends('layouts.app')

@section('content')
    <header class="header position-relative vh-100">
        <div class="overlay position-absolute w-100 h-100 d-flex align-items-center">
            <div class="container mt-4 py-5 text-white">
                <div class="row justify-content-center">
                    <div class="col-md-9 text-center">
                        <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">
                            اسهل طريقة لحجز احسن واكبر
                            <span class="text-primary position-relative text-glow">دكاترة</span>
                            في مصر
                        </h1>
                        <p class="lead mb-5 animate__animated animate__fadeInUp opacity-90">احجز أونلاين أو كلم 77777</p>
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
                    <div class="d-flex">
                        <div class="w-50 pt-4">
                            <div class="line text-center fw-bold pb-4 cursor-pointer transition" data-tab-name="call">
                                <span>
                                    <i class="bi bi-telephone-fill fs-4 d-block mb-2 text-primary"></i>
                                    مكالمة دكتور<br>
                                    <small class="text-muted">كشف عبر مكاملة مع الدكتور</small>
                                </span>
                            </div>
                        </div>
                        <div class="w-50 pt-4">
                            <div class="line active text-center fw-bold pb-4 cursor-pointer transition"
                                data-tab-name="reserve">
                                <span>
                                    <i class="bi bi-calendar-plus-fill fs-4 d-block mb-2 text-primary"></i>
                                    احجز دكتور<br>
                                    <small class="text-muted">الفحص او الاجراء</small>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="tabs-container">
                        <div class="p-5 text-center" style="display: none" id="call">
                            <h4 class="mb-4">مكالمة مع دكتور</h4>
                            <button class="btn btn-primary btn-lg rounded-pill px-5">
                                <i class="bi bi-telephone-fill me-2"></i> اتصل الآن
                            </button>
                        </div>

                        <div class="p-4" id="reserve">
                            <form id="search" action="{{ route('search') }}" method="GET">
                                <div class="row g-4">
                                    <div class="col-md">
                                        <div class="border rounded p-3 h-100 search-box">
                                            <label class="mb-3 text-muted">
                                                <i class="bi bi-search me-2"></i>أنا أبحث عن طبيب
                                            </label>
                                            <select name="category" id="category" class="form-select form-select-lg">
                                                <option value="">إختر التخصص</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md">
                                        <div class="border rounded p-3 h-100 search-box">
                                            <label class="mb-3 text-muted">
                                                <i class="bi bi-geo-alt me-2"></i>فى محافظة
                                            </label>
                                            <select name="governorate_id" id="governorate_id"
                                                class="form-select form-select-lg">
                                                <option value="">اختر المحافظة</option>
                                                @foreach($governorates as $governorate)
                                                    <option value="{{ $governorate->id }}" data-icon="bi-pin-map" {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>
                                                        {{ $governorate->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="border rounded p-3 h-100 search-box">
                                            <label class="mb-3 text-muted">
                                                <i class="bi bi-pin-map me-2"></i>المدينة
                                            </label>
                                            <select name="city_id" id="city_id" class="form-select form-select-lg">
                                                <option value="">اختر المدينة</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="border rounded p-3 h-100 search-box">
                                            <label class="mb-3 text-muted">
                                                <i class="bi bi-person-vcard me-2"></i>الأطباء
                                            </label>
                                            <select name="doctors" id="doctors" class="form-select form-select-lg">
                                                <option value="">إختر الطبيب</option>
                                                @foreach($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-auto d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary btn-lg px-5 h-75">
                                            بحث <i class="bi bi-search ms-2"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">إزاى تحجز معانا</h2>
                <p class="text-muted w-75 mx-auto">اتبع الخطوات البسيطة دي عشان تحجز مع افضل دكتور</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-0 text-center hover-scale transition p-4">
                        <div class="icon-box mb-4 mx-auto">
                            <i class="bi bi-search text-primary display-4"></i>
                        </div>
                        <h4>إبحث على دكتور</h4>
                        <p class="text-muted">بالتخصص و المنطقة و التامين و سعر الكشف</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 text-center hover-scale transition p-4">
                        <div class="icon-box mb-4 mx-auto">
                            <i class="bi bi-star text-primary display-4"></i>
                        </div>
                        <h4>قارن واختار</h4>
                        <p class="text-muted">شوف تقييمات المرضى واختار الدكتور المناسب</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card border-0 text-center hover-scale transition p-4">
                        <div class="icon-box mb-4 mx-auto">
                            <i class="bi bi-calendar-check text-primary display-4"></i>
                        </div>
                        <h4>احجز موعدك</h4>
                        <p class="text-muted">احجز ميعادك اونلاين وهنأكدلك الحجز</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features bg-light py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">ليه تختار منصتنا؟</h2>
                <p class="text-muted w-75 mx-auto">نقدملك خدمة طبية متكاملة وسهلة</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-4">
                        <div class="icon-circle mb-4 mx-auto">
                            <i class="bi bi-shield-check text-primary"></i>
                        </div>
                        <h5>دكاترة معتمدين</h5>
                        <p class="text-muted small">كل دكاترنا معتمدين ومرخصين</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-4">
                        <div class="icon-circle mb-4 mx-auto">
                            <i class="bi bi-clock-history text-primary"></i>
                        </div>
                        <h5>حجز سريع</h5>
                        <p class="text-muted small">احجز ميعادك في دقايق</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-4">
                        <div class="icon-circle mb-4 mx-auto">
                            <i class="bi bi-geo-alt text-primary"></i>
                        </div>
                        <h5>عيادات في كل مكان</h5>
                        <p class="text-muted small">عيادات في كل محافظات مصر</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card text-center p-4">
                        <div class="icon-circle mb-4 mx-auto">
                            <i class="bi bi-headset text-primary"></i>
                        </div>
                        <h5>دعم 24/7</h5>
                        <p class="text-muted small">فريق دعم متواجد على مدار اليوم</p>
                    </div>
                </div>
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
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Function to load doctors based on filters
                function loadDoctors(governorateId = '', cityId = '') {
                    const doctors = @json($doctors);
                    const doctorsSelect = $('#doctors');

                    doctorsSelect.empty().append('<option value="">إختر الطبيب</option>');

                    let filteredDoctors = doctors;

                    if (governorateId) {
                        filteredDoctors = doctors.filter(doctor => doctor.governorate_id == governorateId);
                        if (cityId) {
                            filteredDoctors = filteredDoctors.filter(doctor => doctor.city_id == cityId);
                        }
                    }

                    filteredDoctors.forEach(function (doctor) {
                        doctorsSelect.append(new Option(doctor.name, doctor.id));
                    });
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
                    const cities = @json($governorates->pluck('cities', 'id'));
                    const citySelect = $('#city_id');

                    if (!governorateId) {
                        // If no governorate is selected, show all cities
                        loadAllCities();
                    } else {
                        // If governorate is selected, show only its cities
                        citySelect.empty().append('<option value="">اختر المدينة</option>');
                        if (cities[governorateId]) {
                            cities[governorateId].forEach(function (city) {
                                citySelect.append(new Option(city.name, city.id));
                            });
                        }
                    }

                    // Update doctors when governorate changes
                    loadDoctors(governorateId);
                });

                // Update doctors when city changes
                $('#city_id').on('change', function () {
                    const governorateId = $('#governorate_id').val();
                    const cityId = $(this).val();
                    loadDoctors(governorateId, cityId);
                });

                // Initial load of all doctors
                loadDoctors();

                // إذا كان هناك قيمة محفوظة للمحافظة، قم بتحميل مدنها
                const oldGovernorate = $('#governorate_id').val();
                if (oldGovernorate) {
                    $('#governorate_id').trigger('change');

                    // اختيار المدينة المحفوظة إن وجدت
                    const oldCity = "{{ old('city_id') }}";
                    if (oldCity) {
                        $('#city_id').val(oldCity);
                        $('#city_id').trigger('change');
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
            });
        </script>
    @endpush

@endsection
