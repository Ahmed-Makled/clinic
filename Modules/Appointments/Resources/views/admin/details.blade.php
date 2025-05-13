@extends('layouts.admin')

@section('title', 'تفاصيل الحجز')
@section('breadcrumbs')
    <li class="breadcrumb-item">
        <a href="{{ route('dashboard.index') }}" class="text-decoration-none">لوحة التحكم</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('appointments.index') }}" class="text-decoration-none">الحجوزات</a>
    </li>
    <li class="breadcrumb-item active">تفاصيل الحجز</li>
@endsection
@section('actions')
    <div class="d-flex gap-2">
        @if($appointment->status !== 'completed' && $appointment->status !== 'cancelled')
            <form action="{{ route('appointments.complete', $appointment) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-soft-success">
                    <i class="bi bi-check-circle me-2"></i> إتمام الحجز
                </button>
            </form>
        @endif

        @if($appointment->status !== 'cancelled')
            <form action="{{ route('appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-soft-danger">
                    <i class="bi bi-x-circle me-2"></i> إلغاء الحجز
                </button>
            </form>
        @endif

        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-soft-primary">
            <i class="bi bi-pencil me-2"></i> تعديل الحجز
        </a>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8">
                <!-- تفاصيل الحجز -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header border-0 py-3 d-flex align-items-center">
                        <i class="bi bi-calendar-check me-2 "></i>
                        <h5 class="card-title mb-0 fw-bold">تفاصيل الحجز</h5>
                    </div>
                    <div class="card-body">
                        <div class="appointment-info">
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-hash"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>رقم الحجز</label>
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
                                                <span
                                                    class="payment-status {{ $appointment->is_paid ? 'paid' : 'unpaid' }}">
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
                                                <span class="important-badge">حجز مهم</span>
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

                <!-- قسم تقييم الطبيب -->
                @if($appointment->status === 'completed')
                    <div class="card shadow-sm mb-4">
                        <div class="card-header border-0 py-3 d-flex align-items-center">
                            <i class="bi bi-star me-2"></i>
                            <h5 class="card-title mb-0 fw-bold">تقييم الطبيب</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $existingRating = \Modules\Doctors\Entities\DoctorRating::where('appointment_id', $appointment->id)
                                    ->where('patient_id', $appointment->patient_id)
                                    ->first();
                            @endphp

                            @if($existingRating)
                                <!-- عرض التقييم للمشاهدة فقط -->
                                <div class="existing-rating">
                                    <div class="rating-display mb-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="rating-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $existingRating->rating)
                                                        <i class="bi bi-star-fill text-warning fs-3"></i>
                                                    @else
                                                        <i class="bi bi-star text-warning fs-3"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <div class="rating-value ms-3">
                                                <span class="fw-bold fs-4">{{ $existingRating->rating }}/5</span>
                                            </div>
                                        </div>

                                        @if($existingRating->comment)
                                            <div class="rating-comment p-3 bg-light rounded">
                                                <i class="bi bi-quote me-2"></i>
                                                {{ $existingRating->comment }}
                                            </div>
                                        @endif
                                        <div class="rating-meta text-muted mt-2">
                                            <small>تم التقييم في: {{ $existingRating->created_at->format('Y-m-d H:i') }}</small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="no-rating text-center py-4">
                                    <i class="bi bi-star text-muted fs-1 mb-3 d-block"></i>
                                    <p class="text-muted">لم يقم المريض بتقييم الطبيب لهذا الحجز حتى الآن.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-xl-4">
                <!-- معلومات الطبيب -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header border-0 py-3 d-flex align-items-center">
                        <i class="bi bi-person-badge me-2 "></i>
                        <h5 class="card-title mb-0 fw-bold">معلومات الطبيب</h5>
                    </div>
                    <div class="card-body">
                        <div class="profile-info">
                            <div class="profile-avatar">
                                @if($appointment->doctor->image)
                                    <img src="{{ asset('storage/' . $appointment->doctor->image) }}"
                                        alt="{{ $appointment->doctor->name }}" class="doctor-image"
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
                                    @if($appointment->doctor->category)
                                        <span class="specialty-badge">{{ $appointment->doctor->category->name }}</span>
                                    @endif
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
                        <i class="bi bi-person me-2 "></i>
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
            color: #1a202c;
            /* تغيير لون الأيقونة لتكون داكنة */
        }

        .card-header h5 {
            color: #1a202c;
            /* تغيير لون عنوان البطاقة ليكون داكن */
            font-weight: 600;
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
            background: #f7fafc;
            /* تغيير خلفية الأيقونة */
            color: #1a202c;
            /* تغيير لون الأيقونة لتكون داكنة */
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
            border: 1px solid #edf2f7;
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
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(147, 51, 234, 0.15) 100%);
            color: #9333ea;
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

        .notes-header i {
            color: #1a202c;
            /* تغيير لون أيقونة قسم الملاحظات */
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
            background: #f7fafc;
            color: #2d3748;
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

        .doctor-name,
        .patient-name {
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
            background: #f7fafc;
            /* تغيير الخلفية */
            color: #2d3748;
            /* تغيير اللون */
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

        .contact-item i {
            color: #1a202c;
            /* تغيير لون أيقونات معلومات الاتصال */
        }

        /* أنماط نظام تقييم النجوم */
        .stars {
            display: flex;
            justify-content: center;
            direction: rtl;
            gap: 10px;
        }

        .star-label {
            cursor: pointer;
            font-size: 30px;
            color: #d0d0d0;
            transition: all 0.3s ease;
        }

        .star-label.selected,
        .star-label:hover,
        .star-label:hover~.star-label {
            color: #ffc107;
        }

        input[name="rating"]:checked~label {
            color: #ffc107;
        }

        .visually-hidden {
            position: absolute;
            width: 1px;
            height: 1px;
            margin: -1px;
            padding: 0;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        .rating-value {
            margin-top: 10px;
            font-weight: 600;
            color: #343a40;
        }

        /* تعطيل تأثير التحويم للنجوم في النماذج المصنفة مسبقًا */
        .rated-form .star-label {
            cursor: default;
            pointer-events: none;
        }

        .rating-stars {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .rating-stars i {
            color: #ffc107;
        }

        .rating-comment {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            color: #495057;
        }

        .alert-success {
            background-color: #d1e7dd;
            color: #0f5132;
            border-color: #badbcc;
            border-radius: 8px;
        }

        .rating-display {
            margin: 20px 0;
            padding: 10px;
            border-radius: 8px;
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

            .stars {
                gap: 5px;
            }

            .star-label {
                font-size: 24px;
            }

            .rating-stars {
                font-size: 20px;
            }
        }
    </style>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // التعامل مع زر تعديل التقييم
            const editRatingBtn = document.getElementById('editRatingBtn');
            const editRatingForm = document.getElementById('editRatingForm');
            const cancelEditBtn = document.getElementById('cancelEditBtn');

            if (editRatingBtn && editRatingForm && cancelEditBtn) {
                editRatingBtn.addEventListener('click', function () {
                    editRatingForm.style.display = 'block';
                    editRatingBtn.style.display = 'none';
                });

                cancelEditBtn.addEventListener('click', function () {
                    editRatingForm.style.display = 'none';
                    editRatingBtn.style.display = 'inline-flex';
                });
            }

            // التعامل مع نظام النجوم لإضافة تقييم جديد
            const starLabels = document.querySelectorAll('.stars:not(.rated-form) .star-label');
            const ratingValueDisplay = document.getElementById('rating-value');

            if (starLabels.length > 0 && ratingValueDisplay) {
                starLabels.forEach(label => {
                    label.addEventListener('click', function () {
                        const ratingValue = this.previousElementSibling.value;
                        const ratingText = ratingValue === '1' ? 'نجمة واحدة' : `${ratingValue} نجوم`;
                        ratingValueDisplay.textContent = ratingText;

                        // تحديد النجوم المحددة
                        starLabels.forEach(innerLabel => {
                            if (innerLabel.previousElementSibling.value <= ratingValue) {
                                innerLabel.classList.add('selected');
                            } else {
                                innerLabel.classList.remove('selected');
                            }
                        });
                    });
                });
            }

            // التعامل مع نظام النجوم لتعديل التقييم
            const starLabelsEdit = document.querySelectorAll('#editRatingForm .star-label');
            const ratingValueDisplayEdit = document.getElementById('rating-value-edit');

            if (starLabelsEdit.length > 0 && ratingValueDisplayEdit) {
                starLabelsEdit.forEach(label => {
                    label.addEventListener('click', function () {
                        const ratingValue = this.previousElementSibling.value;
                        const ratingText = ratingValue === '1' ? 'نجمة واحدة' : `${ratingValue} نجوم`;
                        ratingValueDisplayEdit.textContent = ratingText;

                        // تحديد النجوم المحددة
                        starLabelsEdit.forEach(innerLabel => {
                            if (innerLabel.previousElementSibling.value <= ratingValue) {
                                innerLabel.classList.add('selected');
                            } else {
                                innerLabel.classList.remove('selected');
                            }
                        });
                    });
                });
            }
        });
    </script>
@endpush