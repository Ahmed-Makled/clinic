@extends('layouts.admin')

@section('title', 'إضافة حجز جديد')

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
<li class="breadcrumb-item active">حجز جديد</li>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card shadow-sm">
        <div class="card-header border-bottom py-3">
            <h5 class="mb-0">إضافة حجز جديد</h5>
        </div>
        <div class="card-body">

            <form action="{{ route('appointments.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="doctor_id" class="form-label">الطبيب <span class="text-danger">*</span></label>
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
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
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
                               value="{{ old('appointment_date') }}"
                               min="{{ date('Y-m-d') }}"
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
                            </div>
                        </select>
                        @error('appointment_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">يرجى اختيار وقت الحجز</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label for="notes" class="form-label">ملاحظات</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes"
                                  name="notes"
                                  rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <small class="text-muted"><span class="text-danger">*</span>سيتم تحديد رسوم الكشف تلقائياً حسب سعر الكشف المحدد للطبيب</small>

                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> حفظ
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
    const doctorSelect = document.getElementById('doctor_id');
    const patientSelect = document.getElementById('patient_id');
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time');

    // Function to load available time slots
    function loadAvailableTimeSlots() {
        const doctorId = doctorSelect.value;
        const appointmentDate = dateInput.value;

        // Only proceed if both doctor and date are selected
        if (!doctorId || !appointmentDate) {
            return;
        }

        // Clear existing options except the default one
        while (timeSelect.options.length > 1) {
            timeSelect.remove(1);
        }

        // Add loading option
        const loadingOption = document.createElement('option');
        loadingOption.textContent = 'جاري تحميل الأوقات المتاحة...';
        timeSelect.appendChild(loadingOption);

        // Fetch available slots using AJAX
        fetch(`/appointments/available-slots?doctor_id=${doctorId}&date=${appointmentDate}`)
            .then(response => response.json())
            .then(data => {
                // Remove loading option
                timeSelect.remove(timeSelect.options.length - 1);

                if (data.slots && data.slots.length > 0) {
                    // Add each available slot as an option
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSelect.appendChild(option);
                    });
                } else {
                    // No slots available
                    const noSlotsOption = document.createElement('option');
                    noSlotsOption.textContent = 'لا توجد أوقات متاحة في هذا اليوم';
                    noSlotsOption.disabled = true;
                    timeSelect.appendChild(noSlotsOption);
                }
            })
            .catch(error => {
                console.error('Error fetching time slots:', error);
                timeSelect.remove(timeSelect.options.length - 1);

                const errorOption = document.createElement('option');
                errorOption.textContent = 'حدث خطأ أثناء تحميل الأوقات المتاحة';
                errorOption.disabled = true;
                timeSelect.appendChild(errorOption);
            });
    }

    // Event listeners for doctor and date changes
    doctorSelect.addEventListener('change', loadAvailableTimeSlots);
    dateInput.addEventListener('change', loadAvailableTimeSlots);

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });

    // Real-time validation
    [doctorSelect, patientSelect, dateInput, timeSelect].forEach(element => {
        element.addEventListener('change', function() {
            this.classList.remove('is-invalid', 'is-valid');
            if (this.value) {
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
            }
        });
    });

    // If doctor and date are already selected on page load (e.g. after validation error)
    if (doctorSelect.value && dateInput.value) {
        loadAvailableTimeSlots();
    }
});
</script>
@endpush

@endsection

