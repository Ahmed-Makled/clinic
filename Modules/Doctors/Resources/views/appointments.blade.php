@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-md-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">إدارة الحجوزات</h1>
                    <p class="page-subtitle">عرض وإدارة جميع الحجوزات الخاصة بك</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('doctors.profile') }}" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-right me-2"></i> العودة للملف الشخصي
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card appointment-filters mb-4">
        <div class="card-body">
            <form action="{{ route('doctors.appointments') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">حالة الحجز</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">جميع الحالات</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مواعيد مجدولة</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مواعيد مكتملة</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>مواعيد ملغاة</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="date" class="form-label">تاريخ الحجز</label>
                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-filter me-2"></i> تطبيق الفلتر
                    </button>
                    <a href="{{ route('doctors.appointments') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> إلغاء الفلتر
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title m-0">
                <i class="bi bi-calendar-check me-2"></i>
                قائمة الحجوزات
                <span class="text-muted fs-6">({{ $appointments->total() }} حجز)</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table appointment-table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">المريض</th>
                                <th scope="col">تاريخ الحجز</th>
                                <th scope="col">الوقت</th>
                                <th scope="col">رسوم الكشف</th>
                                <th scope="col">الحالة</th>
                                <th scope="col">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $index => $appointment)
                                <tr class="{{ $appointment->status == 'cancelled' ? 'table-danger' : '' }}">
                                    <td>{{ ($appointments->currentPage() - 1) * $appointments->perPage() + $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <span class="avatar-text">
                                                    {{ mb_substr($appointment->patient->name, 0, 2, 'UTF-8') }}
                                                </span>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $appointment->patient->name }}</h6>
                                                <small class="text-muted">{{ $appointment->patient->user->phone_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}</td>
                                    <td>
                                        <span class="badge {{ $appointment->is_paid ? 'bg-success' : 'bg-warning' }}">
                                            {{ $appointment->fees }} جنيه
                                            {{ $appointment->is_paid ? '(مدفوع)' : '(غير مدفوع)' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge status-badge {{ $appointment->status }}">
                                            {{ $appointment->status_text }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#appointmentDetailsModal{{ $appointment->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            @if($appointment->status == 'scheduled')
                                                <form action="#" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="btn btn-outline-success" title="تم اكتمال الزيارة">
                                                        <i class="bi bi-check-circle"></i>
                                                    </button>
                                                </form>

                                                <form action="#" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="btn btn-outline-danger" title="إلغاء الزيارة">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <!-- Modal for Appointment Details -->
                                        <div class="modal fade" id="appointmentDetailsModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="appointmentDetailsModalLabel{{ $appointment->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="appointmentDetailsModalLabel{{ $appointment->id }}">تفاصيل الحجز</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="appointment-detail-card">
                                                            <div class="header">
                                                                <h6 class="mb-2">معلومات المريض</h6>
                                                            </div>
                                                            <div class="detail-row">
                                                                <span class="label"><i class="bi bi-person me-2"></i>اسم المريض</span>
                                                                <span class="value">{{ $appointment->patient->name }}</span>
                                                            </div>
                                                            <div class="detail-row">
                                                                <span class="label"><i class="bi bi-telephone me-2"></i>رقم الهاتف</span>
                                                                <span class="value">{{ $appointment->patient->user->phone_number }}</span>
                                                            </div>
                                                            <div class="detail-row">
                                                                <span class="label"><i class="bi bi-envelope me-2"></i>البريد الإلكتروني</span>
                                                                <span class="value">{{ $appointment->patient->user->email }}</span>
                                                            </div>

                                                            <hr>
                                                            <div class="header">
                                                                <h6 class="mb-2">معلومات الموعد</h6>
                                                            </div>
                                                            <div class="detail-row">
                                                                <span class="label"><i class="bi bi-calendar-date me-2"></i>تاريخ الحجز</span>
                                                                <span class="value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d') }}</span>
                                                            </div>
                                                            <div class="detail-row">
                                                                <span class="label"><i class="bi bi-clock me-2"></i>وقت الحجز</span>
                                                                <span class="value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}</span>
                                                            </div>
                                                            <div class="detail-row">
                                                                <span class="label"><i class="bi bi-cash-coin me-2"></i>رسوم الكشف</span>
                                                                <span class="value">{{ $appointment->fees }} جنيه
                                                                    <span class="badge {{ $appointment->is_paid ? 'bg-success' : 'bg-warning' }}">
                                                                        {{ $appointment->is_paid ? 'مدفوع' : 'غير مدفوع' }}
                                                                    </span>
                                                                </span>
                                                            </div>
                                                            <div class="detail-row">
                                                                <span class="label"><i class="bi bi-patch-check me-2"></i>حالة الحجز</span>
                                                                <span class="value">
                                                                    <span class="badge status-badge {{ $appointment->status }}">
                                                                        {{ $appointment->status_text }}
                                                                    </span>
                                                                </span>
                                                            </div>

                                                            @if($appointment->notes)
                                                                <div class="detail-row">
                                                                    <span class="label"><i class="bi bi-journal-text me-2"></i>ملاحظات</span>
                                                                    <span class="value">{{ $appointment->notes }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-3">
                    {{ $appointments->withQueryString()->links() }}
                </div>
            @else
                <div class="empty-state text-center py-5">
                    <i class="bi bi-calendar-x empty-icon"></i>
                    <h5 class="mt-3">لا توجد حجوزات</h5>
                    <p class="text-muted">لا توجد حجوزات مطابقة للفلتر الحالي</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* تنسيق الصفحة الرئيسية */
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #6c757d;
    }

    /* تنسيق بطاقة الفلتر */
    .appointment-filters {
        border-radius: 15px;
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    }

    /* تنسيق الجدول */
    .appointment-table th {
        font-weight: 600;
    }

    .avatar-sm {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.2) 100%);
        color: #0d6efd;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .status-badge {
        padding: 0.4rem 0.75rem;
        border-radius: 50px;
    }

    .status-badge.scheduled {
        background-color: #28a745;
    }

    .status-badge.completed {
        background-color: #17a2b8;
    }

    .status-badge.cancelled {
        background-color: #dc3545;
    }

    /* تنسيق بطاقة تفاصيل الموعد */
    .appointment-detail-card {
        padding: 1rem;
        border-radius: 10px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }

    .appointment-detail-card .header {
        margin-bottom: 1rem;
    }

    .appointment-detail-card .header h6 {
        font-weight: 600;
        color: #0d6efd;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .detail-row .label {
        font-weight: 500;
        color: #495057;
        display: flex;
        align-items: center;
    }

    .detail-row .value {
        text-align: left;
        color: #212529;
    }

    /* الحالة الفارغة */
    .empty-state {
        padding: 3rem 1rem;
    }

    .empty-icon {
        font-size: 3rem;
        color: #6c757d;
        opacity: 0.5;
    }

    /* تنسيقات للشاشات الصغيرة */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .detail-row {
            flex-direction: column;
            margin-bottom: 1rem;
        }

        .detail-row .value {
            margin-top: 0.25rem;
            text-align: right;
        }
    }
</style>
@endpush
