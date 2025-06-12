@extends('layouts.app')

@section('content')
<div class="container mt-5 py-5">
    <div class="row">
        <div class="col-12">

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
            @if (session('warning'))
                <div class="alert-card warning mb-4">
                    <div class="alert-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h6 class="alert-heading">تنبيه!</h6>
                        <p class="mb-0">{!! session('warning') !!}</p>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            @endif

            @if (!$doctor->is_profile_completed)
                <div class="alert-card warning mb-4">
                    <div class="alert-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h6 class="alert-heading">الملف الشخصي غير مكتمل</h6>
                        <p class="mb-0">يرجى استكمال بيانات ملفك الشخصي للاستفادة من جميع خدمات المنصة</p>
                    </div>
                    <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="profile-header mb-4">
        <div class="row">
            <div class="col-lg-9">
                <h1 class="profile-title">الملف الشخصي</h1>
                <p class="profile-subtitle">مرحباً {{ $doctor->gender == 'ذكر' ? 'د/' : 'د/ة' }} {{ $doctor->name }}، يمكنك إدارة حسابك وحجوزاتك من هنا</p>
            </div>
            <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                <div class="doctor-status {{ $doctor->status ? 'active' : 'inactive' }}">
                    <i class="bi {{ $doctor->status ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                    <span>{{ $doctor->status ? 'حسابك نشط' : 'حسابك غير نشط' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- بطاقة المعلومات الشخصية -->
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card profile-info-card h-100">
                <div class="card-body text-center">
                    <div class="doctor-avatar mb-3">
                        @if($doctor->image)
                            <img src="{{ $doctor->image_url }}"
                                 onerror="this.src='{{ asset('images/default-doctor.png') }}'"
                                 alt="{{ $doctor->name }}" class="img-fluid rounded doctor-image">
                        @else
                            <div class="avatar-placeholder">
                                <i class="bi bi-person-badge fs-1"></i>
                            </div>
                        @endif
                    </div>

                    <h3 class="doctor-name">
                        @if($doctor->gender =='ذكر')دكتور @else دكتورة@endif {{ $doctor->name }}
                    </h3>

                    <div class="doctor-specialties mb-3">
                        <span class="specialty-badge main">{{ $doctor->title }}</span>
                        <div class="mt-2">
                            @if($doctor->category)
                                <span class="specialty-badge">{{ $doctor->category->name }}</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="doctor-contact-info">
                        <div class="contact-item">
                            <i class="bi bi-envelope"></i>
                            <span>{{ $doctor->email }}</span>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-telephone"></i>
                            <span>{{ $doctor->phone }}</span>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-geo-alt"></i>
                            <span>
                                @if($doctor->governorate && $doctor->city)
                                    {{ $doctor->governorate->name }} - {{ $doctor->city->name }}
                                @elseif($doctor->governorate)
                                    {{ $doctor->governorate->name }}
                                @else
                                    غير محدد
                                @endif
                            </span>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-building"></i>
                            <span>{{ $doctor->address ?? 'غير محدد' }}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="doctor-stats">
                        <div class="stat-item">
                            <div class="stat-value">{{ $stats['today_appointments'] }}</div>
                            <div class="stat-label">حجوزات اليوم</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $stats['upcoming_appointments'] }}</div>
                            <div class="stat-label">حجوزات قادمة</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $doctor->consultation_fee }}</div>
                            <div class="stat-label">سعر الكشف</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">{{ $stats['completed_appointments'] }}</div>
                            <div class="stat-label">الحجوزات المكتملة</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- البطاقات المتعددة -->
        <div class="col-lg-8 col-md-7">
            <div class="profile-tabs mb-4">
                <ul class="nav nav-tabs nav-fill" id="doctorProfileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab" aria-controls="personal" aria-selected="true">
                            <i class="bi bi-person me-2"></i> البيانات الشخصية
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                            <i class="bi bi-shield-lock me-2"></i> تغيير كلمة المرور
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="appointments-tab" data-bs-toggle="tab" data-bs-target="#appointments" type="button" role="tab" aria-controls="appointments" aria-selected="false">
                            <i class="bi bi-calendar-check me-2"></i> الحجوزات
                        </button>
                    </li>
                </ul>

                <div class="tab-content mt-4" id="doctorProfileTabsContent">
                    <!-- بطاقة البيانات الشخصية -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel" aria-labelledby="personal-tab">
                        <div class="card shadow-sm rounded-4 mb-4">
                            <div class="card-header border-bottom py-3">
                                <h5 class="card-title m-0">
                                    <i class="bi bi-person-vcard me-2"></i>
                                    تعديل البيانات الشخصية
                                </h5>
                            </div>
                            <div class="card-body px-4 py-3">
                                <form action="{{ route('doctors.profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')


                                    <!-- المعلومات الأساسية -->
                                    <div class="section-divider mb-4">
                                        <h6 class="section-title text-primary fw-bold">المعلومات الأساسية</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="name">الاسم *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                                name="name" value="{{ old('name', $doctor->name) }}" placeholder="اسم الطبيب"  />
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="email">البريد الإلكتروني *</label>
                                            <input type="email" style="direction: rtl;"
                                                class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                                value="{{ old('email', $doctor->email) }}" placeholder="البريد الإلكتروني"  />
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="phone">رقم الهاتف *</label>
                                            <div class="input-group @error('phone') is-invalid @enderror">
                                                <span class="input-group-text">+20</span>
                                                <input type="text" class="form-control" id="phone"
                                                    name="phone" value="{{ old('phone', $doctor->phone) }}" placeholder="ادخل رقم الهاتف"  />
                                            </div>
                                            @error('phone')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-group">
                                                <label for="image" class="form-label">الصورة الشخصية</label>
                                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                @if($doctor->image)
                                                    <div class="current-image mt-2 d-flex align-items-center">
                                                        <img src="{{ $doctor->image_url }}" alt="الصورة الحالية" class="img-thumbnail" style="height: 60px;">
                                                        <span class="text-muted me-2 fs-6">الصورة الحالية</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- المعلومات المهنية -->
                                    <div class="section-divider mb-4 mt-4">
                                        <h6 class="section-title text-primary fw-bold">المعلومات المهنية</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="title">المسمى الوظيفي *</label>
                                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                                id="title" name="title" value="{{ old('title', $doctor->title) }}"
                                                placeholder="مثال: استشاري، أخصائي، طبيب" >
                                            <small class="text-muted">المسمى الوظيفي الرسمي للطبيب مثل (استشاري - أخصائي - طبيب)</small>
                                            @error('title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="category_id" class="form-label">التخصص *</label>
                                            <select name="category_id" id="category_id" class="form-select">
                                                <option value="">اختر التخصص</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $doctor->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">المجالات الطبية العامة مثل (طب أطفال - جراحة - باطنة)</small>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>



                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="experience_years">سنوات الخبرة *</label>
                                            <input type="number" style="direction: rtl"
                                                class="form-control @error('experience_years') is-invalid @enderror"
                                                id="experience_years" name="experience_years" value="{{ old('experience_years', $doctor->experience_years) }}"
                                                placeholder="عدد سنوات الخبرة" min="0"  />
                                            @error('experience_years')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label" for="description">نبذة عن الطبيب</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                id="description" name="description" rows="4"
                                                placeholder="اكتب نبذة تعريفية عن خبراتك ومؤهلاتك">{{ old('description', $doctor->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- معلومات الكشف والحجوزات -->
                                    <div class="section-divider mb-4 mt-4">
                                        <h6 class="section-title text-primary fw-bold">معلومات الكشف والحجوزات</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="consultation_fee">سعر الكشف (جنيه) *</label>
                                            <div class="input-group @error('consultation_fee') is-invalid @enderror">
                                                <input type="number" style="direction: rtl"
                                                    class="form-control"
                                                    id="consultation_fee" name="consultation_fee" value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                                                    placeholder="سعر الكشف" min="0"  />
                                                <span class="input-group-text">جنيه</span>
                                            </div>
                                            @error('consultation_fee')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="waiting_time">مدة الانتظار (بالدقائق) *</label>
                                            <div class="input-group @error('waiting_time') is-invalid @enderror">
                                                <input type="number" class="form-control"
                                                    name="waiting_time" id="waiting_time"
                                                    value="{{ old('waiting_time', $doctor->waiting_time) }}"
                                                    min="0" />
                                                <span class="input-group-text">دقيقة</span>
                                            </div>
                                            @error('waiting_time')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>اليوم</th>
                                                            <th>متاح</th>
                                                            <th>من الساعة</th>
                                                            <th>إلى الساعة</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach(['sunday' => 'الأحد', 'monday' => 'الإثنين', 'tuesday' => 'الثلاثاء', 'wednesday' => 'الأربعاء', 'thursday' => 'الخميس', 'friday' => 'الجمعة', 'saturday' => 'السبت'] as $dayKey => $dayName)
                                                            @php
                                                                $schedule = $doctor->schedules->where('day', $dayKey)->first();
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $dayName }}</td>
                                                                <td>
                                                                    <div class="form-check">
                                                                        <input class="form-check-input schedule-availability" type="checkbox"
                                                                            name="schedules[{{ $loop->index }}][is_available]"
                                                                            id="day_{{ $loop->index }}_available"
                                                                            value="1"
                                                                            {{ $schedule && $schedule->is_active ? 'checked' : '' }}>
                                                                        <input type="hidden" name="schedules[{{ $loop->index }}][day]" value="{{ $dayKey }}">
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <input type="time" class="form-control schedule-time @error("schedules.{$loop->index}.start_time") is-invalid @enderror"
                                                                        name="schedules[{{ $loop->index }}][start_time]"
                                                                        id="day_{{ $loop->index }}_start"
                                                                        value="{{ old("schedules.{$loop->index}.start_time", $schedule ? $schedule->start_time->format('H:i') : '') }}"
                                                                        {{ $schedule && $schedule->is_active ? '' : 'disabled' }}>
                                                                    @error("schedules.{$loop->index}.start_time")
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </td>
                                                                <td>
                                                                    <input type="time" class="form-control schedule-time @error("schedules.{$loop->index}.end_time") is-invalid @enderror"
                                                                        name="schedules[{{ $loop->index }}][end_time]"
                                                                        id="day_{{ $loop->index }}_end"
                                                                        value="{{ old("schedules.{$loop->index}.end_time", $schedule ? $schedule->end_time->format('H:i') : '') }}"
                                                                        {{ $schedule && $schedule->is_active ? '' : 'disabled' }}>
                                                                    @error("schedules.{$loop->index}.end_time")
                                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                                    @enderror
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- معلومات العنوان -->
                                    <div class="section-divider mb-4 mt-4">
                                        <h6 class="section-title text-primary fw-bold">معلومات العنوان</h6>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="governorate_id" class="form-label">المحافظة *</label>
                                            <select name="governorate_id" id="governorate_id" class="form-select" >
                                                <option value="">اختر المحافظة</option>
                                                @foreach($governorates as $governorate)
                                                    <option value="{{ $governorate->id }}"
                                                        {{ old('governorate_id', $doctor->governorate_id) == $governorate->id ? 'selected' : '' }}>
                                                        {{ $governorate->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('governorate_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="city_id">المدينة *</label>
                                            <select class="form-select @error('city_id') is-invalid @enderror" name="city_id" id="city_id" >
                                                <option value="">اختر المدينة</option>
                                                @if($doctor->governorate)
                                                    @foreach($doctor->governorate->cities as $city)
                                                        <option value="{{ $city->id }}" {{ old('city_id', $doctor->city_id) == $city->id ? 'selected' : '' }}>
                                                            {{ $city->name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('city_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 mb-3">
                                            <label class="form-label" for="address">عنوان العيادة *</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror"
                                                id="address" name="address" rows="2"
                                                placeholder="العنوان التفصيلي للعيادة" >{{ old('address', $doctor->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-4 d-flex justify-content-center">
                                        <button type="submit" class="btn btn-primary px-5">
                                            <i class="bi bi-check-circle me-2"></i> حفظ التغييرات
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- قسم تغيير كلمة المرور -->
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title m-0">
                                    <i class="bi bi-shield-lock me-2"></i>
                                    تغيير كلمة المرور
                                </h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('doctors.password.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" >
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" >
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" >
                                    </div>

                                    <div class="password-requirements mb-4">
                                        <h6><i class="bi bi-info-circle me-2"></i> متطلبات كلمة المرور</h6>
                                        <ul>
                                            <li>8 أحرف على الأقل</li>
                                            <li>يفضل استخدام مزيج من الحروف والأرقام والرموز</li>
                                            <li>تجنب استخدام كلمات مرور استخدمتها سابقا</li>
                                        </ul>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-check-circle me-2"></i> تغيير كلمة المرور
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- قسم الحجوزات -->
                    <div class="tab-pane fade" id="appointments" role="tabpanel" aria-labelledby="appointments-tab">
                        <div class="appointment-stats-row mb-4">
                            <div class="stat-card today">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-day"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $stats['today_appointments'] }}</h3>
                                    <p class="stat-title">حجوزات اليوم</p>
                                </div>
                            </div>
                            <div class="stat-card upcoming">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-plus"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $stats['upcoming_appointments'] }}</h3>
                                    <p class="stat-title">حجوزات قادمة</p>
                                </div>
                            </div>
                            <div class="stat-card completed">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $stats['completed_appointments'] }}</h3>
                                    <p class="stat-title">الحجوزات المكتملة</p>
                                </div>
                            </div>
                            <div class="stat-card cancelled">
                                <div class="stat-icon">
                                    <i class="bi bi-calendar-x"></i>
                                </div>
                                <div class="stat-content">
                                    <h3 class="stat-number">{{ $stats['cancelled_appointments'] ?? 0 }}</h3>
                                    <p class="stat-title">الحجوزات الملغاة</p>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h5 class="card-title">
                                            <i class="bi bi-calendar-week me-2"></i>
                                            حجوزات اليوم وغداً
                                        </h5>
                                        <p class="card-subtitle text-muted small mb-0">آخر تحديث {{ now()->format('h:i A') }}</p>
                                    </div>
                                    <a href="{{ route('doctors.appointments') }}" class="btn btn-primary">
                                        <i class="bi bi-calendar2-week me-1"></i>
                                        عرض كل الحجوزات
                                    </a>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                @if($doctor->appointments->isNotEmpty())
                                    <div class="appointment-timeline">
                                        <div class="timeline-header">
                                            <div class="header-cell"><strong>المريض</strong></div>
                                            <div class="header-cell"><strong>التاريخ والوقت</strong></div>
                                            <div class="header-cell"><strong>الحالة</strong></div>
                                            <div class="header-cell"><strong>الإجراءات</strong></div>
                                        </div>

                                        @foreach($doctor->appointments->take(7) as $appointment)
                                            <div class="timeline-item {{ $appointment->status }}">
                                                <div class="appointment-cell patient-info">
                                                    <div class="avatar">{{ mb_substr($appointment->patient->name, 0, 2, 'UTF-8') }}</div>
                                                    <div class="patient-details">
                                                        <h6 class="patient-name">{{ $appointment->patient->name }}</h6>
                                                        <div class="patient-contact">
                                                            <i class="bi bi-telephone"></i>
                                                            <span>{{ $appointment->patient->user->phone_number }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="appointment-cell time-info">
                                                    <div class="date">
                                                        <i class="bi bi-calendar3"></i>
                                                        @php
                                                            $appointmentDate = \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d');
                                                            $today = \Carbon\Carbon::now()->format('Y-m-d');
                                                            $tomorrow = \Carbon\Carbon::tomorrow()->format('Y-m-d');

                                                            if ($appointmentDate == $today) {
                                                                echo '<span class="date-badge today"><i class="bi bi-calendar-check me-1"></i>اليوم</span>';
                                                            } elseif ($appointmentDate == $tomorrow) {
                                                                echo '<span class="date-badge tomorrow"><i class="bi bi-calendar-plus me-1"></i>غداً</span>';
                                                            } else {
                                                                echo '<span class="date-badge"><i class="bi bi-calendar-date me-1"></i>' . \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d') . '</span>';
                                                            }
                                                        @endphp
                                                    </div>
                                                    <div class="time">
                                                        <i class="bi bi-clock"></i>
                                                        {{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}
                                                        <span class="duration">({{ $doctor->waiting_time ?? 30 }} دقيقة)</span>
                                                    </div>
                                                </div>

                                                <div class="appointment-cell status-info">
                                                    <div class="appointment-status {{ $appointment->status }}">
                                                        @if($appointment->status == 'scheduled')
                                                            <i class="bi bi-calendar-event"></i> حجز مجدول
                                                        @elseif($appointment->status == 'completed')
                                                            <i class="bi bi-check-circle"></i> حجز مكتمل
                                                        @elseif($appointment->status == 'cancelled')
                                                            <i class="bi bi-x-circle"></i> حجز ملغي
                                                        @endif
                                                    </div>
                                                    <div class="payment-status">
                                                        @if($appointment->is_paid)
                                                            <span class="paid"><i class="bi bi-check-circle-fill"></i> تم الدفع </span>
                                                        @else
                                                            <span class="unpaid"><i class="bi bi-exclamation-circle"></i> لم يتم الدفع</span>
                                                        @endif
                                                        <span class="fee">{{ $appointment->fees }} ج.م</span>
                                                    </div>
                                                </div>

                                                <div class="appointment-cell actions">
                                                    @if($appointment->status == 'scheduled')
                                                        <form action="{{ route('doctors.appointments.complete', $appointment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="completed">
                                                            <button type="submit" class="btn btn-sm btn-outline-success action-btn" title="تم اكتمال الزيارة">
                                                                <i class="bi bi-check-circle-fill"></i>
                                                                {{-- <span class="action-text">اكتمال</span> --}}
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('doctors.appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="status" value="cancelled">
                                                            <button type="submit" class="btn btn-sm btn-outline-danger action-btn" title="إلغاء الزيارة">
                                                                <i class="bi bi-x-circle-fill"></i>
                                                                {{-- <span class="action-text">إلغاء</span> --}}
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <button type="button" class="btn btn-sm btn-outline-primary action-btn" data-bs-toggle="modal" data-bs-target="#appointmentDetailsModal{{ $appointment->id }}" title="عرض التفاصيل">
                                                        <i class="bi bi-eye-fill"></i>
                                                        {{-- <span class="action-text">التفاصيل</span> --}}
                                                    </button>

                                                    <!-- Modal for Appointment Details -->
                                                    <div class="modal fade" id="appointmentDetailsModal{{ $appointment->id }}" tabindex="-1" aria-labelledby="appointmentDetailsModalLabel{{ $appointment->id }}" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header appointment-modal-header {{ $appointment->status }}">
                                                                    <div>
                                                                        <h5 class="modal-title" id="appointmentDetailsModalLabel{{ $appointment->id }}">
                                                                            <i class="bi bi-calendar-check me-2"></i>
                                                                            تفاصيل الحجز
                                                                            <span class="appointment-id">#{{ $appointment->id }}</span>
                                                                        </h5>
                                                                        <p class="modal-subtitle mb-0">
                                                                            @if($appointment->status == 'scheduled')
                                                                                <i class="bi bi-calendar-event"></i> حجز مجدول
                                                                            @elseif($appointment->status == 'completed')
                                                                                <i class="bi bi-check-circle"></i> حجز مكتمل
                                                                            @elseif($appointment->status == 'cancelled')
                                                                                <i class="bi bi-x-circle"></i> حجز ملغي
                                                                            @endif
                                                                        </p>
                                                                    </div>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>

                                                                <div class="modal-body">
                                                                    <!-- نظرة عامة سريعة -->
                                                                    <div class="appointment-overview">
                                                                        <div class="overview-item">
                                                                            <div class="overview-icon">
                                                                                <i class="bi bi-calendar3"></i>
                                                                            </div>
                                                                            <div class="overview-content">
                                                                                <span class="overview-label">التاريخ</span>
                                                                                <span class="overview-value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('Y-m-d') }}</span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="overview-item">
                                                                            <div class="overview-icon">
                                                                                <i class="bi bi-clock"></i>
                                                                            </div>
                                                                            <div class="overview-content">
                                                                                <span class="overview-label">الوقت</span>
                                                                                <span class="overview-value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') }}</span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="overview-item">
                                                                            <div class="overview-icon">
                                                                                <i class="bi bi-cash-coin"></i>
                                                                            </div>
                                                                            <div class="overview-content">
                                                                                <span class="overview-label">رسوم الكشف</span>
                                                                                <span class="overview-value">{{ $appointment->fees }} جنيه</span>
                                                                            </div>
                                                                        </div>

                                                                        <div class="overview-item">
                                                                            <div class="overview-icon">
                                                                                <i class="bi bi-credit-card"></i>
                                                                            </div>
                                                                            <div class="overview-content">
                                                                                <span class="overview-label">حالة الدفع</span>
                                                                                <span class="overview-value">
                                                                                    @if($appointment->is_paid)
                                                                                        <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> تم الدفع</span>
                                                                                    @else
                                                                                        <span class="text-warning"><i class="bi bi-exclamation-circle me-1"></i> لم يتم الدفع</span>
                                                                                    @endif
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mt-4">
                                                                        <!-- معلومات المريض -->
                                                                        <div class="col-md-5">
                                                                            <div class="patient-profile-card">
                                                                                <div class="patient-avatar">
                                                                                    <span>{{ mb_substr($appointment->patient->name, 0, 2, 'UTF-8') }}</span>
                                                                                </div>
                                                                                <h5 class="patient-fullname">{{ $appointment->patient->name }}</h5>
                                                                                <p class="patient-id text-muted">رقم المريض: #{{ $appointment->patient->id }}</p>

                                                                                <div class="patient-contact-details">
                                                                                    <div class="contact-detail">
                                                                                        <i class="bi bi-telephone"></i>
                                                                                        <span>{{ $appointment->patient->user->phone_number }}</span>
                                                                                    </div>
                                                                                    <div class="contact-detail">
                                                                                        <i class="bi bi-envelope"></i>
                                                                                        <span>{{ $appointment->patient->user->email }}</span>
                                                                                    </div>
                                                                                    @if($appointment->patient->address)
                                                                                    <div class="contact-detail">
                                                                                        <i class="bi bi-geo-alt"></i>
                                                                                        <span>{{ $appointment->patient->address }}</span>
                                                                                    </div>
                                                                                    @endif
                                                                                </div>

                                                                                <div class="patient-stats">
                                                                                    <div class="stat">
                                                                                        <span class="stat-value">{{ $appointment->patient->appointments->count() }}</span>
                                                                                        <span class="stat-label">إجمالي الزيارات</span>
                                                                                    </div>
                                                                                    <div class="stat">
                                                                                        <span class="stat-value">{{ $appointment->patient->appointments->where('status', 'completed')->count() }}</span>
                                                                                        <span class="stat-label">زيارات مكتملة</span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <!-- معلومات الحجز -->
                                                                        <div class="col-md-7">
                                                                            <div class="appointment-details-tabs">
                                                                                <ul class="nav nav-tabs nav-fill mb-3" id="appointmentDetailTabs{{ $appointment->id }}" role="tablist">
                                                                                    <li class="nav-item" role="presentation">
                                                                                        <button class="nav-link active" id="appointment-info-tab{{ $appointment->id }}" data-bs-toggle="tab" data-bs-target="#appointment-info{{ $appointment->id }}" type="button" role="tab" aria-controls="appointment-info" aria-selected="true">
                                                                                            <i class="bi bi-info-circle me-1"></i> معلومات الحجز
                                                                                        </button>
                                                                                    </li>
                                                                                    <li class="nav-item" role="presentation">
                                                                                        <button class="nav-link" id="appointment-notes-tab{{ $appointment->id }}" data-bs-toggle="tab" data-bs-target="#appointment-notes{{ $appointment->id }}" type="button" role="tab" aria-controls="appointment-notes" aria-selected="false">
                                                                                            <i class="bi bi-journal-text me-1"></i> الملاحظات
                                                                                        </button>
                                                                                    </li>
                                                                                </ul>

                                                                                <div class="tab-content" id="appointmentDetailTabsContent{{ $appointment->id }}">
                                                                                    <div class="tab-pane fade show active" id="appointment-info{{ $appointment->id }}" role="tabpanel" aria-labelledby="appointment-info-tab{{ $appointment->id }}">
                                                                                        <div class="appointment-timeline-details">
                                                                                            <div class="timeline-event">
                                                                                                <div class="event-icon"><i class="bi bi-plus-circle"></i></div>
                                                                                                <div class="event-content">
                                                                                                    <h6>تم إنشاء الحجز</h6>
                                                                                                    <p>{{ \Carbon\Carbon::parse($appointment->created_at)->format('Y-m-d h:i A') }}</p>
                                                                                                </div>
                                                                                            </div>

                                                                                            @if($appointment->status == 'completed')
                                                                                            <div class="timeline-event">
                                                                                                <div class="event-icon success"><i class="bi bi-check-circle"></i></div>
                                                                                                <div class="event-content">
                                                                                                    <h6>تم اكتمال الحجز</h6>
                                                                                                    <p>{{ \Carbon\Carbon::parse($appointment->updated_at)->format('Y-m-d h:i A') }}</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            @elseif($appointment->status == 'cancelled')
                                                                                            <div class="timeline-event">
                                                                                                <div class="event-icon danger"><i class="bi bi-x-circle"></i></div>
                                                                                                <div class="event-content">
                                                                                                    <h6>تم إلغاء الحجز</h6>
                                                                                                    <p>{{ \Carbon\Carbon::parse($appointment->updated_at)->format('Y-m-d h:i A') }}</p>
                                                                                                </div>
                                                                                            </div>
                                                                                            @endif

                                                                                            @php
                                                                                                $remainingTime = null;
                                                                                                $now = \Carbon\Carbon::now();
                                                                                                $appointmentTime = \Carbon\Carbon::parse($appointment->scheduled_at);

                                                                                                if ($now < $appointmentTime && $appointment->status == 'scheduled') {
                                                                                                    $remainingTime = $now->diffForHumans($appointmentTime);
                                                                                                }
                                                                                            @endphp

                                                                                            @if($remainingTime && $appointment->status == 'scheduled')
                                                                                            <div class="appointment-countdown">
                                                                                                <i class="bi bi-hourglass-split"></i>
                                                                                                <span>متبقي على الحجز: {{ $remainingTime }}</span>
                                                                                            </div>
                                                                                            @endif
                                                                                        </div>

                                                                                        @if($appointment->patient->medical_history)
                                                                                        <div class="medical-history mt-3">
                                                                                            <h6><i class="bi bi-clipboard2-pulse me-2"></i>السجل الطبي</h6>
                                                                                            <p>{{ $appointment->patient->medical_history }}</p>
                                                                                        </div>
                                                                                        @endif
                                                                                    </div>

                                                                                    <div class="tab-pane fade" id="appointment-notes{{ $appointment->id }}" role="tabpanel" aria-labelledby="appointment-notes-tab{{ $appointment->id }}">
                                                                                        @if($appointment->notes)
                                                                                            <div class="notes-container">
                                                                                                <h6 class="notes-title"><i class="bi bi-journal-text me-2"></i>ملاحظات الحجز</h6>
                                                                                                <div class="appointment-notes">
                                                                                                    <p>{{ $appointment->notes }}</p>
                                                                                                </div>
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="empty-notes text-center py-4">
                                                                                                <i class="bi bi-journal"></i>
                                                                                                <p>لا توجد ملاحظات مسجلة لهذا الحجز</p>
                                                                                            </div>
                                                                                        @endif

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="modal-footer appointment-modal-footer">
                                                                    @if($appointment->status == 'scheduled')
                                                                        <div class="appointment-actions">
                                                                            <form action="{{ route('doctors.appointments.complete', $appointment) }}" method="POST" class="d-inline">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <input type="hidden" name="status" value="completed">
                                                                                <button type="submit" class="btn btn-success">
                                                                                    <i class="bi bi-check-circle me-1"></i>
                                                                                    تم اكتمال الزيارة
                                                                                </button>
                                                                            </form>

                                                                            <form action="{{ route('doctors.appointments.cancel', $appointment) }}" method="POST" class="d-inline ms-2">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <input type="hidden" name="status" value="cancelled">
                                                                                <button type="submit" class="btn btn-danger">
                                                                                    <i class="bi bi-x-circle me-1"></i>
                                                                                    إلغاء الزيارة
                                                                                </button>
                                                                            </form>
                                                                        </div>
                                                                    @elseif($appointment->status == 'completed')
                                                                        <div class="completed-badge">
                                                                            <i class="bi bi-patch-check"></i>
                                                                            تم اكتمال هذا الحجز بنجاح
                                                                        </div>
                                                                    @elseif($appointment->status == 'cancelled')
                                                                        <div class="cancelled-badge">
                                                                            <i class="bi bi-x-octagon"></i>
                                                                            تم إلغاء هذا الحجز
                                                                        </div>
                                                                    @endif

                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state text-center py-5">
                                        <i class="bi bi-calendar-x empty-icon"></i>
                                        <h5 class="mt-3">لا توجد حجوزات</h5>
                                        <p class="text-muted">لا توجد حجوزات مسجلة في الوقت الحالي</p>
                                    </div>
                                @endif
                            </div>
                            @if($doctor->appointments->count() > 7)
                                <div class="card-footer text-center">
                                    <a href="{{ route('doctors.appointments') }}" class="btn btn-link">عرض جميع الحجوزات &leftarrow;</a>
                                </div>
                            @endif
                        </div>

                        <!-- جدول الحجوزات -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title m-0">
                                    <i class="bi bi-calendar-week me-2"></i>
                                    جدول الحجوزات الأسبوعي
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($doctor->schedules->isNotEmpty())
                                    <div class="schedule-grid">
                                        @foreach($doctor->schedules as $schedule)
                                            <div class="schedule-day {{ $schedule->is_active ? 'available' : 'unavailable' }}">
                                                <div class="day-header">
                                                    <span class="day-name">{{ $schedule->day_name }}</span>
                                                    <span class="availability-badge">
                                                        @if($schedule->is_active)
                                                            <i class="bi bi-check-circle-fill text-success"></i>
                                                            متاح
                                                        @else
                                                            <i class="bi bi-x-circle-fill text-danger"></i>
                                                            غير متاح
                                                        @endif
                                                    </span>
                                                </div>
                                                @if($schedule->is_active)
                                                    <div class="time-slots">
                                                        <div class="time-slot">
                                                            <i class="bi bi-clock"></i>
                                                            <span>{{ date('h:i A', strtotime($schedule->start_time)) }}</span>
                                                            <i class="bi bi-arrow-left"></i>
                                                            <span>{{ date('h:i A', strtotime($schedule->end_time)) }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="empty-state text-center py-5">
                                        <i class="bi bi-calendar2-x empty-icon"></i>
                                        <h5 class="mt-3">لا يوجد جدول مواعيد</h5>
                                        <p class="text-muted">يرجى التواصل مع المسؤول لضبط جدول الحجوزات</p>
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
@endsection

@push('styles')
<style>
    /* ألوان وثيم عام */
    :root {
        --primary-color: #0d6efd;
        --secondary-color: #6c757d;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
        --light-bg: #f8f9fa;
    }

    /* تنسيق الصفحة الرئيسية */
    .profile-header {
        margin-bottom: 2rem;
    }

    .profile-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .profile-subtitle {
        color: var(--secondary-color);
    }

    .doctor-status {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-weight: 600;
    }

    .doctor-status.active {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .doctor-status.inactive {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
    }

    .doctor-status i {
        margin-left: 0.5rem;
    }

    /* بطاقة المعلومات الشخصية */
    .profile-info-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
    }

    .doctor-avatar {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        overflow: hidden;
    }

    .doctor-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .avatar-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0.2) 100%);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .doctor-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .specialty-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.875rem;
        margin: 0.25rem;
        background-color: rgba(13, 110, 253, 0.1);
        color: var(--primary-color);
    }

    .specialty-badge.main {
        background-color: var(--primary-color);
        color: white;
    }

    /* معلومات الاتصال */
    .doctor-contact-info {
        text-align: right;
    }

    .contact-item {
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
    }

    .contact-item i {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: rgba(13, 110, 253, 0.1);
        color: var(--primary-color);
        margin-left: 0.75rem;
    }

    /* إحصائيات الطبيب */
    .doctor-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
        border-radius: 10px;
        background-color: var(--light-bg);
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .stat-label {
        font-size: 0.875rem;
        color: var(--secondary-color);
        margin-top: 0.25rem;
    }

    /* علامات التبويب */
    .nav-tabs {
        border-radius: 10px;
        background-color: var(--light-bg);
        padding: 0.5rem;
        border: none;
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin: 0 0.25rem;
    }

    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
    }

    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        background-color: white;
        box-shadow: 0 2px 8px rgba(13, 110, 253, 0.15);
    }

    /* بطاقات المعلومات */
    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.25rem 1.5rem;
    }

    .card-title {
        color: var(--primary-color);
        font-weight: 600;
    }

    /* قسم متطلبات كلمة المرور */
    .password-requirements {
        background-color: var(--light-bg);
        padding: 1rem;
        border-radius: 10px;
        border-right: 3px solid var(--primary-color);
    }

    .password-requirements h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .password-requirements ul {
        padding-right: 1.25rem;
        margin-bottom: 0;
    }

    .password-requirements li {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        color: var(--secondary-color);
    }

    /* صف إحصائيات الحجوزات */
    .appointment-stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }

    .appointment-stats-row .stat-card {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-radius: 12px;
        background-color: white;
        transition: transform 0.3s ease;
        box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        position: relative;
        overflow: hidden;
    }

    .appointment-stats-row .stat-card:hover {
        transform: translateY(-3px);
    }

    .appointment-stats-row .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        transition: all 0.3s ease;
    }

    .appointment-stats-row .stat-card.today::before { background-color: var(--primary-color); }
    .appointment-stats-row .stat-card.upcoming::before { background-color: var(--info-color); }
    .appointment-stats-row .stat-card.completed::before { background-color: var(--success-color); }
    .appointment-stats-row .stat-card.cancelled::before { background-color: var(--danger-color); }

    .appointment-stats-row .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-left: 1rem;
    }

    .appointment-stats-row .stat-card.today .stat-icon {
        background-color: rgba(13, 110, 253, 0.1);
        color: var(--primary-color);
    }

    .appointment-stats-row .stat-card.upcoming .stat-icon {
        background-color: rgba(23, 162, 184, 0.1);
        color: var(--info-color);
    }

    .appointment-stats-row .stat-card.completed .stat-icon {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .appointment-stats-row .stat-card.cancelled .stat-icon {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--danger-color);
    }

    .appointment-stats-row .stat-content {
        flex-grow: 1;
    }

    .appointment-stats-row .stat-number {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.1;
    }

    .appointment-stats-row .today .stat-number { color: var(--primary-color); }
    .appointment-stats-row .upcoming .stat-number { color: var(--info-color); }
    .appointment-stats-row .completed .stat-number { color: var(--success-color); }
    .appointment-stats-row .cancelled .stat-number { color: var(--danger-color); }

    .appointment-stats-row .stat-title {
        font-size: 0.875rem;
        margin: 0;
        color: var(--secondary-color);
    }

    /* مخطط الحجوزات الزمني */
    .appointment-timeline {
        position: relative;
    }

    .timeline-header {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1.5fr 1fr;
        gap: 1rem;
        padding: 0.75rem 1.5rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
    }

    .header-cell {
        font-size: 0.9rem;
        color: #555;
    }

    .timeline-item {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1.5fr 1fr;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        transition: all 0.3s ease;
        position: relative;
    }

    .timeline-item:hover {
        background-color: #f8f9fc;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 4px;
    }

    .timeline-item.scheduled::before {
        background-color: var(--primary-color);
    }

    .timeline-item.completed::before {
        background-color: var(--success-color);
    }

    .timeline-item.cancelled::before {
        background-color: var(--danger-color);
    }

    .appointment-cell {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* معلومات المريض */
    .patient-info {
        display: flex;
        align-items: center;
    }

    .patient-info .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
        flex-shrink: 0;
        margin-bottom: 8px;
    }

    .patient-details {
        overflow: hidden;
    }

    .patient-name {
        margin: 0 0 0.25rem;
        font-size: 1rem;
        font-weight: 600;
    }

    .patient-contact {
        font-size: 0.8rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
 .patient-contact .bi.bi-telephone{
    transform: rotate(270deg);
 }
    /* معلومات الوقت */
    .time-info .date, .time-info .time {
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.3rem;
    }

    .time-info i {
        color: #777;
        font-size: 0.8rem;
    }

    .time-info .duration {
        color: #888;
        font-size: 0.8rem;
    }

    /* معلومات الحالة */
    .status-info {
        justify-content: center;
    }

    .appointment-status {
        font-size: 0.9rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .appointment-status.scheduled {
        color: var(--primary-color);
    }

    .appointment-status.completed {
        color: var(--success-color);
    }

    .appointment-status.cancelled {
        color: var(--danger-color);
    }

    .payment-status {
        font-size: 0.8rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.3rem 0.8rem;
        align-items: center;
    }

    .payment-status .paid {
        color: var(--success-color);
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .payment-status .unpaid {
        color: #f59f00;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .payment-status .fee {
        background-color: rgba(108, 117, 125, 0.1);
        padding: 0.15rem 0.5rem;
        border-radius: 30px;
        font-weight: 600;
        color: #495057;
    }

    /* الإجراءات */
    .actions {
        flex-direction: row !important;
        justify-content: flex-end !important;
        align-items: center;
        gap: 0.5rem;
    }

    .action-btn {
        border-radius: 8px;
        padding: 0.3rem 0.3rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: inherit;
        transition: all 0.2s ease;
        font-size: 0.8rem;
        min-width: 32px;
    }

    .action-btn i {
        font-size: 0.9rem;
    }

    .action-text {
        display: none;
        margin-right: 0.35rem;
    }

    .action-btn:hover .action-text {
        display: inline-block;
    }

    /* أنماط نافذة تفاصيل الحجز المنبثقة */
    .appointment-modal-header {
        background-color: #f8f9fa;
        position: relative;
        padding: 16px;
        border-bottom: none;
    }

    .appointment-modal-header.scheduled {
        border-bottom: 5px solid var(--primary-color);
        background-color: rgba(13, 110, 253, 0.05);
    }

    .appointment-modal-header.completed {
        border-bottom: 5px solid var(--success-color);
        background-color: rgba(40, 167, 69, 0.05);
    }

    .appointment-modal-header.cancelled {
        border-bottom: 5px solid var(--danger-color);
        background-color: rgba(220, 53, 69, 0.05);
    }

    .modal-title {
        font-weight: 700;
        color: #333;
        display: flex;
        align-items: center;
    }

    .appointment-id {
        font-size: 0.9rem;
        margin-right: 0.5rem;
        color: var(--secondary-color);
    }

    .modal-subtitle {
        font-size: 0.9rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* نظرة عامة سريعة */
    .appointment-overview {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        background-color: white;
        border-radius: 10px;
        padding: 0.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .overview-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background-color: #f9f9f9;
        border-radius: 8px;
        gap: 1rem;
    }

    .overview-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .overview-content {
        display: flex;
        flex-direction: column;
    }

    .overview-label {
        font-size: 0.8rem;
        color: #777;
        margin-bottom: 0.2rem;
    }

    .overview-value {
        font-weight: 600;
        color: #333;
        font-size: 0.95rem;
    }
    .overview-value span {
        text-wrap: nowrap;
    }

    /* بطاقة ملف المريض */
    .patient-profile-card {
        background-color: white;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
        height: 100%;
    }

    .patient-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%);
        color: white;
        font-weight: 600;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .patient-fullname {
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .patient-id {
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
    }

    .patient-contact-details {
        margin-bottom: 1.5rem;
        text-align: right;
    }

    .contact-detail {
        display: flex;
        align-items: center;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }

    .contact-detail i {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background-color: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 0.75rem;
        color: var(--primary-color);
    }

    .patient-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .patient-stats .stat {
        background-color: #f8f9fa;
        padding: 0.75rem;
        border-radius: 8px;
        text-align: center;
    }

    .patient-stats .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
        display: block;
    }

    .patient-stats .stat-label {
        font-size: 0.8rem;
        color: #666;
    }

    /* علامات تبويب تفاصيل الحجز */
    .appointment-details-tabs .nav-tabs {
        margin-bottom: 1rem;
    }

    /* مسار زمني للحجز */
    .appointment-timeline-details {
        padding: 1rem;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    .timeline-event {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1.25rem;
        position: relative;
    }

    .timeline-event:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 30px;
        right: 15px;
        height: calc(100% - 15px);
        width: 2px;
        background-color: #e9ecef;
    }

    .event-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
        z-index: 1;
    }

    .event-icon.success {
        background-color: var(--success-color);
    }

    .event-icon.danger {
        background-color: var(--danger-color);
    }

    .event-content h6 {
        font-weight: 600;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .event-content p {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 0;
    }

    .appointment-countdown {
        background-color: rgba(23, 162, 184, 0.1);
        color: var (--info-color);
        padding: 0.75rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        margin-top: 1rem;
    }

    /* السجل الطبي */
    .medical-history {
        background-color: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #eee;
    }

    .medical-history h6 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .medical-history p {
        font-size: 0.9rem;
        color: #444;
        margin-bottom: 0;
    }

    /* ملاحظات الحجز */
    .notes-container {
        padding: 1rem;
        background-color: #f9f9f9;
        border-radius: 8px;
    }

    .notes-title {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .appointment-notes {
        background-color: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #eee;
    }

    .appointment-notes p {
        font-size: 0.9rem;
        color: #444;
        margin-bottom: 0;
    }

    .empty-notes {
        color: #888;
    }

    .empty-notes i {
        font-size: 2rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* تذييل Modal */
    .appointment-modal-footer {
        justify-content: space-between;
        background-color: #f8f9fa;
        border-top: none;
        padding: 1rem 1.5rem;
    }

    .completed-badge, .cancelled-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
    }

    .completed-badge {
        color: var(--success-color);
    }

    .cancelled-badge {
        color: var(--danger-color);
    }

    /* تنسيقات للشاشات المتوسطة والصغيرة */
    @media (max-width: 992px) {
        .appointment-overview {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .appointment-overview {
            grid-template-columns: 1fr;
        }

        .patient-profile-card {
            margin-bottom: 1.5rem;
        }

        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-title {
            font-size: 1.1rem;
        }

        .appointment-id {
            font-size: 0.8rem;
        }

        .modal-subtitle {
            font-size: 0.8rem;
        }
    }

    /* Estilos para los badges de fecha */
    .date-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.875rem;
        background-color: #f1f3f5;
        color: #495057;
        font-weight: 500;
    }

    .date-badge.today {
        background-color: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }

    .date-badge.tomorrow {
        background-color: rgba(23, 162, 184, 0.15);
        color: #17a2b8;
    }

    .date-badge i {
        margin-left: 0.25rem;
    }
</style>

<style>
    /* تخصيص أنماط Select2 للغة العربية */
    .select2-container {
        width: 100% !important;
        direction: rtl;
        text-align: right;
    }

    .select2-container--default .select2-selection--multiple {
        border-color: #ced4da;
        min-height: 38px;
        border-radius: 0.25rem;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #0d6efd;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 2px 8px;
        margin: 3px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-left: 5px;
        margin-right: 0;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #f8f9fa;
        background-color: rgba(255,255,255,0.2);
    }

    .select2-results__option {
        padding: 8px 12px;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #0d6efd;
    }

    .select2-search__field {
        width: 100% !important;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        padding: 4px 8px;
    }

    /* إصلاح مشكلة التداخل مع CSS الأصلي */
    .select2-container--open .select2-dropdown {
        z-index: 1056;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Improved tab handling function - runs on page load
    function activateTabByHash() {
        const hash = window.location.hash;
        if (hash) {
            const tabId = hash.substring(1);
            const tabEl = document.querySelector(`#doctorProfileTabs .nav-link[data-bs-target="#${tabId}"]`);

            if (tabEl) {
                // Force manual tab activation
                const tabPane = document.querySelector(`#${tabId}`);

                // Update nav tab states
                document.querySelectorAll('#doctorProfileTabs .nav-link').forEach(link => {
                    link.classList.remove('active');
                    link.setAttribute('aria-selected', 'false');
                });
                tabEl.classList.add('active');
                tabEl.setAttribute('aria-selected', 'true');

                // Update tab pane states
                document.querySelectorAll('#doctorProfileTabsContent > .tab-pane').forEach(pane => {
                    pane.classList.remove('show', 'active');
                });
                if (tabPane) {
                    tabPane.classList.add('show', 'active');
                }
            }
        }
    }

    // Execute immediately
    activateTabByHash();

    // Correctly handle Bootstrap tab clicks for better hash management
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        // Get the new active tab ID from the clicked tab target
        const tabTarget = $(e.target).attr('data-bs-target');
        const tabId = tabTarget.substring(1); // Remove the # character

        // Update the URL hash directly to ensure it changes
        if (history.pushState) {
            // Modern browsers
            history.pushState(null, null, '#' + tabId);
        } else {
            // Legacy fallback
            window.location.hash = tabId;
        }
    });

    // Schedule/time inputs handling
    $('.schedule-availability').on('change', function() {
        const row = $(this).closest('tr');
        const timeInputs = row.find('.schedule-time');

        if ($(this).is(':checked')) {
            timeInputs.prop('disabled', false);
        } else {
            timeInputs.prop('disabled', true);
            // Reset time values when day is deselected
            timeInputs.val(null);
        }
    });

    // Handle province/city selection
    $('#governorate_id').on('change', function() {
        const governorateId = $(this).val();
        const cities = @json($governorates->pluck('cities', 'id'));
        const citySelect = $('#city_id');

        citySelect.empty().append('<option value="">اختر المدينة</option>');

        if (governorateId && cities[governorateId]) {
            cities[governorateId].forEach(function(city) {
                citySelect.append(new Option(city.name, city.id));
            });
        }
    });

    // Load cities on page load if province is selected
    const oldGovernorate = $('#governorate_id').val();
    if (oldGovernorate) {
        $('#governorate_id').trigger('change');

        // Select saved city if available
        const oldCity = "{{ old('city_id', $doctor->city_id) }}";
        if (oldCity) {
            $('#city_id').val(oldCity);
        }
    }
});
</script>
@endpush
