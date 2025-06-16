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

                    <div class="col-12 mb-4">
                        <!-- Enhanced Header with Progress -->
                        <div class="appointment-scheduler-header mb-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h4 class="text-primary fw-bold mb-1">
                                        <i class="bi bi-calendar-heart me-2"></i>جدولة الموعد
                                    </h4>
                                    <p class="text-muted small mb-0">اختر التاريخ والوقت المناسب للحجز</p>
                                </div>
                                <div class="appointment-status-badge">
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                        <i class="bi bi-clock-history me-1"></i>
                                        <span id="status-text">في انتظار الاختيار</span>
                                    </span>
                                </div>
                            </div>

                            <!-- Compact Progress Steps -->
                            <div class="progress-steps-compact">
                                <div class="steps-container">
                                    <div class="step-compact" id="step-doctor-compact">
                                        <div class="step-icon">
                                            <i class="bi bi-person-check"></i>
                                        </div>
                                        <span>الطبيب</span>
                                    </div>
                                    <div class="step-divider"></div>
                                    <div class="step-compact" id="step-date-compact">
                                        <div class="step-icon">
                                            <i class="bi bi-calendar3"></i>
                                        </div>
                                        <span>التاريخ</span>
                                    </div>
                                    <div class="step-divider"></div>
                                    <div class="step-compact" id="step-time-compact">
                                        <div class="step-icon">
                                            <i class="bi bi-clock"></i>
                                        </div>
                                        <span>الوقت</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Enhanced Date & Time Selection Layout -->
                        <div class="appointment-scheduler-content">
                            <div class="row g-3">
                                <!-- Calendar Section - Optimized -->
                                <div class="col-xl-8 col-lg-7">
                                    <div class="calendar-card">
                                        <div class="calendar-header">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-0 fw-bold text-primary">
                                                    <i class="bi bi-calendar3 me-2"></i>اختر التاريخ
                                                </h6>

                                            </div>
                                        </div>

                                        <div class="calendar-body">
                                            <!-- Hidden inputs -->
                                            <input type="hidden" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}">

                                            <!-- Calendar Container -->
                                            <div id="calendar-container" class="calendar-grid-container">
                                                <div class="calendar-placeholder">
                                                    <i class="bi bi-calendar-week display-1"></i>
                                                    <p class="mt-3 mb-0">اختر الطبيب لعرض التقويم</p>
                                                </div>
                                            </div>

                                            @error('appointment_date')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Time Slots Section - Enhanced -->
                                <div class="col-xl-4 col-lg-5">
                                    <div class="time-slots-card">
                                        <div class="time-slots-header">
                                            <h6 class="mb-0 fw-bold text-primary">
                                                <i class="bi bi-clock-fill me-2"></i>الأوقات المتاحة
                                            </h6>
                                            <div class="time-counter" id="time-counter" style="display: none;">
                                                <span class="badge bg-primary text-white fw-bold">0</span>
                                            </div>
                                        </div>

                                        <div class="time-slots-body">
                                            <input type="hidden" id="appointment_time" name="appointment_time" value="{{ old('appointment_time') }}">

                                            <div id="time-slots-container" class="time-slots-container">
                                                <div class="time-slots-placeholder">
                                                    <i class="bi bi-clock-history display-1"></i>
                                                    <p class="mt-3 mb-1">اختر التاريخ أولاً</p>
                                                    <small class="text-muted">لعرض الأوقات المتاحة</small>
                                                </div>
                                            </div>

                                            @error('appointment_time')
                                                <div class="error-message">
                                                    <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Summary Bar -->
                            <div class="appointment-summary-bar mt-3">
                                <div class="summary-content" id="appointment-summary">
                                    <div class="summary-item">
                                        <i class="bi bi-info-circle text-primary"></i>
                                        <span class="summary-text">اختر الطبيب والتاريخ والوقت لإكمال جدولة الموعد</span>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<style>
/* Appointment Scheduler Header */
.appointment-scheduler-header {
    background: linear-gradient(135deg, #f0f7ff, #e7efff);
    border-radius: 15px;
    padding: 25px;
    border: 1px solid rgba(13, 110, 253, 0.1);
    box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    margin-bottom: 25px;
    position: relative;
    overflow: hidden;
}

.appointment-scheduler-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    background: radial-gradient(circle, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0) 70%);
    border-radius: 50%;
    transform: translate(30%, -30%);
    z-index: 0;
}

.appointment-scheduler-header::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0) 70%);
    border-radius: 50%;
    transform: translate(-30%, 30%);
    z-index: 0;
}

.appointment-status-badge .badge {
    font-size: 0.9rem;
    padding: 8px 15px;
    border-radius: 30px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

/* Simplified Progress Steps */
.progress-steps-compact {
    margin-top: 20px;
}

.steps-container {
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 12px;
    padding: 12px 20px;
    border: 1px solid #e7efff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.03);
}

.step-compact {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
    color: #6c757d;
    background: #f8f9fa;
}

.step-compact.active {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    box-shadow: 0 2px 5px rgba(13, 110, 253, 0.15);
}

.step-compact.completed {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
}

.step-icon {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.step-divider {
    width: 30px;
    height: 2px;
    background: #e9ecef;
    margin: 0 10px;
    transition: all 0.3s ease;
}

.step-divider.active {
    background: #198754;
    height: 3px;
}

/* Simplified Calendar Card */
.calendar-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #dee2e6;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    height: 100%;
}

.calendar-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0;
    padding: 15px;
}

