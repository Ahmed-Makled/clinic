@extends('dashboard::layouts.master')

@section('title', 'تفاصيل المدفوعات')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تفاصيل المدفوعات</h1>
        <a href="{{ route('admin.payments.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> العودة للمدفوعات
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Payment Details Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">تفاصيل عملية الدفع</h6>
                    <div class="dropdown no-arrow">
                        @if ($payment->status == 'pending')
                            <div class="btn-group">
                                <form method="POST" action="{{ route('admin.payments.mark-completed', $payment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('هل أنت متأكد من تحديث حالة الدفع إلى مكتمل؟')">
                                        <i class="fas fa-check fa-sm"></i> تأكيد الدفع
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.payments.mark-failed', $payment) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-danger ml-2" onclick="return confirm('هل أنت متأكد من تحديث حالة الدفع إلى فاشل؟')">
                                        <i class="fas fa-times fa-sm"></i> تعليم كفاشل
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h5 class="text-muted">معلومات الدفع</h5>
                            <table class="table table-bordered table-sm">
                                <tr>
                                    <th style="width: 40%">معرف المعاملة</th>
                                    <td><strong>{{ $payment->transaction_id }}</strong></td>
                                </tr>
                                <tr>
                                    <th>المبلغ</th>
                                    <td>{{ $payment->amount }} {{ $payment->currency }}</td>
                                </tr>
                                <tr>
                                    <th>حالة الدفع</th>
                                    <td>
                                        @if ($payment->status == 'completed')
                                            <span class="badge badge-success">مكتمل</span>
                                        @elseif ($payment->status == 'pending')
                                            <span class="badge badge-warning">معلق</span>
                                        @elseif ($payment->status == 'failed')
                                            <span class="badge badge-danger">فاشل</span>
                                        @elseif ($payment->status == 'refunded')
                                            <span class="badge badge-info">مسترجع</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>طريقة الدفع</th>
                                    <td>
                                        @if ($payment->payment_method == 'stripe')
                                            <span><i class="fab fa-cc-stripe me-1"></i> بطاقة ائتمان</span>
                                        @elseif ($payment->payment_method == 'cash')
                                            <span><i class="fas fa-money-bill-wave me-1"></i> نقدي</span>
                                        @else
                                            {{ $payment->payment_method }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>تاريخ الإنشاء</th>
                                    <td>{{ $payment->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>تاريخ الدفع</th>
                                    <td>{{ $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : 'غير متوفر' }}</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6 mb-4">
                            <h5 class="text-muted">معلومات الحجز المرتبط</h5>
                            @if ($payment->appointment)
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th style="width: 40%">رقم الحجز</th>
                                        <td>
                                            <a href="{{ route('admin.appointments.show', $payment->appointment) }}">
                                                {{ $payment->appointment->id }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>الطبيب</th>
                                        <td>
                                            @if ($payment->appointment->doctor)
                                                <a href="{{ route('admin.doctors.show', $payment->appointment->doctor) }}">
                                                    {{ $payment->appointment->doctor->name }}
                                                </a>
                                            @else
                                                غير متوفر
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>المريض</th>
                                        <td>
                                            @if ($payment->appointment->patient)
                                                <a href="{{ route('admin.patients.show', $payment->appointment->patient) }}">
                                                    {{ $payment->appointment->patient->name }}
                                                </a>
                                            @else
                                                غير متوفر
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الحجز</th>
                                        <td>{{ $payment->appointment->formatted_date }}</td>
                                    </tr>
                                    <tr>
                                        <th>وقت الحجز</th>
                                        <td>{{ $payment->appointment->formatted_time }}</td>
                                    </tr>
                                    <tr>
                                        <th>حالة الحجز</th>
                                        <td>
                                            <span class="badge badge-{{ $payment->appointment->status_color }}">
                                                {{ $payment->appointment->status_text }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <div class="alert alert-info">
                                    لا يوجد حجز مرتبط بعملية الدفع هذه.
                                </div>
                            @endif
                        </div>
                    </div>

                    @if ($payment->metadata)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-muted">بيانات إضافية</h5>
                                <div class="card">
                                    <div class="card-body" style="max-height: 250px; overflow-y: auto;">
                                        <pre class="mb-0">{{ json_encode($payment->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Payment Status Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">سجل الدفع</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @if ($payment->paid_at)
                            <div class="timeline-item">
                                <div class="timeline-item-marker">
                                    <div class="timeline-item-marker-text">{{ $payment->paid_at->format('H:i') }}</div>
                                    <div class="timeline-item-marker-indicator bg-success"></div>
                                </div>
                                <div class="timeline-item-content">
                                    تم اكتمال عملية الدفع
                                    <span class="text-muted">{{ $payment->paid_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-text">{{ $payment->created_at->format('H:i') }}</div>
                                <div class="timeline-item-marker-indicator bg-primary"></div>
                            </div>
                            <div class="timeline-item-content">
                                تم إنشاء عملية الدفع
                                <span class="text-muted">{{ $payment->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .timeline {
        position: relative;
        padding-left: 1rem;
        margin: 1rem 0;
        border-left: 1px solid #dee2e6;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-item-marker {
        position: absolute;
        left: -1.3rem;
        width: 1.5rem;
        height: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .timeline-item-marker-text {
        font-size: 0.8rem;
        color: #a7aeb8;
        margin-bottom: 0.25rem;
        width: 4rem;
        text-align: center;
        margin-left: -1.3rem;
    }
    .timeline-item-marker-indicator {
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 100%;
        background-color: #e9ecef;
    }
    .timeline-item-content {
        padding-top: 0.15rem;
        padding-bottom: 0.15rem;
        padding-left: 1.5rem;
    }
</style>
@endsection
