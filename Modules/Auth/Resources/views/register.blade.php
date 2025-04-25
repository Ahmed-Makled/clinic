@extends('layouts.app')

@section('title', 'تسجيل حساب جديد')

@push('styles')
    <style>
        .login-wrapper {
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        }

        .login-card {
            transition: all 0.3s ease;
            border-radius: 15px;
        }

        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }

        .icon-wrapper {
            width: 70px;
            height: 70px;
            background: #f8f9fa;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .icon-wrapper i {
            font-size: 2rem;
            color: #0d6efd;
        }

        .input-group {
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .form-control::placeholder {
            text-align: right;
            direction: rtl;
        }

        .form-control {
            text-align: right;
            direction: rtl;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .btn-primary {
            padding: 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
        }

        .login-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
<div class="login-wrapper">
    <div class="container mt-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0 shadow-lg login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <div class="icon-wrapper mb-3">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <h1 class="h3 mb-2">إنشاء حساب جديد 🚀</h1>
                            <p class="text-muted">قم بإنشاء حساب للوصول إلى خدماتنا</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" autocomplete="off">
                            @csrf

                            <div class="mb-4">
                                <label for="name" class="form-label fw-medium mb-2">الاسم الكامل</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-person text-muted"></i>
                                    </span>
                                    <input type="text" name="name" id="name"
                                        class="form-control border-start-0 ps-2 @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" required autofocus
                                        placeholder="أدخل اسمك الكامل">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label fw-medium mb-2">البريد الإلكتروني</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-envelope text-muted"></i>
                                    </span>
                                    <input type="email" name="email" id="email"
                                        class="form-control border-start-0 ps-2 @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required
                                        placeholder="أدخل بريدك الإلكتروني">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="phone_number" class="form-label fw-medium mb-2">رقم الهاتف</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-phone text-muted"></i>
                                    </span>
                                    <input type="tel" name="phone_number" id="phone_number"
                                        class="form-control border-start-0 ps-2 @error('phone_number') is-invalid @enderror"
                                        value="{{ old('phone_number') }}" required
                                        placeholder="أدخل رقم هاتفك">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-medium mb-2">كلمة المرور</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-lock text-muted"></i>
                                    </span>
                                    <input type="password" name="password" id="password"
                                        class="form-control border-start-0 ps-2 @error('password') is-invalid @enderror"
                                        required placeholder="أدخل كلمة المرور">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-medium mb-2">تأكيد كلمة المرور</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0">
                                        <i class="bi bi-lock-fill text-muted"></i>
                                    </span>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control border-start-0 ps-2 "
                                        required placeholder="أعد إدخال كلمة المرور">
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>إنشاء حساب
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="text-muted mb-0">
                                    لديك حساب بالفعل؟
                                    <a href="{{ route('login') }}" class="login-link">تسجيل الدخول</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> جاري التحميل...`;
        });

        // Toggle password visibility for both password fields
        const passwordInputs = ['password', 'password_confirmation'];

        passwordInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            const togglePassword = document.createElement('span');
            togglePassword.className = 'input-group-text border-start-0 cursor-pointer';
            togglePassword.innerHTML = '<i class="bi bi-eye-slash text-muted"></i>';
            togglePassword.style.cursor = 'pointer';

            input.parentElement.appendChild(togglePassword);

            togglePassword.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                togglePassword.innerHTML = `<i class="bi bi-eye${type === 'password' ? '-slash' : ''} text-muted"></i>`;
            });
        });

        // Add floating labels effect
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.closest('.input-group').classList.add('border-primary');
            });

            input.addEventListener('blur', () => {
                input.closest('.input-group').classList.remove('border-primary');
            });
        });
    });
</script>
@endpush