.calendar-status-indicator {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    border-radius: 20px;
    padding: 5px 10px;
    font-size: 0.8rem;
}

.calendar-body {
    padding: 20px;
    min-height: 350px;
}

.calendar-grid-container {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 16px;
    min-height: 300px;
}

.calendar-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 250px;
    color: #6c757d;
    background: white;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

/* Simplified Time Slots Card */
.time-slots-card {
    background: white;
    border-radius: 12px;
    border: 1px solid #dee2e6;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    height: 100%;
}

.time-slots-header {
    background: #f8f9fa;
    color: #0d6efd;
    border-bottom: 1px solid #dee2e6;
    border-radius: 12px 12px 0 0;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.time-counter .badge {
    font-size: 0.8rem;
    padding: 5px 8px;
}

.time-slots-body {
    padding: 20px;
    min-height: 350px;
}

.time-slots-container {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 16px;
    min-height: 300px;
}

.time-slots-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 250px;
    color: #6c757d;
    background: white;
    border-radius: 8px;
    border: 2px dashed #dee2e6;
}

/* Simplified Calendar Navigation */
.calendar-header-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    background: white;
    padding: 12px 16px;
    border-radius: 12px;
    border: 1px solid #e7efff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
}

.calendar-nav-btn {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    border: 1px solid #e7efff;
    background: white;
    color: #0d6efd;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.calendar-nav-btn:hover {
    background: #0d6efd;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
}

.calendar-month-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #0d6efd;
    margin: 0;
    text-align: center;
    flex-grow: 1;
}

/* Simplified Calendar Grid */
.calendar-weekdays {
    margin-bottom: 12px;
}

.weekday-header {
    background: #f0f7ff;
    color: #0d6efd;
    padding: 10px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    text-align: center;
    letter-spacing: 0.5px;
}

.calendar-days {
    gap: 8px;
}

.calendar-day-btn {
    height: 55px;
    border: 1px solid #dee2e6;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 0.9rem;
    background: white;
    color: #495057;
    position: relative;
    overflow: hidden;
}

.calendar-day-btn:not(:disabled):hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 10px rgba(0,0,0,0.1);
    border-color: #0d6efd;
    background: #4e8fca;
    z-index: 1;
}

.calendar-day-available {
  background: #5c9bd5;
    color: #ffffff;
    border-color: #0d6efd;
}

.calendar-day-available:hover {
    background: #0d6efd;
    color: white;
}

.calendar-day-selected {
    background: #0d6efd;
    color: white;
    border-color: #0d6efd;
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
}

.calendar-day-today {
    background: #fff8e1;
    color: #ff9800;
    border-color: #ffca28;
}

.calendar-day-unavailable {
    background: #f8f9fa;
    color: #adb5bd;
    border: 2px dashed #dee2e6;
}

.calendar-day-past {
    background: #f5f5f5;
    color: #adb5bd;
    opacity: 0.7;
}

/* Enhanced Time Slots */
.time-group {
    margin-bottom: 24px;
}

.time-group-header {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    padding: 10px 15px;
    background: linear-gradient(135deg, #f0f7ff, #e2efff);
    border-radius: 12px;
    border-left: 4px solid #0d6efd;
}

.time-group-header .bi {
    color: #0d6efd;
    margin-right: 8px;
    font-size: 1.1rem;
}

.time-group-slots {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 10px;
}

.time-slot-btn {
    height: 42px;
    border: 1px solid #0d6efd;
    border-radius: 10px;
    background: white;
    color: #0d6efd;
    font-weight: 500;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.time-slot-btn:hover {
    background: #e7f3ff;
    border-color: #0056b3;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.time-slot-btn.btn-primary {
    background: #0d6efd;
    border-color: #0d6efd;
    color: white;
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
}

.time-slot-btn.scale-selected {
    animation: pulse-scale 0.3s ease;
}

/* Simplified Summary Bar */
.appointment-summary-bar {
    background: white;
    border-radius: 12px;
    padding: 15px 20px;
    border: 1px solid #e7efff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    margin-top: 20px;
    transition: all 0.3s ease;
}

.summary-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 12px;
    color: #495057;
    width: 100%;
}

.summary-item .bi {
    color: #0d6efd;
}

.summary-text {
    font-weight: 500;
}

.summary-details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 5px;
}

.summary-detail-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.85rem;
    color: #6c757d;
    background: #f8f9fa;
    padding: 4px 10px;
    border-radius: 20px;
}

/* Simplified Loading and Error States */
.loading-spinner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 180px;
    color: #6c757d;
}

.spinner-modern {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(13, 110, 253, 0.1);
    border-top: 3px solid #0d6efd;
    border-radius: 50%;
    animation: spin 1.2s linear infinite;
}

