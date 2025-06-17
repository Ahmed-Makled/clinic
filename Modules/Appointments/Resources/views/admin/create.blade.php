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

            <form action="{{ route('appointments.store') }}" method="POST" class="needs-validation appointment-form" novalidate>
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
                               placeholder="اختر تاريخ الحجز"
                               disabled
                               required>
                        <div id="doctor-required-message" class="mt-2 small text-warning">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            يرجى اختيار الطبيب أولاً
                        </div>
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @else
                            <div class="invalid-feedback">يرجى اختيار تاريخ الحجز</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="appointment_time_select" class="form-label">وقت الحجز <span class="text-danger">*</span></label>
                        <select class="form-select @error('appointment_time') is-invalid @enderror"
                                id="appointment_time_select"
                                disabled>
                            <option value="">اختر وقت الحجز</option>
                        </select>
                        <div id="time-loading-indicator" class="mt-2 d-none">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                                <span class="visually-hidden">جاري التحميل...</span>
                            </div>
                            <small class="text-muted">جاري تحميل الأوقات المتاحة...</small>
                        </div>
                        <div id="time-required-message" class="mt-2 small text-muted">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            يرجى اختيار التاريخ أولاً لعرض الأوقات المتاحة
                        </div>
                        <input type="hidden"
                               id="appointment_time"
                               name="appointment_time"
                               value="{{ old('appointment_time') }}"
                               required>
                        @error('appointment_time')
                            <div class="text-danger mt-2 small">{{ $message }}</div>
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

