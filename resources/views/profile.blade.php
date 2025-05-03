@extends('layouts.app')

@section('content')
<div class="container mt-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light fw-bold">{{ $title }}</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <!-- User Information Section -->
                    <div class="mb-4">
                        <h5 class="text-primary mb-3">المعلومات الأساسية</h5>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">الاسم</label>
                            <div class="col-md-6">
                                <p class="form-control-plaintext">{{ $user->name }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">البريد الإلكتروني</label>
                            <div class="col-md-6">
                                <p class="form-control-plaintext">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">رقم الهاتف</label>
                            <div class="col-md-6">
                                <p class="form-control-plaintext">{{ $user->phone_number }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">الدور</label>
                            <div class="col-md-6">
                                <p class="form-control-plaintext">{{ $user->getRoleNames()->first() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Profile Section -->
                    @if($user->isPatient())
                        <hr class="my-4">
                        <h5 class="text-primary mb-3">بيانات الملف الطبي</h5>

                        @if($user->patient)
                            <!-- Display Patient Information -->
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">تاريخ الميلاد</label>
                                <div class="col-md-6">
                                    <p class="form-control-plaintext">{{ $user->patient->date_of_birth ? $user->patient->date_of_birth->format('Y-m-d') : 'غير محدد' }}</p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">الجنس</label>
                                <div class="col-md-6">
                                    <p class="form-control-plaintext">
                                        @if($user->patient->gender == 'male')
                                            ذكر
                                        @elseif($user->patient->gender == 'female')
                                            أنثى
                                        @else
                                            غير محدد
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label text-md-end">العنوان</label>
                                <div class="col-md-6">
                                    <p class="form-control-plaintext">{{ $user->patient->address ?: 'غير محدد' }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10 text-md-end">
                                    <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editPatientModal">
                                        <i class="bi bi-pencil-square me-1"></i> تعديل البيانات
                                    </a>
                                </div>
                            </div>

                            <!-- Edit Patient Modal -->
                            <div class="modal fade" id="editPatientModal" tabindex="-1" aria-labelledby="editPatientModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editPatientModalLabel">تعديل الملف الطبي</h5>
                                            <button type="button" class="btn-close ms-0 me-2" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form method="POST" action="{{ route('profile.update') }}">
                                            @csrf
                                            @method('PUT')
                                            @if(request()->has('redirect_to'))
                                                <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                                            @endif
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="date_of_birth" class="form-label">تاريخ الميلاد</label>
                                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->patient->date_of_birth ? $user->patient->date_of_birth->format('Y-m-d') : '') }}">
                                                    @error('date_of_birth')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label d-block">الجنس</label>
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

                                                <div class="mb-3">
                                                    <label for="address" class="form-label">العنوان</label>
                                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $user->patient->address) }}</textarea>
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Create Patient Profile Form -->
                            <div class="alert alert-info mb-4">
                                <i class="bi bi-info-circle me-2"></i> من فضلك قم بإكمال بياناتك الطبية لتتمكن من حجز المواعيد
                            </div>

                            <form method="POST" action="{{ route('profile.store') }}" class="mt-4">
                                @csrf
                                @if(request()->has('redirect_to'))
                                    <input type="hidden" name="redirect_to" value="{{ request('redirect_to') }}">
                                @endif
                                <div class="row mb-3">
                                    <label for="date_of_birth" class="col-md-4 col-form-label text-md-end">تاريخ الميلاد</label>
                                    <div class="col-md-6">
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label text-md-end">الجنس</label>
                                    <div class="col-md-6 mt-2">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_male" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required>
                                            <label class="form-check-label" for="gender_male">ذكر</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender" id="gender_female" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                            <label class="form-check-label" for="gender_female">أنثى</label>
                                        </div>
                                        @error('gender')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="address" class="col-md-4 col-form-label text-md-end">العنوان</label>
                                    <div class="col-md-6">
                                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-1"></i> حفظ البيانات
                                        </button>
                                    </div>
                                </div>
                            </form>
                        @endif
                    @endif
                </div>
            </div>

            @if($user->isPatient() && request()->has('redirect_to'))
                <div class="text-center mt-4">
                    <a href="{{ request('redirect_to') }}" class="btn btn-success">
                        <i class="bi bi-arrow-right me-1"></i> العودة لحجز الموعد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
