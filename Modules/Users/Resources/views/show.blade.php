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

@section('actions')
    <div class="d-flex gap-2">
        <!-- زر تغيير الحالة -->
        <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn {{ $user->status ? 'btn-soft-warning' : 'btn-soft-success' }}">
                @if($user->status)
                    <i class="bi bi-pause-circle me-2"></i> تعطيل
                @else
                    <i class="bi bi-play-circle me-2"></i> تفعيل
                @endif
            </button>
        </form>

        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-soft-danger" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                <i class="bi bi-x-circle me-2"></i> حذف
            </button>
        </form>

        <a href="{{ route('users.edit', $user) }}" class="btn btn-soft-primary">
            <i class="bi bi-pencil me-2"></i> تعديل البيانات
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8">
            <!-- معلومات المستخدم -->
            <div class="card shadow-sm h-100  mb-4">
                <div class="card-header border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-person-vcard me-2 text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold">معلومات المستخدم</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row mb-4 align-items-center">
                        <div class="user-avatar mb-3 mb-md-0 me-md-4">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="avatar-img">
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif
                        </div>
                        <div>
                            <h4 class="mb-1">{{ $user->name }}</h4>
                            <p class="mb-2 text-muted">{{ $user->email }}</p>
                            <div class="d-flex align-items-center">
                                <span class="status {{ $user->status ? 'active' : 'inactive' }}">
                                    {{ $user->status ? 'نشط' : 'غير نشط' }}
                                </span>
                                <span class="mx-2">•</span>
                                <span class="role-badge">
                                    {{ $user->roles->first()->name ?? 'لا يوجد دور' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-hash"></i>
                            </div>
                            <div class="info-content">
                                <label>كود المستخدم</label>
                                <div class="info-value">#{{ $user->id }}</div>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="info-content">
                                <label>رقم الهاتف</label>
                                <div class="info-value">{{ $user->phone_number }}</div>
                            </div>
                        </div>

                        @if($user->last_login_at)
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="info-content">
                                <label>آخر تسجيل دخول</label>
                                <div class="info-value">{{ $user->last_login_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($user->address || $user->city)
            <!-- معلومات العنوان -->
            <div class="card shadow-sm h-100  mb-4">
                <div class="card-header border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold">معلومات العنوان</h5>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        @if($user->address)
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-house"></i>
                            </div>
                            <div class="info-content">
                                <label>العنوان</label>
                                <div class="info-value">{{ $user->address }}</div>
                            </div>
                        </div>
                        @endif

                        @if($user->city)
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="info-content">
                                <label>المدينة</label>
                                <div class="info-value">{{ $user->city->name }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-xl-4">
            <!-- الإحصائيات -->
            <div class="card shadow-sm h-100  mb-4">
                <div class="card-header border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-graph-up me-2 text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold">الإحصائيات</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-calendar2-check"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-label">تاريخ التسجيل</div>
                                <div class="stat-value">{{ $user->created_at->format('Y-m-d') }}</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="bi bi-door-open"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-label">عدد مرات الدخول</div>
                                <div class="stat-value">{{ $user->login_count ?? 0 }}</div>
                            </div>
                        </div>

                        @if($user->roles->first()->name === 'patient')
                        <div class="stat-card">
                            <div class="stat-icon appointments">
                                <i class="bi bi-calendar2-week"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-label">عدد المواعيد</div>
                                <div class="stat-value">{{ $user->patient->appointments->count() }}</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon pending">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-label">المواعيد المعلقة</div>
                                <div class="stat-value">{{ $user->patient->appointments->where('status', 'pending')->count() }}</div>
                            </div>
                        </div>
                        @endif

                        @if($user->roles->first()->name === 'doctor')
                        <div class="stat-card">
                            <div class="stat-icon patients">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-label">عدد المرضى</div>
                                <div class="stat-value">{{ $user->doctor->appointments->count() }}</div>
                            </div>
                        </div>

                        <div class="stat-card">
                            <div class="stat-icon rating">
                                <i class="bi bi-star"></i>
                            </div>
                            <div class="stat-details">
                                <div class="stat-label">متوسط التقييم</div>
                                <div class="stat-value">{{ number_format($user->doctor->rating_avg, 1) }}</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border: none;
    margin-bottom: 1.5rem;
}

.card-header {
    background: transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.card-header i {
    font-size: 1.25rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2rem;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.25rem;
    border-radius: 12px;
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
    color: #64748b;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
    display: block;
}

.info-value {
    color: #1e293b;
    font-weight: 500;
    font-size: 1rem;
}

.status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status.active {
    background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
    color: #38c172;
}

.status.inactive {
    background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
    color: #e3342f;
}

.status.scheduled {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.15) 100%);
    color: #9333ea;
    border: 1px solid rgba(147, 51, 234, 0.1);
}

.status .badge {
    background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.15) 100%) !important;
    color: #9333ea;
}

.role-badge {
    display: inline-flex;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: var(--bs-primary);
    font-size: 0.875rem;
    font-weight: 500;
    border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border-radius: 12px;
    transition: all 0.3s ease;
}


.stat-icon {
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

.stat-icon.appointments {
    background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
    color: #38c172;
}

.stat-icon.pending {
    background: linear-gradient(135deg, rgba(246, 153, 63, 0.1) 0%, rgba(255, 139, 20, 0.1) 100%);
    color: #f59e0b;
}

.stat-icon.patients {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(79, 70, 229, 0.1) 100%);
    color: #6366f1;
}

.stat-icon.rating {
    background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(251, 191, 36, 0.1) 100%);
    color: #f59e0b;
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
    font-size: 1.25rem;
    font-weight: 600;
}

