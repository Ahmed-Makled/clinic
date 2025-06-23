@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('patients.index') }}">المرضى</a>
    </li>
    <li class="breadcrumb-item active">تفاصيل المريض</li>
@endsection

@section('actions')
    <div class="d-flex gap-2">
        <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-soft-danger">
                <i class="bi bi-x-circle me-2"></i> حذف
            </button>
        </form>

        <a href="{{ route('patients.edit', $patient) }}" class="btn btn-soft-primary">
            <i class="bi bi-pencil me-2"></i> تعديل البيانات
        </a>
    </div>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Patient Profile Card -->
        <div class="profile-card">
            <div class="profile-info">
                <div class="profile-avatar">
                    <div class="avatar-wrapper">
                        <div class="avatar-placeholder">
                            {{ substr($patient->name, 0, 2) }}
                        </div>
                    </div>
                    <div class="status-indicator {{ $patient->status ? 'active' : 'inactive' }}">
                        <i class="bi {{ $patient->status ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                    </div>
                </div>

                <div class="profile-details w-100">
                    <div class="d-flex justify-content-between align-items-start w-100">
                        <div>
                            <h1 class="patient-name">{{ $patient->name }}</h1>
                            <div class="badges">
                                @if($patient->patient?->blood_type)
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        <i class="bi bi-droplet-fill me-1"></i>{{ $patient->patient->blood_type }}
                                    </span>
                                @endif
                                <span class="status-badge {{ $patient->status ? 'active' : 'inactive' }}">
                                    {{ $patient->status ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon appointments">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">عدد الحجوزات</div>
                        <div class="stat-value">{{ $patient->appointments_count ?? 0 }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon completed">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">الحجوزات المكتملة</div>
                        <div class="stat-value">{{ $patient->appointments()->where('appointments.status', 'completed')->count() }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon cancelled">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">الحجوزات الملغاة</div>
                        <div class="stat-value">{{ $patient->appointments()->where('appointments.status', 'cancelled')->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Patient Information -->
            <div class="info-section">
                <h2 class="section-title">
                    <i class="bi bi-person-vcard me-2"></i>
                    معلومات المريض
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="info-content">
                            <label>رقم الهاتف</label>
                            <span class="info-value">{{ $patient->phone_number }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="info-content">
                            <label>البريد الإلكتروني</label>
                            <span class="info-value">{{ $patient->email }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-gender-ambiguous"></i>
                        </div>
                        <div class="info-content">
                            <label>الجنس</label>
                            <span class="info-value">{{ $patient->patient?->gender == 'male' ? 'ذكر' : 'أنثى' }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-calendar3"></i>
                        </div>
                        <div class="info-content">
                            <label>تاريخ الميلاد</label>
                            <span class="info-value">{{ $patient->patient?->date_of_birth ? $patient->patient->date_of_birth->format('Y-m-d') : 'غير محدد' }}</span>
                        </div>
                    </div>

                    @if($patient->patient?->address)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="info-content">
                            <label>العنوان</label>
                            <span class="info-value">{{ $patient->patient->address }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Patient's Appointments -->
            @if($patient->appointments->isNotEmpty())
                <div class="appointments-section">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="m-0">
                            <i class="bi bi-calendar2-week me-2"></i>
                            الحجوزات
                            <span class="appointments-count">({{ $patient->appointments->count() }})</span>
                        </h2>
                    </div>
                    <div class="timeline">
                        @foreach($patient->appointments as $appointment)
                            <div class="timeline-item {{ $appointment->status }}">
                                <div class="timeline-point"></div>
                                <div class="appointment-card">
                                    <div class="appointment-header">
                                        <div class="time-slot">
                                            <div class="time">
                                                {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}
                                            </div>
                                            <div class="duration">{{ $appointment->doctor->waiting_time ?? 30 }} دقيقة</div>
                                        </div>
                                        <div class="status {{ $appointment->status }}">
                                            <i class="bi bi-circle-fill"></i>
                                            {{ $appointment->status_text }}
                                        </div>
                                    </div>

                                    <div class="doctor-info">
                                        <div class="doctor-primary">
                                            @if($appointment->doctor->image)
                                                <img src="{{ $appointment->doctor->image_url }}" alt="{{ $appointment->doctor->name }}" class="doctor-avatar">
                                            @else
                                                <div class="    ">
                                                    {{ substr($appointment->doctor->name, 0, 2) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="doctor-name">د. {{ $appointment->doctor->name }}</h4>
                                                <div class="doctor-specialties">
                                                    @php
                                                        $categories = optional($appointment->doctor->categories);
                                                        $categoryNames = $categories->isNotEmpty() ? $categories->pluck('name')->implode(' ، ') : 'غير محدد';
                                                    @endphp
                                                    {{ $categoryNames }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="appointment-meta">
                                            @if($appointment->fees)
                                                <div class="fees {{ $appointment->is_paid ? 'paid' : 'unpaid' }}">
                                                    <i class="bi {{ $appointment->is_paid ? 'bi-check-circle' : 'bi-exclamation-circle' }}"></i>
                                                    <span>{{ $appointment->fees }} جنيه</span>
                                                    <small>({{ $appointment->is_paid ? 'مدفوع' : 'غير مدفوع' }})</small>
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
            align-items: flex-start;
        }

        .profile-avatar {
            position: relative;
        }

        .avatar-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 15px;
            overflow: hidden;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(31, 41, 55, 0.05) 0%, rgba(55, 65, 81, 0.1) 100%);
            color: #1f2937;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 600;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .avatar-placeholder:hover {
            background: linear-gradient(135deg, rgba(31, 41, 55, 0.08) 0%, rgba(55, 65, 81, 0.15) 100%);
        }

        .status-indicator {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border: 3px solid white;
            transition: all 0.3s ease;
        }

        .status-indicator.active {
            background: linear-gradient(135deg, #38c172 0%, #2fb344 100%);
            color: white;
            box-shadow: 0 4px 8px rgba(56, 193, 114, 0.3);
        }

        .status-indicator.inactive {
            background: linear-gradient(135deg, #e3342f 0%, #dc2626 100%);
            color: white;
            box-shadow: 0 4px 8px rgba(227, 52, 47, 0.3);
        }

        .patient-name {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-badge.active {
            background: #e8f5e9;
            color: #28a745;
        }

        .status-badge.inactive {
            background: #ffebee;
            color: #dc3545;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
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
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s ease;
        }

        .stat-icon.appointments {
            background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
            color: #38c172;
        }

        .stat-icon.completed {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(99, 102, 241, 0.1) 100%);
            color: #6366f1;
        }

        .stat-icon.cancelled {
            background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
            color: #e3342f;
        }

        .stat-details {
            flex: 1;
        }

        .stat-label {
            color: #64748b;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .info-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(var(--bs-primary-rgb), 0.08);
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

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(250px, 1fr));
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
            background: linear-gradient(135deg, rgba(var(--bs-dark-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            color: var(--bs-dark);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            border: 1px solid rgba(var(--bs-dark-rgb), 0.1);
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

        .appointments-section {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(var(--bs-primary-rgb), 0.08);
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
                    rgba(var(--bs-primary-rgb), 0.1) 100%);
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
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status.scheduled {
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.15) 100%);
            color: #9333ea;
            border: 1px solid rgba(147, 51, 234, 0.1);
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

        .doctor-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin: 1rem 0;
        }

        .doctor-primary {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .doctor-avatar, .    {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            object-fit: cover;
        }

        .    {
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
            color: var(--bs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .doctor-name {
            color: #1e293b;
            font-weight: 600;
            margin: 0;
            font-size: 1rem;
            margin-bottom: 0.25rem;
        }

        .doctor-specialties {
            font-size: 0.875rem;
            color: #64748b;
        }

        .appointment-meta {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-end;
        }

        .fees {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 500;
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
        }

        @media (max-width: 768px) {
            .profile-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .badges {
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .timeline {
                padding-right: 1.5rem;
            }

            .timeline-point {
                right: -1.5rem;
            }

            .appointment-card {
                margin-right: 1rem;
            }

            .doctor-info {
                flex-direction: column;
            }

            .doctor-meta {
                width: 100%;
                flex-direction: row;
                justify-content: space-between;
            }
        }
    </style>
@endsection
