@extends('layouts.admin')

@section('title', 'لوحة التحكم')

@section('header_icon')
    <i class="bi bi-speedometer2 text-primary me-2 fs-5"></i>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item active">لوحة التحكم</li>
@endsection

@section('content')
    <!-- Detailed Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Users Card -->
        <div class="col-md-6 col-lg-4">
            <div class="stat-card users">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title d-flex align-items-center gap-2">
                            المستخدمين
                            <div class="growth-badge bg-success-subtle text-success px-2 py-1 rounded-pill">
                                <small>
                                    <i class="bi bi-person-plus-fill"></i>
                                    {{ $stats['total_users']['today'] }} جديد
                                </small>
                            </div>
                        </h3>
                        <div class="stat-total d-flex align-items-center gap-2">
                            <div class="total-number">{{ $stats['total_users']['total'] }}</div>
                            <div class="total-distribution d-flex gap-1">
                                <span class="distribution-item doctors" title="الأطباء">
                                    <i class="bi bi-person-badge-fill"></i>
                                    {{ $stats['doctors']['total'] }}
                                </span>
                                <span class="distribution-item patients" title="المرضى">
                                    <i class="bi bi-person-fill"></i>
                                    {{ $stats['patients']['total'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stat-details">
                    <!-- User Categories -->
                    <div class="detail-section mb-3">
                        <div class="categories-grid">
                            <div class="category-card doctors">
                                <div class="category-icon">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <div class="category-info">
                                    <h4 class="category-title">الأطباء</h4>
                                    <div class="category-stats">
                                        <div class="main-stat">{{ $stats['doctors']['total'] }}</div>
                                        <div class="sub-stats">
                                            <span class="active-stat" title="الأطباء النشطين">
                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                {{ $stats['doctors']['active'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="category-progress">
                                        <div class="progress" style="height: 4px">
                                            <div class="progress-bar bg-primary"
                                                style="width: {{ ($stats['doctors']['active'] / $stats['doctors']['total']) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="category-card patients">
                                <div class="category-icon">
                                    <i class="bi bi-person-fill"></i>
                                </div>
                                <div class="category-info">
                                    <h4 class="category-title">المرضى</h4>
                                    <div class="category-stats">
                                        <div class="main-stat">{{ $stats['patients']['total'] }}</div>
                                        <div class="sub-stats">
                                            <span class="active-stat" title="المرضى النشطين">
                                                <i class="bi bi-calendar-check-fill text-primary"></i>
                                                {{ $stats['patients']['with_appointments'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="category-progress">
                                        <div class="progress" style="height: 4px">
                                            <div class="progress-bar bg-info"
                                                style="width: {{ ($stats['patients']['with_appointments'] / $stats['patients']['total']) * 100 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Activity -->
                    <div class="detail-section">
                        <div class="activity-stats">
                            <div class="activity-header d-flex justify-content-between align-items-center mb-3">
                                <h6 class="detail-title mb-0">نشاط المستخدمين</h6>
                                <div class="activity-period badge bg-primary-subtle text-primary">
                                    اليوم
                                </div>
                            </div>
                            <div class="activity-metrics">
                                <div class="metric-row">
                                    <div class="metric-item">
                                        <div class="metric-icon">
                                            <i class="bi bi-calendar2-plus"></i>
                                        </div>
                                        <div class="metric-info">
                                            <div class="metric-value">{{ $stats['appointments']['today'] }}</div>
                                            <div class="metric-label">موعد جديد</div>
                                        </div>
                                    </div>
                                    <div class="metric-item">
                                        <div class="metric-icon">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="metric-info">
                                            <div class="metric-value">{{ $stats['patients']['with_appointments'] }}</div>
                                            <div class="metric-label">مريض نشط</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .growth-badge {
                        font-size: 0.75rem;
                    }

                    .total-distribution {
                        font-size: 0.875rem;
                    }

                    .distribution-item {
                        padding: 0.25rem 0.5rem;
                        border-radius: 4px;
                        font-size: 0.75rem;
                        font-weight: 500;
                    }

                    .distribution-item.doctors {
                        background-color: var(--primary-bg-subtle);
                        color: var(--primary-color);
                    }

                    .distribution-item.patients {
                        background-color: var(--info-bg-subtle);
                        color: var(--info-color);
                    }

                    .categories-grid {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 1rem;
                    }

                    .category-card {
                        background: white;
                        border-radius: 12px;
                        padding: 1rem;
                        display: flex;
                        gap: 1rem;
                        align-items: flex-start;
                        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                    }

                    .category-icon {
                        width: 40px;
                        height: 40px;
                        border-radius: 10px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.25rem;
                    }

                    .category-card.doctors .category-icon {
                        background-color: var(--primary-bg-subtle);
                        color: var(--primary-color);
                    }

                    .category-card.patients .category-icon {
                        background-color: var(--info-bg-subtle);
                        color: var(--info-color);
                    }

                    .category-info {
                        flex: 1;
                    }

                    .category-title {
                        font-size: 0.875rem;
                        color: #64748b;
                        margin-bottom: 0.5rem;
                    }

                    .category-stats {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-end;
                        margin-bottom: 0.5rem;
                    }

                    .main-stat {
                        font-size: 1.25rem;
                        font-weight: 600;
                        color: #1e293b;
                    }

                    .sub-stats {
                        display: flex;
                        gap: 0.5rem;
                    }

                    .active-stat {
                        font-size: 0.75rem;
                        color: #64748b;
                    }

                    .category-progress {
                        margin-top: 0.5rem;
                    }

                    .activity-stats {

                        padding: 1rem;
                    }

                    .metric-row {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 1rem;
                    }

                    .metric-item {
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        padding: 0.75rem;
                        background: white;
                        border-radius: 8px;
                        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
                    }

                    .metric-icon {
                        width: 32px;
                        height: 32px;
                        border-radius: 8px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1rem;
                        background-color: var(--primary-bg-subtle);
                        color: var(--primary-color);
                    }

                    .metric-info {
                        flex: 1;
                    }

                    .metric-value {
                        font-size: 1.125rem;
                        font-weight: 600;
                        color: #1e293b;
                        line-height: 1;
                        margin-bottom: 0.25rem;
                    }

                    .metric-label {
                        font-size: 0.75rem;
                        color: #64748b;
                    }

                    @media (max-width: 768px) {
                        .categories-grid {
                            grid-template-columns: 1fr;
                        }

                        .metric-row {
                            grid-template-columns: 1fr;
                        }
                    }
                </style>
            </div>
        </div>

        <!-- Appointments Card -->
        <div class="col-md-6 col-lg-4">
            <div class="stat-card appointments">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-week"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">الحجوزات</h3>
                        <div class="stat-total d-flex align-items-center">
                            <span class="me-2">{{ $stats['appointments']['total'] }}</span>
                            <div class="stat-badge bg-info-subtle text-info">
                                إجمالي الحجوزات
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-details">
                    <!-- Today's Stats -->
                    <div class="detail-section mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="detail-title mb-0">إحصائيات اليوم</h6>
                            <div class="today-stat badge bg-primary-subtle text-primary">
                                {{ $stats['appointments']['today'] }} موعد
                            </div>
                        </div>
                        <div class="appointments-timeline p-2 rounded-3">
                            <div class="d-flex justify-content-between">
                                <div class="timeline-segment completed">
                                    <div class="segment-value">{{ $stats['appointments']['completed'] }}</div>
                                    <div class="segment-label text-success">مكتملة</div>
                                </div>
                                <div class="timeline-segment scheduled">
                                    <div class="segment-value">{{ $stats['appointments']['scheduled'] }}</div>
                                    <div class="segment-label text-warning">قيد الانتظار</div>
                                </div>
                                <div class="timeline-segment cancelled">
                                    <div class="segment-value">{{ $stats['appointments']['cancelled'] }}</div>
                                    <div class="segment-label text-danger">ملغية</div>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 8px;">
                                @php
                                    $totalAppointments = $stats['appointments']['total'] > 0 ? $stats['appointments']['total'] : 1;
                                    $completedPercentage = ($stats['appointments']['completed'] / $totalAppointments) * 100;
                                    $scheduledPercentage = ($stats['appointments']['scheduled'] / $totalAppointments) * 100;
                                    $cancelledPercentage = ($stats['appointments']['cancelled'] / $totalAppointments) * 100;
                                @endphp
                                <div class="progress-bar bg-success" style="width: {{ $completedPercentage }}%"
                                    role="progressbar"></div>
                                <div class="progress-bar bg-warning" style="width: {{ $scheduledPercentage }}%"
                                    role="progressbar"></div>
                                <div class="progress-bar bg-danger" style="width: {{ $cancelledPercentage }}%"
                                    role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .timeline-segment {
                        text-align: center;
                        padding: 0.5rem;
                        min-width: 80px;
                    }

                    .segment-value {
                        font-size: 1.25rem;
                        font-weight: 600;
                        margin-bottom: 0.25rem;
                    }

                    .segment-label {
                        font-size: 0.75rem;
                        font-weight: 500;
                    }
                </style>
            </div>
        </div>

        <!-- Financial Card -->
        <div class="col-md-6 col-lg-4">
            <div class="stat-card finance">
                <div class="stat-header">
                    <div class="stat-icon">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="stat-info">
                        <h3 class="stat-title">المالية</h3>
                        <div class="stat-total d-flex align-items-center">
                            <span
                                class="me-2">{{ number_format($stats['financial']['total_income'] + $stats['financial']['pending_payments']) }}
                                ج.م</span>
                            <div
                                class="stat-badge {{ $stats['financial']['collection_percentage'] >= 70 ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }}">
                                إجمالي المستحقات
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-details">
                    <!-- Financial Summary -->
                    <div class="detail-section mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="detail-title mb-0">ملخص المدفوعات</h6>
                            <div
                                class="trend-indicator {{ $stats['financial']['total_income'] > 0 ? 'positive' : 'neutral' }}">
                                <i
                                    class="bi {{ $stats['financial']['total_income'] > 0 ? 'bi-graph-up-arrow' : 'bi-dash' }}"></i>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-item">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <div class="detail-info">
                                    <span class="detail-label">المبالغ المحصلة</span>
                                    <span class="detail-value">{{ number_format($stats['financial']['total_income']) }}
                                        ج.م</span>
                                    <small class="detail-sub">تم التحصيل بنجاح</small>
                                </div>
                            </div>
                            <div class="detail-item">
                                <i class="bi bi-hourglass-split text-warning"></i>
                                <div class="detail-info">
                                    <span class="detail-label">المبالغ المعلقة</span>
                                    <span class="detail-value">{{ number_format($stats['financial']['pending_payments']) }}
                                        ج.م</span>
                                    <small class="detail-sub">في انتظار التحصيل</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Collection Progress -->
                    <div class="detail-section">
                        <div class="collection-progress">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="detail-title mb-0">معدل التحصيل</h6>
                                    <p class="text-muted small mb-0">نسبة المبالغ المحصلة من الإجمالي</p>
                                </div>
                                <div
                                    class="progress-percentage {{ $stats['financial']['collection_percentage'] >= 70 ? 'text-success' : 'text-warning' }}">
                                    <span class="h3 mb-0 fw-bold">{{ $stats['financial']['collection_percentage'] }}%</span>
                                </div>
                            </div>
                            <div class="progress collection-bar" style="height: 10px;">
                                <div class="progress-bar {{ $stats['financial']['collection_percentage'] >= 70 ? 'bg-success' : 'bg-warning' }}"
                                    role="progressbar" style="width: {{ $stats['financial']['collection_percentage'] }}%"
                                    aria-valuenow="{{ $stats['financial']['collection_percentage'] }}" aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .stat-badge {
                        font-size: 0.75rem;
                        padding: 0.25rem 0.5rem;
                        border-radius: 6px;
                        font-weight: 500;
                    }

                    .trend-indicator {
                        width: 24px;
                        height: 24px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 50%;
                        font-size: 14px;
                    }

                    .trend-indicator.positive {
                        background-color: var(--success-bg-subtle);
                        color: var(--success-color);
                    }

                    .trend-indicator.neutral {
                        background-color: var(--secondary-bg-subtle);
                        color: var(--secondary-color);
                    }

                    .collection-progress {
                        padding: 1rem;
                    }

                    .progress-percentage {
                        font-weight: 600;
                    }

                    .collection-bar {
                        box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
                        border-radius: 6px;
                    }
                </style>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Appointments Trend -->
        <div class="col-md-8">
            <div class="card chart-card">
                <div class="card-header bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            اتجاه الحجوزات
                        </h5>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="appointmentsChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Specialties Distribution -->
        <div class="col-md-4">
            <div class="card chart-card">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0">
                        <i class="bi bi-pie-chart-fill me-2"></i>
                        توزيع التخصصات
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="specialtiesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities & Reminders Row -->
    <div class="row g-4">
        <!-- Recent Activities -->
        <div class="col-md-8">
            <div class="card activities-card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-activity me-2"></i>
                        آخر النشاطات
                    </h5>
                    <span class="badge bg-primary-subtle text-primary">اليوم</span>
                </div>
                <div class="activities-container" style="max-height: 500px; overflow-y: auto;">
                    @forelse($activities as $activity)
                        <div class="activity-item">
                            <div class="d-flex">
                                <div class="activity-icon-wrapper">
                                    <div class="activity-icon {{ $activity['status_color'] }}">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div class="activity-line"></div>
                                </div>
                                <div class="activity-content">
                                    <div class="activity-header">
                                        <h6 class="activity-title mb-1">
                                            موعد جديد
                                            <span class="badge {{ $activity['status_color'] }} ms-2">
                                                {{ $activity['status'] === 'completed' ? 'مكتمل' :
                                                   ($activity['status'] === 'cancelled' ? 'ملغي' : 'جديد') }}
                                            </span>
                                        </h6>
                                        <span class="activity-time">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ Carbon\Carbon::parse($activity['scheduled_at'])->format('h:i A') }}
                                        </span>
                                    </div>
                                    <div class="activity-details">
                                        <div class="patient-info">
                                            <i class="bi bi-person text-primary me-1"></i>
                                            <strong>{{ $activity['patient_name'] }}</strong>
                                        </div>
                                        <div class="doctor-info">
                                            <i class="bi bi-person-badge text-info me-1"></i>
                                            <strong>{{ $activity['doctor_name'] }}</strong>
                                        </div>
                                        @if(isset($activity['fees']))
                                            <div class="fees-info {{ $activity['is_paid'] ? 'text-success' : 'text-warning' }}">
                                                <i class="bi {{ $activity['is_paid'] ? 'bi-check-circle' : 'bi-hourglass-split' }} me-1"></i>
                                                {{ number_format($activity['fees']) }} ج.م
                                                <small>({{ $activity['is_paid'] ? 'مدفوع' : 'غير مدفوع' }})</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                            <h6>لا توجد نشاطات حديثة</h6>
                            <p class="text-muted small">ستظهر هنا آخر النشاطات في النظام</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Reminders & Quick Actions -->
        <div class="col-md-4">
            <div class="card reminders-card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="bi bi-bell me-2"></i>
                        التذكيرات
                    </h5>

                </div>
                <div class="card-body p-0">
                    <div class="reminder-list" style="max-height: 450px; overflow-y: auto;">
                        @if($stats['pending_appointments'] > 0)
                            <div class="reminder-item pending-appointments">
                                <div class="reminder-icon">
                                    <i class="bi bi-calendar2-week"></i>
                                </div>
                                <div class="reminder-content pending-appointments">
                                    <h6 class="reminder-title">مواعيد في الانتظار</h6>
                                    <p class="reminder-text mb-2">
                                        {{ $stats['pending_appointments'] }} موعد في قائمة الانتظار
                                    </p>
                                    <a href="{{ route('appointments.index', ['status' => 'pending']) }}"
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-eye me-1"></i>
                                        عرض المواعيد
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if($stats['unpaid_fees'] > 0)
                            <div class="reminder-item unpaid-fees">
                                <div class="reminder-icon">
                                    <i class="bi bi-cash-stack"></i>
                                </div>
                                <div class="reminder-content">
                                    <h6 class="reminder-title">رسوم غير محصلة</h6>
                                    <p class="reminder-text mb-2 ">
                                        {{ number_format($stats['unpaid_fees']) }} ج.م في انتظار التحصيل
                                    </p>
                                    <a href="{{ route('appointments.index', ['payment_status' => 'unpaid']) }}"
                                       class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-wallet2 me-1"></i>
                                        إدارة المدفوعات
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if(!$stats['pending_appointments'] && !$stats['unpaid_fees'])
                            <div class="empty-state">
                                <div class="empty-state-icon success">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <h6>لا توجد تذكيرات</h6>
                                <p class="text-muted small">كل شيء تحت السيطرة!</p>
                            </div>
                        @endif
                    </div>
                </div>

                <style>
                    .activity-item {
                        padding: 1.25rem;
                        border-bottom: 1px solid var(--border-color);
                        position: relative;
                    }

                    .activity-icon-wrapper {
                        position: relative;
                        margin-left: 1rem;
                    }

                    .activity-icon {
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: white;
                        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                        position: relative;
                        z-index: 1;
                    }

                    .activity-line {
                        position: absolute;
                        top: 40px;
                        bottom: -40px;
                        left: 50%;
                        width: 2px;
                        background: var(--border-color);
                    }

                    .activity-item:last-child .activity-line {
                        display: none;
                    }

                    .activity-content {
                        flex: 1;
                    }

                    .activity-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                    }

                    .activity-title {
                        font-size: 0.9rem;
                        color: var(--bs-gray-700);
                        margin: 0;
                    }

                    .activity-time {
                        font-size: 0.8rem;
                        color: var(--bs-gray-600);
                    }

                    .activity-details {
                        margin-top: 0.5rem;
                        display: flex;
                        flex-wrap: wrap;
                        gap: 1rem;
                        font-size: 0.875rem;
                    }

                    .reminder-item {
                        display: flex;
                        align-items: flex-start;
                        padding: 1.25rem;
                        border-bottom: 1px solid var(--border-color);
                        gap: 1rem;
                    }

                    .reminder-icon {
                        width: 42px;
                        height: 42px;
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.25rem;
                    }

                    .reminder-item.pending-appointments .reminder-icon {
                        background-color: var(--warning-bg-subtle);
                        color: var(--warning-color);
                    }

                    .reminder-item.unpaid-fees .reminder-icon {
                        background-color: rgba(220, 53, 69, 0.1);
                        color: #dc3545;
                    }

                    .reminder-content {
                        flex: 1;
                    }

                    .reminder-title {
                        font-size: 0.875rem;
                        font-weight: 600;
                        margin-bottom: 0.25rem;
                        color: var(--bs-gray-700);
                    }

                    .reminder-text {
                        font-size: 0.8125rem;
                        color: var(--bs-gray-600);
                        margin-bottom: 0;
                    }

                    .empty-state {
                        padding: 3rem 1.5rem;
                        text-align: center;
                    }

                    .empty-state-icon {
                        width: 64px;
                        height: 64px;
                        border-radius: 50%;
                        background: var(--bs-gray-100);
                        color: var(--bs-gray-500);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.5rem;
                        margin: 0 auto 1rem;
                    }

                    .empty-state-icon.success {
                        background: var(--success-bg-subtle);
                        color: var(--success-color);
                    }

                    /* Custom Scrollbar Styles */
                    .activities-container,
                    .reminder-list {
                        scrollbar-width: thin;
                        scrollbar-color: rgba(0,0,0,0.2) transparent;
                    }

                    .activities-container::-webkit-scrollbar,
                    .reminder-list::-webkit-scrollbar {
                        width: 6px;
                    }

                    .activities-container::-webkit-scrollbar-track,
                    .reminder-list::-webkit-scrollbar-track {
                        background: transparent;
                    }

                    .activities-container::-webkit-scrollbar-thumb,
                    .reminder-list::-webkit-scrollbar-thumb {
                        background-color: rgba(0,0,0,0.2);
                        border-radius: 3px;
                    }

                    .activities-container::-webkit-scrollbar-thumb:hover,
                    .reminder-list::-webkit-scrollbar-thumb:hover {
                        background-color: rgba(0,0,0,0.3);
                    }

                    /* Handle Shorter Screens */
                    @media (max-height: 800px) {
                        .activities-container {
                            max-height: 400px;
                        }
                        .reminder-list {
                            max-height: 350px;
                        }
                    }

                    @media (max-width: 768px) {
                        .activity-details {
                            flex-direction: column;
                            gap: 0.5rem;
                        }
                    }
                </style>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* Main Layout Styles */
            .card {
                border: none;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
                border-radius: 15px;
                overflow: hidden;
            }

            /* Stat Cards */
            .stat-card {
                background: white;
                padding: 1.5rem;
                border-radius: 15px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
                height: 100%;
            }

            .stat-header {
                display: flex;
                align-items: flex-start;
                margin-bottom: 1.5rem;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                margin-left: 1rem;
            }

            .stat-info {
                flex: 1;
            }

            .stat-title {
                font-size: 1rem;
                color: #64748b;
                margin-bottom: 0.5rem;
            }

            .stat-total {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1e293b;
            }

            /* Detail Sections */
            .detail-section {
                padding: 1rem;
                background: #f8fafc;
                border-radius: 10px;
                margin-bottom: 1rem;
            }

            .detail-section:last-child {
                margin-bottom: 0;
            }

            .detail-title {
                font-size: 0.875rem;
                color: #64748b;
                margin-bottom: 1rem;
                font-weight: 600;
            }

            .detail-row {
                display: flex;
                justify-content: space-between;
                gap: 1rem;
            }

            .detail-item {
                flex: 1;
                display: flex;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .detail-info {
                display: flex;
                flex-direction: column;
            }

            .detail-label {
                font-size: 0.75rem;
                color: #64748b;
                margin-bottom: 0.25rem;
            }

            .detail-value {
                font-size: 1rem;
                font-weight: 600;
                color: #1e293b;
            }

            .detail-sub {
                font-size: 0.75rem;
                color: #94a3b8;
            }

            /* Progress Items */
            .progress-item {
                margin-top: 0.5rem;
            }

            .progress-label {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
                color: #64748b;
            }

            .progress {
                height: 6px;
                background-color: #f1f5f9;
                border-radius: 3px;
                overflow: hidden;
            }

            .progress-bar {
                transition: width 0.3s ease;
            }

            /* Progress Bar Specific Styles */
            .progress {
                background-color: #f1f5f9;
                border-radius: 3px;
                overflow: hidden;
                height: 6px !important;
            }

            .progress-bar {
                transition: width 0.3s ease;
                display: flex;
                flex-direction: column;
                justify-content: center;
                overflow: hidden;
                color: #fff;
                text-align: center;
                white-space: nowrap;
            }

            .progress-bar.bg-success {
                background-color: #2e7d32 !important;
            }

            .progress-bar.bg-warning {
                background-color: #f57c00 !important;
            }

            /* Card Colors */
            .users .stat-icon {
                background: #e8f5e9;
                color: #2e7d32;
            }

            .appointments .stat-icon {
                background: #e3f2fd;
                color: #1976d2;
            }

            .finance .stat-icon {
                background: #fff8e1;
                color: #f57c00;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .detail-row {
                    flex-direction: column;
                }

                .detail-item {
                    margin-bottom: 1rem;
                }

                .detail-item:last-child {
                    margin-bottom: 0;
                }
            }

            /* Performance Metrics Styling */
            .performance-metrics {
                padding: 1rem;
            }

            .performance-metrics .metric-item {
                background: white;
                border-radius: 10px;
                padding: 1.25rem;
                margin-bottom: 1rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }

            .performance-metrics .metric-item:last-child {
                margin-bottom: 0;
            }

            .performance-metrics .metric-label {
                font-size: 0.875rem;
                color: #64748b;
                font-weight: 500;
            }

            .performance-metrics .metric-value {
                font-size: 1.125rem;
                font-weight: 600;
            }

            .performance-metrics .progress {
                background-color: #f1f5f9;
                border-radius: 6px;
                height: 8px !important;
                margin-top: 0.75rem;
                overflow: hidden;
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
            }

            .performance-metrics .progress-bar {
                transition: width 0.5s ease;
                display: flex;
                flex-direction: column;
                justify-content: center;
                overflow: hidden;
                color: #fff;
                text-align: center;
                white-space: nowrap;
            }

            .performance-metrics .progress-bar.bg-success {
                background-color: #22c55e !important;
            }

            .performance-metrics .progress-bar.bg-warning {
                background-color: #f59e0b !important;
            }

            .performance-metrics .text-success {
                color: #16a34a !important;
            }

            .performance-metrics .text-warning {
                color: #d97706 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let appointmentsChart;

                function initAppointmentsChart(data) {
                    const appointmentsCtx = document.getElementById('appointmentsChart');
                    if (!appointmentsCtx) return;

                    if (appointmentsChart) {
                        appointmentsChart.destroy();
                    }

                    appointmentsChart = new Chart(appointmentsCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'الحجوزات',
                                data: data.appointments,
                                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-color'),
                                backgroundColor: getComputedStyle(document.documentElement).getPropertyValue('--primary-bg-subtle'),
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }

                // Initialize charts with initial data
                initAppointmentsChart(@json($chartData));

                // Initialize specialties chart
                const specialtiesCtx = document.getElementById('specialtiesChart');
                if (specialtiesCtx) {
                    const colors = [
                        'rgb(59, 130, 246)', // أزرق
                        'rgb(16, 185, 129)', // أخضر
                        'rgb(249, 115, 22)', // برتقالي
                        'rgb(99, 102, 241)', // أزرق فاتح
                        'rgb(236, 72, 153)'  // وردي
                    ];

                    const specialtiesChart = new Chart(specialtiesCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: @json($chartData['specialtyLabels']),
                            datasets: [{
                                data: @json($chartData['specialtyCounts']),
                                backgroundColor: colors,
                                borderWidth: 0,
                                hoverOffset: 15
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            layout: {
                                padding: 20
                            },
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                    titleColor: '#1e293b',
                                    bodyColor: '#64748b',
                                    borderColor: '#e2e8f0',
                                    borderWidth: 1,
                                    padding: 12,
                                    boxPadding: 8,
                                    usePointStyle: true,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return ` ${label}: ${value} (${percentage}%)`;
                                        }
                                    }
                                }
                            },
                            cutout: '60%'
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
