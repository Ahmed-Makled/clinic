@extends('layouts.app')

@section('content')
    <div class="container mt-5 py-5">

        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- رسائل النجاح والخطأ -->

                @if (session('success'))
                    <div class="alert-card success mb-4">
                        <div class="alert-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="alert-content">
                            <h6 class="alert-heading">تمت العملية بنجاح!</h6>
                            <p class="mb-0">{!! session('success') !!}</p>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert-card error mb-4">
                        <div class="alert-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <h6 class="alert-heading">حدث خطأ!</h6>
                            <p class="mb-0">{!! session('error') !!}</p>
                        </div>
                        <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endif

                <!-- بطاقة الملف الشخصي الرئيسية -->
                <div class="card profile-card border-0 shadow-sm overflow-hidden mb-4">
                    <div class="card-header  py-3 border-0 bg-light text-dark">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle fs-3 me-3"></i>
                            <h4 class="mb-0 fw-bold">{{ $title }}</h4>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="row g-0">
                            <!-- القائمة الجانبية -->
                            <div class="col-lg-2 profile-sidebar-wrapper">
                                <div class="profile-sidebar p-4">
                                    <!-- معلومات المستخدم المختصرة -->
                                    <div class="text-center mb-4 pb-3 border-bottom">
                                        <div class="profile-avatar mx-auto mb-3">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                                        <p class="text-muted small mb-3">{{ $user->email }}</p>
                                        <div class="user-role">
                                            <span>{{ $user->getRoleNames()->first() }}</span>
                                        </div>
                                    </div>

                                    <!-- روابط الأقسام -->
                                    <div class="nav flex-column nav-pills mb-4" id="profile-tabs" role="tablist">
                                        <a class="nav-link active d-flex align-items-center mb-2 py-2" id="personal-tab"
                                            data-bs-toggle="pill" href="#personal" role="tab">
                                            <i class="bi bi-person-vcard me-2"></i>
                                            <span>بياناتي</span>
                                        </a>

                                        @if($user->isPatient())
                                            <a class="nav-link d-flex align-items-center mb-2 py-2" id="appointments-tab"
                                                data-bs-toggle="pill" href="#appointments" role="tab">
                                                <i class="bi bi-calendar2-check me-2"></i>
                                                <span>حجوزاتي</span>
                                            </a>
                                        @endif
                                        <a class="nav-link d-flex align-items-center mb-2 py-2" id="password-tab"
                                            data-bs-toggle="pill" href="#password" role="tab">
                                            <i class="bi bi-lock me-2"></i>
                                            <span>تغيير كلمة المرور</span>
                                        </a>
                                    </div>


                                </div>
                            </div>

                            <!-- محتوى الصفحة -->
                            <div class="col-lg-10 profile-content-wrapper">
                                <div class="tab-content p-4" id="profile-tabsContent">
                                    <!-- تبويب البيانات الشخصية -->
                                    <div class="tab-pane fade show active" id="personal" role="tabpanel"
                                        aria-labelledby="personal-tab">
                                        <div class="content-section mb-4">
                                            <div
                                                class="section-header d-flex align-items-center mb-3 pb-2 border-bottom justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <div class="section-icon">
                                                        <i class="bi bi-person-vcard"></i>
                                                    </div>
                                                    <h5 class="mb-0 fw-bold">بياناتي</h5>
                                                </div>
                                                @if($user->patient)
                                                    <div class="">
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal" data-bs-target="#editPatientModal">
                                                            <i class="bi bi-pencil-square me-1"></i> تعديل البيانات
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>


                                            <div class="profile-info">
                                                <div class="info-item">
                                                    <div class="info-label">
                                                        <i class="bi bi-person"></i>
                                                        <span>الاسم</span>
                                                    </div>
                                                    <div class="info-value">
                                                        {{ $user->name }}
                                                    </div>
                                                </div>

                                                <div class="info-item">
                                                    <div class="info-label">
                                                        <i class="bi bi-envelope"></i>
                                                        <span>البريد الإلكتروني</span>
                                                    </div>
                                                    <div class="info-value">
                                                        {{ $user->email }}
                                                    </div>
                                                </div>

                                                <div class="info-item">
                                                    <div class="info-label">
                                                        <i class="bi bi-telephone"></i>
                                                        <span>رقم الهاتف</span>
                                                    </div>
                                                    <div class="info-value">
                                                        {{ $user->phone_number ?: 'غير محدد' }}
                                                    </div>
                                                </div>
                                                @if($user->patient)
                                                    <div class="profile-info">
                                                        <div class="info-item">
                                                            <div class="info-label">
                                                                <i class="bi bi-calendar-event"></i>
                                                                <span>تاريخ الميلاد</span>
                                                            </div>
                                                            <div class="info-value">
                                                                {{ $user->patient && $user->patient->date_of_birth ? $user->patient->date_of_birth->format('Y-m-d') : 'غير محدد' }}
                                                            </div>
                                                        </div>

                                                        <div class="info-item">
                                                            <div class="info-label">
                                                                <i class="bi bi-gender-ambiguous"></i>
                                                                <span>الجنس</span>
                                                            </div>
                                                            <div class="info-value">
                                                                @if($user->patient && $user->patient->gender == 'male')
                                                                    <i class="bi bi-gender-male text-primary me-1"></i> ذكر
                                                                @elseif($user->patient && $user->patient->gender == 'female')
                                                                    <i class="bi bi-gender-female text-danger me-1"></i> أنثى
                                                                @else
                                                                    غير محدد
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="info-item">
                                                            <div class="info-label">
                                                                <i class="bi bi-geo-alt"></i>
                                                                <span>العنوان</span>
                                                            </div>
                                                            <div class="info-value">
                                                                {{ $user->patient && $user->patient->address ? $user->patient->address : 'غير محدد' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- قسم الملف الطبي (للمرضى فقط) -->
                                        @if($user->isPatient())
                                            <div class="content-section ">
                                                @if($user->patient)

                                                    <!-- نافذة تعديل البيانات -->
                                                    <div class="modal fade" id="editPatientModal" tabindex="-1"
                                                        aria-labelledby="editPatientModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editPatientModalLabel">
                                                                        <i class="bi bi-pencil-square text-dark me-2"></i>تعديل
                                                                        الملف الشخصي
                                                                    </h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                        aria-label="إغلاق"></button>
                                                                </div>
                                                                <form method="POST" action="{{ route('profile.update') }}">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    @if(request()->has('redirect_to'))
                                                                        <input type="hidden" name="redirect_to"
                                                                            value="{{ request('redirect_to') }}">
                                                                    @endif
                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <label for="name" class="form-label">
                                                                                <i class="bi bi-person me-1"></i>الاسم
                                                                            </label>
                                                                            <input type="text"
                                                                                class="form-control @error('name') is-invalid @enderror"
                                                                                id="name" name="name"
                                                                                value="{{ old('name', $user->name) }}">
                                                                            @error('name')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message ?? 'الاسم مطلوب' }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="email" class="form-label">
                                                                                <i class="bi bi-envelope me-1"></i>البريد الإلكتروني
                                                                            </label>
                                                                            <input type="email"
                                                                                class="form-control @error('email') is-invalid @enderror"
                                                                                id="email" name="email"
                                                                                value="{{ old('email', $user->email) }}">
                                                                            @error('email')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message ?? 'يرجى إدخال بريد إلكتروني صحيح' }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="phone_number" class="form-label">
                                                                                <i class="bi bi-telephone me-1"></i>رقم الهاتف
                                                                            </label>
                                                                            <input type="tel"
                                                                                class="form-control @error('phone_number') is-invalid @enderror"
                                                                                id="phone_number" name="phone_number"
                                                                                value="{{ old('phone_number', $user->phone_number) }}">
                                                                            @error('phone_number')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message ?? 'رقم الهاتف مطلوب' }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="date_of_birth" class="form-label">
                                                                                <i class="bi bi-calendar-date me-1"></i>تاريخ
                                                                                الميلاد
                                                                            </label>
                                                                            <input type="date"
                                                                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                                                                id="date_of_birth" name="date_of_birth"
                                                                                value="{{ old('date_of_birth', $user->patient->date_of_birth ? $user->patient->date_of_birth->format('Y-m-d') : '') }}">
                                                                            @error('date_of_birth')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message ?? 'صيغة تاريخ الميلاد غير صحيحة' }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label class="form-label">
                                                                                <i class="bi bi-gender-ambiguous me-1"></i>الجنس
                                                                            </label>
                                                                            <div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input" type="radio"
                                                                                        name="gender" id="gender_male" value="male"
                                                                                        {{ old('gender', $user->patient->gender) == 'male' ? 'checked' : '' }}>
                                                                                    <label class="form-check-label"
                                                                                        for="gender_male">ذكر</label>
                                                                                </div>
                                                                                <div class="form-check form-check-inline">
                                                                                    <input class="form-check-input" type="radio"
                                                                                        name="gender" id="gender_female"
                                                                                        value="female" {{ old('gender', $user->patient->gender) == 'female' ? 'checked' : '' }}>
                                                                                    <label class="form-check-label"
                                                                                        for="gender_female">أنثى</label>
                                                                                </div>
                                                                                @error('gender')
                                                                                    <div class="invalid-feedback d-block">
                                                                                        {{ $message ?? 'الجنس مطلوب' }}
                                                                                    </div>
                                                                                @enderror
                                                                            </div>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <label for="address" class="form-label">
                                                                                <i class="bi bi-geo-alt me-1"></i>العنوان
                                                                            </label>
                                                                            <textarea
                                                                                class="form-control @error('address') is-invalid @enderror"
                                                                                id="address" name="address" rows="3"
                                                                                placeholder="أدخل عنوانك هنا...">{{ old('address', $user->patient->address) }}</textarea>
                                                                            @error('address')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message ?? 'العنوان يتجاوز الحد المسموح به' }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">
                                                                            <i class="bi bi-x-circle me-1"></i>إلغاء
                                                                        </button>
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="bi bi-check2-circle me-1"></i>حفظ التغييرات
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- نموذج إنشاء الملف الطبي -->
                                                    <div class="alert alert-info border-0 shadow-sm mb-4 d-flex">
                                                        <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                                                        <div>
                                                            <h5 class="alert-heading mb-1">مطلوب استكمال البيانات</h5>
                                                            <p class="mb-0">من فضلك قم بإكمال بياناتك الطبية لتتمكن من حجز موعد
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <form method="POST" action="{{ route('profile.store') }}" class="profile-form">
                                                        @csrf
                                                        @if(request()->has('redirect_to'))
                                                            <input type="hidden" name="redirect_to"
                                                                value="{{ request('redirect_to') }}">
                                                        @endif

                                                        <div class="mb-3">
                                                            <label for="date_of_birth" class="form-label">
                                                                <i class="bi bi-calendar3 me-1"></i>تاريخ الميلاد
                                                            </label>
                                                            <input type="date"
                                                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                                                id="date_of_birth" name="date_of_birth"
                                                                value="{{ old('date_of_birth') }}">
                                                            @error('date_of_birth')
                                                                <div class="invalid-feedback">
                                                                    {{ $message ?? 'صيغة تاريخ الميلاد غير صحيحة' }}
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                <i class="bi bi-gender-ambiguous me-1"></i>الجنس
                                                            </label>
                                                            <div class="radio-group">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="gender"
                                                                        id="gender_male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                                                    <label class="form-check-label" for="gender_male"><i
                                                                            class="bi bi-gender-male me-1 text-primary"></i>ذكر</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio" name="gender"
                                                                        id="gender_female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="gender_female"><i
                                                                            class="bi bi-gender-female me-1 text-danger"></i>أنثى</label>
                                                                </div>
                                                                @error('gender')
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $message ?? 'الجنس مطلوب' }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="address" class="form-label">
                                                                <i class="bi bi-geo-alt me-1"></i>العنوان
                                                            </label>
                                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                                id="address" name="address" rows="3"
                                                                placeholder="أدخل عنوانك هنا...">{{ old('address') }}</textarea>
                                                            @error('address')
                                                                <div class="invalid-feedback">
                                                                    {{ $message ?? 'العنوان يتجاوز الحد المسموح به' }}
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-actions">
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="bi bi-save me-1"></i> حفظ البيانات
                                                            </button>
                                                        </div>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- تبويب الحجوزات -->
                                    @if($user->isPatient())
                                        <div class="tab-pane fade" id="appointments" role="tabpanel"
                                            aria-labelledby="appointments-tab">
                                            <div class="content-section mb-4">
                                                <div
                                                    class="section-header d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                                                    <div class="d-flex align-items-center">
                                                        <div class="section-icon green">
                                                            <i class="bi bi-calendar2-check"></i>
                                                        </div>
                                                        <h5 class="mb-0 fw-bold">حجوزاتي</h5>
                                                    </div>
                                                    <a href="{{ route('search') }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-plus-circle me-1"></i> حجز موعد جديد
                                                    </a>
                                                </div>

                                                @if($user->patient && count($user->patient->appointments) > 0)
                                                    <div class="appointments-wrapper">
                                                        <div class="table-responsive ">
                                                            <table class="table table-hover align-middle data-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">
                                                                            <span class="d-block fw-bold">الرقم</span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="d-block fw-bold">الطبيب</span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="d-block fw-bold">التخصص</span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="d-block fw-bold">التاريخ</span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="d-block fw-bold">الوقت</span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="d-block fw-bold">الحالة</span>
                                                                        </th>
                                                                        <th>
                                                                            <span class="d-block fw-bold">الإجراءات</span>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($user->patient->appointments as $appointment)
                                                                        <tr
                                                                            class=" {{ $appointment->status == 'cancelled' ? 'cancelled-row' : '' }}">
                                                                            <td class="text-center appointment-id">
                                                                                {{ $appointment->id }}
                                                                            </td>
                                                                            <td>
                                                                                <div class="doctor-info">
                                                                                    <div class="doctor-avatar">
                                                                                        @if($appointment->doctor->image)
                                                                                            <img src="{{ asset('storage/' . $appointment->doctor->image) }}"
                                                                                                alt="{{ $appointment->doctor->user->name }}"
                                                                                                class="img-fluid ">
                                                                                        @else
                                                                                            <i class="bi bi-person-fill"></i>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="doctor-details">
                                                                                        <div class="doctor-name">د.
                                                                                            {{ $appointment->doctor->user->name }}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <span class="specialization-badge">
                                                                                    {{ $appointment->doctor->category->name ?? 'غير محدد' }}
                                                                                </span>
                                                                            </td>
                                                                            <td>
                                                                                <div class="date-info">
                                                                                    <i
                                                                                        class="bi bi-calendar-date fs-5 text-primary"></i>
                                                                                    <div>
                                                                                        <strong
                                                                                            class="d-block appointment-date">{{ $appointment->scheduled_at ? $appointment->scheduled_at->locale('ar')->translatedFormat('l') : 'غير محدد' }}</strong>
                                                                                        <span
                                                                                            class="appointment-date-detail">{{ $appointment->scheduled_at ? $appointment->scheduled_at->locale('ar')->translatedFormat('d F Y') : '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="time-info">
                                                                                    <i class="bi bi-clock fs-5 text-primary"></i>
                                                                                    <div>
                                                                                        <strong
                                                                                            class="appointment-time">{{ $appointment->scheduled_at ? $appointment->scheduled_at->format('h:i') : 'غير محدد' }}</strong>
                                                                                        <span
                                                                                            class="appointment-time-period">{{ $appointment->scheduled_at ? $appointment->scheduled_at->format('A') : '' }}</span>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="status-badge {{ $appointment->status }}">
                                                                                    <i class="bi bi-circle-fill me-2"></i>
                                                                                    {{ $appointment->status_text }}
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div class="action-buttons">
                                                                                    <a href="{{ route('appointments.show', $appointment->id) }}"
                                                                                        class="" data-bs-toggle="tooltip"
                                                                                        title="عرض التفاصيل">
                                                                                        <i class="bi bi-eye-fill"></i>
                                                                                    </a>

                                                                                    @if($appointment->status == 'pending' || $appointment->status == 'confirmed')
                                                                                        <form method="POST"
                                                                                            action="{{ route('appointments.cancel', $appointment->id) }}"
                                                                                            class="d-inline-block cancel-form">
                                                                                            @csrf
                                                                                            @method('PUT')
                                                                                            <button type="submit"
                                                                                                class="action-btn cancel-btn"
                                                                                                data-bs-toggle="tooltip"
                                                                                                title="إلغاء الحجز">
                                                                                                <i class="bi bi-x-circle-fill"></i>
                                                                                            </button>
                                                                                        </form>
                                                                                    @endif

                                                                                    @if($appointment->status == 'completed')
                                                                                        <a type="button" class=" rate-btn"
                                                                                            data-bs-toggle="modal"
                                                                                            data-bs-target="#rateModal{{ $appointment->id }}"
                                                                                            title="تقييم الطبيب">
                                                                                            <i class="bi bi-star-fill"></i>
                                                                                        </a>
                                                                                    @endif

                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                            <!-- Modales de valoración para cada cita completada -->
                                                            @foreach($user->patient->appointments as $appointment)
                                                                @if($appointment->status == 'completed')
                                                                    <!-- Verificar si ya existe una valoración para esta cita -->
                                                                    @php
                                                                        $existingRating = \Modules\Doctors\Entities\DoctorRating::where('doctor_id', $appointment->doctor->id)
                                                                            ->where('patient_id', $appointment->patient_id)
                                                                            ->where('appointment_id', $appointment->id)
                                                                            ->first();
                                                                    @endphp
                                                                    <!-- Modal de valoración -->
                                                                    <div class="modal fade" id="rateModal{{ $appointment->id }}"
                                                                        tabindex="-1" aria-labelledby="rateModalLabel{{ $appointment->id }}"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="rateModalLabel{{ $appointment->id }}">
                                                                                        <i class="bi bi-star-fill text-warning me-2"></i>
                                                                                        @if($existingRating)
                                                                                            عرض التقييم
                                                                                        @else
                                                                                            تقييم الدكتور
                                                                                        @endif
                                                                                    </h5>
                                                                                    <button type="button" class="btn-close"
                                                                                        data-bs-dismiss="modal" aria-label="إغلاق"></button>
                                                                                </div>
                                                                                <form
                                                                                    action="{{ route('doctors.rate', $appointment->doctor->id) }}"
                                                                                    method="POST" @if($existingRating) class="rated-form"
                                                                                    @endif>
                                                                                    @csrf
                                                                                    <input type="hidden" name="appointment_id"
                                                                                        value="{{ $appointment->id }}">
                                                                                    <div class="modal-body">
                                                                                        <!-- Doctor info -->
                                                                                        <div class="doctor-profile mb-3">
                                                                                            <div
                                                                                                class="doctor-info d-flex align-items-center">
                                                                                                <div class="doctor-avatar">
                                                                                                    @if($appointment->doctor->image)
                                                                                                        <img src="{{ Storage::url($appointment->doctor->image) }}"
                                                                                                            alt="{{ $appointment->doctor->name }}" class="img-fluid ">
                                                                                                    @else
                                                                                                        <i class="bi bi-person-badge-fill"></i>
                                                                                                    @endif
                                                                                                </div>
                                                                                                <div class="doctor-details ms-3">
                                                                                                    <h5 class="doctor-name mb-1">د.
                                                                                                        {{ $appointment->doctor->name }}
                                                                                                    </h5>
                                                                                                    <div class="d-flex align-items-center gap-2">
                                                                                                        <div class="specialization-badge">
                                                                                                            {{ $appointment->doctor->category->name ?? 'غير محدد' }}
                                                                                                        </div>
                                                                                                        <div class="rating-mini">
                                                                                                            @for ($i = 1; $i <= 5; $i++)
                                                                                                                @if ($i <= floor($appointment->doctor->rating_avg))
                                                                                                                    <i class="bi bi-star-fill text-warning"></i>
                                                                                                                @elseif ($i - 0.5 <= $appointment->doctor->rating_avg)
                                                                                                                    <i class="bi bi-star-half text-warning"></i>
                                                                                                                @else
                                                                                                                    <i class="bi bi-star text-warning"></i>
                                                                                                                @endif
                                                                                                            @endfor
                                                                                                            <span class="text-muted small">({{ $appointment->doctor->ratings_count }})</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        @if($existingRating)
                                                                                            <!-- Mostrar valoración existente -->
                                                                                            <div class="alert alert-info">
                                                                                                <i class="bi bi-info-circle me-2"></i>
                                                                                                لقد قمت بتقييم الدكتور مسبقًا
                                                                                            </div>
                                                                                        @endif

                                                                                        <!-- Star rating -->
                                                                                        <div class="rating-container">
                                                                                            <h6 class="text-center mb-2">
                                                                                                @if($existingRating)
                                                                                                    تقييمك للدكتور
                                                                                                @else
                                                                                                    كيف كانت تجربتك مع الدكتور؟
                                                                                                @endif
                                                                                            </h6>
                                                                                            <div class="star-rating">
                                                                                                <div class="stars">
                                                                                                    <input type="radio"
                                                                                                        id="star5-{{ $appointment->id }}"
                                                                                                        name="rating" value="5"
                                                                                                        class="visually-hidden"
                                                                                                        @if($existingRating && $existingRating->rating == 5) checked
                                                                                                        @endif @if($existingRating) disabled
                                                                                                        @endif>
                                                                                                    <label
                                                                                                        for="star5-{{ $appointment->id }}"
                                                                                                        class="star-label @if($existingRating && $existingRating->rating >= 5) selected @endif"
                                                                                                        title="ممتاز - 5 نجوم">★</label>

                                                                                                    <input type="radio"
                                                                                                        id="star4-{{ $appointment->id }}"
                                                                                                        name="rating" value="4"
                                                                                                        class="visually-hidden"
                                                                                                        @if($existingRating && $existingRating->rating == 4) checked
                                                                                                        @endif @if($existingRating) disabled
                                                                                                        @endif>
                                                                                                    <label
                                                                                                        for="star4-{{ $appointment->id }}"
                                                                                                        class="star-label @if($existingRating && $existingRating->rating >= 4) selected @endif"
                                                                                                        title="جيد جدا - 4 نجوم">★</label>

                                                                                                    <input type="radio"
                                                                                                        id="star3-{{ $appointment->id }}"
                                                                                                        name="rating" value="3"
                                                                                                        class="visually-hidden"
                                                                                                        @if($existingRating && $existingRating->rating == 3) checked
                                                                                                        @endif @if($existingRating) disabled
                                                                                                        @endif>
                                                                                                    <label
                                                                                                        for="star3-{{ $appointment->id }}"
                                                                                                        class="star-label @if($existingRating && $existingRating->rating >= 3) selected @endif"
                                                                                                        title="جيد - 3 نجوم">★</label>

                                                                                                    <input type="radio"
                                                                                                        id="star2-{{ $appointment->id }}"
                                                                                                        name="rating" value="2"
                                                                                                        class="visually-hidden"
                                                                                                        @if($existingRating && $existingRating->rating == 2) checked
                                                                                                        @endif @if($existingRating) disabled
                                                                                                        @endif>
                                                                                                    <label
                                                                                                        for="star2-{{ $appointment->id }}"
                                                                                                        class="star-label @if($existingRating && $existingRating->rating >= 2) selected @endif"
                                                                                                        title="مقبول - 2 نجوم">★</label>

                                                                                                    <input type="radio"
                                                                                                        id="star1-{{ $appointment->id }}"
                                                                                                        name="rating" value="1"
                                                                                                        class="visually-hidden"
                                                                                                        @if($existingRating && $existingRating->rating == 1) checked
                                                                                                        @endif @if($existingRating) disabled
                                                                                                        @endif>
                                                                                                    <label
                                                                                                        for="star1-{{ $appointment->id }}"
                                                                                                        class="star-label @if($existingRating && $existingRating->rating >= 1) selected @endif"
                                                                                                        title="ضعيف - نجمة واحدة">★</label>
                                                                                                </div>
                                                                                                <div class="rating-value text-center mt-2">
                                                                                                    @if($existingRating)
                                                                                                        {{ $existingRating->rating }} من 5
                                                                                                    @else
                                                                                                        0 من 5
                                                                                                    @endif
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Comment -->
                                                                                        <div class="mb-3 mt-3">
                                                                                            <label for="comment-{{ $appointment->id }}"
                                                                                                class="form-label">تعليقك (اختياري)</label>
                                                                                            <textarea class="form-control"
                                                                                                id="comment-{{ $appointment->id }}"
                                                                                                name="comment" rows="3" @if($existingRating)
                                                                                                readonly @endif
                                                                                                placeholder="اكتب تعليقك هنا...">{{ $existingRating ? $existingRating->comment : '' }}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-secondary"
                                                                                            data-bs-dismiss="modal">
                                                                                            @if($existingRating)
                                                                                                إغلاق
                                                                                            @else
                                                                                                إلغاء
                                                                                            @endif
                                                                                        </button>
                                                                                        @if(!$existingRating)
                                                                                            <button type="submit" class="btn btn-primary">إرسال
                                                                                                التقييم</button>
                                                                                        @endif
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="empty-state">
                                                        <div class="empty-state-icon">
                                                            <i class="bi bi-calendar-x"></i>
                                                        </div>
                                                        <h5>لا توجد حجوزات حالية</h5>
                                                        <p class="text-muted">يمكنك حجز موعد جديد مع أطبائنا</p>
                                                        <a href="{{ route('search') }}" class="btn btn-primary">
                                                            <i class="bi bi-plus-circle me-1"></i> حجز موعد جديد
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- تبويب تغيير كلمة المرور -->
                                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                        <div class="content-section mb-4">
                                            <div class="section-header d-flex align-items-center mb-3 pb-2 border-bottom">
                                                <div class="section-icon yellow">
                                                    <i class="bi bi-lock"></i>
                                                </div>
                                                <h5 class="mb-0 fw-bold">تغيير كلمة المرور</h5>
                                            </div>

                                            <form method="POST" action="{{ route('profile.password.update') }}"
                                                class="profile-form">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="current_password" class="form-label">
                                                        <i class="bi bi-key me-1"></i>كلمة المرور الحالية
                                                    </label>
                                                    <div class="password-input">
                                                        <input type="password"
                                                            class="form-control @error('current_password') is-invalid @enderror"
                                                            id="current_password" name="current_password" required>
                                                        <button type="button" class="password-toggle" tabindex="-1">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </div>
                                                    @error('current_password')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message ?? 'كلمة المرور الحالية غير صحيحة' }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="password" class="form-label">
                                                        <i class="bi bi-lock me-1"></i>كلمة المرور الجديدة
                                                    </label>
                                                    <div class="password-input">
                                                        <input type="password"
                                                            class="form-control @error('password') is-invalid @enderror"
                                                            id="password" name="password" required>
                                                        <button type="button" class="password-toggle" tabindex="-1">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </div>
                                                    @error('password')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message ?? 'كلمة المرور الجديدة غير صالحة' }}
                                                        </div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="password_confirmation" class="form-label">
                                                        <i class="bi bi-lock me-1"></i>تأكيد كلمة المرور الجديدة
                                                    </label>
                                                    <div class="password-input">
                                                        <input type="password" class="form-control"
                                                            id="password_confirmation" name="password_confirmation"
                                                            required>
                                                        <button type="button" class="password-toggle" tabindex="-1">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="form-actions">
                                                    <button type="submit" class="btn btn-secondary">
                                                        <i class="bi bi-check2-circle me-1"></i> تغيير كلمة المرور
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- زر العودة لحجز موعد في حالة وجود redirect_to -->
                @if($user->isPatient() && request()->has('redirect_to'))
                    <div class="text-center mt-3">
                        <a href="{{ request('redirect_to') }}" class="btn btn-secondary">
                            <i class="bi bi-calendar-check me-1"></i> العودة لحجز الحجز
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .profile-page {
                padding: 20px 0;
            }

            .profile-card {
                border-radius: 12px;
                overflow: hidden;
            }

            .profile-sidebar-wrapper {
                border-left: 1px solid rgba(0, 0, 0, .08);
                background-color: #fff;
            }

            .profile-content-wrapper {
                background-color: #fff;
            }

            /* القائمة الجانبية */
            .profile-sidebar {
                padding: 20px;
            }

            .profile-avatar {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background-color: #f2f2f2;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .profile-avatar i {
                font-size: 40px;
                color: #343a40;
            }

            .user-role {
                display: inline-block;
                background-color: #f2f2f2;
                color: #343a40;
                border-radius: 30px;
                padding: 5px 15px;
                font-size: 14px;
            }

            .nav-pills .nav-link {
                color: #495057;
                border-radius: 8px;
                padding: 12px 15px;
                margin-bottom: 5px;
                transition: all 0.3s ease;
                border-right: 3px solid transparent;
                display: flex;
                align-items: center;
            }

            .nav-pills .nav-link:hover {
                background-color: rgba(0, 0, 0, 0.04);
                color: #343a40;
            }

            .nav-pills .nav-link.active {
                background-color: rgba(13, 110, 253, 0.08);
                color: #0d6efd;
                font-weight: 600;
                border-right: 3px solid #0d6efd;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            }

            .appointments-count {
                background-color: #0d6efd;
                color: white;
                font-size: 12px;
                padding: 2px 8px;
                border-radius: 20px;
                margin-right: auto;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 24px;
            }

            .btn-create-appointment {
                padding: 10px;
                border-radius: 8px;
                transition: all 0.3s ease;
            }

            .btn-create-appointment:hover {
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                transform: translateY(-2px);
            }

            /* محتوى الصفحة */
            .content-section {
                background-color: #fff;
                border-radius: 12px;
                margin-bottom: 20px;
            }



            .section-header {
                margin-bottom: 20px;
                position: relative;
            }



            /* معلومات الملف الشخصي */
            .profile-info {
                margin-top: 20px;
            }

            .info-item {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                padding: 12px 15px;
                border-radius: 8px;
                transition: all 0.3s ease;
                background-color: #f9f9f9;
            }



            .info-label {
                font-weight: 600;
                color: #495057;
                min-width: 150px;
                display: flex;
                align-items: center;
            }

            .info-label i {
                margin-right: 10px;
                color: #343a40;
                font-size: 16px;
            }

            .info-value {
                flex: 1;
            }

            .user-role-badge {
                display: inline-flex;
                align-items: center;
                background-color: #f2f2f2;
                color: #343a40;
                padding: 5px 12px;
                border-radius: 30px;
                font-size: 14px;
            }

            .action-buttons a {
                font-size: 18px
            }

            /* نماذج المدخلات */
            .profile-form {
                background-color: #f9f9f9;
                padding: 20px;
                border-radius: 10px;
            }

            .form-label {
                font-weight: 600;
                color: #495057;
                display: flex;
                align-items: center;
                margin-bottom: 10px;
            }

            .form-label i {
                margin-right: 8px;
                color: #343a40;
            }

            .form-control {
                border-radius: 8px;
                padding: 12px;
                padding-inline-start: 36px;
                border: 1px solid #dee2e6;
                background-color: white;
            }

            .form-control:focus {
                border-color: #343a40;
                box-shadow: 0 0 0 0.2rem rgba(52, 58, 64, 0.15);
            }

            .radio-group {
                display: flex;
                gap: 15px;
            }

            .form-check-label {
                cursor: pointer;
            }

            .password-input {
                position: relative;
                display: flex;
                align-items: center;
            }

            .password-input .form-control {
                padding-right: 45px; /* Make space for the toggle button */
            }

            .password-toggle {
                position: absolute;
                top: 50%;
                right: 12px;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #6c757d;
                cursor: pointer;
                z-index: 10;
                padding: 4px;
                border-radius: 4px;
                transition: all 0.2s ease;
            }

            .password-toggle:hover {
                color: #495057;
                background-color: rgba(0, 0, 0, 0.05);
            }

            .password-toggle:focus {
                outline: none;
                box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
            }

            .form-actions {
                margin-top: 20px;
                display: flex;
                justify-content: flex-end;
            }

            /* جدول الحجوزات */


            .appointment-id {
                font-weight: 600;
                color: #343a40;
            }

            /* حالة الحجز */


            /* معلومات الطبيب في الجدول */
            .doctor-info {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .doctor-avatar {
                width: 35px;
                height: 35px;
                border-radius: 50%;
                background-color: #f2f2f2;
                color: #343a40;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* مجموعة الإجراءات */
            .actions-group {
                display: flex;
                gap: 5px;
            }

            .btn-icon {
                width: 32px;
                height: 32px;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #f8f9fa;
                color: #343a40;
                border: 1px solid #dee2e6;
                transition: all 0.3s ease;
            }

            .btn-icon:hover {
                background-color: #f0f0f0;
            }

            .btn-icon.btn-danger {
                background-color: #ffebee;
                color: #dc3545;
                border-color: #ffebee;
            }

            .btn-icon.btn-danger:hover {
                background-color: #ffdde1;
            }

            .btn-icon.btn-warning {
                background-color: #f2f2f2;
                color: #666666;
                border-color: #f2f2f2;
            }

            .btn-icon.btn-warning:hover {
                background-color: #e6e6e6;
            }

            /* حالة عدم وجود حجوزات */
            .empty-state {
                text-align: center;
                padding: 40px 20px;
            }

            .empty-state-icon {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background-color: #f2f2f2;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
            }

            .empty-state-icon i {
                font-size: 40px;
                color: #666666;
            }

            .empty-state h5 {
                margin-bottom: 10px;
                font-weight: 600;
                color: #343a40;
            }

            /* تقييم الطبيب */
            .doctor-profile {
                display: flex;
                align-items: center;
                padding: 15px;
                background-color: #f2f2f2;
                border-radius: 10px;
                margin-bottom: 20px;
            }

            .doctor-profile-avatar {
                width: 50px;
                height: 50px;
                border-radius: 10px;
                background-color: #e6e6e6;
                color: #343a40;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 15px;
            }

            .rating-container {
                margin-bottom: 20px;
            }

            .star-rating {
                text-align: center;
                padding: 15px;
                background-color: #f2f2f2;
                border-radius: 10px;
            }

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
            .rate-btn,
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

            /* Estilos para los formularios de valoración ya realizados */
            .rated-form .stars .star-label {
                cursor: default;
            }

            .rated-form .star-label:hover {
                color: inherit;
                /* Evita el efecto hover en las estrellas */
            }

            .rated-form .star-label.selected {
                color: #ffc107;
                /* Mantiene el color de las estrellas seleccionadas */
            }

            /* Este selector evita que el efecto hover afecte a las demás estrellas cuando el formulario está deshabilitado */
            .rated-form .star-label:hover~.star-label {
                color: #d0d0d0;
            }
        </style>
        <style>
            /* تعديلات لنظام النجوم */
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
                cursor: default !important;
                pointer-events: none !important;
            }

            .rated-form .star-label.selected {
                color: #ffc107 !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // معالجة إظهار وإخفاء كلمة المرور
                document.querySelectorAll('.password-toggle').forEach(function (button) {
                    button.addEventListener('click', function () {
                        const input = this.previousElementSibling;
                        const icon = this.querySelector('i');

                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('bi-eye');
                            icon.classList.add('bi-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('bi-eye-slash');
                            icon.classList.add('bi-eye');
                        }
                    });
                });

                // معالجة نجوم التقييم
                document.querySelectorAll('input[name="rating"]').forEach(function (radio) {
                    radio.addEventListener('change', function () {
                        const ratingValue = this.closest('.star-rating').querySelector('.rating-value');
                        const stars = parseInt(this.value);
                        let message = '';

                        if (stars === 5) message = ' - ممتاز!';
                        else if (stars === 4) message = ' - جيد جداً';
                        else if (stars === 3) message = ' - جيد';
                        else if (stars === 2) message = ' - مقبول';
                        else message = ' - يحتاج إلى تحسين';

                        ratingValue.textContent = stars + ' من 5' + message;
                    });
                });

                // تنشيط التبويب بناءً على الهاش في عنوان URL
                function activateTab(tabId) {
                    const tabLink = document.querySelector(`#${tabId}-tab`);
                    const tabContent = document.querySelector(`#${tabId}`);

                    if (tabLink && tabContent) {
                        // إلغاء تنشيط جميع التبويبات
                        document.querySelectorAll('.nav-link').forEach(link => {
                            link.classList.remove('active');
                        });
                        document.querySelectorAll('.tab-pane').forEach(pane => {
                            pane.classList.remove('show', 'active');
                        });

                        // تنشيط التبويب المطلوب
                        tabLink.classList.add('active');
                        tabContent.classList.add('show', 'active');
                    }
                }

                // التنقل بين التبويبات والاحتفاظ بالتبويب المحدد حتى بعد تحديث الصفحة
                const url = window.location.href;
                if (url.includes('#')) {
                    const tabId = url.substring(url.indexOf('#') + 1);
                    activateTab(tabId);
                }

                // تحديث عنوان URL عند تغيير التبويب
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', function () {
                        const tabId = this.getAttribute('href').substring(1);
                        history.pushState(null, null, `#${tabId}`);
                    });
                });

                // معالجة النقر على رابط حجوزاتي من القائمة العلوية
                const appointmentsLink = document.getElementById('navbar-appointments-link');
                if (appointmentsLink) {
                    appointmentsLink.addEventListener('click', function (event) {
                        // إذا كنا بالفعل في صفحة الملف الشخصي
                        if (window.location.pathname === this.pathname ||
                            window.location.pathname === '/profile') {
                            event.preventDefault();
                            activateTab('appointments');
                            history.pushState(null, null, `#appointments`);
                        }
                    });
                }

                // التعامل مع الهاش في URL عند تحديث الصفحة أو العودة
                window.addEventListener('hashchange', function () {
                    const hash = window.location.hash.substring(1);
                    if (hash) {
                        activateTab(hash);
                    }
                });

                // إضافة تأثيرات انتقالية للعناصر
                document.querySelectorAll('.info-item').forEach(item => {
                    item.addEventListener('mouseenter', function () {
                        this.style.transform = 'translateY(-2px)';
                        this.style.boxShadow = '0 4px 8px rgba(0,0,0,0.1)';
                    });

                    item.addEventListener('mouseleave', function () {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = 'none';
                    });
                });

                // تحسين تأكيد إلغاء الحجوزات
                document.querySelectorAll('form[action*="appointments.cancel"]').forEach(form => {
                    form.addEventListener('submit', function (event) {
                        event.preventDefault();

                        // إنشاء نافذة تأكيد مخصصة بدلاً من استخدام التأكيد الافتراضي
                        if (confirm('هل أنت متأكد من رغبتك في إلغاء هذا الحجز؟ لن تتمكن من التراجع عن هذه العملية.')) {
                            this.submit();
                        }
                    });
                });

                // إغلاق التنبيهات تلقائيًا بعد فترة
                document.querySelectorAll('.alert').forEach(function (alert) {
                    setTimeout(() => {
                        if (alert && typeof bootstrap !== 'undefined') {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        }
                    }, 5000);
                });

                // تحسين عرض نموذج تقييم الطبيب
                document.querySelectorAll('button[data-bs-target^="#rateModal"]').forEach(button => {
                    button.addEventListener('click', function () {
                        const modalId = this.getAttribute('data-bs-target');
                        const stars = document.querySelectorAll(`${modalId} .star-label`);

                        // إضافة تأثير على النجوم
                        stars.forEach((star, index) => {
                            star.addEventListener('mouseenter', function () {
                                // إضافة تأثير للنجوم التي تم التحويم فوقها
                                for (let i = stars.length - 1; i >= index; i--) {
                                    stars[i].classList.add('hover');
                                }
                            });

                            star.addEventListener('mouseleave', function () {
                                // إزالة التأثير
                                stars.forEach(s => s.classList.remove('hover'));
                            });
                        });
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Manejar la selección de estrellas en modales de valoración
                document.querySelectorAll('.modal').forEach(modal => {
                    const appointmentId = modal.id.replace('rateModal', '');
                    const stars = modal.querySelectorAll('.star-label');
                    const ratingValue = modal.querySelector('.rating-value');
                    const radioInputs = modal.querySelectorAll('input[name="rating"]');

                    // Verificar si el formulario ya está clasificado (tiene la clase rated-form)
                    const isRated = modal.querySelector('form.rated-form') !== null;

                    // Si ya está clasificado, no agregamos los eventos de interacción
                    if (isRated) {
                        return;
                    }

                    // Inicializar el valor de la calificación
                    let selectedRating = 0;

                    // Función para actualizar la visualización de las estrellas
                    function updateStarsDisplay(rating) {
                        stars.forEach((star, index) => {
                            // El índice es 0-based, pero las estrellas son 1-5
                            const starValue = 5 - index;

                            if (starValue <= rating) {
                                star.classList.add('selected');
                            } else {
                                star.classList.remove('selected');
                            }
                        });

                        // Actualizar el texto del valor
                        ratingValue.textContent = rating + ' من 5';
                    }

                    // Evento de clic para cada estrella
                    stars.forEach((star, index) => {
                        star.addEventListener('click', function () {
                            // Si el formulario ya está clasificado, no hacemos nada
                            if (isRated) {
                                return;
                            }

                            // Calcular el valor de la estrella (5 para la primera, 1 para la última)
                            const value = 5 - index;
                            selectedRating = value;

                            // Marcar el radio button correspondiente
                            radioInputs.forEach(input => {
                                if (parseInt(input.value) === value) {
                                    input.checked = true;
                                }
                            });

                            // Actualizar la visualización
                            updateStarsDisplay(value);
                        });
                    });

                    // Eventos de hover para las estrellas
                    stars.forEach((star, index) => {
                        const starValue = 5 - index;

                        star.addEventListener('mouseenter', function () {
                            // Si el formulario ya está clasificado, no hacemos nada
                            if (isRated) {
                                return;
                            }
                            updateStarsDisplay(starValue);
                        });

                        star.addEventListener('mouseleave', function () {
                            // Si el formulario ya está clasificado, no hacemos nada
                            if (isRated) {
                                return;
                            }
                            updateStarsDisplay(selectedRating);
                        });
                    });
                });

                // Manejar la confirmación de cancelación de citas
                document.querySelectorAll('.cancel-form').forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        if (confirm('هل أنت متأكد من إلغاء هذا الحجز؟')) {
                            this.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