@push('styles')
<style>
    .appointment-form .form-control:disabled,
    .appointment-form .form-select:disabled {
        background-color: #f8f9fa;
        cursor: not-allowed;
    }

    .appointment-alert {
        font-size: 0.875rem;
        padding: 8px 0;
        display: flex;
        align-items: center;
    }

    /* تأثير الوميض لحقل التاريخ */
    @keyframes highlight-pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }

    .highlight-input {
        animation: highlight-pulse 1.5s ease-out;
        border-color: #ffc107 !important;
        background-color: rgba(255, 248, 230, 0.5) !important;
    }

    /* تنسيق زر اختيار يوم آخر */
    #try-another-day-btn:hover {
        background-color: #f8f9fa;
        border-color: #6c757d;
    }

    /* تنسيق لتنبيه عدم وجود أوقات متاحة */
    #no-slots-alert {
        border-right: 4px solid #ffc107;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.classList.add('appointment-form');

    const doctorSelect = document.getElementById('doctor_id');
    const patientSelect = document.getElementById('patient_id');
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time_select');
    const timeInput = document.getElementById('appointment_time');
    const doctorRequiredMessage = document.getElementById('doctor-required-message');
    const timeRequiredMessage = document.getElementById('time-required-message');
    const timeLoadingIndicator = document.getElementById('time-loading-indicator');

    // تحميل الأوقات المتاحة
    function loadAvailableTimeSlots() {
        const doctorId = doctorSelect.value;
        const appointmentDate = dateInput.value;

        if (!doctorId || !appointmentDate) {
            resetTimeField();
            return;
        }

        // إعادة تعيين قيمة الوقت
        timeInput.value = '';

        // تعطيل حقل اختيار الوقت أثناء التحميل
        timeSelect.disabled = true;

        // إظهار مؤشر التحميل وإخفاء الرسالة
        timeLoadingIndicator.classList.remove('d-none');
        timeRequiredMessage.classList.add('d-none');

        // إفراغ قائمة الأوقات ماعدا الخيار الأول
        while (timeSelect.options.length > 1) {
            timeSelect.remove(1);
        }

        // إضافة خيار جاري التحميل
        const loadingOption = document.createElement('option');
        loadingOption.textContent = 'جاري تحميل الأوقات المتاحة...';
        loadingOption.disabled = true;
        timeSelect.appendChild(loadingOption);

        // جلب الأوقات المتاحة من الخادم
        fetch(`/appointments/available-slots?doctor_id=${doctorId}&date=${appointmentDate}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // إخفاء مؤشر التحميل
                timeLoadingIndicator.classList.add('d-none');

                // إزالة خيار "جاري التحميل"
                timeSelect.remove(timeSelect.options.length - 1);

                if (data.success && data.slots && data.slots.length > 0) {
                    // إضافة الأوقات المتاحة للقائمة المنسدلة
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = formatTime(slot);
                        timeSelect.appendChild(option);
                    });

                    // تمكين حقل اختيار الوقت
                    timeSelect.disabled = false;

                } else {
                    // إضافة رسالة عدم توفر أوقات
                    const noTimesOption = document.createElement('option');
                    noTimesOption.textContent = 'لا توجد أوقات متاحة في هذا اليوم';
                    noTimesOption.disabled = true;
                    timeSelect.appendChild(noTimesOption);

                    // إضافة تنبيه واضح بشكل بارز
                    const noSlotsAlert = document.createElement('div');
                    noSlotsAlert.id = 'no-slots-alert';
                    noSlotsAlert.className = 'alert alert-warning mt-2 fade show';
                    noSlotsAlert.innerHTML = `
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-2 text-warning"></i>
                            <div>
                                <strong>تنبيه:</strong> لا توجد مواعيد متاحة في هذا اليوم!
                                <div class="mt-1">يرجى اختيار <strong>يوم آخر</strong> من التقويم للعثور على مواعيد متاحة.</div>
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="try-another-day-btn">
                                        <i class="bi bi-calendar-date me-1"></i> اختيار يوم آخر
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    // إضافة التنبيه بعد قائمة الاختيار
                    timeSelect.parentNode.appendChild(noSlotsAlert);

                    // تمييز حقل التاريخ للفت انتباه المستخدم
                    dateInput.classList.add('border-warning');

                    // إضافة مستمع حدث لزر "اختيار يوم آخر"
                    setTimeout(() => {
                        const tryAnotherDayBtn = document.getElementById('try-another-day-btn');
                        if (tryAnotherDayBtn) {
                            tryAnotherDayBtn.addEventListener('click', function() {
                                dateInput.focus();
                                // إضافة تأثير وميض للفت الانتباه
                                dateInput.classList.add('highlight-input');
                                setTimeout(() => {
                                    dateInput.classList.remove('highlight-input');
                                }, 1500);
                            });
                        }
                    }, 100);
                }
            })
            .catch(error => {
                console.error('Error fetching time slots:', error);

                // إخفاء مؤشر التحميل
                timeLoadingIndicator.classList.add('d-none');

                // إزالة خيار "جاري التحميل"
                timeSelect.remove(timeSelect.options.length - 1);

                // إضافة رسالة الخطأ
                const errorOption = document.createElement('option');
                errorOption.textContent = 'حدث خطأ أثناء تحميل الأوقات المتاحة';
                errorOption.disabled = true;
                timeSelect.appendChild(errorOption);
            });
    }

    // إعادة تعيين حقل اختيار الوقت
    function resetTimeField() {
        // إعادة تعيين القيمة المخفية
        timeInput.value = '';

        // تفريغ قائمة الأوقات ماعدا الخيار الأول
        while (timeSelect.options.length > 1) {
            timeSelect.remove(1);
        }

        // تعطيل الحقل
        timeSelect.disabled = true;

        // إظهار الرسالة
        timeRequiredMessage.classList.remove('d-none');

        // إزالة تنبيه عدم وجود أوقات متاحة إذا كان موجودًا
        const noSlotsAlert = document.getElementById('no-slots-alert');
        if (noSlotsAlert) {
            noSlotsAlert.parentNode.removeChild(noSlotsAlert);
        }

        // إزالة تمييز حقل التاريخ
        dateInput.classList.remove('border-warning');
        dateInput.classList.remove('highlight-input');
    }

    // تنسيق الوقت من 24 ساعة إلى 12 ساعة
    function formatTime(time24) {
        const [hours, minutes] = time24.split(':');
        const hour = parseInt(hours, 10);

        const period = hour >= 12 ? 'م' : 'ص';
        const hour12 = hour % 12 || 12;

        return `${hour12}:${minutes} ${period}`;
    }

    // حدث تغيير الطبيب
    doctorSelect.addEventListener('change', function() {
        // إعادة تعيين حقول التاريخ والوقت
        dateInput.value = '';
        resetTimeField();

        // تفعيل/تعطيل حقل التاريخ
        if (doctorSelect.value) {
            dateInput.disabled = false;
            doctorRequiredMessage.classList.add('d-none');
        } else {
            dateInput.disabled = true;
            doctorRequiredMessage.classList.remove('d-none');
        }

        // تحديث حالة التحقق
        this.classList.remove('is-invalid', 'is-valid');
        if (this.value) {
            this.classList.add('is-valid');
        } else {
            this.classList.add('is-invalid');
        }
    });

    // حدث تغيير التاريخ
    dateInput.addEventListener('change', function() {
        // إزالة تنبيه عدم وجود أوقات متاحة إذا كان موجودًا
        const noSlotsAlert = document.getElementById('no-slots-alert');
        if (noSlotsAlert) {
            noSlotsAlert.parentNode.removeChild(noSlotsAlert);
        }

        // إزالة تمييز حقل التاريخ
        dateInput.classList.remove('border-warning');
        dateInput.classList.remove('highlight-input');

        // تحديث الأوقات المتاحة
        if (this.value) {
            loadAvailableTimeSlots();
        } else {
            resetTimeField();
        }

        // تحديث حالة التحقق
        this.classList.remove('is-invalid', 'is-valid');
        if (this.value) {
            this.classList.add('is-valid');
        } else {
            this.classList.add('is-invalid');
        }
    });

    // حدث تغيير الوقت
    timeSelect.addEventListener('change', function() {
        // تحديث القيمة المخفية
        timeInput.value = this.value;
    });

    // حدث تغيير المريض
    patientSelect.addEventListener('change', function() {
        this.classList.remove('is-invalid', 'is-valid');
        if (this.value) {
            this.classList.add('is-valid');
        } else {
            this.classList.add('is-invalid');
        }
    });

    // التحقق من النموذج عند الإرسال
    form.addEventListener('submit', function(event) {
        // التحقق من اختيار كل الحقول المطلوبة
        if (!doctorSelect.value) {
            event.preventDefault();
            alert('يرجى اختيار الطبيب');
            doctorSelect.focus();
            return;
        }

        if (!dateInput.value) {
            event.preventDefault();
            alert('يرجى اختيار تاريخ الحجز');
            dateInput.focus();
            return;
        }

        if (!timeInput.value) {
            event.preventDefault();
            alert('يرجى اختيار وقت الحجز');
            timeSelect.focus();
            return;
        }

        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });

    // التهيئة عند تحميل الصفحة
    if (doctorSelect.value) {
        dateInput.disabled = false;
        doctorRequiredMessage.classList.add('d-none');

        if (dateInput.value) {
            loadAvailableTimeSlots();
        }
    } else {
        dateInput.disabled = true;
    }
});
</script>
@endpush

@endsection

