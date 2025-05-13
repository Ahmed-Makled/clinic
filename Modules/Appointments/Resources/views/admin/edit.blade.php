@extends('layouts.admin')

@section('title', 'تعديل حجز')

@section('header_icon')
<i class="bi bi-calendar2-check text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('appointments.index') }}" class="text-decoration-none">الحجوزات</a>
</li>
<li class="breadcrumb-item active">تعديل حجز</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card shadow-sm">
        <div class="card-header border-bottom py-3">
            <h5 class="mb-0">تعديل الحجز</h5>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="doctor_id" class="form-label">الطبيب <span class="text-danger">*</span></label>
                        <select class="form-select @error('doctor_id') is-invalid @enderror"
                                id="doctor_id"
                                name="doctor_id"
                                required>
                            <option value="">اختر الطبيب</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">يرجى اختيار الطبيب</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="patient_id" class="form-label">المريض <span class="text-danger">*</span></label>
                        <select class="form-select @error('patient_id') is-invalid @enderror"
                                id="patient_id"
                                name="patient_id"
                                required>
                            <option value="">اختر المريض</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">يرجى اختيار المريض</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="appointment_date" class="form-label">تاريخ الحجز <span class="text-danger">*</span></label>
                        <input type="date"
                               class="form-control @error('appointment_date') is-invalid @enderror"
                               id="appointment_date"
                               name="appointment_date"
                               value="{{ old('appointment_date', $appointment->scheduled_at->format('Y-m-d')) }}"
                               required>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">يرجى اختيار تاريخ الحجز</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="appointment_time" class="form-label">وقت الحجز <span class="text-danger">*</span></label>
                        <select class="form-select @error('appointment_time') is-invalid @enderror"
                                id="appointment_time"
                                name="appointment_time"
                                required>
                            <option value="">اختر الوقت</option>
                            <div id="time-slots-container">
                                <!-- سيتم تحديث الأوقات المتاحة بناءً على اختيار الطبيب والتاريخ -->
                                <!-- Current appointment time is pre-selected -->
                                <option value="{{ $appointment->scheduled_at->format('H:i') }}" selected>
                                    {{ $appointment->scheduled_at->format('H:i') }}
                                </option>
                            </div>
                        </select>
                        @error('appointment_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">يرجى اختيار وقت الحجز</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
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
                        @else
                            <div class="invalid-feedback">يرجى اختيار الحالة</div>
                        @enderror
                    </div>


                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="payment-status-info">
                                <span class="{{ $appointment->is_paid ? 'text-success' : 'text-warning' }}">
                                    @if($appointment->is_paid)
                                        <i class="bi bi-check-circle-fill me-1"></i> تم الدفع 
                                    @else
                                        <i class="bi bi-exclamation-circle me-1"></i> لم يتم الدفع
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox"
                                       class="form-check-input"
                                       id="is_important"
                                       name="is_important"
                                       value="1"
                                       {{ old('is_important', $appointment->is_important) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_important">حجز مهم</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes"
                                  name="notes"
                                  rows="3">{{ old('notes', $appointment->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3">
                        <small class="text-muted"><span class="text-danger">*</span>سيتم تحديد رسوم الكشف تلقائياً حسب سعر الكشف المحدد للطبيب</small>

                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> حفظ التغييرات
                    </button>
                    <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-lg me-1"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const requiredFields = form.querySelectorAll('select[required], input[required]');

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Real-time validation for required fields
    requiredFields.forEach(element => {
        element.addEventListener('change', function() {
            this.classList.remove('is-invalid', 'is-valid');
            if (this.value) {
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
            }
        });
    });
});
</script>
@endpush

@endsection
