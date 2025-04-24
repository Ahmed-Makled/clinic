@extends('admin::layouts.master')

@section('title', 'إضافة مريض جديد')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.patients.store') }}" method="POST" id="patientForm" class="needs-validation" novalidate>
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم المريض</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="name"
                               name="name"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text"
                               class="form-control @error('phone') is-invalid @enderror"
                               id="phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="gender" class="form-label">الجنس</label>
                        <select class="form-select @error('gender') is-invalid @enderror"
                                id="gender"
                                name="gender"
                                required>
                            <option value="">اختر الجنس</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                        </select>
                        @error('gender')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required>
                    </div>

                    <div class="mb-3">
                        <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                        <input type="date"
                               class="form-control @error('date_of_birth') is-invalid @enderror"
                               id="date_of_birth"
                               name="date_of_birth"
                               value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">العنوان</label>
                        <textarea class="form-control @error('address') is-invalid @enderror"
                                  id="address"
                                  name="address"
                                  rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">حفظ</button>
                <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    const form = $('#patientForm');
    
    form.on('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        // Password match validation
        const password = $('#password').val();
        const confirmation = $('#password_confirmation').val();
        
        if (password !== confirmation) {
            event.preventDefault();
            $('#password_confirmation').addClass('is-invalid')
                .siblings('.invalid-feedback')
                .text('كلمة المرور غير متطابقة');
        }
        
        // Phone number format validation
        const phone = $('#phone').val();
        const phoneRegex = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/;
        if (!phoneRegex.test(phone)) {
            event.preventDefault();
            $('#phone').addClass('is-invalid')
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
        
        if (password !== confirmation) {}
            $(this).addClass('is-invalid')
                .removeClass('is-valid')
                .siblings('.invalid-feedback')
                .text('كلمة المرور غير متطابقة');
        } else {
            $(this).removeClass('is-invalid')
                .addClass('is-valid');
        }
    });

    $('#phone').on('input', function() {}
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
        } else {}
            $(this).removeClass('is-invalid')
                .addClass('is-valid');
        }
    });
});
</script>
@endpush
