@extends('layouts.admin')
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('users.index') }}" class="text-decoration-none">المستخدمين</a>
    </li>
    <li class="breadcrumb-item active">تفاصيل المستخدم</li>
@endsection
@section('content')
    <div class="content-wrapper">


        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-info">
                <div class="profile-avatar animate-pop-in">
                    {{ substr($user->name, 0, 2) }}
                    <div class="status-indicator {{ $user->status ? 'active pulse' : 'inactive' }}"></div>
                </div>
                <div class="profile-details">

                    <div class="d-flex">

                        <div >
                            <h1 class="name animate-fade-in delay-1">{{ $user->name }}</h1>
                            <div class="badges animate-fade-in delay-2">
                                <span class="role">{{ $user->roles->first()->name ?? 'لا يوجد دور' }}</span>
                                <span class="status {{ $user->status ? 'active' : 'inactive' }}">
                                    <i class="fas {{ $user->status ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                    {{ $user->status ? 'نشط' : 'غير نشط' }}
                                </span>
                                @if($user->last_login_at)
                                    <span class="last-login">
                                        <i class="fas fa-clock me-1"></i>
                                        آخر دخول: {{ $user->last_login_at->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="profile-actions ms-auto">

                            <a href="{{ route('users.edit', $user) }}" class="btn btn-soft-primary rounded-pill">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M15.502 1.94a.5.5 0 010 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 01.707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 00-.121.196l-.805 2.414a.25.25 0 00.316.316l2.414-.805a.5.5 0 00.196-.12l6.813-6.814z" />
                                    <path fill-rule="evenodd"
                                        d="M1 13.5A1.5 1.5 0 002.5 15h11a1.5 1.5 0 001.5-1.5v-6a.5.5 0 00-1 0v6a.5.5 0 01-.5.5h-11a.5.5 0 01-.5-.5v-11a.5.5 0 01.5-.5H9a.5.5 0 000-1H2.5A1.5 1.5 0 001 2.5v11z" />
                                </svg>
                                تعديل البيانات
                            </a>
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-soft-danger rounded-pill"
                                    onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="حذف المستخدم">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M5.5 5.5A.5.5 0 016 6v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm2.5 0a.5.5 0 01.5.5v6a.5.5 0 01-1 0V6a.5.5 0 01.5-.5zm3 .5a.5.5 0 00-1 0v6a.5.5 0 001 0V6z" />
                                        <path fill-rule="evenodd"
                                            d="M14.5 3a1 1 0 01-1 1H13v9a2 2 0 01-2 2H5a2 2 0 01-2-2V4h-.5a1 1 0 01-1-1V2a1 1 0 011-1H6a1 1 0 011-1h2a1 1 0 011 1h3.5a1 1 0 011 1v1zM4.118 4L4 4.059V13a1 1 0 001 1h6a1 1 0 001-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                    </svg>
                                    <span>حذف</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="profile-contact animate-fade-in delay-3">
                        <div class="contact-item hover-effect" data-bs-toggle="tooltip" title="رقم الهاتف">
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M11 1a1 1 0 011 1v12a1 1 0 01-1 1H5a1 1 0 01-1-1V2a1 1 0 011-1h6zM5 0a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V2a2 2 0 00-2-2H5z" />
                                    <path d="M8 14a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </i>
                            <div>
                                <div class="contact-label">رقم الهاتف</div>
                                <div class="contact-value">{{ $user->phone_number }}</div>
                            </div>
                        </div>
                        <div class="contact-item hover-effect" data-bs-toggle="tooltip" title="البريد الإلكتروني">
                            <i>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M0 4a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H2a2 2 0 01-2-2V4zm2-1a1 1 0 00-1 1v.217l7 4.2 7-4.2V4a1 1 0 00-1-1H2zm13 2.383l-4.758 2.855L15 11.114v-5.73zm-.034 6.878L9.271 8.82 8 9.583 6.728 8.82l-5.694 3.44A1 1 0 002 13h12a1 1 0 00.966-.739zM1 11.114l4.758-2.876L1 5.383v5.73z" />
                                </svg>
                            </i>
                            <div>
                                <div class="contact-label">البريد الالكترونى</div>
                                <div class="contact-value">{{ $user->email }}</div>
                            </div>
                        </div>
                        @if($user->address)
                            <div class="contact-item hover-effect" data-bs-toggle="tooltip" title="العنوان">
                                <i>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                    </svg>
                                </i>
                                <div>
                                    <div class="contact-label">العنوان</div>
                                    <div class="contact-value">{{ $user->address }}</div>
                                </div>
                            </div>
                        @endif
                        @if($user->city)
                            <div class="contact-item hover-effect" data-bs-toggle="tooltip" title="المدينة">
                                <i>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                    </svg>
                                </i>
                                <div>
                                    <div class="contact-label">المدينة</div>
                                    <div class="contact-value">{{ $user->city->name }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="profile-stats animate-slide-up delay-4">
                <div class="stat-item hover-effect">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-value">{{ $user->created_at->format('Y-m-d') }}</div>
                    <div class="stat-label">تاريخ التسجيل</div>
                </div>

                @if($user->roles->first()->name === 'patient')
                    <div class="stat-item hover-effect">
                        <div class="stat-icon">
                            <i class="fas fa-stethoscope"></i>
                        </div>
                        <div class="stat-value">{{ $user->patient->appointments->count() }}</div>
                        <div class="stat-label">عدد المواعيد</div>
                    </div>
                    <div class="stat-item hover-effect">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value">{{ $user->patient->appointments->where('status', 'pending')->count() }}</div>
                        <div class="stat-label">مواعيد قيد الانتظار</div>
                    </div>
                @endif

                @if($user->roles->first()->name === 'doctor')
                    <div class="stat-item hover-effect">
                        <div class="stat-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="stat-value">{{ $user->doctor->appointments->count() }}</div>
                        <div class="stat-label">عدد المرضى</div>
                    </div>
                    <div class="stat-item hover-effect">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-value">{{ number_format($user->doctor->rating_avg, 1) }}</div>
                        <div class="stat-label">متوسط التقييم</div>
                    </div>
                @endif

                <div class="stat-item hover-effect">
                    <div class="stat-icon">
                        <i class="fas fa-sign-in-alt"></i>
                    </div>
                    <div class="stat-value">{{ $user->login_count ?? 0 }}</div>
                    <div class="stat-label">عدد مرات الدخول</div>
                </div>
            </div>

            @if($user->roles->first()->name === 'patient' && $user->patient->appointments->isNotEmpty())
                <div class="recent-appointments">
                    <h3>آخر المواعيد</h3>
                    <div class="appointments-list">
                        @foreach($user->patient->appointments->take(3) as $appointment)
                            <div class="appointment-item">
                                <div class="appointment-info">
                                    <div class="doctor-name">د. {{ $appointment->doctor->user->name }}</div>
                                    <div class="appointment-date">{{ $appointment->appointment_date->format('Y-m-d H:i') }}</div>
                                </div>
                                <div class="appointment-status {{ $appointment->status }}">
                                    {{ __('appointments.status.' . $appointment->status) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    </div>

    <style>
        :root {
            --primary: #0066cc;
            --primary-rgb: 0, 102, 204;
            --success: #28a745;
            --success-rgb: 40, 167, 69;
            --danger: #dc3545;
            --danger-rgb: 220, 53, 69;
            --card-border-radius: 16px;
            --button-border-radius: 50px;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: var(--button-border-radius);
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition-base);
        }

        .btn-soft-primary {
            background: rgba(var(--primary-rgb), 0.1);
            color: var(--primary);
        }

        .btn-soft-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-soft-danger {
            background: rgba(var(--danger-rgb), 0.1);
            color: var(--danger);
        }

        .btn-soft-danger:hover {
            background: var(--danger);
            color: white;
            transform: translateY(-2px);
        }

        .profile-actions  {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }

        .btn svg {
            margin-left: 8px;
        }

        .btn-primary {
            background-color: #0066cc;
            color: #fff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #f8f9fa;
            color: #ff3c5f;
        }

        .btn-danger:hover {
            background-color: #ffe5e9;
        }

        /* Profile Card Styles */
        .profile-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .profile-info {
            display: flex;
            padding: 30px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #0066cc;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin-left: 30px;
            position: relative;
        }

        .status-indicator {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 3px solid white;
        }

        .status-indicator.active {
            background-color: #28a745;
        }

        .status-indicator.inactive {
            background-color: #ff3c5f;
        }

        .profile-details {
            flex: 1;
        }

        .profile-details .name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-details .role {
            display: inline-block;
            padding: 5px 12px;
            background-color: #e8f4ff;
            color: #0066cc;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .profile-details .status {
            display: inline-block;
            margin-right: 10px;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .status.active {
            background-color: #e8f8f0;
            color: #28a745;
        }

        .status.inactive {
            background-color: #ffeeee;
            color: #ff3c5f;
        }

        .profile-contact {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .contact-item {
            display: flex;
            align-items: center;
        }

        .contact-item i {
            width: 35px;
            height: 35px;
            background-color: #e9f0ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0066cc;
            margin-left: 10px;
        }

        .contact-label {
            font-size: 12px;
            color: #777;
        }

        .contact-value {
            font-weight: 500;
        }

        .profile-stats {
            display: flex;
            padding: 20px 30px;
            border-top: 1px solid #e1e5eb;
            background-color: #f9fafc;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            padding: 0 15px;
            border-left: 1px solid #e1e5eb;
        }

        .stat-item:last-child {
            border-left: none;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #0066cc;
        }

        .stat-label {
            font-size: 13px;
            color: #777;
        }

        /* Recent Appointments Styles */
        .recent-appointments {
            padding: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }

        .recent-appointments h3 {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .appointments-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .appointment-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: rgba(var(--primary-rgb), 0.03);
            border-radius: 12px;
            transition: var(--transition-base);
        }

        .appointment-item:hover {
            transform: translateX(-5px);
            background: rgba(var(--primary-rgb), 0.05);
        }

        .appointment-info {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .doctor-name {
            font-weight: 500;
            color: var(--primary);
        }

        .appointment-date {
            font-size: 14px;
            color: #718096;
        }

        .appointment-status {
            padding: 0.25rem 0.75rem;
            border-radius: var(--button-border-radius);
            font-size: 14px;
            font-weight: 500;
        }

        .appointment-status.pending {
            background: rgba(var(--primary-rgb), 0.1);
            color: var(--primary);
        }

        .appointment-status.completed {
            background: rgba(var(--success-rgb), 0.1);
            color: var(--success);
        }

        .appointment-status.cancelled {
            background: rgba(var(--danger-rgb), 0.1);
            color: var(--danger);
        }

        /* Additional Style for Last Login */
        .last-login {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: rgba(var(--primary-rgb), 0.05);
            border-radius: var(--button-border-radius);
            font-size: 0.875rem;
            color: #718096;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }

            .profile-info {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .profile-avatar {
                margin-left: 0;
                margin-bottom: 20px;
            }

            .profile-contact {
                justify-content: center;
            }

            .profile-stats {
                flex-wrap: wrap;
            }

            .stat-item {
                flex: 0 0 50%;
                border: none;
                margin-bottom: 15px;
            }
        }

        /* Dark Mode Support for New Elements */
        @media (prefers-color-scheme: dark) {
            .recent-appointments h3 {
                color: #e2e8f0;
            }

            .appointment-item {
                background: rgba(255, 255, 255, 0.05);
            }

            .appointment-item:hover {
                background: rgba(255, 255, 255, 0.08);
            }

            .doctor-name {
                color: #90cdf4;
            }

            .appointment-date {
                color: #a0aec0;
            }

            .last-login {
                background: rgba(255, 255, 255, 0.05);
                color: #a0aec0;
            }
        }
    </style>
@endsection