.error-message {
    background: #fff5f5;
    color: #dc3545;
    padding: 10px 15px;
    border-radius: 8px;
    margin-top: 10px;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid rgba(220, 53, 69, 0.2);
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Enhanced Animations */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes pulse-scale {
    0% { transform: scale(1); }
    50% { transform: scale(0.95); }
    100% { transform: scale(1); }
}

.calendar-day-btn:active,
.time-slot-btn:active {
    transform: scale(0.95);
}


@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .appointment-scheduler-content .col-xl-8 {
        order: 1;
    }

    .appointment-scheduler-content .col-xl-4 {
        order: 2;
    }
}

@media (max-width: 768px) {
    .appointment-scheduler-header {
        padding: 16px;
    }

    .steps-container {
        flex-wrap: wrap;
        gap: 8px;
        padding: 12px;
    }

    .step-compact {
        font-size: 0.8rem;
        padding: 6px 10px;
    }

    .calendar-body,
    .time-slots-body {
        padding: 16px;
    }

    .calendar-day-btn {
        height: 56px;
        font-size: 0.8rem;
    }

    .time-group-slots {
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    }

    .time-slot-btn {
        height: 44px;
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .appointment-scheduler-header .d-flex {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .calendar-grid-container,
    .time-slots-container {
        padding: 16px;
    }

    .time-group-slots {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const doctorSelect = document.getElementById('doctor_id');
    const patientSelect = document.getElementById('patient_id');
    const dateInput = document.getElementById('appointment_date');
    const timeHiddenInput = document.getElementById('appointment_time');
    const timeSlotsContainer = document.getElementById('time-slots-container');
    const calendarContainer = document.getElementById('calendar-container');

    // Check if all elements exist
    if (!doctorSelect || !calendarContainer || !timeSlotsContainer) {
        console.error('Required elements not found:', {
            doctorSelect: !!doctorSelect,
            calendarContainer: !!calendarContainer,
            timeSlotsContainer: !!timeSlotsContainer
        });
        return;
    }

    // Arabic month names
    const arabicMonths = [
        'يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو',
        'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'
    ];

    // Arabic day names
    const arabicDays = ['أحد', 'اثنين', 'ثلاثاء', 'أربعاء', 'خميس', 'جمعة', 'سبت'];

    let currentDate = new Date();
    let selectedDate = null;
    let availableDates = []; // Array to store available dates

    // Function to fetch available dates for the doctor
    function fetchAvailableDates(doctorId, year, month) {
        return fetch(`/appointments/available-dates?doctor_id=${doctorId}&year=${year}&month=${month + 1}`)
            .then(response => response.json())
            .then(data => {
                availableDates = data.dates || [];
                return availableDates;
            })
            .catch(error => {
                console.error('Error fetching available dates:', error);
                availableDates = [];
                return [];
            });
    }

    // Function to check if a date has available slots
    function hasAvailableSlots(dateString) {
        return availableDates.includes(dateString);
    }

    // Function to create modern calendar
    function createCalendar(year, month) {
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const today = new Date();

        let html = `
            <!-- Calendar Navigation Header -->
            <div class="calendar-header-nav">
                <button type="button" class="calendar-nav-btn" id="prev-month">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <h3 class="calendar-month-title">${arabicMonths[month]} ${year}</h3>
                <button type="button" class="calendar-nav-btn" id="next-month">
                    <i class="bi bi-chevron-left"></i>
                </button>
            </div>

            <!-- Days of week header -->
            <div class="calendar-weekdays row g-1 mb-3">
        `;

        arabicDays.forEach(day => {
            html += `
                <div class="col">
                    <div class="weekday-header">
                        ${day}
                    </div>
                </div>
            `;
        });
        html += `</div>`;

        // Calculate starting day (0 = Sunday in Arabic calendar)
        let startDay = firstDay.getDay();

        // Create calendar grid
        html += `<div class="calendar-days row g-2">`;

        // Empty cells for days before the first day of month
        for (let i = 0; i < startDay; i++) {
            html += `<div class="col p-1"></div>`;
        }

        // Days of the month
        for (let day = 1; day <= lastDay.getDate(); day++) {
            const currentDateCheck = new Date(year, month, day);
            const dateString = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isPast = currentDateCheck < today.setHours(0,0,0,0);
            const isToday = currentDateCheck.toDateString() === new Date().toDateString();
            const isSelected = selectedDate && currentDateCheck.toDateString() === selectedDate.toDateString();
            const hasSlots = hasAvailableSlots(dateString);

            let buttonClass = 'calendar-day-btn btn w-100 text-muted calendar-day-past calendar-day';
            let disabled = 'disabled';
            let dayContent = `<div class="day-number">${day}</div>`;
            let statusIcon = '';

            // Only enable days that have available slots and are not in the past
            if (!isPast && hasSlots) {
                if (isSelected) {
                    buttonClass = 'calendar-day-btn btn w-100 calendar-day-selected calendar-day';
                    disabled = '';
                    statusIcon = '<div class="day-status"><i class="bi bi-check-circle-fill"></i></div>';
                } else if (isToday) {
                    buttonClass = 'calendar-day-btn btn w-100 calendar-day-today calendar-day';
                    disabled = '';
                    statusIcon = '<div class="day-status"><i class="bi bi-star-fill"></i></div>';
                } else {
                    buttonClass = 'calendar-day-btn btn w-100 calendar-day-available calendar-day';
                    disabled = '';
                    statusIcon = '<div class="day-status"><i class="bi bi-circle-fill"></i></div>';
                }
            } else if (!isPast && !hasSlots) {
                // Future days without slots - show but disabled
                buttonClass = 'calendar-day-btn btn w-100 calendar-day-unavailable calendar-day';
                disabled = 'disabled';
                statusIcon = '<div class="day-status"><i class="bi bi-x-circle"></i></div>';
            }

            html += `
                <div class="col p-1">
                    <button type="button"
                            class="${buttonClass}"
                            data-date="${dateString}"
                            ${disabled}
                            style="position: relative;">
                        ${dayContent}
                        ${statusIcon}
                    </button>
                </div>
            `;

            // Start new row after Saturday
            if ((startDay + day) % 7 === 0) {
                html += `</div><div class="calendar-days row g-2">`;
            }
        }

        html += `</div>`;

        // Add legend with modern design
        html += `
            <div class="calendar-legend mt-4 p-4 bg-white rounded-3 shadow-sm">
                <div class="row text-center">
                    <div class="col-6 col-md-3 mb-2">
                        <div class="legend-item d-flex align-items-center justify-content-center">
                            <div class="legend-color me-2" style="width: 16px; height: 16px; background: linear-gradient(135deg, #20c997, #0ea770); border-radius: 4px;"></div>
                            <small class="fw-medium">متاح</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="legend-item d-flex align-items-center justify-content-center">
                            <div class="legend-color me-2" style="width: 16px; height: 16px; background: linear-gradient(135deg, #0d6efd, #0056b3); border-radius: 4px;"></div>
                            <small class="fw-medium">محدد</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="legend-item d-flex align-items-center justify-content-center">
                            <div class="legend-color me-2" style="width: 16px; height: 16px; background: linear-gradient(135deg, #fd7e14, #e55a00); border-radius: 4px;"></div>
                            <small class="fw-medium">اليوم</small>
                        </div>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <div class="legend-item d-flex align-items-center justify-content-center">
                            <div class="legend-color me-2" style="width: 16px; height: 16px; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 4px;"></div>
                            <small class="fw-medium">غير متاح</small>
                        </div>
                    </div>
                </div>
            </div>
        `;

        return html;
    }

    // Function to attach calendar events
    function attachCalendarEvents() {
        // Add event listeners for month navigation
        const prevBtn = document.getElementById('prev-month');
        const nextBtn = document.getElementById('next-month');

        if (prevBtn) {
            prevBtn.addEventListener('click', async () => {
                currentDate.setMonth(currentDate.getMonth() - 1);
                await loadCalendarWithDates();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', async () => {
                currentDate.setMonth(currentDate.getMonth() + 1);
                await loadCalendarWithDates();
            });
        }

        // Add click listeners to calendar days
        document.querySelectorAll('.calendar-day').forEach(button => {
            if (!button.disabled) {
                button.addEventListener('click', function() {
                    // Remove selection from all days
                    document.querySelectorAll('.calendar-day').forEach(btn => {
                        if (!btn.disabled && hasAvailableSlots(btn.dataset.date)) {
                            const isToday = btn.dataset.date === new Date().toISOString().split('T')[0];
                            btn.className = isToday ?
                                'calendar-day-btn btn btn-outline-primary w-100 border-2 calendar-day-today calendar-day' :
                                'calendar-day-btn btn btn-success w-100 border-0 calendar-day-available calendar-day';

                            // Reset status icon
                            const statusDiv = btn.querySelector('.day-status');
                            if (statusDiv) {
                                statusDiv.innerHTML = isToday ?
                                    '<i class="bi bi-star-fill"></i>' :
                                    '<i class="bi bi-circle-fill small"></i>';
                                statusDiv.className = isToday ? 'day-status text-primary' : 'day-status text-white';
                            }
                        }
                    });

                    // Select this day with animation
                    this.className = 'calendar-day-btn btn w-100 calendar-day-selected calendar-day';
                    this.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        this.style.transform = 'scale(1)';
                        this.style.boxShadow = '0 4px 10px rgba(13, 110, 253, 0.3)';
                    }, 150);

                    // Update status icon
                    const statusDiv = this.querySelector('.day-status');
                    if (statusDiv) {
                        statusDiv.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
                        statusDiv.className = 'day-status text-white';
                    }

                    // Set selected date
                    selectedDate = new Date(this.dataset.date);
                    dateInput.value = this.dataset.date;

                    // Remove calendar border error if any
                    calendarContainer.classList.remove('border-danger');

                    // Update steps and summary
                    updateCompactSteps();
                    updateAppointmentSummary();

                    // Load time slots with delay for better UX
                    setTimeout(() => {
                        loadAvailableTimeSlots();
                    }, 300);
                });
            }
        });
    }

    // Function to load calendar with available dates
    async function loadCalendarWithDates() {
        if (!doctorSelect.value) return;

        // Show loading state
        calendarContainer.innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">جاري التحميل...</span>
                </div>
                <p class="mb-0 mt-2 text-muted">جاري تحميل الأيام المتاحة...</p>
            </div>
        `;

        try {
            await fetchAvailableDates(doctorSelect.value, currentDate.getFullYear(), currentDate.getMonth());
            calendarContainer.innerHTML = createCalendar(currentDate.getFullYear(), currentDate.getMonth());
            calendarContainer.classList.add('fade-in');
            attachCalendarEvents();

            // Show available dates count
            const availableCount = availableDates.length;
            if (availableCount > 0) {
                const countInfo = document.createElement('div');
                countInfo.className = 'text-center mt-2';
                countInfo.innerHTML = `
                    <small class="text-success fw-bold">
                        <i class="bi bi-calendar-check me-1"></i>
                        ${availableCount} يوم متاح في ${arabicMonths[currentDate.getMonth()]}
                    </small>
                `;
                calendarContainer.appendChild(countInfo);
            } else {
                const noAvailableInfo = document.createElement('div');
                noAvailableInfo.className = 'text-center mt-2';
                noAvailableInfo.innerHTML = `
                    <small class="text-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        لا توجد أيام متاحة في ${arabicMonths[currentDate.getMonth()]}
                    </small>
                `;
                calendarContainer.appendChild(noAvailableInfo);
            }
        } catch (error) {
            calendarContainer.innerHTML = `
                <div class="text-center text-danger py-4">
                    <i class="bi bi-exclamation-circle fs-1"></i>
                    <p class="mb-0 mt-2">حدث خطأ أثناء تحميل الأيام المتاحة</p>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="loadCalendarWithDates()">
                        <i class="bi bi-arrow-clockwise me-1"></i>إعادة المحاولة
                    </button>
                </div>
            `;
        }
    }

    // Function to create time slot buttons
    function createTimeSlotButton(slot, isSelected = false) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = `btn ${isSelected ? 'btn-primary' : 'btn-outline-primary'} time-slot-btn rounded-pill px-3 py-2 mb-2 fw-medium`;
        button.style.cssText = 'transition: all 0.3s ease; min-width: 90px; position: relative; overflow: hidden;';
        button.dataset.time = slot;

        // Create time content with icon
        button.innerHTML = `
            <i class="bi bi-clock me-1"></i>
            <span>${slot}</span>
            ${isSelected ? '<i class="bi bi-check-circle-fill ms-1"></i>' : ''}
        `;

        button.addEventListener('click', function() {
            // Animate out previous selection
            document.querySelectorAll('.time-slot-btn').forEach(btn => {
                btn.classList.remove('btn-primary', 'scale-selected');
                btn.classList.add('btn-outline-primary');
                // Remove check icon
                const checkIcon = btn.querySelector('.bi-check-circle-fill');
                if (checkIcon) checkIcon.remove();
            });

            // Animate in new selection
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary', 'scale-selected');

            // Add check icon
            const span = this.querySelector('span');
            span.insertAdjacentHTML('afterend', '<i class="bi bi-check-circle-fill ms-1"></i>');

            // Set the hidden input value
            timeHiddenInput.value = this.dataset.time;

            // Remove validation classes
            timeSlotsContainer.classList.remove('border-danger');

            // Update summary
            updateSelectionSummary();

            // Update steps
            updateSteps();
        });

        return button;
    }

    // Function to show empty time slots state
    function showEmptyTimeSlots(message = 'اختر التاريخ أولاً لعرض الأوقات المتاحة') {
        timeSlotsContainer.innerHTML = `
            <div class="time-slots-placeholder">
                <i class="bi bi-clock-history display-1"></i>
                <p class="mt-3 mb-1">اختر التاريخ أولاً</p>
                <small class="text-muted">${message}</small>
            </div>
        `;

        // Update time counter
        const timeCounter = document.getElementById('time-counter');
        if (timeCounter) {
            timeCounter.style.display = 'none';
        }
    }

    // Function to show loading state for time slots
    function showLoadingTimeSlots() {
        timeSlotsContainer.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner-modern"></div>
                <p class="mt-3 mb-0 text-primary fw-medium">جاري تحميل الأوقات المتاحة...</p>
            </div>
        `;
    }    // Function to load available time slots with modern design
    function loadAvailableTimeSlots() {
        const doctorId = doctorSelect.value;
        const appointmentDate = dateInput.value;

        // Clear previous selection
        timeHiddenInput.value = '';

        // Only proceed if both doctor and date are selected
        if (!doctorId || !appointmentDate) {
            showEmptyTimeSlots();
            return;
        }

        showLoadingTimeSlots();

        // Fetch available slots using AJAX
        fetch(`/appointments/available-slots?doctor_id=${doctorId}&date=${appointmentDate}`)
            .then(response => response.json())
            .then(data => {
                if (data.slots && data.slots.length > 0) {
                    // Update time counter
                    const timeCounter = document.getElementById('time-counter');
                    if (timeCounter) {
                        timeCounter.style.display = 'block';
                        timeCounter.querySelector('span').textContent = data.slots.length;
                    }

                    // Group time slots by period with enhanced design
                    const timeGroups = {
                        morning: { slots: [], icon: 'bi-sunrise', title: 'الصباح', color: '#fd7e14' },
                        afternoon: { slots: [], icon: 'bi-sun', title: 'الظهيرة', color: '#ffc107' },
                        evening: { slots: [], icon: 'bi-moon-stars', title: 'المساء', color: '#6f42c1' }
                    };

                    data.slots.forEach(slot => {
                        const hour = parseInt(slot.split(':')[0]);
                        if (hour < 12) {
                            timeGroups.morning.slots.push(slot);
                        } else if (hour < 17) {
                            timeGroups.afternoon.slots.push(slot);
                        } else {
                            timeGroups.evening.slots.push(slot);
                        }
                    });

                    // Create enhanced time slots HTML
                    let slotsHtml = ``;

                    // Add time groups
                    Object.entries(timeGroups).forEach(([period, group]) => {
                        if (group.slots.length > 0) {
                            slotsHtml += createEnhancedTimeGroup(group.title, group.slots, group.icon, group.color);
                        }
                    });

                    timeSlotsContainer.innerHTML = slotsHtml;
                    timeSlotsContainer.classList.add('fade-in');

                    // Add event listeners to all time buttons
                    attachTimeSlotEvents();

                } else {
                    // No slots available with enhanced design
                    timeSlotsContainer.innerHTML = `
                        <div class="no-slots-message text-center py-5">
                            <div class="mb-4">
                                <div class="no-slots-icon mb-3">
                                    <i class="bi bi-calendar-x display-1"></i>
                                </div>
                                <h6 class="text-warning fw-bold mb-2">لا توجد أوقات متاحة</h6>
                                <p class="text-muted mb-3">جميع المواعيد محجوزة في هذا اليوم</p>
                                <small class="text-muted">يرجى اختيار تاريخ آخر من التقويم</small>
                            </div>
                            <div class="suggestion-box p-3 rounded-3" style="background: linear-gradient(135deg, #fff3cd, #fef7e0); border: 1px solid rgba(255, 193, 7, 0.3);">
                                <i class="bi bi-lightbulb text-warning me-2"></i>
                                <small class="text-muted">يمكنك اختيار يوم آخر متاح من التقويم</small>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error fetching time slots:', error);
                timeSlotsContainer.innerHTML = `
                    <div class="error-state text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-exclamation-triangle display-1 text-danger"></i>
                            <h6 class="text-danger fw-bold mb-2 mt-3">حدث خطأ في التحميل</h6>
                            <p class="text-muted mb-3">لا يمكن تحميل الأوقات المتاحة في الوقت الحالي</p>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4" onclick="loadAvailableTimeSlots()">
                            <i class="bi bi-arrow-clockwise me-1"></i>إعادة المحاولة
                        </button>
                    </div>
                `;
            });
    }

    // Function to create enhanced time group
    function createEnhancedTimeGroup(title, slots, icon, color) {
        let html = `
            <div class="time-group mb-4">
                <div class="time-group-header mb-3 p-2 rounded-pill" style="background: linear-gradient(135deg, ${color}15, ${color}25); border-left: 3px solid ${color};">
                    <div class="d-flex align-items-center">
                        <div class="time-group-icon me-2" style="color: ${color};">
                            <i class="bi ${icon}"></i>
                        </div>
                        <small class="fw-bold" style="color: ${color};">${title}</small>
                        <span class="badge ms-auto" style="background: ${color}; color: white; font-size: 0.7rem;">${slots.length}</span>
                    </div>
                </div>
                <div class="time-group-slots">
        `;

        slots.forEach((slot, index) => {
            const isSelected = timeHiddenInput.value === slot;
            html += `
                <button type="button"
                        class="time-slot-btn ${isSelected ? 'btn-primary' : 'btn-outline-primary'}"
                        data-time="${slot}"
                        style="animation-delay: ${index * 0.1}s;">
                    <i class="bi bi-clock me-1"></i>
                    <span>${slot}</span>
                    ${isSelected ? '<i class="bi bi-check-circle-fill ms-1"></i>' : ''}
                </button>
            `;
        });

        html += `
                </div>
            </div>
        `;

        return html;
    }

    // Function to attach time slot events
    function attachTimeSlotEvents() {
        document.querySelectorAll('.time-slot-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove selection from all buttons
                document.querySelectorAll('.time-slot-btn').forEach(b => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-outline-primary');
                    const checkIcon = b.querySelector('.bi-check-circle-fill');
                    if (checkIcon) checkIcon.remove();
                });

                // Select this button
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary');

                const span = this.querySelector('span');
                span.insertAdjacentHTML('afterend', '<i class="bi bi-check-circle-fill ms-1"></i>');

                timeHiddenInput.value = this.dataset.time;
                timeSlotsContainer.classList.remove('border-danger');

                updateCompactSteps();
                updateAppointmentSummary();
            });
        });
    }// Event listeners for doctor changes
    doctorSelect.addEventListener('change', async function() {
        console.log('Doctor changed to:', this.value); // Debug line

        if (this.value) {
            console.log('Loading calendar with available dates...'); // Debug line
            await loadCalendarWithDates();
        } else {
            console.log('Hiding calendar...'); // Debug line
            calendarContainer.innerHTML = `
                <div class="calendar-placeholder">
                    <i class="bi bi-calendar-week display-1"></i>
                    <p class="mt-3 mb-0">اختر الطبيب لعرض التقويم</p>
                </div>
            `;
        }

        updateCalendarStatus();
        updateCompactSteps();
        updateAppointmentSummary();

        // Clear selections when doctor changes
        selectedDate = null;
        dateInput.value = '';
        timeHiddenInput.value = '';
        showEmptyTimeSlots();
    });

    // Function to update compact steps indicator
    function updateCompactSteps() {
        const doctorSelected = doctorSelect.value;
        const dateSelected = dateInput.value;
        const timeSelected = timeHiddenInput.value;

        // Update doctor step
        const doctorStep = document.getElementById('step-doctor-compact');
        if (doctorSelected) {
            doctorStep.className = 'step-compact completed';
            doctorStep.querySelector('i').className = 'bi bi-check-lg';
        } else {
            doctorStep.className = 'step-compact';
            doctorStep.querySelector('i').className = 'bi bi-person-check';
        }

        // Update date step
        const dateStep = document.getElementById('step-date-compact');
        if (dateSelected) {
            dateStep.className = 'step-compact completed';
            dateStep.querySelector('i').className = 'bi bi-check-lg';
        } else if (doctorSelected) {
            dateStep.className = 'step-compact active';
            dateStep.querySelector('i').className = 'bi bi-calendar3';
        } else {
            dateStep.className = 'step-compact';
            dateStep.querySelector('i').className = 'bi bi-calendar3';
        }

        // Update time step
        const timeStep = document.getElementById('step-time-compact');
        if (timeSelected) {
            timeStep.className = 'step-compact completed';
            timeStep.querySelector('i').className = 'bi bi-check-lg';
        } else if (dateSelected) {
            timeStep.className = 'step-compact active';
            timeStep.querySelector('i').className = 'bi bi-clock';
        } else {
            timeStep.className = 'step-compact';
            timeStep.querySelector('i').className = 'bi bi-clock';
        }

        // Update step dividers
        const dividers = document.querySelectorAll('.step-divider');
        dividers[0].className = doctorSelected ? 'step-divider active' : 'step-divider';
        dividers[1].className = dateSelected ? 'step-divider active' : 'step-divider';

        // Update status badge
        updateStatusBadge();
    }

    // Function to update status badge
    function updateStatusBadge() {
        const doctorSelected = doctorSelect.value;
        const dateSelected = dateInput.value;
        const timeSelected = timeHiddenInput.value;
        const statusText = document.getElementById('status-text');

        if (doctorSelected && dateSelected && timeSelected) {
            statusText.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>جاهز للحفظ';
            statusText.parentElement.className = 'badge bg-success text-white px-3 py-2 rounded-pill';
        } else if (doctorSelected && dateSelected) {
            statusText.innerHTML = '<i class="bi bi-clock text-warning me-1"></i>اختر الوقت';
            statusText.parentElement.className = 'badge bg-warning text-dark px-3 py-2 rounded-pill';
        } else if (doctorSelected) {
            statusText.innerHTML = '<i class="bi bi-calendar text-info me-1"></i>اختر التاريخ';
            statusText.parentElement.className = 'badge bg-info text-white px-3 py-2 rounded-pill';
        } else {
            statusText.innerHTML = '<i class="bi bi-clock-history me-1"></i>في انتظار الاختيار';
            statusText.parentElement.className = 'badge bg-light text-dark px-3 py-2 rounded-pill';
        }
    }

    // Function to update calendar status
    function updateCalendarStatus() {
        const doctorId = doctorSelect.value;
        const statusIndicator = document.getElementById('calendar-status');

        if (doctorId) {
            statusIndicator.innerHTML = `
                <i class="bi bi-check-circle me-1"></i>
                <small>جاهز للاختيار</small>
            `;
        } else {
            statusIndicator.innerHTML = `
                <i class="bi bi-info-circle me-1"></i>
                <small>اختر الطبيب أولاً</small>
            `;
            statusIndicator.style.background = 'rgba(255, 255, 255, 0.15)';
        }
    }

    // Function to update appointment summary
    function updateAppointmentSummary() {
        const doctorName = doctorSelect.options[doctorSelect.selectedIndex]?.text || '';
        const selectedDateFormatted = selectedDate ? selectedDate.toLocaleDateString('ar-EG', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        }) : '';
        const selectedTime = timeHiddenInput.value;

        const summaryContent = document.getElementById('appointment-summary');

        if (doctorName && selectedDateFormatted && selectedTime) {
            summaryContent.innerHTML = `
                <div class="summary-item">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <div>
                        <div class="fw-bold text-success mb-1">تم تحديد موعد الحجز بنجاح!</div>
                        <div class="summary-details d-flex flex-wrap gap-3 small text-muted">
                            <div class="summary-detail-item">
                                <i class="bi bi-person-badge me-1"></i>
                                <strong>الطبيب:</strong> ${doctorName}
                            </div>
                            <div class="summary-detail-item">
                                <i class="bi bi-calendar-check me-1"></i>
                                <strong>التاريخ:</strong> ${selectedDateFormatted}
                            </div>
                            <div class="summary-detail-item">
                                <i class="bi bi-clock me-1"></i>
                                <strong>الوقت:</strong> ${selectedTime}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            summaryContent.parentElement.style.background = 'linear-gradient(135deg, #d1edff, #e7f3ff)';
        } else if (doctorName && selectedDateFormatted) {
            summaryContent.innerHTML = `
                <div class="summary-item">
                    <i class="bi bi-clock text-warning fs-5"></i>
                    <div>
                        <div class="fw-bold text-primary mb-1">تم اختيار الطبيب والتاريخ</div>
                        <div class="summary-details d-flex flex-wrap gap-3 small text-muted">
                            <span><i class="bi bi-person-check me-1"></i><strong>الطبيب:</strong> ${doctorName}</span>
                            <span><i class="bi bi-calendar-check me-1"></i><strong>التاريخ:</strong> ${selectedDateFormatted}</span>
                            <span class="text-warning"><i class="bi bi-exclamation-triangle me-1"></i>اختر الوقت لإكمال الحجز</span>
                        </div>
                    </div>
                </div>
            `;
            summaryContent.parentElement.style.background = 'linear-gradient(135deg, #fff3cd, #fef7e0)';
            summaryContent.parentElement.style.borderColor = 'rgba(255, 193, 7, 0.3)';
        } else if (doctorName) {
            summaryContent.innerHTML = `
                <div class="summary-item">
                    <i class="bi bi-person-check text-info fs-5"></i>
                    <div>
                        <div class="fw-bold text-primary mb-1">تم اختيار الطبيب</div>
                        <div class="summary-details small text-muted">
                            <span><i class="bi bi-person-check me-1"></i><strong>الطبيب:</strong> ${doctorName}</span>
                            <span class="text-info ms-3"><i class="bi bi-info-circle me-1"></i>اختر التاريخ والوقت لإكمال الحجز</span>
                        </div>
                    </div>
                </div>
            `;
            summaryContent.parentElement.style.background = 'linear-gradient(135deg, #cff4fc, #e7f6fd)';
            summaryContent.parentElement.style.borderColor = 'rgba(13, 202, 240, 0.3)';
        } else {
            summaryContent.innerHTML = `
                <div class="summary-item">
                    <i class="bi bi-info-circle text-primary fs-5"></i>
                    <span class="summary-text">اختر الطبيب والتاريخ والوقت لإكمال جدولة الموعد</span>
                </div>
            `;
            summaryContent.parentElement.style.background = 'linear-gradient(135deg, #f8f9fa, #e3f2fd)';
            summaryContent.parentElement.style.borderColor = 'rgba(13, 110, 253, 0.1)';
        }
    }

    // Form validation
    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Check if date is selected
        if (!dateInput.value) {
            calendarContainer.classList.add('border-danger');
            isValid = false;
        }

        // Check if time is selected
        if (!timeHiddenInput.value) {
            timeSlotsContainer.classList.add('border-danger');
            isValid = false;
        }

        if (!form.checkValidity() || !isValid) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });

    // Real-time validation for other fields
    [doctorSelect, patientSelect].forEach(element => {
        element.addEventListener('change', function() {
            this.classList.remove('is-invalid', 'is-valid');
            if (this.value) {
                this.classList.add('is-valid');
            } else {
                this.classList.add('is-invalid');
            }
        });
    });

    // Make functions globally accessible
    window.loadAvailableTimeSlots = loadAvailableTimeSlots;
    window.loadCalendarWithDates = loadCalendarWithDates;

    // Initialize on page load
    console.log('DOM loaded, initializing...'); // Debug line
    console.log('Doctor select element:', doctorSelect); // Debug line
    console.log('Calendar container element:', calendarContainer); // Debug line

    // Show initial state with new functions
    updateCalendarStatus();
    updateCompactSteps();
    updateAppointmentSummary();

    // If date is already selected (e.g. after validation error)
    if (dateInput.value) {
        selectedDate = new Date(dateInput.value);
        currentDate = new Date(selectedDate);
        if (doctorSelect.value) {
            loadCalendarWithDates().then(() => {
                loadAvailableTimeSlots();
            });
        }
    }

    // Update UI when doctor or patient selection changes
    [doctorSelect, patientSelect].forEach(element => {
        element.addEventListener('change', function() {
            updateCompactSteps();
            updateAppointmentSummary();
        });
    });
});
</script>
@endpush

@endsection

