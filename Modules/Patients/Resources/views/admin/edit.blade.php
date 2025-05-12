@extends('layouts.admin')

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
<li class="breadcrumb-item active">تعديل مريض</li>
@endsection

@section('title', 'تعديل بيانات المريض')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="card shadow-sm rounded-4">
                <div class="card-header border-bottom py-3 mb-4">
                    <h5 class="mb-0 ms-2">تعديل بيانات المريض</h5>
                </div>
                <div class="card-body px-4 py-3">
                    <form method="POST" action="{{ route('patients.update', $patient) }}" class="needs-validation" novalidate>
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
                                           >
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
                                           >
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
                                           >
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">الجنس *</label>
                                    <select class="form-select @error('gender') is-invalid @enderror"
                                            id="gender"
                                            name="gender"
                                            >
                                        <option value="">اختر الجنس</option>
                                        <option value="male" {{ old('gender', optional($patient->patient)->gender) == 'male' ? 'selected' : '' }}>ذكر</option>
                                        <option value="female" {{ old('gender', optional($patient->patient)->gender) == 'female' ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                                    <div class="d-flex gap-2">
                                        <input type="date"
                                               class="form-control @error('date_of_birth') is-invalid @enderror"
                                               id="date_of_birth"
                                               name="date_of_birth"
                                               value="{{ old('date_of_birth', (optional($patient->patient)->date_of_birth ? $patient->patient->date_of_birth->format('Y-m-d') : '')) }}">
                                    </div>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">العنوان</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address"
                                              name="address"
                                              rows="3">{{ old('address', optional($patient->patient)->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">الحالة</label>
                                    <div class="form-check form-switch mt-2">
                                        <input class="form-check-input" type="checkbox" name="status" id="status"
                                            {{ old('status', $patient->status) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">نشط</label>
                                    </div>
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
                                                    rows="3">{{ old('medical_history', optional($patient->patient)->medical_history) }}</textarea>
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
                                                <option value="A+" {{ old('blood_type', optional($patient->patient)->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                                <option value="A-" {{ old('blood_type', optional($patient->patient)->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                                <option value="B+" {{ old('blood_type', optional($patient->patient)->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                                <option value="B-" {{ old('blood_type', optional($patient->patient)->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                                <option value="AB+" {{ old('blood_type', optional($patient->patient)->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                                <option value="AB-" {{ old('blood_type', optional($patient->patient)->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                                <option value="O+" {{ old('blood_type', optional($patient->patient)->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                                <option value="O-" {{ old('blood_type', optional($patient->patient)->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
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
                                                   value="{{ old('emergency_contact', optional($patient->patient)->emergency_contact) }}">
                                            @error('emergency_contact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="allergies" class="form-label">الحساسية</label>
                                            <textarea class="form-control @error('allergies') is-invalid @enderror"
                                                    id="allergies"
                                                    name="allergies"
                                                    rows="3">{{ old('allergies', optional($patient->patient)->allergies) }}</textarea>
                                            @error('allergies')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
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

@push('scripts')
<script>
$(document).ready(function() {
    const form = $('form');

    form.on('submit', function(event) {
        if (!this.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
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
