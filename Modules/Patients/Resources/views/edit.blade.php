@extends('layouts.admin')

@section('title', 'تعديل بيانات المريض')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm rounded-4">
                <div class="card-header border-bottom py-3 mb-4">
                    <h5 class="mb-0 ms-2">تعديل بيانات المريض</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form method="POST" action="{{ route('admin.patients.update', $patient) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المريض *</label>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           id="name"
                                           name="name"
                                           value="{{ old('name', $patient->name) }}"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني *</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email', $patient->email) }}"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">رقم الهاتف *</label>
                                    <input type="text"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           id="phone_number"
                                           name="phone_number"
                                           value="{{ old('phone_number', $patient->phone_number) }}"
                                           required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password">
                                    <small class="text-muted">اتركه فارغاً إذا كنت لا تريد تغيير كلمة المرور</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">الجنس *</label>
                                    <select class="form-select @error('gender') is-invalid @enderror"
                                            id="gender"
                                            name="gender"
                                            required>
                                        <option value="">اختر الجنس</option>
                                        <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                                    <input type="date"
                                           class="form-control @error('date_of_birth') is-invalid @enderror"
                                           id="date_of_birth"
                                           name="date_of_birth"
                                           value="{{ old('date_of_birth', $patient->date_of_birth ? date('Y-m-d', strtotime($patient->date_of_birth)) : '') }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">العنوان</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address"
                                              name="address"
                                              rows="3">{{ old('address', $patient->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary ms-1">حفظ التغييرات</button>
                            <a href="{{ route('patients.index') }}" class="btn btn-label-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@push('styles')
<style>
    .card {
        background: #fff;
        transition: all 0.3s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.08) !important;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #566a7f;
    }

    .form-control,
    .form-select {
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #d9dee3;
        background-color: #fff;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0.25rem rgba(67, 94, 190, 0.1);
    }

    .btn {
        padding: 0.6rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #364b98;
        border-color: #364b98;
        transform: translateY(-1px);
        box-shadow: 0 0.125rem 0.25rem rgba(67, 94, 190, 0.3);
    }

    .btn-label-secondary {
        color: #8592a3;
        border: 1px solid #8592a3;
        background: transparent;
    }

    .btn-label-secondary:hover {
        background-color: #8592a3;
        color: #fff;
    }

    .mb-3 {
        margin-bottom: 1.5rem !important;
    }

    .text-muted {
        color: #97a3af !important;
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    const form = $('form');

    form.on('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        // Optional password match validation (only if password is being changed)
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();

        if (password && password !== confirmation) {
            event.preventDefault();
            $('#password_confirmation').addClass('is-invalid')
                .siblings('.invalid-feedback')
                .text('كلمة المرور غير متطابقة');
        }

        // Phone number format validation
        const phone = $('#phone_number').val();
        const phoneRegex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
        if (!phoneRegex.test(phone)) {
            event.preventDefault();
            $('#phone_number').addClass('is-invalid')
                .siblings('.invalid-feedback')
                .text('رقم الهاتف غير صالح');
        }

        // Email format validation
        const email = $('#email').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            event.preventDefault();
            $('#email').addClass('is-invalid')
                .siblings('.invalid-feedback')
                .text('البريد الإلكتروني غير صالح');
        }

        $(this).addClass('was-validated');
    });

    // Real-time validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const confirmation = $(this).val();

        if (password && password !== confirmation) {
            $(this).addClass('is-invalid')
                .removeClass('is-valid')
                .siblings('.invalid-feedback')
                .text('كلمة المرور غير متطابقة');
        } else {
            $(this).removeClass('is-invalid')
                .addClass('is-valid');
        }
    });

    $('#phone_number').on('input', function() {
        const phone = $(this).val();
        const phoneRegex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;

        if (!phoneRegex.test(phone)) {
            $(this).addClass('is-invalid')
                .removeClass('is-valid')
                .siblings('.invalid-feedback')
                .text('رقم الهاتف غير صالح');
        } else {
            $(this).removeClass('is-invalid')
                .addClass('is-valid');
        }
    });

    $('#email').on('input', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email)) {
            $(this).addClass('is-invalid')
                .removeClass('is-valid')
                .siblings('.invalid-feedback')
                .text('البريد الإلكتروني غير صالح');
        } else {
            $(this).removeClass('is-invalid')
                .addClass('is-valid');
        }
    });
});
</script>
@endpush
@endsection
