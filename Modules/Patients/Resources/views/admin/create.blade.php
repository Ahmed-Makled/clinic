@extends('layouts.admin')

@section('title', 'إضافة مريض جديد')

@section('header_icon')
<i class="bi bi-person-vcard text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('patients.index') }}" class="text-decoration-none">المرضى</a>
</li>
<li class="breadcrumb-item active">إضافة مريض</li>
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm rounded-4">
                <div class="card-header border-bottom py-3 mb-4">
                    <h5 class="mb-0 ms-2">إضافة مريض جديد</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form method="POST" action="{{ route('patients.store') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم المريض *</label>
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
                                    <label for="email" class="form-label">البريد الإلكتروني *</label>
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
                                    <label for="phone_number" class="form-label">رقم الهاتف *</label>
                                    <input type="text"
                                           class="form-control @error('phone_number') is-invalid @enderror"
                                           id="phone_number"
                                           name="phone_number"
                                           value="{{ old('phone_number') }}"
                                           required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور *</label>
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
                                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور *</label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           required>
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
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
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

                        <!-- بيانات طبية إضافية -->
                        <div class="card mt-4 mb-3">
                            <div class="card-header py-2 bg-light">
                                <h6 class="mb-0">المعلومات الطبية</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="medical_history" class="form-label">التاريخ الطبي</label>
                                            <textarea class="form-control @error('medical_history') is-invalid @enderror"
                                                    id="medical_history"
                                                    name="medical_history"
                                                    rows="3">{{ old('medical_history') }}</textarea>
                                            @error('medical_history')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="blood_type" class="form-label">فصيلة الدم</label>
                                            <select class="form-select @error('blood_type') is-invalid @enderror"
                                                    id="blood_type"
                                                    name="blood_type">
                                                <option value="">اختر فصيلة الدم</option>
                                                <option value="A+" {{ old('blood_type') == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('blood_type') == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('blood_type') == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('blood_type') == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ old('blood_type') == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('blood_type') == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ old('blood_type') == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('blood_type') == 'O-' ? 'selected' : '' }}>O-</option>
                                            </select>
                                            @error('blood_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="emergency_contact" class="form-label">رقم الاتصال في حالات الطوارئ</label>
                                            <input type="text"
                                                   class="form-control @error('emergency_contact') is-invalid @enderror"
                                                   id="emergency_contact"
                                                   name="emergency_contact"
                                                   value="{{ old('emergency_contact') }}">
                                            @error('emergency_contact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="allergies" class="form-label">الحساسية</label>
                                            <textarea class="form-control @error('allergies') is-invalid @enderror"
                                                    id="allergies"
                                                    name="allergies"
                                                    rows="3">{{ old('allergies') }}</textarea>
                                            @error('allergies')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary ms-1">حفظ</button>
                            <a href="{{ route('patients.index') }}" class="btn btn-label-secondary">إلغاء</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@push('scripts')
<script>
$(document).ready(function() {
    const form = $('form');

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

        if (password !== confirmation) {
            $(this).addClass('is-invalid')
                .removeClass('is-valid')
                .siblings('.invalid-feedback')
                .text('كلمة المرور غير متطابقة');
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
