@extends('layouts.admin')

@section('title', 'تفاصيل المدفوعات')

@section('header_icon')
    <i class="bi bi-cash-coin text-success me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('admin.payments.index') }}" class="text-decoration-none">المدفوعات</a>
    </li>
    <li class="breadcrumb-item active">تفاصيل المدفوعات</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Payment Details Card -->
            <div class="card shadow mb-4 border-0">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-primary text-white">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-info-circle me-2"></i> تفاصيل المعاملة</h6>
                </div>
                <div class="card-body p-4">
                    <!-- Payment Amount Card -->
                    <div class="mb-4">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="bg-light rounded-lg p-4 text-center shadow-sm">
                                    <h5 class="text-muted mb-3">إجمالي المبلغ</h5>
                                    <h1 class="display-4 font-weight-bold text-primary mb-0">{{ $payment->amount }} <small class="text-muted fs-6">{{ $payment->currency }}</small></h1>
                                    <p class="text-muted mt-3">
                                        <i class="fas {{ $payment->payment_method == 'stripe' ? 'fa-credit-card' : 'fa-money-bill-wave' }} me-1"></i>
                                        {{ $payment->payment_method == 'stripe' ? 'بطاقة ائتمان' : 'نقدي' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <div class="card border-left-primary h-100">
                                <div class="card-body p-4">
                                    <h5 class="text-primary border-bottom pb-2 d-flex align-items-center">
                                        <i class="fas fa-money-check-alt me-2"></i> معلومات الدفع
                                    </h5>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <tr>
                                                <th class="bg-light" style="width: 40%">معرف المعاملة</th>
                                                <td><strong class="text-dark">{{ $payment->transaction_id }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">حالة الدفع</th>
                                                <td>
                                                    @if ($payment->status == 'completed')
                                                        <span class="badge badge-pill bg-success text-white"><i class="fas fa-check-circle me-1"></i> مكتمل</span>
                                                    @elseif ($payment->status == 'pending')
                                                        <span class="badge badge-pill bg-warning text-dark"><i class="fas fa-clock me-1"></i> معلق</span>
                                                    @elseif ($payment->status == 'failed')
                                                        <span class="badge badge-pill bg-danger text-white"><i class="fas fa-times-circle me-1"></i> مرفوض</span>
                                                    @elseif ($payment->status == 'refunded')
                                                        <span class="badge badge-pill bg-info text-white"><i class="fas fa-undo me-1"></i> مسترجع</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">طريقة الدفع</th>
                                                <td>
                                                    @if ($payment->payment_method == 'stripe')
                                                        <span><i class="fab fa-cc-stripe me-1 text-primary fs-5"></i> بطاقة ائتمان</span>
                                                    @elseif ($payment->payment_method == 'cash')
                                                        <span><i class="fas fa-money-bill-wave me-1 text-success fs-5"></i> نقدي</span>
                                                    @else
                                                        {{ $payment->payment_method }}
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">تاريخ الإنشاء</th>
                                                <td><i class="far fa-calendar-alt text-muted me-1"></i> {{ $payment->created_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                            <tr>
                                                <th class="bg-light">تاريخ الدفع</th>
                                                <td>
                                                    @if($payment->paid_at)
                                                        <i class="far fa-calendar-check text-success me-1"></i> {{ $payment->paid_at->format('Y-m-d H:i:s') }}
                                                    @else
                                                        <span class="text-muted">غير متوفر</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card border-left-info h-100">
                                <div class="card-body p-4">
                                    <h5 class="text-primary border-bottom pb-2 d-flex align-items-center">
                                        <i class="fas fa-calendar-check me-2"></i> معلومات الحجز المرتبط
                                    </h5>
                                    @if ($payment->appointment)
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <tr>
                                                    <th class="bg-light" style="width: 40%">رقم الحجز</th>
                                                    <td>
                                                        <span class="font-weight-bold">#{{ $payment->appointment->id }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">الطبيب</th>
                                                    <td>
                                                        @if ($payment->appointment->doctor)
                                                            <i class="fas fa-user-md text-primary me-1"></i> {{ $payment->appointment->doctor->name }}
                                                        @else
                                                            <span class="text-muted">غير متوفر</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">المريض</th>
                                                    <td>
                                                        @if ($payment->appointment->patient)
                                                            <i class="fas fa-user text-info me-1"></i> {{ $payment->appointment->patient->name }}
                                                        @else
                                                            <span class="text-muted">غير متوفر</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">تاريخ الحجز</th>
                                                    <td><i class="far fa-calendar-alt text-muted me-1"></i> {{ $payment->appointment->formatted_date }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">وقت الحجز</th>
                                                    <td><i class="far fa-clock text-muted me-1"></i> {{ $payment->appointment->formatted_time }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="bg-light">حالة الحجز</th>
                                                    <td>
                                                        <span class="badge badge-pill bg-{{ $payment->appointment->status_color }} {{ in_array($payment->appointment->status_color, ['warning', 'light']) ? 'text-dark' : 'text-white' }}">
                                                            <i class="fas fa-circle fa-xs me-1"></i> {{ $payment->appointment->status_text }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
                                            <i class="fas fa-info-circle fa-2x me-3 text-info"></i>
                                            <div>لا يوجد حجز مرتبط بعملية الدفع هذه.</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($payment->metadata)
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="card border-left-dark">
                                    <div class="card-body">
                                        <h5 class="text-primary border-bottom pb-2 d-flex align-items-center">
                                            <i class="fas fa-code me-2"></i> بيانات إضافية
                                        </h5>
                                        <div class="bg-light rounded p-3" style="max-height: 250px; overflow-y: auto;">
                                            <pre class="mb-0 text-dark">{{ json_encode($payment->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Payment Summary Card -->
            <div class="card shadow mb-4 border-0">
                <div class="card-header py-3 bg-gradient-success text-white d-flex align-items-center">
                    <i class="fas fa-chart-pie me-2"></i>
                    <h6 class="m-0 font-weight-bold">ملخص الدفع</h6>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        @if ($payment->status == 'completed')
                            <div class="avatar avatar-4xl mb-3">
                                <div class="avatar-title rounded-circle bg-soft-success text-success pulse-success">
                                    <i class="fas fa-check-circle fa-4x"></i>
                                </div>
                            </div>
                            <h4 class="text-success mb-2">تم الدفع بنجاح</h4>
                            <p class="text-muted">تمت معالجة المدفوعات بنجاح</p>
                        @elseif ($payment->status == 'pending')
                            <div class="avatar avatar-4xl mb-3">
                                <div class="avatar-title rounded-circle bg-soft-warning text-warning pulse-warning">
                                    <i class="fas fa-clock fa-4x"></i>
                                </div>
                            </div>
                            <h4 class="text-warning mb-2">في انتظار الدفع</h4>
                            <p class="text-muted">لم يتم تأكيد المدفوعات بعد</p>
                        @elseif ($payment->status == 'failed')
                            <div class="avatar avatar-4xl mb-3">
                                <div class="avatar-title rounded-circle bg-soft-danger text-danger">
                                    <i class="fas fa-times-circle fa-4x"></i>
                                </div>
                            </div>
                            <h4 class="text-danger mb-2">فشل الدفع</h4>
                            <p class="text-muted">حدث خطأ أثناء معالجة المدفوعات</p>
                        @elseif ($payment->status == 'refunded')
                            <div class="avatar avatar-4xl mb-3">
                                <div class="avatar-title rounded-circle bg-soft-info text-info">
                                    <i class="fas fa-undo fa-4x"></i>
                                </div>
                            </div>
                            <h4 class="text-info mb-2">تم استرداد المبلغ</h4>
                            <p class="text-muted">تم رد المدفوعات إلى العميل</p>
                        @endif
                    </div>

                    <div class="card bg-light mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">المبلغ الإجمالي</span>
                                <span class="h5 mb-0 font-weight-bold text-primary">{{ $payment->amount }} {{ $payment->currency }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-primary rounded-pill">
                            <i class="fas fa-arrow-left me-1"></i> العودة إلى قائمة المدفوعات
                        </a>
                    </div>
                </div>
            </div>

            <!-- Payment Status Timeline -->
            <div class="card shadow mb-4 border-0">
                <div class="card-header py-3 bg-gradient-primary text-white d-flex align-items-center">
                    <i class="fas fa-history me-2"></i>
                    <h6 class="m-0 font-weight-bold">سجل الدفع</h6>
                </div>
                <div class="card-body p-4">
                    <div class="timeline">
                        @if ($payment->paid_at)
                            <div class="timeline-item">
                                <div class="timeline-item-marker">
                                    <div class="timeline-item-marker-text">{{ $payment->paid_at->format('H:i') }}</div>
                                    <div class="timeline-item-marker-indicator bg-success"><i class="fas fa-check text-white fa-xs"></i></div>
                                </div>
                                <div class="timeline-item-content bg-light p-3 rounded shadow-sm">
                                    <strong>تم اكتمال عملية الدفع</strong>
                                    <div class="text-muted"><small><i class="far fa-calendar-alt me-1"></i> {{ $payment->paid_at->format('d/m/Y') }}</small></div>
                                </div>
                            </div>
                        @endif

                        <div class="timeline-item">
                            <div class="timeline-item-marker">
                                <div class="timeline-item-marker-text">{{ $payment->created_at->format('H:i') }}</div>
                                <div class="timeline-item-marker-indicator bg-primary"><i class="fas fa-plus text-white fa-xs"></i></div>
                            </div>
                            <div class="timeline-item-content bg-light p-3 rounded shadow-sm">
                                <strong>تم إنشاء عملية الدفع</strong>
                                <div class="text-muted"><small><i class="far fa-calendar-alt me-1"></i> {{ $payment->created_at->format('d/m/Y') }}</small></div>
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
    .card {
        border-radius: 15px;
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.07);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-bottom: none;
        padding: 1rem 1.5rem;
    }

    .bg-gradient-primary {
        background: linear-gradient(to right, #4e73df, #224abe);
    }

    .bg-gradient-success {
        background: linear-gradient(to right, #1cc88a, #13855c);
    }

    .table {
        margin-bottom: 0;
    }

    .table th, .table td {
        padding: 0.85rem;
        vertical-align: middle;
        border-top: none;
        border-bottom: 1px solid #e3e6f0;
    }

    .table th {
        color: #5a5c69;
        font-weight: 600;
        border-radius: 5px 0 0 5px;
    }

    .table td {
        border-radius: 0 5px 5px 0;
    }




    .btn {
        font-weight: 600;
        letter-spacing: 0.5px;
        padding: 0.5rem 1.2rem;
        transition: all 0.2s;
    }

    .btn:hover {
        transform: translateY(-2px);
    }

    .btn-sm {
        padding: 0.35rem 0.8rem;
        font-size: 0.85rem;
    }

    .btn-icon-split {
        display: inline-flex;
        align-items: center;
    }

    .btn-icon-split .icon {
        background: rgba(0, 0, 0, 0.15);
        padding: 0.6rem 0.75rem;
        border-radius: 50rem 0 0 50rem;
        margin-right: 2px;
    }

    .btn-icon-split .text {
        padding: 0.6rem 1rem;
    }

    .rounded-pill {
        border-radius: 50rem !important;
    }

    .timeline {
        position: relative;
        padding-left: 1.5rem;
        margin: 0;
    }

    .timeline:before {
        content: '';
        position: absolute;
        left: 0.75rem;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #4e73df 0%, #e9ecef 100%);
    }

    .timeline-item {
        position: relative;
        padding-bottom: 2rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-item-marker {
        position: absolute;
        left: -1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .timeline-item-marker-text {
        font-size: 0.75rem;
        color: #858796;
        margin-bottom: 0.25rem;
        width: 3.5rem;
        text-align: center;
    }

    .timeline-item-marker-indicator {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 100%;
        box-shadow: 0 0 0 4px white;
    }

    .timeline-item-content {
        width: 100%;
        line-height: 1.4;
        transition: all 0.2s ease;
        margin-bottom: 0.75rem;
    }

    .timeline-item:hover .timeline-item-content {
        transform: translateX(5px);
    }

    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .avatar-4xl {
        width: 7rem;
        height: 7rem;
        font-size: 1.5rem;
    }

    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-soft-success {
        background-color: rgba(28, 200, 138, 0.1);
    }

    .bg-soft-warning {
        background-color: rgba(246, 194, 62, 0.1);
    }

    .bg-soft-danger {
        background-color: rgba(231, 74, 59, 0.1);
    }

    .bg-soft-info {
        background-color: rgba(54, 185, 204, 0.1);
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-dark {
        border-left: 0.25rem solid #5a5c69 !important;
    }

    .pulse-success {
        animation: pulse-success 2s infinite;
    }

    .pulse-warning {
        animation: pulse-warning 2s infinite;
    }

    @keyframes pulse-success {
        0% {
            box-shadow: 0 0 0 0 rgba(28, 200, 138, 0.7);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(28, 200, 138, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(28, 200, 138, 0);
        }
    }

    @keyframes pulse-warning {
        0% {
            box-shadow: 0 0 0 0 rgba(246, 194, 62, 0.7);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(246, 194, 62, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(246, 194, 62, 0);
        }
    }

    .progress {
        border-radius: 1rem;
        height: 6px;
    }

    .display-4 {
        font-size: 2.5rem;
        font-weight: 300;
        line-height: 1.2;
    }
</style>
@endsection
