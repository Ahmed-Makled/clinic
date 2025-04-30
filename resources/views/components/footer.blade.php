<!-- Modern Footer with Three Columns -->
<footer class="footer mt-auto py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Patient Links -->
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-heading mb-4">روابط المرضى</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('appointments.index') }}">مواعيدي</a></li>
                    <li><a href="{{ route('doctors.profiles') }}">البحث عن طبيب</a></li>
                    <li><a href="{{ route('specialties.index') }}">التخصصات الطبية</a></li>
                    <li><a href="">السجل الطبي</a></li>
                    <li><a href="">التأمين الطبي</a></li>
                </ul>
            </div>

            <!-- Doctor Links -->
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-heading mb-4">روابط الأطباء</h5>
                <ul class="footer-links">
                    <li><a href="">انضم كطبيب</a></li>
                    <li><a href="">لوحة تحكم الطبيب</a></li>
                    <li><a href="">إدارة الحجوزات</a></li>
                    <li><a href="">قائمة المرضى</a></li>
                    <li><a href="">الملف الشخصي</a></li>
                </ul>
            </div>

            <!-- Help & Resources -->
            <div class="col-lg-4 col-md-12">
                <h5 class="footer-heading mb-4">المساعدة والموارد</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('about') }}">عن المنصة</a></li>
                    <li><a href="{{ route('contact') }}">اتصل بنا</a></li>
                    <li><a href="{{ route('about') }}">الأسئلة الشائعة</a></li>
                    <li><a href="{{ route('about') }}">سياسة الخصوصية</a></li>
                    <li><a href="{{ route('about') }}">الشروط والأحكام</a></li>
                </ul>

                <!-- Social Icons -->
                <div class="social-icons mt-4">
                    <h6 class="text-muted mb-3">تابعنا على</h6>
                    <div class="d-flex gap-3">
                        <a href="#" class="social-icon" title="فيسبوك">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-icon" title="تويتر">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-icon" title="انستجرام">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-icon" title="لينكد إن">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="footer-bottom text-center mt-5 pt-4 border-top">
            <p class="mb-0">© {{ date('Y') }} جميع الحقوق محفوظة</p>
        </div>
    </div>
</footer>

<style>
    .footer {
        background-color: #f8f9fa;
    }

    .footer-heading {
        color: #2d3748;
        font-weight: 600;
        font-family: "Cairo", "Tajawal", sans-serif;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 0.75rem;
    }

    .footer-links a {
        color: #4a5568;
        text-decoration: none;
        transition: color 0.2s ease;
        font-size: 0.95rem;
    }

    .footer-links a:hover {
        color: #0d6efd;
    }

    .social-icons {
        display: flex;
        flex-direction: column;
    }

    .social-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4a5568;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    .social-icon:hover {
        transform: translateY(-3px);
        color: #0d6efd;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .footer-bottom {
        color: #718096;
    }

    @media (max-width: 768px) {
        .social-icons {
            margin-top: 2rem;
        }
    }
</style>
