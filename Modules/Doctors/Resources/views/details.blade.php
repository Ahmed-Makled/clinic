@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('doctors.index') }}">الأطباء</a>
    </li>
    <li class="breadcrumb-item active">تفاصيل الطبيب</li>
@endsection
@section('actions')
    <div class="d-flex gap-2">

            <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" class="d-inline">
                @csrf
                @method('POST')
                <button type="submit" class="btn btn-soft-danger">
                    <i class="bi bi-x-circle me-2"></i> حذف
                </button>
            </form>

        <a href="{{ route('doctors.edit', $doctor) }}" class="btn btn-soft-primary">
            <i class="bi bi-pencil me-2"></i>  تعديل البيانات
        </a>
    </div>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Doctor Profile Card -->
        <div class="profile-card">
            <div class="profile-info">
                <div class="profile-avatar">
                    @if($doctor->image)
                        <img src="{{ $doctor->image_url }}"
                             alt="{{ $doctor->name }}"
                             class="doctor-image"
                             onerror="this.src='{{ asset('images/default-doctor.png') }}'; this.onerror=null;">
                    @else
                        <div class="avatar-placeholder">
                            {{ substr($doctor->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="status-indicator {{ $doctor->status ? 'active' : 'inactive' }}"></div>
                </div>

                <div class="profile-details w-100">
                    <div class="d-flex justify-content-between align-items-start w-100">
                        <div>
                            <h1 class="doctor-name">د. {{ $doctor->name }}</h1>
                            <div class="badges">
                                @foreach($doctor->categories as $category)
                                    <span class="specialty-badge">{{ $category->name }}</span>
                                @endforeach
                                <span class="status-badge {{ $doctor->status ? 'active' : 'inactive' }}">
                                    {{ $doctor->status ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon consultation">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">رسوم الكشف</div>
                        <div class="stat-value">{{ $doctor->consultation_fee }} جنيه</div>
                        <div class="stat-trend {{ $feeComparisonRate >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi {{ $feeComparisonRate >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                            {{ abs(round($feeComparisonRate)) }}% {{ $feeComparisonRate >= 0 ? 'أعلى من' : 'أقل من' }} متوسط التخصص
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon appointments">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">المواعيد المكتملة</div>
                        <div class="stat-value">{{ $completedAppointments }}</div>
                        <div class="stat-trend {{ $completedGrowthRate >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi {{ $completedGrowthRate >= 0 ? 'bi-arrow-up-short' : 'bi-arrow-down-short' }}"></i>
                            {{ abs(round($completedGrowthRate)) }}% {{ $completedGrowthRate >= 0 ? 'زيادة' : 'انخفاض' }} عن الشهر السابق
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon cancelled">
                        <i class="bi bi-calendar2-x"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">المواعيد الملغاة</div>
                        <div class="stat-value">{{ $cancelledAppointments }}</div>
                        <div class="stat-trend neutral">
                            <i class="bi bi-calendar3"></i>
                            هذا الشهر
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon earnings">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">إجمالي الإيرادات</div>
                        <div class="stat-value">{{ $totalEarnings }} جنيه</div>
                        <div class="stat-trend {{ $earningsGrowthRate >= 0 ? 'positive' : 'negative' }}">
                            <i class="bi {{ $earningsGrowthRate >= 0 ? 'bi-graph-up' : 'bi-graph-down' }}"></i>
                            {{ abs(round($earningsGrowthRate)) }}% {{ $earningsGrowthRate >= 0 ? 'نمو' : 'انخفاض' }} عن الشهر السابق
                        </div>
                    </div>
                </div>
            </div>

            <!-- Doctor Information -->
            <div class="info-section">
                <h2 class="section-title">
                    <i class="bi bi-person-vcard me-2"></i>
                    معلومات الطبيب
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="info-content">
                            <label>رقم الهاتف</label>
                            <span class="info-value">{{ $doctor->phone }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="info-content">
                            <label>البريد الإلكتروني</label>
                            <span class="info-value">{{ $doctor->email }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="info-content">
                            <label>العنوان</label>
                            <span class="info-value">{{ $doctor->governorate->name }} - {{ $doctor->city->name }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div class="info-content">
                            <label>سنوات الخبرة</label>
                            <span class="info-value">{{ $doctor->experience_years ?? 'غير محدد' }} سنة</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="info-content">
                            <label>وقت الانتظار</label>
                            <span class="info-value">{{ $doctor->waiting_time ?? 'غير محدد' }} دقيقة</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-gender-ambiguous"></i>
                        </div>
                        <div class="info-content">
                            <label>الجنس</label>
                            <span class="info-value">{{ $doctor->gender }}</span>
                        </div>
                    </div>
                </div>

                @if($doctor->bio)
                    <div class="bio-section content-card">
                        <div class="card-header">
                            <i class="bi bi-person-lines-fill"></i>
                            <h3>نبذة عن الطبيب</h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $doctor->bio }}</p>
                        </div>
                    </div>
                @endif

                @if($doctor->description)
                    <div class="description-section content-card">
                        <div class="card-header">
                            <i class="bi bi-file-text"></i>
                            <h3>وصف تفصيلي</h3>
                        </div>
                        <div class="card-body">
                            <p>{{ $doctor->description }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Today's Appointments -->
            @if($appointments->isNotEmpty())
                <div class="appointments-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="m-0">
                            <i class="bi bi-calendar2-week me-2"></i>
                            مواعيد اليوم
                            <span class="appointments-count">({{ $appointments->count() }})</span>
                        </h2>
                    </div>
                    <div class="timeline">
                        @foreach($appointments as $appointment)
                            <div class="timeline-item {{ $appointment->status }}">
                                <div class="timeline-point"></div>
                                <div class="appointment-card">
                                    <div class="appointment-header">
                                        <div class="time-slot">
                                            <div class="time">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}</div>
                                            <div class="duration">{{ $doctor->waiting_time ?? 30 }} دقيقة</div>
                                        </div>
                                        <div class="status {{ $appointment->status }}">
                                            <i class="bi bi-circle-fill"></i>
                                            {{ $appointment->status_text }}
                                        </div>
                                    </div>

                                    <div class="patient-info">
                                        <div class="patient-primary">
                                            <div class="avatar">{{ substr($appointment->patient->name, 0, 2) }}</div>
                                            <div>
                                                <h4 class="patient-name">{{ $appointment->patient->name }}</h4>
                                                <div class="patient-details">
                                                    <span class="detail-item">
                                                        <i class="bi bi-telephone"></i>
                                                            {{ $appointment->patient->user->phone_number }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="patient-meta">
                                            @if($appointment->fees)
                                                <div class="fees {{ $appointment->is_paid ? 'paid' : 'unpaid' }}">
                                                    <i class="bi {{ $appointment->is_paid ? 'bi-check-circle' : 'bi-exclamation-circle' }}"></i>
                                                    <span>{{ $appointment->fees }} جنيه</span>
                                                    <small>({{ $appointment->is_paid ? 'مدفوع' : 'غير مدفوع' }})</small>
                                                </div>
                                            @endif
                                            @if($appointment->is_important)
                                                <div class="important-flag">
                                                    <i class="bi bi-star-fill"></i>
                                                    موعد مهم
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($appointment->notes)
                                        <div class="appointment-notes">
                                            <i class="bi bi-journal-text"></i>
                                            {{ $appointment->notes }}
                                        </div>
                                    @endif

                                    <div class="appointment-actions">
                                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                            عرض التفاصيل
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <style>
                    .appointments-section {
                        margin-top: 2rem;
                        padding-top: 2rem;
                        border-top: 1px solid #eee;
                    }

                    .appointments-count {
                        font-size: 1rem;
                        color: #666;
                        margin-right: 0.5rem;
                    }

                    .timeline {
                        position: relative;
                        padding-right: 2rem;
                    }

                    .timeline::before {
                        content: '';
                        position: absolute;
                        top: 0;
                        right: 7px;
                        height: 100%;
                        width: 3px;
                        background: linear-gradient(180deg,
                            rgba(var(--bs-primary-rgb), 0.2) 0%,
                            rgba(var(--bs-primary-rgb), 0.1) 100%
                        );
                        border-radius: 3px;
                    }

                    .timeline-item {
                        position: relative;
                        margin-bottom: 1.5rem;
                    }

                    .timeline-point {
                        position: absolute;
                        right: -2rem;
                        width: 16px;
                        height: 16px;
                        border-radius: 50%;
                        background: #fff;
                        border: 3px solid var(--bs-primary);
                        top: 1.5rem;
                        transform: translateY(-50%);
                        transition: all 0.3s ease;
                        box-shadow: 0 0 0 4px rgba(var(--bs-primary-rgb), 0.1);
                    }

                    .timeline-item.completed .timeline-point {
                        background: #38c172;
                        border-color: #38c172;
                        box-shadow: 0 0 0 4px rgba(56, 193, 114, 0.2);
                    }

                    .timeline-item.cancelled .timeline-point {
                        background: #e3342f;
                        border-color: #e3342f;
                        box-shadow: 0 0 0 4px rgba(227, 52, 47, 0.2);
                    }

                    .appointment-card {
                        background: #fff;
                        border-radius: 15px;
                        padding: 1.5rem;
                        margin-right: 1.5rem;
                        box-shadow: 0 2px 8px rgba(var(--bs-primary-rgb), 0.06);
                        border: 1px solid rgba(var(--bs-primary-rgb), 0.08);
                        transition: all 0.3s ease;
                    }

                    .appointment-card:hover {
                        transform: translateY(-2px) translateX(-2px);
                        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.12);
                    }

                    .appointment-header {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        margin-bottom: 1rem;
                    }

                    .time-slot {
                        display: flex;
                        flex-direction: column;
                    }

                    .time {
                        font-size: 1.25rem;
                        font-weight: 600;
                        background: linear-gradient(135deg, var(--bs-primary) 0%, #2563eb 100%);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                    }

                    .duration {
                        font-size: 0.875rem;
                        color: #64748b;
                    }

                    .status {
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        padding: 0.5rem 1rem;
                        border-radius: 50px;
                        font-size: 0.875rem;
                        font-weight: 500;
                    }

                    .status.scheduled {
                        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
                        color: var(--bs-primary);
                        border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
                    }

                    .status.completed {
                        background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
                        color: #38c172;
                        border: 1px solid rgba(56, 193, 114, 0.1);
                    }

                    .status.cancelled {
                        background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
                        color: #e3342f;
                        border: 1px solid rgba(227, 52, 47, 0.1);
                    }

                    .status i {
                        font-size: 0.5rem;
                    }

                    .patient-info {
                        display: flex;
                        justify-content: space-between;
                        align-items: flex-start;
                        gap: 1rem;
                        margin: 1rem 0;
                    }

                    .patient-primary {
                        display: flex;
                        align-items: center;
                        gap: 1rem;
                    }

                    .avatar {
                        width: 42px;
                        height: 42px;
                        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
                        color: var(--bs-primary);
                        border-radius: 12px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: 600;
                        transition: all 0.3s ease;
                        border: 2px solid rgba(var(--bs-primary-rgb), 0.1);
                    }

                    .patient-info:hover .avatar {
                        transform: scale(1.1) rotate(8deg);
                    }

                    .patient-name {
                        color: #1e293b;
                        font-weight: 600;
                        margin: 0;
                        font-size: 1rem;
                        margin-bottom: 0.25rem;
                    }

                    .patient-details {
                        display: flex;
                        gap: 1rem;
                        font-size: 0.875rem;
                        color: #666;
                    }

                    .detail-item {
                        display: flex;
                        align-items: center;
                        gap: 0.25rem;
                    }

                    .patient-meta {
                        display: flex;
                        flex-direction: column;
                        gap: 0.5rem;
                        align-items: flex-end;
                    }

                    .fees {
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        font-size: 0.875rem;
                        padding: 0.5rem 1rem;
                        border-radius: 12px;
                        transition: all 0.3s ease;
                    }

                    .fees.paid {
                        background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
                        color: #38c172;
                        border: 1px solid rgba(56, 193, 114, 0.1);
                    }

                    .fees.unpaid {
                        background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
                        color: #e3342f;
                        border: 1px solid rgba(227, 52, 47, 0.1);
                    }

                    .important-flag {
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                        padding: 0.5rem 1rem;
                        border-radius: 12px;
                        background: linear-gradient(135deg, rgba(251, 191, 36, 0.1) 0%, rgba(245, 158, 11, 0.1) 100%);
                        color: #f59e0b;
                        border: 1px solid rgba(251, 191, 36, 0.1);
                        font-size: 0.875rem;
                        font-weight: 500;
                    }

                    .appointment-notes {
                        margin-top: 1rem;
                        padding: 1rem;
                        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.03) 0%, rgba(37, 99, 235, 0.03) 100%);
                        border-radius: 12px;
                        font-size: 0.875rem;
                        color: #64748b;
                        display: flex;
                        gap: 0.75rem;
                        align-items: flex-start;
                        border: 1px solid rgba(var(--bs-primary-rgb), 0.06);
                    }

                    .appointment-notes i {
                        color: var(--bs-primary);
                        margin-top: 0.125rem;
                    }

                    .appointment-actions {
                        margin-top: 1.25rem;
                        padding-top: 1.25rem;
                        border-top: 1px solid rgba(var(--bs-primary-rgb), 0.08);
                        display: flex;
                        justify-content: flex-end;
                        gap: 0.75rem;
                    }

                    .btn-light {
                        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
                        color: var(--bs-primary);
                        border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
                        transition: all 0.3s ease;
                    }

                    .btn-light:hover {
                        background: var(--bs-primary);
                        color: #fff;
                        transform: translateY(-1px);
                    }

                    @media (max-width: 768px) {
                        .timeline {
                            padding-right: 1.5rem;
                        }

                        .timeline-point {
                            right: -1.5rem;
                        }

                        .patient-info {
                            flex-direction: column;
                        }

                        .patient-meta {
                            flex-direction: row;
                            flex-wrap: wrap;
                            justify-content: space-between;
                            width: 100%;
                            gap: 0.75rem;
                        }
                    }
                </style>
            @endif
        </div>
    </div>

    <style>
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .profile-info {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
        }

        .doctor-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: #0066cc;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
        }

        .status-indicator {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
        }

        .status-indicator.active {
            background-color: #28a745;
        }

        .status-indicator.inactive {
            background-color: #dc3545;
        }

        .doctor-name {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .specialty-badge {
            background: #e3f2fd;
            color: #0066cc;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .status-badge {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .status-badge.active {
            background: #e8f5e9;
            color: #28a745;
        }

        .status-badge.inactive {
            background: #ffebee;
            color: #dc3545;
        }

        .contact-info {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .stats-grid {
            display: flex;
            gap: 1.5rem;
            padding: 1rem;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.6);
            box-shadow:
                0 4px 6px -1px rgba(0, 0, 0, 0.05),
                0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            width: 250px;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.5) 0%,
                rgba(255, 255, 255, 0.1) 100%
            );
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: rgba(226, 232, 240, 0.9);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-icon::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 20px;
            background: inherit;
            filter: blur(8px);
            opacity: 0.4;
            z-index: -1;
            transition: all 0.3s ease;
        }

        .stat-icon.consultation {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: white;
        }

        .stat-icon.appointments {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
        }

        .stat-icon.cancelled {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
        }

        .stat-icon.earnings {
            background: linear-gradient(135deg, #eab308 0%, #facc15 100%);
            color: white;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(10deg);
        }

        .stat-card:hover .stat-icon::after {
            opacity: 0.6;
            filter: blur(12px);
        }

        .stat-icon i {
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }

        .stat-details {
            position: relative;
            z-index: 1;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
            letter-spacing: 0.025em;
        }

        .stat-value {
            color: #1e293b;
            font-size: 1.875rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 100px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .stat-trend.positive {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .stat-trend.negative {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .stat-trend.neutral {
            background: rgba(100, 116, 139, 0.1);
            color: #475569;
        }

        .stat-trend i {
            font-size: 1.125rem;
            transition: transform 0.3s ease;
        }

        .stat-card:hover .stat-trend {
            transform: translateX(5px);
        }

        @media (max-width: 768px) {


            .stat-card {
                padding: 1.5rem;
            }

            .stat-icon {
                width: 48px;
                height: 48px;
                margin-bottom: 1.25rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }
        }

        .info-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }

        .info-section h2 {
            margin-bottom: 1.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .info-item {
            /* background: #f8f9fa; */
            padding: 1rem;
            border-radius: 10px;
        }

        .info-item label {
            display: block;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .bio-section, .description-section {
            margin-top: 2rem;
        }

        .bio-section h3, .description-section h3 {
            color: #333;
            margin-bottom: 1rem;
        }

        .appointments-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #eee;
        }

        .appointments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .appointment-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .time {
            font-weight: bold;
            color: #0066cc;
        }

        .status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .status.scheduled {
            background: #e3f2fd;
            color: #0066cc;
        }

        .status.completed {
            background: #e8f5e9;
            color: #28a745;
        }

        .status.cancelled {
            background: #ffebee;
            color: #dc3545;
        }

        .patient-info {
            margin-bottom: 1rem;
        }

        .patient-info h4 {
            margin-bottom: 0.3rem;
        }

        .fees {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .fees.paid {
            background: #e8f5e9;
            color: #28a745;
        }

        .fees.unpaid {
            background: #ffebee;
            color: #dc3545;
        }

        .profile-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-soft-primary {
            background: #e3f2fd;
            color: #0066cc;
        }

        .btn-soft-primary:hover {
            background: #0066cc;
            color: white;
        }

        .btn-soft-danger {
            background: #ffebee;
            color: #dc3545;
        }

        .btn-soft-danger:hover {
            background: #dc3545;
            color: white;
        }

        @media (max-width: 768px) {
            .profile-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .contact-info {
                flex-direction: column;
                align-items: center;
            }

            .profile-actions {
                justify-content: center;
                margin-top: 1rem;
            }
        }

    .section-title {
        color: #1e293b;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: var(--bs-primary);
        font-size: 1.25rem;
    }

    .info-section {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(var(--bs-primary-rgb), 0.08);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .info-item {
        padding: 1.25rem;
        border-radius: 15px;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        transition: all 0.3s ease;
    }


    .info-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
        color: var(--bs-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
        transition: all 0.3s ease;
    }



    .info-content {
        flex: 1;
    }

    .info-content label {
        display: block;
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .info-value {
        color: #1e293b;
        font-weight: 500;
        font-size: 1rem;
    }





    .card-header {
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-header i {
        color: var(--bs-primary);
        font-size: 1.25rem;
    }

    .card-header h3 {
        color: #1e293b;
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }

    .card-body {
        padding: 1.25rem;
    }

    .card-body p {
        color: #64748b;
        line-height: 1.6;
        margin: 0;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .info-item {
            padding: 1rem;
        }

        .info-icon {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }
    }

    /* Contact Info Styles */
    .contact-info {
        display: flex;
        gap: 2rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }

    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.03) 0%, rgba(37, 99, 235, 0.03) 100%);
        border-radius: 12px;
        border: 1px solid rgba(var(--bs-primary-rgb), 0.08);
        transition: all 0.3s ease;
        flex: 1;
        min-width: max-content;
    }

    .contact-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.08);
    }

    .contact-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
        color: var(--bs-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
        transition: all 0.3s ease;
    }

    .contact-item:hover .contact-icon {
        transform: scale(1.1) rotate(8deg);
        background: var(--bs-primary);
        color: white;
    }

    .contact-details {
        flex: 1;
    }

    .contact-label {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        display: block;
    }

    .contact-value {
        color: #1e293b;
        font-weight: 500;
        font-size: 1rem;
    }


    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        box-shadow: 0 2px 8px rgba(var(--bs-primary-rgb), 0.06);
        border: 1px solid rgba(var(--bs-primary-rgb), 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        width: max-content;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right,
            rgba(var(--bs-primary-rgb), 0.5),
            rgba(var(--bs-primary-rgb), 0.2)
        );
        opacity: 0;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(var(--bs-primary-rgb), 0.12);
    }

    .stat-card:hover::before {
        opacity: 1;
    }

    .stat-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .stat-icon.consultation {
        background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
        color: var(--bs-primary);
    }

    .stat-icon.consultation i {
        font-size: 1.25rem;
        transform: scale(0.9);
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon.consultation i {
        transform: scale(1);
    }

    .stat-icon.appointments {
        background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
        color: #38c172;
    }

    .stat-icon.cancelled {
        background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
        color: #e3342f;
    }

    .stat-icon.earnings {
        background: linear-gradient(135deg, rgba(246, 153, 63, 0.1) 0%, rgba(255, 139, 20, 0.1) 100%);
        color: #f59e0b;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(10deg);
    }

    .stat-details {
        flex: 1;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        margin-bottom: 0.375rem;
    }

    .stat-value {
        color: #1e293b;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.875rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        width: fit-content;
        text-wrap: nowrap;
    }

    .stat-trend.positive {
        background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
        color: #38c172;
    }

    .stat-trend.negative {
        background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
        color: #e3342f;
    }

    .stat-trend.neutral {
        background: linear-gradient(135deg, rgba(var(--bs-secondary-rgb), 0.1) 0%, rgba(108, 117, 125, 0.1) 100%);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.1);
    }

    @media (max-width: 768px) {
        .contact-info {
            flex-direction: column;
        }

        .contact-item {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 1.25rem;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            font-size: 1.25rem;
        }
    }
</style>
@endsection