.appointments-timeline {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    position: relative;
    padding-right: 1.5rem;
}

.appointments-timeline::before {
    content: '';
    position: absolute;
    top: 0;
    right: 7px;
    height: 100%;
    width: 2px;
    background: linear-gradient(to bottom, rgba(var(--bs-primary-rgb), 0.2), rgba(var(--bs-primary-rgb), 0.1));
}

.timeline-item {
    position: relative;
    padding-right: 2rem;
}

.timeline-marker {
    position: absolute;
    right: -2rem;
    top: 1.5rem;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--bs-primary);
    border: 2px solid white;
    transform: translateY(-50%);
}

.timeline-marker.completed {
    background: #38c172;
}

.timeline-marker.cancelled {
    background: #e3342f;
}

.timeline-marker.pending {
    background: #f59e0b;
}

.appointment-card {
    background: white;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 2px 8px rgba(var(--bs-primary-rgb), 0.06);
    border: 1px solid rgba(var(--bs-primary-rgb), 0.08);
}

.appointment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.time {
    font-weight: 600;
    color: var(--bs-primary);
}

.doctor-info {
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
}

.doctor-name {
    font-weight: 500;
    color: #1e293b;
}

.appointment-date {
    font-size: 0.875rem;
    color: #64748b;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }

    .stat-card {
        padding: 0.875rem;
    }

    .timeline-item {
        padding-right: 1.5rem;
    }
}

.btn-soft-success {
    background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
    color: #38c172;
    border: 1px solid rgba(56, 193, 114, 0.1);
}

.btn-soft-success:hover {
    background: #38c172;
    color: white;
}

.btn-soft-danger {
    background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
    color: #e3342f;
    border: 1px solid rgba(227, 52, 47, 0.1);
}

.btn-soft-danger:hover {
    background: #e3342f;
    color: white;
}

.btn-soft-primary {
    background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: var(--bs-primary);
    border: 1px solid rgba(var(--bs-primary-rgb), 0.1);
}

.btn-soft-primary:hover {
    background: var(--bs-primary);
    color: white;
}

.btn-soft-warning {
    background: linear-gradient(135deg, rgba(246, 153, 63, 0.1) 0%, rgba(255, 139, 20, 0.1) 100%);
    color: #f59e0b;
    border: 1px solid rgba(246, 153, 63, 0.1);
}

.btn-soft-warning:hover {
    background: #f59e0b;
    color: white;
}

.user-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(var(--bs-dark-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: var(--bs-dark);
    font-size: 2rem;
    font-weight: 600;
}

.avatar-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}
</style>
@endsection
