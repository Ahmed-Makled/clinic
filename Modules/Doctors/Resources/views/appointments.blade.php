@extends('layouts.app')

@section('content')
<div class="appointments-dashboard">
    <div class="container mt-5 py-5">
        <div class="row">
            <div class="col-12">

                    @if (session('success'))
                    <div class="alert-card success mb-4">
                        <div class="alert-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <h6 class="alert-heading">تمت العملية بنجاح!</h6>
                            <p class="mb-0">{!! session('success') !!}</p>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert-card error mb-4">
                        <div class="alert-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <h6 class="alert-heading">حدث خطأ!</h6>
                            <p class="mb-0">{!! session('error') !!}</p>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- رأس الصفحة -->
        <div class="dashboard-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title">
                        <i class="bi bi-calendar-week-fill text-primary me-2" aria-hidden="true"></i>إدارة الحجوزات
                    </h1>
                    <p class="page-subtitle">عرض وإدارة جميع الحجوزات والحجوزات الخاصة بك</p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-wrap justify-content-md-end mt-3 mt-md-0 gap-3">
                        <div class="d-flex gap-2">
                            <div class="stat-card scheduled">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">{{ $appointments->where('status', 'scheduled')->count() }}</span>
                                    <span class="stat-label">قيد الانتظار</span>
                                </div>
                            </div>
                            <div class="stat-card completed">
                                <div class="stat-icon">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">{{ $appointments->where('status', 'completed')->count() }}</span>
                                    <span class="stat-label">مكتملة</span>
                                </div>
                            </div>
                            <div class="stat-card cancelled">
                                <div class="stat-icon">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">{{ $appointments->where('status', 'cancelled')->count() }}</span>
                                    <span class="stat-label">ملغاة</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('doctors.profile') }}" class="btn btn-outline-primary d-inline-flex align-items-center" aria-label="العودة للملف الشخصي">
                            <i class="bi bi-arrow-right me-2" aria-hidden="true"></i> العودة للملف الشخصي
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- قسم الفلتر - تصميم محسن -->
        <div class="filter-section mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary-soft text-primary me-2">
                            <i class="bi bi-funnel" aria-hidden="true"></i>
                        </div>
                        <h6 class="card-title m-0 fw-bold">تصفية الحجوزات</h6>
                    </div>

                </div>
                <div class="card-body filter-body">
                    <form action="{{ route('doctors.appointments') }}" method="GET" class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <label for="status" class="form-label fw-medium">
                                <i class="bi bi-check-circle-fill me-2 text-success"></i>حالة الحجز
                            </label>
                            <select class="form-select custom-select shadow-none" id="status" name="status">
                                <option value="">جميع الحالات</option>
                                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>
                                    <i class="bi bi-calendar-event"></i> مواعيد قيد الانتظار
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    مواعيد مكتملة
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                    مواعيد ملغاة
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date_range" class="form-label fw-medium">
                                <i class="bi bi-calendar-range me-2 text-primary"></i>الفترة الزمنية
                            </label>
                            <select class="form-select custom-select shadow-none" id="date_range" name="date_range">
                                <option value="">اختر الفترة</option>
                                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>اليوم</option>
                                <option value="tomorrow" {{ request('date_range') == 'tomorrow' ? 'selected' : '' }}>غداً</option>
                                <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>هذا الأسبوع</option>
                                <option value="next_week" {{ request('date_range') == 'next_week' ? 'selected' : '' }}>الأسبوع القادم</option>
                                <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>هذا الشهر</option>
                                <option value="custom" {{ request('date_range') == 'custom' ? 'selected' : '' }}>تحديد فترة مخصصة</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 date-custom-range" style="{{ request('date_range') == 'custom' ? '' : 'display: none;' }}">
                            <label for="date_from" class="form-label fw-medium">
                                <i class="bi bi-calendar3 me-2 text-primary"></i>من تاريخ
                            </label>
                            <input type="date" class="form-control shadow-none" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 date-custom-range" style="{{ request('date_range') == 'custom' ? '' : 'display: none;' }}">
                            <label for="date_to" class="form-label fw-medium">
                                <i class="bi bi-calendar3 me-2 text-primary"></i>إلى تاريخ
                            </label>
                            <input type="date" class="form-control shadow-none" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="date" class="form-label fw-medium">
                                <i class="bi bi-calendar-date me-2 text-info"></i>تاريخ محدد
                            </label>
                            <input type="date" class="form-control shadow-none" id="date" name="date" value="{{ request('date') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="patient_name" class="form-label fw-medium">
                                <i class="bi bi-person me-2 text-warning"></i>اسم المريض
                            </label>
                            <input type="text" class="form-control shadow-none" id="patient_name" name="patient_name"
                                   placeholder="بحث باسم المريض" value="{{ request('patient_name') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="appointment_id" class="form-label fw-medium">
                                <i class="bi bi-hash me-2 text-danger"></i>رقم الكشف
                            </label>
                            <input type="number" class="form-control shadow-none" id="appointment_id" name="appointment_id"
                                   placeholder="رقم الكشف" value="{{ request('appointment_id') }}">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="payment_status" class="form-label fw-medium">
                                <i class="bi bi-cash-coin me-2 text-success"></i>حالة الدفع
                            </label>
                            <select class="form-select custom-select shadow-none" id="payment_status" name="payment_status">
                                <option value="">جميع الحالات</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>غير مدفوع</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 mt-4">

                            <button type="submit" class="btn btn-primary px-4 mt-4  ">
                                <i class="bi bi-search me-2" aria-hidden="true"></i> بحث
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- قسم الحجوزات -->
        <div class="appointments-content">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-primary-soft text-primary me-2">
                            <i class="bi bi-calendar-check" aria-hidden="true"></i>
                        </div>
                        <h5 class="card-title m-0">
                            قائمة الحجوزات
                            <div class="appointments-counter d-inline-flex align-items-center justify-content-center ms-2">
                                {{ $appointments->total() }}
                            </div>
                        </h5>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($appointments->count() > 0)
                        <!-- عرض الجدول -->
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3 py-3">#</th>
                                        <th class="py-3">المريض</th>
                                        <th class="py-3">التاريخ</th>
                                        <th class="py-3">الوقت</th>
                                        <th class="py-3">رسوم الكشف</th>
                                        <th class="py-3">الحالة</th>
                                        <th class="py-3">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $index => $appointment)
                                        <tr data-appointment-id="{{ $appointment->id }}">
                                            <td class="ps-3">{{ ($appointments->currentPage() - 1) * $appointments->perPage() + $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar {{ $appointment->status }}-avatar me-3">
                                                        <span>{{ mb_substr($appointment->patient->name, 0, 2, 'UTF-8') }}</span>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $appointment->patient->name }}</h6>
                                                        <div class="small text-muted d-flex align-items-center">
                                                            <i class="bi bi-telephone-fill me-1"></i>
                                                            {{ $appointment->patient->user->phone_number }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d') }}</span>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->locale('ar')->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="time-badge">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-medium">{{ $appointment->fees }} جنيه</span>
                                                    <span class="status-badge {{ $appointment->is_paid ? 'active' : 'inactive' }} ms-2">
                                                        {{ $appointment->is_paid ? 'تم الدفع ' : 'لم يتم الدفع' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge {{ $appointment->status }}">
                                                    <i class="bi {{ $appointment->status == 'scheduled' ? 'bi-calendar-event' : ($appointment->status == 'completed' ? 'bi-check-circle-fill' : 'bi-x-circle-fill') }}"></i>
                                                    {{ $appointment->status_text }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="actions">
                                                    <button type="button" class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#appointmentDetailsModal{{ $appointment->id }}" title="عرض التفاصيل">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </button>

                                                    @if($appointment->status == 'scheduled')
                                                        <form action="{{ route('doctors.appointments.complete', $appointment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="btn btn-icon complete-action" title="اكتمال الزيارة">
                                                                <i class="bi bi-check-circle-fill"></i>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('doctors.appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')

                                                            <button type="submit" class="btn btn-icon cancel-action" title="إلغاء الزيارة">
                                                                <i class="bi bi-x-circle-fill"></i>
                                                            </button>


                                                        </form>
                                                    @endif
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($appointments->hasPages())
                            <div class="pagination-wrapper p-3">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="pagination-info">
                                            <span class="text-muted">
                                                عرض {{ $appointments->firstItem() }} إلى {{ $appointments->lastItem() }} من أصل {{ $appointments->total() }} نتيجة
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <nav aria-label="Page navigation" class="d-flex justify-content-end">
                                            <ul class="pagination custom-pagination mb-0">
                                                {{-- First Page Link --}}
                                                @if (!$appointments->onFirstPage())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $appointments->url(1) }}" title="الصفحة الأولى">
                                                            <i class="bi bi-chevron-double-right"></i>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Previous Page Link --}}
                                                @if ($appointments->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <span class="page-link">
                                                            <i class="bi bi-chevron-right"></i>
                                                        </span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $appointments->previousPageUrl() }}" title="الصفحة السابقة">
                                                            <i class="bi bi-chevron-right"></i>
                                                        </a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @php
                                                    $start = max($appointments->currentPage() - 2, 1);
                                                    $end = min($appointments->currentPage() + 2, $appointments->lastPage());

                                                    $showStartDots = $start > 2;
                                                    $showEndDots = $end < $appointments->lastPage() - 1;
                                                @endphp

                                                {{-- Always show first page if not in range --}}
                                                @if ($start > 1)
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $appointments->url(1) }}">1</a>
                                                    </li>
                                                    @if ($showStartDots)
                                                        <li class="page-item disabled">
                                                            <span class="page-link dots">...</span>
                                                        </li>
                                                    @endif
                                                @endif

                                                {{-- Page Numbers --}}
                                                @for ($page = $start; $page <= $end; $page++)
                                                    @if ($page == $appointments->currentPage())
                                                        <li class="page-item active">
                                                            <span class="page-link">{{ $page }}</span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $appointments->url($page) }}">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endfor

                                                {{-- Always show last page if not in range --}}
                                                @if ($end < $appointments->lastPage())
                                                    @if ($showEndDots)
                                                        <li class="page-item disabled">
                                                            <span class="page-link dots">...</span>
                                                        </li>
                                                    @endif
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $appointments->url($appointments->lastPage()) }}">{{ $appointments->lastPage() }}</a>
                                                    </li>
                                                @endif

                                                {{-- Next Page Link --}}
                                                @if ($appointments->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $appointments->nextPageUrl() }}" title="الصفحة التالية">
                                                            <i class="bi bi-chevron-left"></i>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled">
                                                        <span class="page-link">
                                                            <i class="bi bi-chevron-left"></i>
                                                        </span>
                                                    </li>
                                                @endif

                                                {{-- Last Page Link --}}
                                                @if ($appointments->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $appointments->url($appointments->lastPage()) }}" title="الصفحة الأخيرة">
                                                            <i class="bi bi-chevron-double-left"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-calendar-x" aria-hidden="true"></i>
                            </div>
                            <h5 class="empty-title">لا توجد حجوزات</h5>
                            <p class="empty-text">لا توجد حجوزات مطابقة للفلتر الحالي</p>
                            <a href="{{ route('doctors.appointments') }}" class="btn btn-primary mt-2">
                                <i class="bi bi-arrow-clockwise me-1" aria-hidden="true"></i> عرض جميع الحجوزات
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Appointment Details -->
@foreach($appointments as $appointment)
    <div class="modal fade" id="appointmentDetailsModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="appointmentDetailsModalLabel{{ $appointment->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header appointment-modal-header {{ $appointment->status }}">
                    <div>
                        <h5 class="modal-title" id="appointmentDetailsModalLabel{{ $appointment->id }}">
                            <i class="bi bi-calendar-check me-2"></i>
                            تفاصيل الحجز
                            <span class="appointment-id">#{{ $appointment->id }}</span>
                        </h5>
                        <p class="modal-subtitle mb-0">
                            @if($appointment->status == 'scheduled')
                                <i class="bi bi-calendar-event"></i> حجز مجدول
                            @elseif($appointment->status == 'completed')
                                <i class="bi bi-check-circle"></i> حجز مكتمل
                            @elseif($appointment->status == 'cancelled')
                                <i class="bi bi-x-circle"></i> حجز ملغي
                            @endif
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- نظرة عامة سريعة -->
                    <div class="appointment-overview">
                        <div class="overview-item">
                            <div class="overview-icon">
                                <i class="bi bi-calendar3"></i>
                            </div>
                            <div class="overview-content">
                                <span class="overview-label">التاريخ</span>
                                <span class="overview-value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d') }}</span>
                            </div>
                        </div>

                        <div class="overview-item">
                            <div class="overview-icon">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="overview-content">
                                <span class="overview-label">الوقت</span>
                                <span class="overview-value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}</span>
                            </div>
                        </div>

                        <div class="overview-item">
                            <div class="overview-icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <div class="overview-content">
                                <span class="overview-label">رسوم الكشف</span>
                                <span class="overview-value">{{ $appointment->fees }} جنيه</span>
                            </div>
                        </div>

                        <div class="overview-item">
                            <div class="overview-icon">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <div class="overview-content">
                                <span class="overview-label">حالة الدفع</span>
                                <span class="overview-value">
                                    @if($appointment->is_paid)
                                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> تم الدفع</span>
                                    @else
                                        <span class="text-warning"><i class="bi bi-exclamation-circle me-1"></i> لم يتم الدفع</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr class="details-divider">

                    <!-- معلومات المريض -->
                    <div class="details-section">
                        <h6 class="section-title">
                            <i class="bi bi-person-fill me-2"></i>
                            معلومات المريض
                        </h6>
                        <div class="details-grid">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-person me-2"></i>
                                    الاسم الكامل
                                </div>
                                <div class="detail-value">{{ $appointment->patient->name }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-telephone me-2"></i>
                                    رقم الهاتف
                                </div>
                                <div class="detail-value">{{ $appointment->patient->user->phone_number }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="bi bi-envelope me-2"></i>
                                    البريد الإلكتروني
                                </div>
                                <div class="detail-value">{{ $appointment->patient->user->email }}</div>
                            </div>
                            @if($appointment->patient->date_of_birth)
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-calendar2-event me-2"></i>
                                        تاريخ الميلاد
                                    </div>
                                    <div class="detail-value">{{ $appointment->patient->date_of_birth->format('Y-m-d') }}</div>
                                </div>
                            @endif
                            @if($appointment->patient->gender)
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="bi bi-gender-ambiguous me-2"></i>
                                        الجنس
                                    </div>
                                    <div class="detail-value">{{ $appointment->patient->gender }}</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($appointment->notes)
                        <hr class="details-divider">
                        <!-- ملاحظات -->
                        <div class="details-section">
                            <h6 class="section-title">
                                <i class="bi bi-journal-text me-2"></i>
                                ملاحظات الحجز
                            </h6>
                            <div class="details-grid">
                                <div class="detail-item notes-item">
                                    <div class="notes-text">{{ $appointment->notes }}</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr class="details-divider">

                    <!-- تاريخ الحجز -->
                    <div class="details-section">
                        <h6 class="section-title">
                            <i class="bi bi-clock-history me-2"></i>
                            سجل الحجز
                        </h6>
                        <div class="timeline-container">
                            <div class="timeline-event">
                                <div class="event-icon primary"><i class="bi bi-calendar-plus"></i></div>
                                <div class="event-content">
                                    <h6>تم إنشاء الحجز</h6>
                                    <p>{{ \Carbon\Carbon::parse($appointment->created_at)->format('Y-m-d h:i A') }}</p>
                                </div>
                            </div>

                            @if($appointment->status == 'completed')
                            <div class="timeline-event">
                                <div class="event-icon success"><i class="bi bi-check-circle"></i></div>
                                <div class="event-content">
                                    <h6>تم اكتمال الحجز</h6>
                                    <p>{{ \Carbon\Carbon::parse($appointment->updated_at)->format('Y-m-d h:i A') }}</p>
                                </div>
                            </div>
                            @elseif($appointment->status == 'cancelled')
                            <div class="timeline-event">
                                <div class="event-icon danger"><i class="bi bi-x-circle"></i></div>
                                <div class="event-content">
                                    <h6>تم إلغاء الحجز</h6>
                                    <p>{{ \Carbon\Carbon::parse($appointment->updated_at)->format('Y-m-d h:i A') }}</p>
                                </div>
                            </div>
                            @endif

                            @php
                                $remainingTime = null;
                                $now = \Carbon\Carbon::now();
                                $appointmentTime = \Carbon\Carbon::parse($appointment->scheduled_at);

                                if ($now < $appointmentTime && $appointment->status == 'scheduled') {
                                    $remainingTime = $now->diffForHumans($appointmentTime);
                                }
                            @endphp

                            @if($remainingTime && $appointment->status == 'scheduled')
                            <div class="appointment-countdown">
                                <i class="bi bi-hourglass-split"></i>
                                <span>متبقي على الحجز: {{ $remainingTime }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    @if($appointment->status == 'scheduled')
                        <div class="appointment-actions">
                            <form action="{{ route('doctors.appointments.complete', $appointment) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    تم اكتمال الزيارة
                                </button>
                            </form>

                            <form action="{{ route('doctors.appointments.cancel', $appointment) }}" method="POST" class="d-inline ms-2">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-x-circle me-1"></i>
                                    إلغاء الزيارة
                                </button>
                            </form>
                        </div>
                    @elseif($appointment->status == 'completed')
                        <div class="completed-badge">
                            <i class="bi bi-patch-check"></i>
                            تم اكتمال هذا الحجز بنجاح
                        </div>
                    @elseif($appointment->status == 'cancelled')
                        <div class="cancelled-badge">
                            <i class="bi bi-x-octagon"></i>
                            تم إلغاء هذا الحجز
                        </div>
                    @endif

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('styles')
<style>
    :root {
        --primary-color: #3b82f6;
        --primary-light: #eff6ff;
        --primary-dark: #2563eb;
        --text-dark: #1f2937;
        --text-medium: #4b5563;
        --text-light: #6b7280;
        --surface-bg: #ffffff;
        --surface-light: #f9fafb;
        --surface-medium: #f3f4f6;
        --border-light: #e5e7eb;
        --border-medium: #d1d5db;
        --success-color: #10b981;
        --success-light: #d1fae5;
        --danger-color: #ef4444;
        --danger-light: #fee2e2;
        --warning-color: #f59e0b;
        --warning-light: #fef3c7;
        --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
        --card-hover-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    body {
        background-color: var(--surface-light);
        font-family: 'Tajawal', 'Cairo', system-ui, sans-serif;
        color: var(--text-dark);
    }

    .appointments-dashboard {
        min-height: calc(100vh - 60px);
    }

    /* === رأس الصفحة === */
    .page-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        color: var(--text-dark);
    }

    .page-subtitle {
        color: var(--text-medium);
        font-size: 1rem;
    }

    /* === بطاقات الإحصائيات === */
    .stat-card {
        display: flex;
        align-items: center;
        background-color: var(--surface-bg);
        border-radius: 12px;
        padding: 0.8rem 1.2rem;
        box-shadow: var(--card-shadow);
        min-width: 120px;
        transition: all 0.2s ease;
        border-left: 3px solid var(--primary-color);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--card-hover-shadow);
    }

    .stat-card.scheduled {
        border-left: 3px solid var(--success-color);
    }

    .stat-card.completed {
        border-left: 3px solid var(--primary-color);
    }

    .stat-card.cancelled {
        border-left: 3px solid var(--danger-color);
    }

    .stat-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        margin-right: 12px;
        font-size: 1.1rem;
    }

    .stat-card.scheduled .stat-icon {
        color: var(--success-color);
    }

    .stat-card.completed .stat-icon {
        color: var(--primary-color);
    }

    .stat-card.cancelled .stat-icon {
        color: var(--danger-color);
    }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-value {
        font-size: 1.3rem;
        font-weight: 700;
        line-height: 1.1;
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--text-light);
    }

    /* === قسم الفلتر === */
    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: box-shadow 0.2s ease-in-out;
    }

    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        color: var(--text-medium);
    }

    .form-select, .form-control {
        border-radius: 10px;
        border: 1px solid var(--border-medium);
        padding: 0.6rem 1rem;
        transition: all 0.2s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    /* === أزرار === */
    .btn {
        border-radius: 10px;
        font-weight: 500;
        padding: 0.6rem 1.2rem;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.2);
    }

    .btn-outline-primary {
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-light);
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    .btn-success {
        background-color: var (--success-color);
        border-color: var (--success-color);
    }

    .btn-success:hover {
        background-color: #0d9488;
        border-color: #0d9488;
    }

    .btn-danger {
        background-color: var(--danger-color);
        border-color: var(--danger-color);
    }

    .btn-danger:hover {
        background-color: #dc2626;
        border-color: #dc2626;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: transparent;
        border: none;
        color: var(--text-medium);
        transition: all 0.2s ease;
    }

    .btn-icon:hover {
        background-color: var(--surface-medium);
        color: var(--text-dark);
    }

    /* === رأس البطاقة === */
    .card-header {
        border-bottom: 1px solid var(--border-light);
    }

    .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        font-size: 1rem;
    }

    .bg-primary-soft {
        background-color: var(--primary-light);
    }

    /* === عداد الحجوزات === */
    .appointments-counter {
        background-color: var(--primary-color);
        color: white;
        border-radius: 50px;
        min-width: 26px;
        height: 26px;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0 8px;
        box-shadow: 0 2px 5px rgba(59, 130, 246, 0.2);
        position: relative;
        top: -1px;
    }

    /* === جدول الحجوزات === */
    .table thead th {
        background-color: var(--surface-medium);
        font-weight: 600;
        color: var(--text-medium);
        font-size: 0.9rem;
        border: none;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: var(--primary-light);
    }

    .avatar {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
    }

    .scheduled-avatar {
        background: linear-gradient(135deg, var(--success-color) 0%, #34d399 100%);
    }

    .completed-avatar {
        background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
    }

    .cancelled-avatar {
        background: linear-gradient(135deg, var(--danger-color) 0%, #f87171 100%);
    }

    .time-badge {
        display: inline-flex;
        align-items: center;
        background-color: var(--primary-light);
        color: var(--primary-color);
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* === أزرار الإجراءات === */
    .actions {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .complete-action {
        color: var (--success-color);
    }

    .complete-action:hover {
        background-color: var(--success-light);
        color: var(--success-color);
    }

    .cancel-action {
    }

    .cancel-action:hover {
        background-color: var(--danger-light);
        color: var(--danger-color);

    }

    /* === مودال التفاصيل === */
    .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .modal-header {
        border-bottom: 1px solid var(--border-light);
        padding: 1.2rem 1.5rem;
    }

    .scheduled-header {
        border-bottom: 3px solid var(--success-color);
    }

    .completed-header {
        border-bottom: 3px solid var(--primary-color);
    }

    .cancelled-header {
        border-bottom: 3px solid var(--danger-color);
    }

    .modal-status-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .modal-status-icon.scheduled {
        background: linear-gradient(135deg, var(--success-color) 0%, #34d399 100%);
    }

    .modal-status-icon.completed {
        background: linear-gradient(135deg, var(--primary-color) 0%, #3b82f6 100%);
    }

    .modal-status-icon.cancelled {
        background: linear-gradient(135deg, var(--danger-color) 0%, #f87171 100%);
    }

    .appointment-details {
        background-color: var(--surface-medium);
        border-radius: 12px;
        padding: 1.5rem;
    }

    .details-section {
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--text-dark);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;

    }


    .detail-label {
        display: flex;
        align-items: center;
        color: var(--text-medium);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }

    .detail-value {
        font-weight: 600;
        color: var(--text-dark);
    }

    .notes-item {
        grid-column: 1 / -1;
    }

    .notes-text {
        white-space: pre-line;
        font-weight: normal;
        background-color: var(--surface-medium);
        padding: 0.75rem;
        border-radius: 8px;
        border-right: 3px solid var(--primary-color);
    }

    .details-divider {
        margin: 1.5rem 0;
        opacity: 0.1;
    }

    /* === حالة عدم وجود حجوزات === */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: var(--primary-light);
        color: var(--primary-color);
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-text {
        color: var(--text-light);
        max-width: 300px;
        margin: 0 auto 1rem;
    }


    /* === الترقيم === */
    .pagination-wrapper {
        padding-top: 1.5rem;
        border-top: 1px solid var(--bs-gray-200);
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 0 0 0.5rem 0.5rem;
        padding: 1.5rem;
        margin: 0 -1.5rem -1.5rem -1.5rem;
    }

    .pagination-info {
        font-size: 0.875rem;
        font-weight: 500;
    }

    .custom-pagination {
        margin: 0;
        gap: 0.25rem;
    }

    .custom-pagination .page-item {
        margin: 0;
    }

    .custom-pagination .page-link {
        border: 1px solid var(--bs-gray-300);
        color: var(--bs-gray-700);
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 0.375rem;
        transition: all 0.15s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        background: white;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .custom-pagination .page-item:first-child .page-link,
    .custom-pagination .page-item:last-child .page-link {
        min-width: 2.5rem;
    }

    .custom-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--bs-primary) 0%, #0056b3 100%);
        border-color: var(--bs-primary);
        color: white;
        box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.3);
        transform: translateY(-1px);
    }

    .custom-pagination .page-link:hover:not(.disabled) {
        background: var(--bs-primary);
        border-color: var(--bs-primary);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.2);
    }

    .custom-pagination .page-item.disabled .page-link {
        background-color: var(--bs-gray-100);
        border-color: var(--bs-gray-200);
        color: var(--bs-gray-400);
        cursor: not-allowed;
        box-shadow: none;
        transform: none;
    }

    .custom-pagination .page-link.dots {
        border: none;
        background: transparent;
        box-shadow: none;
        color: var(--bs-gray-500);
        cursor: default;
        font-weight: 600;
        letter-spacing: 0.1em;
    }

    .custom-pagination .page-item.disabled .page-link.dots {
        background: transparent;
        border: none;
    }

    .custom-pagination .page-link i {
        font-size: 0.75rem;
        line-height: 1;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .pagination-wrapper .row {
            flex-direction: column-reverse;
            gap: 1rem;
        }

        .pagination-wrapper .col-md-6 {
            text-align: center;
        }

        .custom-pagination {
            justify-content: center;
            flex-wrap: wrap;
        }

        .custom-pagination .page-link {
            padding: 0.375rem 0.5rem;
            min-width: 2rem;
            height: 2rem;
            font-size: 0.8rem;
        }

        .pagination-info {
            font-size: 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .custom-pagination .page-item:not(.active):not(:first-child):not(:last-child):not(:nth-child(2)):not(:nth-last-child(2)) {
            display: none;
        }
    }

    /* === روابط === */
    .contact-link {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .contact-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    /* === تصميم نظرة عامة للمودال === */
    .appointment-overview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .overview-item {
        display: flex;
        align-items: center;
        background-color: var(--surface-light);
        border-radius: 10px;
        padding: 1rem;
    }

    .overview-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background-color: var(--primary-light);
        color: var(--primary-color);
        margin-right: 0.75rem;
        font-size: 1rem;
    }

    .overview-content {
        display: flex;
        flex-direction: column;
    }

    .overview-label {
        font-size: 0.8rem;
        color: var(--text-light);
    }

    .overview-value {
        font-weight: 600;
        color: var(--text-dark);
    }

    /* === تصميم التايم لاين === */
    .timeline-container {
        padding: 1rem 0;
    }

    .timeline-event {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.25rem;
        position: relative;
    }

    .timeline-event:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 35px;
        left: 17px;
        height: calc(100% - 15px);
        width: 2px;
        background-color: var(--border-light);
    }

    .event-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin-right: 1rem;
        font-size: 1rem;
        z-index: 2;
        color: white;
    }

    .event-icon.primary {
        background: linear-gradient(135deg, var(--primary-color) 0%, #60a5fa 100%);
    }

    .event-icon.success {
        background: linear-gradient(135deg, var(--success-color) 0%, #34d399 100%);
    }

    .event-icon.danger {
        background: linear-gradient(135deg, var(--danger-color) 0%, #f87171 100%);
    }

    .event-content h6 {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .event-content p {
        font-size: 0.85rem;
        color: var(--text-light);
        margin: 0;
    }

    .appointment-countdown {
        display: flex;
        align-items: center;
        background-color: var(--warning-light);
        color: var(--warning-color);
        padding: 0.75rem;
        border-radius: 10px;
        font-weight: 500;
    }

    .appointment-countdown i {
        margin-right: 0.5rem;
        font-size: 1.1rem;
    }

    /* === بادجات الحالة في المودال === */
    .appointment-modal-header.scheduled {
        border-bottom: 3px solid var(--success-color);
    }

    .appointment-modal-header.completed {
        border-bottom: 3px solid var(--primary-color);
    }

    .appointment-modal-header.cancelled {
        border-bottom: 3px solid var(--danger-color);
    }

    .appointment-id {
        font-size: 0.9rem;
        color: var(--text-light);
        font-weight: normal;
        margin-left: 0.5rem;
    }

    .modal-subtitle {
        color: var(--text-medium);
        font-size: 0.9rem;
    }

    .completed-badge, .cancelled-badge {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        border-radius: 10px;
        font-weight: 500;
    }

    .completed-badge {
        background-color: var(--success-light);
        color: var(--success-color);
    }

    .cancelled-badge {
        background-color: var(--danger-light);
        color: var(--danger-color);
    }

    .completed-badge i, .cancelled-badge i {
        margin-right: 0.5rem;
    }

    /* === تحسينات لوضع الهاتف === */
    @media (max-width: 767px) {
        .page-title {
            font-size: 1.5rem;
        }

        .stat-card {
            min-width: auto;
            flex: 1 0 auto;
            padding: 0.6rem 1rem;
        }

        .stat-icon {
            width: 34px;
            height: 34px;
            font-size: 1rem;
        }

        .stat-value {
            font-size: 1.1rem;
        }

        .details-grid {
            grid-template-columns: 1fr;
        }

        .appointment-overview {
            grid-template-columns: 1fr;
        }
    }

    /* === التأثيرات والتحسينات === */
    .fade-in {
        animation: fadeIn 0.3s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تطبيق تأثير الظهور التدريجي على الصفوف
        document.querySelectorAll('.table tbody tr').forEach((row, index) => {
            setTimeout(() => row.classList.add('fade-in'), index * 50);
        });

        // إدارة الفلاتر وتوسيع/طي قسم البحث المتقدم
        const toggleFiltersBtn = document.getElementById('toggleFilters');
        const dateRangeSelect = document.getElementById('date_range');
        const dateCustomRangeFields = document.querySelectorAll('.date-custom-range');
        const clearFiltersBtn = document.getElementById('clearFilters');

        // عرض/إخفاء قسم الفلاتر المتقدمة
        toggleFiltersBtn.addEventListener('click', function() {
            const filterBody = document.querySelector('.filter-body');
            if (filterBody.classList.contains('filter-body-collapsed')) {
                filterBody.classList.remove('filter-body-collapsed');
                toggleFiltersBtn.innerHTML = '<i class="bi bi-chevron-up me-1"></i> طي خيارات البحث';
            } else {
                filterBody.classList.add('filter-body-collapsed');
                toggleFiltersBtn.innerHTML = '<i class="bi bi-sliders me-1"></i> خيارات البحث';
            }
        });

        // إظهار/إخفاء حقول التاريخ المخصصة بناءً على الاختيار
        dateRangeSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                dateCustomRangeFields.forEach(field => field.style.display = 'block');
            } else {
                dateCustomRangeFields.forEach(field => field.style.display = 'none');
            }
        });

        // مسح جميع الفلاتر
        clearFiltersBtn.addEventListener('click', function() {
            const form = this.closest('form');
            const inputs = form.querySelectorAll('input:not([type="hidden"]), select');

            inputs.forEach(input => {
                if (input.type === 'text' || input.type === 'number' || input.type === 'date') {
                    input.value = '';
                } else if (input.type === 'select-one') {
                    input.selectedIndex = 0;
                }
            });

            // إخفاء حقول التاريخ المخصصة
            dateCustomRangeFields.forEach(field => field.style.display = 'none');
        });

        // إضافة سلوك الفلترة التلقائية عند التغيير (اختياري)
        // غير مفعّل افتراضيًا لأن المستخدم قد يريد ضبط عدة فلاتر قبل البحث
        /*
        const filterForm = document.querySelector('.filter-section form');
        const autoFilterInputs = filterForm.querySelectorAll('select');

        autoFilterInputs.forEach(input => {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });
        */
    });
</script>
@endpush
