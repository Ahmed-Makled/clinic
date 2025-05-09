@extends('layouts.app')

@section('title', 'تم إلغاء الدفع')

@section('content')
<div class="container mt-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow-lg cancel-card">
                <div class="card-body p-lg-5 p-4">
                    <div class="cancel-icon-wrapper mb-4">
                        <div class="cancel-icon mb-3">
                            <span class="icon-circle bg-warning">
                                <i class="bi bi-exclamation-triangle text-white"></i>
                            </span>
                        </div>
                        <h2 class="mb-2 text-warning fw-bold">تم إلغاء عملية الدفع</h2>
                        <p class="text-muted mb-4 lead">لم تكتمل عملية الدفع، ولكن حجزك لا يزال محفوظًا</p>
                    </div>

                    <div class="appointment-info bg-light p-4 rounded-4 mb-4 shadow-sm">
                        <div class="d-flex align-items-center border-bottom pb-3 mb-3">
                            <div class="icon-box bg-primary-soft me-3">
                                <i class="bi bi-info-circle text-primary"></i>
                            </div>
                            <h5 class="m-0">تفاصيل الحجز</h5>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-icon bg-primary-soft">
                                        <i class="bi bi-person-badge text-primary"></i>
                                    </div>
                                    <div class="detail-content">
                                        <span class="detail-label">الطبيب</span>
                                        <span class="detail-value">{{ $appointment->doctor->name }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-icon bg-info-soft">
                                        <i class="bi bi-calendar-date text-info"></i>
                                    </div>
                                    <div class="detail-content">
                                        <span class="detail-label">التاريخ</span>
                                        <span class="detail-value">{{ $appointment->formatted_date }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-icon bg-info-soft">
                                        <i class="bi bi-clock text-info"></i>
                                    </div>
                                    <div class="detail-content">
                                        <span class="detail-label">الوقت</span>
                                        <span class="detail-value">{{ $appointment->scheduled_at->format('h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-icon bg-warning-soft">
                                        <i class="bi bi-cash-coin text-warning"></i>
                                    </div>
                                    <div class="detail-content">
                                        <span class="detail-label">رسوم الكشف</span>
                                        <span class="detail-value">
                                            <span class="">
                                                {{ number_format($appointment->fees) }} ج.م
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="appointment-reference mt-4 pt-3 border-top">
                            <div class="d-flex align-items-center">
                                <div class="detail-icon bg-secondary-soft me-3">
                                    <i class="bi bi-qr-code text-secondary"></i>
                                </div>
                                <div>
                                    <span class="detail-label">رقم الحجز</span>
                                    <span class="detail-value">#{{ $appointment->id }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="warning-message bg-warning-soft p-3 rounded-4 mb-4 text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-info-circle fs-4 text-warning me-2"></i>
                            <span class="text-dark">يمكنك إتمام الدفع في وقت لاحق ولكن تأكد من استكمال الدفع قبل موعد الحجز</span>
                        </div>
                    </div>

                    <div class="actions d-flex flex-column flex-md-row gap-3 justify-content-center align-items-center">
                        <a href="{{ route('payments.checkout', $appointment) }}" class="btn btn-warning btn-lg px-4">
                            <i class="bi bi-credit-card me-2"></i> المحاولة مرة أخرى
                        </a>
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="bi bi-calendar-check me-2"></i> العودة للحجز
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-decoration-none">
                    <i class="bi bi-house-door me-1"></i>
                    العودة للصفحة الرئيسية
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .cancel-card {
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .cancel-card:hover {
        transform: translateY(-5px);
    }

    .cancel-icon-wrapper {
        text-align: center;
        padding-bottom: 1.5rem;
    }

    .cancel-icon {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .icon-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto;
        animation: pulse 1.5s infinite;
    }

    .icon-circle i {
        font-size: 50px;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(255, 193, 7, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
        }
    }

    .bg-warning-soft {
        background-color: rgba(255, 193, 7, 0.1);
    }

    .bg-primary-soft {
        background-color: rgba(79, 70, 229, 0.1);
    }

    .bg-info-soft {
        background-color: rgba(0, 195, 237, 0.1);
    }

    .bg-secondary-soft {
        background-color: rgba(108, 117, 125, 0.1);
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .detail-icon {
        min-width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        display: block;
        font-size: 0.813rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        display: block;
        font-weight: 600;
        font-size: 1.05rem;
    }

    .icon-box {
        min-width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .actions {
        margin-top: 1rem;
    }

    @media (max-width: 768px) {
        .actions {
            flex-direction: column;
            gap: 0.75rem;
        }

        .actions .btn {
            width: 100%;
        }
    }
</style>
@endpush
