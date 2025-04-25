@extends('layouts.admin')

@section('title', 'تعديل موعد')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-light py-3">
            <h5 class="card-title mb-0 fw-bold">تعديل موعد</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('appointments.update', $appointment) }}" class="row g-3 needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label for="doctor_id" class="form-label fw-medium">الطبيب <span class="text-danger">*</span></label>
                    <select class="form-select @error('doctor_id') is-invalid @enderror"
                            id="doctor_id"
                            name="doctor_id"
                            required>
                        <option value="">اختر الطبيب</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}"
                                {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
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
                            <option value="{{ $patient->id }}"
                                {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
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
                               value="{{ old('scheduled_at', $appointment->scheduled_at->format('Y-m-d\TH:i')) }}"
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
                        <option value="scheduled" {{ old('status', $appointment->status) == 'scheduled' ? 'selected' : '' }}>
                            قيد الانتظار
                        </option>
                        <option value="completed" {{ old('status', $appointment->status) == 'completed' ? 'selected' : '' }}>
                            مكتمل
                        </option>
                        <option value="cancelled" {{ old('status', $appointment->status) == 'cancelled' ? 'selected' : '' }}>
                            ملغي
                        </option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="fees" class="form-label fw-medium">رسوم الكشف</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                        <input type="number"
                               class="form-control @error('fees') is-invalid @enderror"
                               id="fees"
                               name="fees"
                               value="{{ old('fees', $appointment->fees) }}"
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
                                       {{ old('is_paid', $appointment->is_paid) ? 'checked' : '' }}>
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
                                       {{ old('is_important', $appointment->is_important) ? 'checked' : '' }}>
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
                              placeholder="أدخل أي ملاحظات إضافية">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-2"></i>حفظ التغييرات
                    </button>
                    <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-secondary px-4">
                        <i class="bi bi-x-lg me-2"></i>إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

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

@push('styles')
<style>
    .form-select, .form-control {
        padding: 0.6rem 0.75rem;
    }

    .input-group-text {
        padding: 0.6rem 1rem;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: calc(3.5rem + 2px);
        padding: 1rem 0.75rem;
    }
</style>
@endpush

@endsection
