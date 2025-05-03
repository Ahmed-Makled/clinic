@extends('layouts.app')

@section('title', 'تفاصيل الموعد')

@section('content')
<div class="container py-5 mt-4">
    <!-- رسالة التأكيد -->
    @if (session('success'))
    <div class="alert alert-success custom-alert mb-4 shadow-sm">
        <div class="d-flex">
            <div class="alert-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="alert-content">
                <h6 class="alert-heading mb-1">تم تأكيد الحجز بنجاح!</h6>
                <p class="mb-0">{!! session('success') !!}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <!-- بطاقة تفاصيل الموعد -->
            <div class="card appointment-card rounded-4 mb-4 shadow-sm">
                <div class="card-header border-0 bg-white pt-4 pb-0 px-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="header-icon">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <div>
                            <h1 class="card-title fs-4 fw-bold mb-0">تفاصيل الحجز</h1>
                            <span class="text-secondary">رقم الحجز: #{{ $appointment->id }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0 px-4">
                    <!-- معلومات الموعد -->
                    <div class="appointment-info mt-3">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-calendar2-event"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">تاريخ الموعد</span>
                                        <h6 class="info-value mb-0">
                                            {{ $appointment->scheduled_at->locale('ar')->translatedFormat('l d F Y') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-clock"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">وقت الكشف</span>
                                        <h6 class="info-value mb-0">
                                            {{ $appointment->scheduled_at->format('h:i A') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-icon status-{{ $appointment->status }}">
                                        <i class="bi bi-circle-fill"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">حالة الموعد</span>
                                        <h6 class="info-value status-text-{{ $appointment->status }} mb-0">
                                            {{ $appointment->status_text }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-cash-stack"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">تكلفة الكشف</span>
                                        <h6 class="info-value mb-0">
                                            {{ $appointment->fees }} جنيه
                                            <span class="payment-badge {{ $appointment->is_paid ? 'paid' : 'unpaid' }}">
                                                {{ $appointment->is_paid ? 'مدفوع' : 'غير مدفوع' }}
                                            </span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($appointment->notes)
                    <!-- ملاحظات الموعد -->
                    <div class="appointment-notes mt-4">
                        <h6 class="section-title">
                            <i class="bi bi-journal-text text-primary me-2"></i>
                            ملاحظات
                        </h6>
                        <div class="notes-content p-3 bg-light rounded-3 text-secondary">
                            {{ $appointment->notes }}
                        </div>
                    </div>
                    @endif

                    <!-- تذكيرات وتعليمات -->
                    <div class="instructions mt-4">
                        <h6 class="section-title">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>
                            تعليمات هامة
                        </h6>
                        <div class="instructions-content p-3 bg-light rounded-3">
                            <ul class="instructions-list mb-0 text-secondary">
                                <li>يرجى الوصول قبل الموعد المحدد ب 15-20 دقيقة لإتمام إجراءات التسجيل</li>
                                <li>يجب إحضار البطاقة الشخصية وأي تقارير طبية سابقة إن وجدت</li>
                                <li>يمكنك إلغاء الحجز قبل 24 ساعة على الأقل من موعد الكشف</li>
                                <li>في حالة التأخير أكثر من 20 دقيقة، قد يتم تأجيل الموعد</li>
                            </ul>
                        </div>
                    </div>

                    <!-- أزرار إجراءات -->
                    @if($appointment->status === 'scheduled')
                    <div class="appointment-actions mt-4 text-center">
                        <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الموعد؟')">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle me-2"></i>
                                إلغاء الموعد
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- بطاقة معلومات الطبيب -->
            <div class="card rounded-4 mb-4 shadow-sm">
                <div class="card-header border-0 bg-white pt-4 pb-0 px-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-person-badge text-primary me-1"></i>
                        <h6 class="card-title mb-0 fw-bold">معلومات الطبيب</h6>
                    </div>
                </div>
                <div class="card-body pt-0 px-4">
                    <div class="doctor-profile text-center">
                        <div class="doctor-avatar-wrapper mx-auto mb-3">
                            @if($appointment->doctor->image)
                            <img src="{{ asset('storage/' . $appointment->doctor->image) }}" alt="{{ $appointment->doctor->name }}" class="doctor-avatar"
                                 onerror="this.src='{{ asset('images/default-doctor.png') }}'; this.onerror=null;">
                            @else
                            <div class="doctor-avatar-placeholder">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            @endif
                        </div>
                        <h5 class="doctor-name mb-1">{{ $appointment->doctor->gender == 'ذكر' ? 'د.' : 'د.' }} {{ $appointment->doctor->name }}</h5>
                        <p class="doctor-title text-primary mb-2">{{ $appointment->doctor->title }}</p>

                        <div class="categories mb-3">
                            @foreach($appointment->doctor->categories as $category)
                            <span class="category-badge">{{ $category->name }}</span>
                            @endforeach
                        </div>

                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>
                                    {{ $appointment->doctor->governorate->name }} - {{ $appointment->doctor->city->name }}
                                </span>
                            </div>
                            <div class="contact-item">
                                <i class="bi bi-building"></i>
                                <span>{{ $appointment->doctor->address ?: 'عنوان العيادة غير متوفر' }}</span>
                            </div>
                            @if($appointment->doctor->phone)
                            <div class="contact-item">
                                <i class="bi bi-telephone-fill"></i>
                                <span>{{ $appointment->doctor->phone }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- بطاقة المساعدة والدعم -->
            <div class="card support-card rounded-4 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="bi bi-headset text-primary me-2"></i>
                        مساعدة ودعم
                    </h6>
                    <p class="text-muted">هل تواجه مشكلة أو لديك استفسار بخصوص موعدك؟</p>
                    <div class="support-contact">
                        <div class="support-item mb-2">
                            <i class="bi bi-telephone"></i>
                            <span>02-123456789</span>
                        </div>
                        <div class="support-item">
                            <i class="bi bi-envelope"></i>
                            <span>support@clinic.com</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* رسالة التأكيد */
.custom-alert {
    border-radius: 12px;
    border-left: 5px solid #28a745;
    padding: 1.25rem;
    background: linear-gradient(to right, rgba(40, 167, 69, 0.05), transparent);
}

.alert-icon {
    font-size: 2rem;
    color: #28a745;
    margin-right: 1.25rem;
}

.alert-content h6 {
    font-weight: 600;
    color: #28a745;
}

/* بطاقات المعلومات */
.appointment-card {
    border: none;
    overflow: hidden;
}

.header-icon {
    font-size: 2rem;
    color: var(--bs-primary);
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    width: 64px;
    height: 64px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* عناصر المعلومات */
.info-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.info-icon {
    width: 46px;
    height: 46px;
    border-radius: 12px;
    background-color: #f8f9fa;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.info-content {
    flex: 1;
}

.info-label {
    display: block;
    color: #6c757d;
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.info-value {
    color: #212529;
    font-weight: 600;
}

/* أيقونات الحالة */
.info-icon.status-scheduled {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    color: var(--bs-primary);
}

.info-icon.status-completed {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.info-icon.status-cancelled {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

.status-text-scheduled {
    color: var(--bs-primary);
}

.status-text-completed {
    color: #28a745;
}

.status-text-cancelled {
    color: #dc3545;
}

/* شارات الدفع */
.payment-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 50px;
    font-size: 0.75rem;
    margin-right: 0.5rem;
}

.payment-badge.paid {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28a745;
}

.payment-badge.unpaid {
    background-color: rgba(220, 53, 69, 0.1);
    color: #dc3545;
}

/* عناوين الأقسام */
.section-title {
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

/* قائمة التعليمات */
.instructions-list {
    padding-right: 1rem;
}

.instructions-list li {
    margin-bottom: 0.75rem;
    position: relative;
}

.instructions-list li:last-child {
    margin-bottom: 0;
}

/* معلومات الطبيب */
.doctor-avatar-wrapper {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.doctor-avatar, .doctor-avatar-placeholder {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.doctor-avatar-placeholder {
    background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.2), rgba(var(--bs-primary-rgb), 0.1));
    color: var(--bs-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.doctor-name {
    font-weight: 600;
}

.doctor-title {
    font-size: 0.9rem;
}

.categories {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
}

.category-badge {
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    color: var(--bs-primary);
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
}

.contact-info {
    margin-top: 1rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #6c757d;
}

.contact-item i {
    color: var(--bs-primary);
}

/* بطاقة الدعم */
.support-card {
    border: none;
    background: linear-gradient(135deg, #f8f9fa, #fff);
}

.support-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
}

.support-item i {
    color: var(--bs-primary);
}
</style>
@endsection
