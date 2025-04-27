@extends('layouts.admin')

@section('title', 'تفاصيل الموعد')
@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('appointments.index') }}" class="text-decoration-none">المواعيد</a>
</li>
<li class="breadcrumb-item active">تفاصيل الموعد</li>
@endsection
@section('actions')
    <div class="d-flex gap-2">
        @if($appointment->status !== 'completed')
            <form action="{{ route('appointments.complete', $appointment) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-soft-success">
                    <i class="bi bi-check-circle me-2"></i> إتمام الموعد
                </button>
            </form>
        @endif

        @if($appointment->status !== 'cancelled')
            <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-soft-danger">
                    <i class="bi bi-x-circle me-2"></i> إلغاء الموعد
                </button>
            </form>
        @endif

        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-soft-primary">
            <i class="bi bi-pencil me-2"></i> تعديل الموعد
        </a>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8">
            <!-- تفاصيل الموعد -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-calendar-check me-2 text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold">تفاصيل الموعد</h5>
                </div>
                <div class="card-body">
                    <div class="appointment-info">
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-hash"></i>
                                </div>
                                <div class="info-content">
                                    <label>رقم الموعد</label>
                                    <div class="info-value">#{{ $appointment->id }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-circle"></i>
                                </div>
                                <div class="info-content">
                                    <label>الحالة</label>
                                    <div class="info-value">
                                        <span class="status {{ $appointment->status }}">
                                            {{ $appointment->status_text }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-calendar2"></i>
                                </div>
                                <div class="info-content">
                                    <label>التاريخ</label>
                                    <div class="info-value">{{ $appointment->scheduled_at->format('Y-m-d') }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <div class="info-content">
                                    <label>الوقت</label>
                                    <div class="info-value">{{ $appointment->scheduled_at->format('h:i A') }}</div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-cash"></i>
                                </div>
                                <div class="info-content">
                                    <label>رسوم الكشف</label>
                                    <div class="info-value">
                                        <span class="d-flex align-items-center gap-2">
                                            {{ number_format($appointment->fees, 2) }} جنيه
                                            <span class="payment-status {{ $appointment->is_paid ? 'paid' : 'unpaid' }}">
                                                {{ $appointment->is_paid ? 'تم الدفع' : 'لم يتم الدفع' }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            @if($appointment->is_important)
                                <div class="info-item">
                                    <div class="info-icon important">
                                        <i class="bi bi-exclamation-triangle"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>الأهمية</label>
                                        <div class="info-value">
                                            <span class="important-badge">موعد مهم</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if($appointment->notes)
                            <div class="notes-section">
                                <div class="notes-header">
                                    <i class="bi bi-journal-text"></i>
                                    <h6 class="mb-0">ملاحظات</h6>
                                </div>
                                <div class="notes-content">
                                    {{ $appointment->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <!-- معلومات الطبيب -->
            <div class="card shadow-sm mb-4">
                <div class="card-header border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-person-badge me-2 text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold">معلومات الطبيب</h5>
                </div>
                <div class="card-body">
                    <div class="profile-info">
                        <div class="profile-avatar">
                            @if($appointment->doctor->image)
                                <img src="{{ asset('storage/' . $appointment->doctor->image) }}"
                                    alt="{{ $appointment->doctor->name }}"
                                    class="doctor-image"
                                    onerror="this.src='{{ asset('images/default-doctor.png') }}'; this.onerror=null;">
                            @else
                                <div class="avatar-placeholder">
                                    {{ substr($appointment->doctor->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <div class="profile-details">
                            <h5 class="doctor-name">د. {{ $appointment->doctor->name }}</h5>
                            <div class="specialties">
                                @foreach($appointment->doctor->categories as $category)
                                    <span class="specialty-badge">{{ $category->name }}</span>
                                @endforeach
                            </div>
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="bi bi-envelope"></i>
                                    {{ $appointment->doctor->email }}
                                </div>
                                <div class="contact-item">
                                    <i class="bi bi-telephone"></i>
                                    {{ $appointment->doctor->phone }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات المريض -->
            <div class="card shadow-sm">
                <div class="card-header border-0 py-3 d-flex align-items-center">
                    <i class="bi bi-person me-2 text-primary"></i>
                    <h5 class="card-title mb-0 fw-bold">معلومات المريض</h5>
                </div>
                <div class="card-body">
                    <div class="profile-info">
                        <div class="profile-avatar patient">
                            <div class="avatar-placeholder">
                                {{ substr($appointment->patient->name, 0, 2) }}
                            </div>
                        </div>
                        <div class="profile-details">
                            <h5 class="patient-name">{{ $appointment->patient->name }}</h5>
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="bi bi-envelope"></i>
                                    {{ $appointment->patient->user->email }}
                                </div>
                                @if($appointment->patient->phone)
                                    <div class="contact-item">
                                        <i class="bi bi-telephone"></i>
                                        {{ $appointment->patient->phone }}
                                    </div>
                                @endif
                                @if($appointment->patient->gender)
                                    <div class="contact-item">
                                        <i class="bi bi-gender-ambiguous"></i>
                                        {{ $appointment->patient->gender }}
                                    </div>
                                @endif
                            </div>
                        </div>
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


.info-icon.important {
    background: #dc2626;
    color: white;
    border-color: rgba(220, 38, 38, 0.1);
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

.status.scheduled {
    background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: var(--bs-primary);
}

.status.completed {
    background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
    color: #38c172;
}

.status.cancelled {
    background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
    color: #e3342f;
}

.payment-status {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
}

.payment-status.paid {
    background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
    color: #38c172;
}

.payment-status.unpaid {
    background: linear-gradient(135deg, rgba(227, 52, 47, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);
    color: #e3342f;
}

.important-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    background: linear-gradient(135deg, rgba(220, 38, 38, 0.1) 0%, rgba(239, 68, 68, 0.1) 100%);
    color: #dc2626;
    font-size: 0.875rem;
    font-weight: 500;
}

.notes-section {
    margin-top: 2rem;
    padding: 1.5rem;
}

.notes-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
    color: var(--bs-primary);
}

.notes-content {
    color: #64748b;
    line-height: 1.6;
}

.profile-info {
    display: flex;
    gap: 1.25rem;
    align-items: flex-start;
}

.profile-avatar {
    width: 64px;
    height: 64px;
    border-radius: 15px;
    overflow: hidden;
    flex-shrink: 0;
}

.doctor-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: var(--bs-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 1.25rem;
}

.profile-avatar.patient .avatar-placeholder {
    background: linear-gradient(135deg, rgba(56, 193, 114, 0.1) 0%, rgba(47, 182, 100, 0.1) 100%);
    color: #38c172;
}

.profile-details {
    flex: 1;
}

.doctor-name, .patient-name {
    color: #1e293b;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.specialties {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.specialty-badge {
    display: inline-flex;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.1) 0%, rgba(37, 99, 235, 0.1) 100%);
    color: var(--bs-primary);
    font-size: 0.875rem;
    font-weight: 500;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #64748b;
    font-size: 0.875rem;
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

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }

    .profile-info {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .contact-info {
        align-items: center;
    }

    .specialties {
        justify-content: center;
    }
}
</style>
@endsection
