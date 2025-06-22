@extends('layouts.app')

@section('content')
<style>
    .contact-section {
        padding: 60px 0;
        min-height: 100vh;
        direction: rtl;
        text-align: right;
    }

    .contact-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .contact-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 15px;
    }

    .contact-subtitle {
        font-size: 1.1rem;
        color: #718096;
        max-width: 600px;
        margin: 0 auto;
    }

    .contact-form-container {
        background: white;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: #ffffff;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .form-control::placeholder {
        color: #a0aec0;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .btn-send {
        background: #3b82f6;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        width: 100%;
    }

    .btn-send:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .contact-info-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        height: fit-content;
    }

    .contact-info-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 25px;
        text-align: center;
    }

    .contact-info-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 8px;
        transition: background 0.2s ease;
        gap: 8px
    }

    .contact-info-item:hover {
        background: #f7fafc;
    }

    .contact-info-icon {
        width: 40px;
        height: 40px;
        background: #3b82f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: white;
        font-size: 16px;
        flex-shrink: 0;
    }

    .contact-info-content h6 {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 4px;
        font-size: 0.9rem;
    }

    .contact-info-content p {
        color: #718096;
        margin: 0;
        font-size: 0.9rem;
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 25px;
        padding-top: 25px;
        border-top: 1px solid #e2e8f0;
    }

    .social-link {
        width: 40px;
        height: 40px;
        background: #f7fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #718096;
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 16px;
    }

    .social-link:hover {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
        transform: translateY(-1px);
    }

    .alert {
        border: none;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }

    .alert-success {
        background: #dbeafe;
        color: #1e40af;
        border-left: 4px solid #3b82f6;
    }

    .alert-danger {
        background: #fed7d7;
        color: #742a2a;
        border-left: 4px solid #e53e3e;
    }

    .alert i {
        margin-right: 10px;
    }

    @media (max-width: 768px) {
        .contact-section {
            padding: 40px 0;
        }

        .contact-title {
            font-size: 2rem;
        }

        .contact-form-container,
        .contact-info-card {
            padding: 25px 20px;
        }

        .contact-info-card {
            margin-top: 30px;
        }
    }
</style>

<div class="contact-section mt-5 py-5">
    <div class="container">
        <!-- Header -->
        <div class="contact-header">
            <h1 class="contact-title">{{ __('اتصل بنا') }}</h1>
            <p class="contact-subtitle">{{ __('تواصل مع خبرائنا الطبيين. نحن هنا لمساعدتك في أي أسئلة أو استفسارات.') }}</p>
        </div>

        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form-container">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h3 class="mb-2" style="color: #2d3748; font-weight: 600; font-size: 1.25rem;">{{ __('أرسل لنا رسالة') }}</h3>
                        <p style="color: #718096; font-size: 0.95rem;">{{ __('املأ النموذج أدناه وسنرد عليك في أقرب وقت ممكن.') }}</p>
                    </div>

                    <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="name">{{ __('الاسم') }}</label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('أدخل اسمك') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="email">{{ __('البريد الإلكتروني') }}</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('أدخل بريدك الإلكتروني') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="subject">{{ __('الموضوع') }}</label>
                            <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="{{ __('أدخل الموضوع') }}">
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="message">{{ __('رسالتك') }}</label>
                            <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" placeholder="{{ __('أدخل رسالتك') }}">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-send">
                            <i class="bi bi-send me-2"></i>
                            {{ __('إرسال الرسالة') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="contact-info-card">
                    <h4 class="contact-info-title">{{ __('معلومات الاتصال') }}</h4>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="contact-info-content">
                            <h6>{{ __('العنوان') }}</h6>
                            <p>{{ __('123 شارع رئيسي، القاهرة، مصر') }}</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="contact-info-content">
                            <h6>{{ __('الهاتف') }}</h6>
                            <p>{{ __('+20 123 456 789') }}</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="contact-info-content">
                            <h6>{{ __('البريد الإلكتروني') }}</h6>
                            <p>{{ __('info@clinic.com') }}</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="contact-info-content">
                            <h6>{{ __('ساعات العمل') }}</h6>
                            <p>{{ __('الاثنين - الجمعة: 9:00 صباحًا - 6:00 مساءً') }}</p>
                        </div>
                    </div>

                    <div class="social-links">
                        <a href="#" class="social-link" title="Facebook">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="social-link" title="Twitter">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="social-link" title="Instagram">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="social-link" title="LinkedIn">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
