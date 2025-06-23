@extends('layouts.admin')

@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}">المستخدمين</a>
    </li>
    <li class="breadcrumb-item active">تفاصيل المستخدم</li>
@endsection

@section('actions')
    <div class="d-flex gap-2">
        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-soft-danger">
                <i class="bi bi-x-circle me-2"></i> حذف
            </button>
        </form>

        <a href="{{ route('users.edit', $user) }}" class="btn btn-soft-primary">
            <i class="bi bi-pencil me-2"></i> تعديل البيانات
        </a>
    </div>
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- User Profile Card -->
        <div class="profile-card">
            <div class="profile-info">
                <div class="profile-avatar">
                    <div class="avatar-wrapper">
                        <div class="avatar-placeholder">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                    </div>
                    <div class="status-indicator {{ $user->status ? 'active' : 'inactive' }}">
                        <i class="bi {{ $user->status ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                    </div>
                </div>

                <div class="profile-details w-100">
                    <div class="d-flex justify-content-between align-items-start w-100">
                        <div>
                            <h1 class="user-name">{{ $user->name }}</h1>
                            <div class="badges">
                                @foreach($user->roles as $role)
                                    <span class="role-badge {{ strtolower($role->name) }}-role">
                                        <i class="bi bi-shield-check me-1"></i>
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                                <span class="status-badge {{ $user->status ? 'active' : 'inactive' }}">
                                    {{ $user->status ? 'نشط' : 'غير نشط' }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <form action="{{ route('users.toggle-status', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $user->status ? 'btn-soft-danger' : 'btn-soft-success' }}">
                                    @if($user->status)
                                        <i class="bi bi-x-circle me-1"></i> تعطيل
                                    @else
                                        <i class="bi bi-check-circle me-1"></i> تفعيل
                                    @endif
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                @if($user->hasRole('Doctor'))
                    <div class="stat-card">
                        <div class="stat-icon appointments">
                            <i class="bi bi-calendar2-check"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-label">عدد الحجوزات</div>
                            <div class="stat-value">{{ $user->doctor ? $user->doctor->appointments_count ?? 0 : 0 }}</div>
                        </div>
                    </div>
                @elseif($user->hasRole('Patient'))
                    <div class="stat-card">
                        <div class="stat-icon appointments">
                            <i class="bi bi-calendar2-check"></i>
                        </div>
                        <div class="stat-details">
                            <div class="stat-label">عدد الحجوزات</div>
                            <div class="stat-value">{{ $user->patient ? $user->appointments_count ?? 0 : 0 }}</div>
                        </div>
                    </div>
                @endif

                <div class="stat-card">
                    <div class="stat-icon users">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">تاريخ التسجيل</div>
                        <div class="stat-value">{{ $user->created_at->format('Y-m-d') }}</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon actions">
                        <i class="bi bi-activity"></i>
                    </div>
                    <div class="stat-details">
                        <div class="stat-label">آخر نشاط</div>
                        <div class="stat-value">
                            {{ $user->last_seen ? $user->last_seen->diffForHumans() : 'غير متوفر' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="info-section mt-4">
                <h2 class="section-title">
                    <i class="bi bi-person-badge me-2"></i>
                    معلومات المستخدم
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="info-content">
                            <label>البريد الإلكتروني</label>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div class="info-content">
                            <label>رقم الهاتف</label>
                            <span class="info-value">{{ $user->phone_number ?? 'غير محدد' }}</span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="info-content">
                            <label>نوع المستخدم</label>
                            <span class="info-value">{{ $user->roles->pluck('name')->implode(', ') }}</span>
                        </div>
                    </div>

                    @if($user->hasRole('Doctor') && $user->doctor)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <div class="info-content">
                            <label>التخصص</label>
                            <span class="info-value">
                                @php
                                    $categories = optional($user->doctor->categories);
                                    $categoryNames = $categories->isNotEmpty() ? $categories->pluck('name')->implode(', ') : 'غير محدد';
                                @endphp
                                {{ $categoryNames }}
                            </span>
                        </div>
                    </div>
                    @endif

                    @if($user->hasRole('Patient') && $user->patient)
                    <div class="info-item">
                        <div class="info-icon">
                            <i class="bi bi-gender-ambiguous"></i>
                        </div>
                        <div class="info-content">
                            <label>الجنس</label>
                            <span class="info-value">
                                {{ $user->patient->gender == 'male' ? 'ذكر' : 'أنثى' }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Role-specific information -->
            @if($user->hasRole('Doctor') && $user->doctor)
            <div class="role-specific-section mt-4">
                <h2 class="section-title">
                    <i class="bi bi-person-badge me-2"></i>
                    معلومات الطبيب
                </h2>
                <div class="mb-3">
                    <a href="{{ route('doctors.details', $user->doctor->id) }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right me-1"></i> عرض الملف الكامل للطبيب
                    </a>
                </div>
            </div>
            @endif

            @if($user->hasRole('Patient') && $user->patient)
            <div class="role-specific-section mt-4">
                <h2 class="section-title">
                    <i class="bi bi-person-badge me-2"></i>
                    معلومات المريض
                </h2>
                <div class="mb-3">
                    <a href="{{ route('patients.details', $user->id) }}" class="btn btn-primary">
                        <i class="bi bi-arrow-right me-1"></i> عرض الملف الكامل للمريض
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Base Profile Card Styles */
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        /* Profile Info Styles */
        .profile-info {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
            align-items: flex-start;
        }

        .profile-avatar {
            position: relative;
            flex-shrink: 0;
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
            background: linear-gradient(45deg, #3b82f6, #2563eb);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .status-indicator {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .status-indicator.active i {
            color: #10b981;
        }

        .status-indicator.inactive i {
            color: #ef4444;
        }

        /* User name and badges */
        .user-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .badges {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .role-badge {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
        }

        .doctor-role {
            background-color: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
        }

        .patient-role {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .admin-role {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
        }

        .status-badge.active {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-badge.inactive {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background-color: #f9fafb;
            border-radius: 10px;
            padding: 1.25rem;
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon.appointments {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .stat-icon.users {
            background-color: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
        }

        .stat-icon.actions {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .stat-details {
            flex-grow: 1;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }

        /* Info Section */
        .info-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.25rem;
        }

        .info-item {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background-color: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4b5563;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .info-content {
            flex-grow: 1;
        }

        .info-content label {
            display: block;
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .info-value {
            font-weight: 500;
            color: #1f2937;
        }
    </style>
@endpush
