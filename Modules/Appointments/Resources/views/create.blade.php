@extends('layouts.admin')

@section('title', 'إضافة موعد جديد')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-light py-3">
            <h5 class="card-title mb-0 fw-bold">إضافة موعد جديد</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('appointments.store') }}" class="row g-3 needs-validation" novalidate>
                @csrf

                <div class="col-md-6">
                    <label for="doctor_id" class="form-label fw-medium">الطبيب <span class="text-danger">*</span></label>
                    <select class="form-select @error('doctor_id') is-invalid @enderror"
                            id="doctor_id"
                            name="doctor_id"
                            required>
                        <option value="">اختر الطبيب</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="patient_id" class="form-label fw-medium">المريض <span class="text-danger">*</span></label>
                    <select class="form-select @error('patient_id') is-invalid @enderror"
                            id="patient_id"
                            name="patient_id"
                            required>
                        <option value="">اختر المريض</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="scheduled_at" class="form-label fw-medium">موعد الحجز <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                        <input type="datetime-local"
                               class="form-control @error('scheduled_at') is-invalid @enderror"
                               id="scheduled_at"
                               name="scheduled_at"
                               value="{{ old('scheduled_at') }}"
                               required>
                        @error('scheduled_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label fw-medium">الحالة <span class="text-danger">*</span></label>
                    <select class="form-select @error('status') is-invalid @enderror"
                            id="status"
                            name="status"
                            required>
                        <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="status" class="form-label">حالة الموعد</label>
                    <select name="status" id="status" class="form-select" data-icon="bi-clock-history" data-color="#0d6efd">
                        <option value="pending" data-icon="bi-clock">قيد الانتظار</option>
                        <option value="confirmed" data-icon="bi-check-circle">مؤكد</option>
                        <option value="completed" data-icon="bi-check-circle-fill">مكتمل</option>
                        <option value="cancelled" data-icon="bi-x-circle">ملغي</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="payment_status" class="form-label">حالة الدفع</label>
                    <select name="payment_status" id="payment_status" class="form-select" data-icon="bi-credit-card" data-color="#198754">
                        <option value="pending" data-icon="bi-hourglass">قيد الانتظار</option>
                        <option value="paid" data-icon="bi-check2-circle">مدفوع</option>
                        <option value="refunded" data-icon="bi-arrow-return-left">مسترجع</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="payment_method" class="form-label">طريقة الدفع</label>
                    <select name="payment_method" id="payment_method" class="form-select" data-icon="bi-wallet2" data-color="#6c757d">
                        <option value="cash" data-icon="bi-cash">نقداً</option>
                        <option value="card" data-icon="bi-credit-card">بطاقة ائتمان</option>
                        <option value="wallet" data-icon="bi-phone">محفظة إلكترونية</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="fees" class="form-label fw-medium">رسوم الكشف</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                        <input type="number"
                               class="form-control @error('fees') is-invalid @enderror"
                               id="fees"
                               name="fees"
                               value="{{ old('fees') }}"
                               min="0"
                               step="0.01"
                               placeholder="أدخل المبلغ">
                        <span class="input-group-text">جنيه</span>
                        @error('fees')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-medium">خيارات إضافية</label>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       role="switch"
                                       id="is_paid"
                                       name="is_paid"
                                       value="1"
                                       {{ old('is_paid') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_paid">تم الدفع</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input"
                                       type="checkbox"
                                       role="switch"
                                       id="is_important"
                                       name="is_important"
                                       value="1"
                                       {{ old('is_important') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_important">موعد مهم</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label fw-medium">ملاحظات</label>
                    <textarea class="form-control @error('notes') is-invalid @enderror"
                              id="notes"
                              name="notes"
                              rows="3"
                              placeholder="أدخل أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-2"></i>حفظ
                    </button>
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary px-4">
                        <i class="bi bi-x-lg me-2"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for better select boxes
    $('#doctor_id, #patient_id').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Enable form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endpush

