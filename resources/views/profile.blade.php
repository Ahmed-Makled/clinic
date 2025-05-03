@extends('layouts.app')

@section('content')
<div class="container mt-5 py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- رسائل النجاح -->
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center" role="alert">
                    <div class="me-3 bg-success bg-opacity-10 p-2 rounded-circle">
                        <i class="bi bi-check-circle-fill text-success fs-4"></i>
                    </div>
                    <div>
                        <strong>تم بنجاح!</strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- بطاقة الملف الشخصي الرئيسية -->
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <div class="card-header bg-gradient bg-primary bg-opacity-10 py-3 border-0">
                    <h4 class="mb-0 text-primary fw-bold">
                        <i class="bi bi-person-circle me-2"></i>{{ $title }}
                    </h4>
                </div>

                <div class="card-body p-lg-4">
                    <!-- قسم المعلومات الأساسية -->
                    <div class="profile-section mb-5">
                        <div class="section-header d-flex align-items-center mb-4">
                            <div class="section-icon bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="bi bi-person-vcard text-primary"></i>
                            </div>
                            <h5 class="text-primary mb-0 fw-bold">المعلومات الأساسية</h5>
                        </div>

                        <div class="profile-info">
                            <div class="row mb-3 pb-3 border-bottom">
                                <div class="col-md-4 col-lg-3">
                                    <label class="fw-bold text-muted"><i class="bi bi-person me-2"></i>الاسم</label>
                                </div>
                                <div class="col-md-8 col-lg-9">
                                    <p class="mb-0">{{ $user->name }}</p>
                                </div>
                            </div>

                            <div class="row mb-3 pb-3 border-bottom">
                                <div class="col-md-4 col-lg-3">
                                    <label class="fw-bold text-muted"><i class="bi bi-envelope me-2"></i>البريد الإلكتروني</label>
                                </div>
                                <div class="col-md-8 col-lg-9">
                                    <p class="mb-0">{{ $user->email }}</p>
                                </div>
                            </div>

                            <div class="row mb-3 pb-3 border-bottom">
                                <div class="col-md-4 col-lg-3">
                                    <label class="fw-bold text-muted"><i class="bi bi-telephone me-2"></i>رقم الهاتف</label>
                                </div>
                                <div class="col-md-8 col-lg-9">
                                    <p class="mb-0">{{ $user->phone_number ?: 'غير محدد' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 col-lg-3">
                                    <label class="fw-bold text-muted"><i class="bi bi-person-badge me-2"></i>الدور</label>
                                </div>
                                <div class="col-md-8 col-lg-9">
                                    <p class="mb-0">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                            <i class="bi bi-shield-check me-1"></i>{{ $user->getRoleNames()->first() }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- قسم الملف الطبي (للمرضى فقط) -->
                    @if($user->isPatient())
                        <hr class="my-4">

                        <div class="profile-section">
                            <div class="section-header d-flex align-items-center mb-4">
                                <div class="section-icon bg-info bg-opacity-10 p-2 rounded-circle me-3">
                                    <i class="bi bi-file-medical text-info"></i>
                                </div>
                                <h5 class="text-info mb-0 fw-bold">بيانات المريض</h5>
                            </div>

                            @if($user->patient)
                                <div class="profile-info">
                                    <div class="row mb-3 pb-3 border-bottom">
                                        <div class="col-md-4 col-lg-3">
                                            <label class="fw-bold text-muted"><i class="bi bi-calendar-event me-2"></i>تاريخ الميلاد</label>
                                        </div>
                                        <div class="col-md-8 col-lg-9">
                                            <p class="mb-0">{{ $user->patient->date_of_birth ? $user->patient->date_of_birth->format('Y-m-d') : 'غير محدد' }}</p>
                                        </div>
                                    </div>

                                    <div class="row mb-3 pb-3 border-bottom">
                                        <div class="col-md-4 col-lg-3">
                                            <label class="fw-bold text-muted"><i class="bi bi-gender-ambiguous me-2"></i>الجنس</label>
                                        </div>
                                        <div class="col-md-8 col-lg-9">
                                            <p class="mb-0">
                                                @if($user->patient->gender == 'male')
                                                    <i class="bi bi-gender-male text-primary me-1"></i> ذكر
                                                @elseif($user->patient->gender == 'female')
                                                    <i class="bi bi-gender-female text-danger me-1"></i> أنثى
                                                @else
                                                    غير محدد
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-4 col-lg-3">
                                            <label class="fw-bold text-muted"><i class="bi bi-geo-alt me-2"></i>العنوان</label>
                                        </div>
                                        <div class="col-md-8 col-lg-9">
                                            <p class="mb-0">{{ $user->patient->address ?: 'غير محدد' }}</p>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-outline-primary rounded-pill px-4 py-2" data-bs-toggle="modal" data-bs-target="#editPatientModal">
                                            <i class="bi bi-pencil-square me-2"></i> تعديل البيانات
                                        </button>
                                    </div>
                                </div>

                                <!-- نافذة تعديل البيانات -->
                                <div class="modal fade" id="editPatientModal" tabindex="-1" aria-labelledby="editPatientModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <div class="modal-header bg-light">
                                                <h5 class="modal-title" id="editPatientModalLabel">
                                                    <i class="bi bi-pencil-square text-primary me-2"></i>تعديل الملف الطبي
                                                </h5>
                                                <button type="button" class="btn-close ms-0 me-2" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form method="POST" action="{{ route('profile.update') }}">
                                                @csrf
                                                @method('PUT')
                                                @if(request()->has('redirect_to'))
                                                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                                                @endif
                                                <div class="modal-body p-4">
                                                    <div class="mb-4">
                                                        <label for="date_of_birth" class="form-label">
                                                            <i class="bi bi-calendar-date me-1"></i>تاريخ الميلاد
                                                        </label>
                                                        <input type="date" class="form-control form-control-lg @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->patient->date_of_birth ? $user->patient->date_of_birth->format('Y-m-d') : '') }}">
                                                        @error('date_of_birth')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="form-label">
                                                            <i class="bi bi-gender-ambiguous me-1"></i>الجنس
                                                        </label>
                                                        <div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" {{ old('gender', $user->patient->gender) == 'male' ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="gender_male">ذكر</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female" {{ old('gender', $user->patient->gender) == 'female' ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="gender_female">أنثى</label>
                                                            </div>
                                                            @error('gender')
                                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="address" class="form-label">
                                                            <i class="bi bi-geo-alt me-1"></i>العنوان
                                                        </label>
                                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="أدخل عنوانك هنا...">{{ old('address', $user->patient->address) }}</textarea>
                                                        @error('address')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle me-2"></i>إلغاء
                                                    </button>
                                                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                                                        <i class="bi bi-check2-circle me-2"></i>حفظ التغييرات
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- نموذج إنشاء الملف الطبي -->
                                <div class="alert alert-light border border-info border-start-3 bg-info bg-opacity-10 mb-4 d-flex">
                                    <div class="me-3">
                                        <i class="bi bi-info-circle-fill text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading text-info mb-1">مطلوب استكمال البيانات</h5>
                                        <p class="mb-0">من فضلك قم بإكمال بياناتك الطبية لتتمكن من حجز المواعيد</p>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('profile.store') }}" class="patient-form p-4 bg-light rounded-3 border">
                                    @csrf
                                    @if(request()->has('redirect_to'))
                                        <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                                    @endif

                                    <div class="row mb-4">
                                        <label for="date_of_birth" class="col-md-4 col-form-label">
                                            <i class="bi bi-calendar3 me-2"></i>تاريخ الميلاد
                                        </label>
                                        <div class="col-md-8">
                                            <input type="date" class="form-control form-control-lg @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-md-4 col-form-label">
                                            <i class="bi bi-gender-ambiguous me-2"></i>الجنس
                                        </label>
                                        <div class="col-md-8 mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                                <label class="form-check-label" for="gender_male"><i class="bi bi-gender-male me-1 text-primary"></i>ذكر</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="gender_female"><i class="bi bi-gender-female me-1 text-danger"></i>أنثى</label>
                                            </div>
                                            @error('gender')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="address" class="col-md-4 col-form-label">
                                            <i class="bi bi-geo-alt me-2"></i>العنوان
                                        </label>
                                        <div class="col-md-8">
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="أدخل عنوانك هنا...">{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">
                                                <i class="bi bi-save me-2"></i> حفظ البيانات
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- زر العودة لحجز موعد في حالة وجود redirect_to -->
            @if($user->isPatient() && request()->has('redirect_to'))
                <div class="text-center mt-4">
                    <a href="{{ request('redirect_to') }}" class="btn btn-success btn-lg rounded-pill px-5 py-2">
                        <i class="bi bi-calendar-check me-2"></i> العودة لحجز الموعد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    /* أنماط خاصة بصفحة البروفايل */
    .profile-section {
        position: relative;
    }

    .section-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
    }

    .section-icon i {
        font-size: 1.2rem;
    }

    .profile-info .row {
        transition: all 0.3s ease;
    }

    .profile-info .row:hover {
        background-color: rgba(0, 0, 0, 0.01);
    }

    .border-start-3 {
        border-left-width: 3px !important;
    }

    .patient-form {
        transition: all 0.3s ease;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .patient-form:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
</style>
@endpush
@endsection
