<!-- Footer -->
<footer class="pt-5  border-top bg-light">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-4 col-md-6">
                <h4 class="mb-4 fw-bold">هل أنت طبيب ؟</h4>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <a href="{{ route('home') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-house-door me-2"></i>الصفحة الرئيسية
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('doctors.index') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-people me-2"></i>الأطباء
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('appointments.create') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-calendar-plus me-2"></i>إحجز دكتورك
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('contact') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-envelope me-2"></i>إتصل بنا
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="{{ route('about') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-info-circle me-2"></i>عن الموقع
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h4 class="mb-4 fw-bold">ابحث عن طريق</h4>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-tag me-2"></i>التخصص
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-geo-alt me-2"></i>المنطقة
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-shield-check me-2"></i>التأمين
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-hospital me-2"></i>المستشفى
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-building me-2"></i>المركز
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h4 class="mb-4 fw-bold">تحتاج للمساعدة ؟</h4>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <a href="{{ route('contact') }}" class="text-decoration-none text-dark">
                            <i class="bi bi-headset me-2"></i>اتصل بنا
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-file-text me-2"></i>شروط الاستخدام
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-shield-lock me-2"></i>اتفاقية الخصوصية
                        </a>
                    </li>
                    <li class="mb-3">
                        <a href="#" class="text-decoration-none text-dark">
                            <i class="bi bi-shield-lock me-2"></i>اتفاقية الخصوصية للأطباء
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row border-top pt-4 mt-4">
            <div class="col-md-6 mb-3 mb-md-0">
                <p class="mb-1 text-md-start">:Under the supervision of</p>
                <p class="mb-1 text-md-start">xxxx</p>
                <p class="text-md-start">xxxx</p>
            </div>
            <div class="col-md-6">
                <p class="text-md-start">:By</p>
                <p class="mb-1 text-md-start">xxx</p>
                <p class="mb-1 text-md-start">xxx</p>
                <p class="text-md-start">xxx</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="border-top py-4">
                    <ul class="list-inline d-flex justify-content-center mb-0">
                        <li class="list-inline-item mx-2">
                            <a href="https://www.google.com" target="_blank" class="text-decoration-none fs-4">
                                <i class="bi bi-google text-dark"></i>
                            </a>
                        </li>
                        <li class="list-inline-item mx-2">
                            <a href="https://www.facebook.com" target="_blank" class="text-decoration-none fs-4">
                                <i class="bi bi-facebook text-dark"></i>
                            </a>
                        </li>
                        <li class="list-inline-item mx-2">
                            <a href="https://www.twitter.com" target="_blank" class="text-decoration-none fs-4">
                                <i class="bi bi-twitter text-dark"></i>
                            </a>
                        </li>
                        <li class="list-inline-item mx-2">
                            <a href="https://www.youtube.com" target="_blank" class="text-decoration-none fs-4">
                                <i class="bi bi-youtube text-dark"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

@push('styles')
<style>
    footer .list-unstyled li a:hover {
        color: var(--bs-primary) !important;
    }

    footer .list-inline-item a:hover i {
        color: var(--bs-primary) !important;
        transform: translateY(-2px);
    }

    footer .list-inline-item a i {
        transition: all 0.3s ease;
    }
</style>
@endpush
