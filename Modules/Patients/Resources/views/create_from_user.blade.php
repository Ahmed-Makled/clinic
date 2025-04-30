@extends('dashboard::layouts.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                        <div class="bg-gradient-primary shadow-primary border-radius-lg pt-4 pb-3 d-flex justify-content-between align-items-center">
                            <h6 class="text-white text-capitalize me-3 text-end">إكمال بيانات المريض</h6>
                            <a href="{{ route('users.index') }}" class="btn bg-gradient-dark btn-sm mb-0 ms-3">العودة للمستخدمين</a>
                        </div>
                    </div>
                    <div class="card-body p-3 pb-2">
                        <form method="post" action="{{ route('patients.storeFromUser') }}" dir="rtl">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $user->id }}">

                            <div class="row">
                                <div class="card card-plain border-radius-lg my-3">
                                    <div class="card-header bg-light pb-0 text-center">
                                        <h5>معلومات المستخدم الحالية</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="name">الاسم</label>
                                                    <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="email">البريد الإلكتروني</label>
                                                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <div class="form-group">
                                                    <label for="phone">رقم الهاتف</label>
                                                    <input type="tel" class="form-control" value="{{ $user->phone_number }}" disabled>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- معلومات المريض -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="gender">الجنس <span class="text-danger">*</span></label>
                                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                                            <option value="">اختر الجنس</option>
                                            <option value="male">ذكر</option>
                                            <option value="female">أنثى</option>
                                        </select>
                                        @error('gender')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="date_of_birth">تاريخ الميلاد</label>
                                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror">
                                        @error('date_of_birth')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label for="address">العنوان</label>
                                        <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror">
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- أزرار التحكم -->
                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn bg-gradient-success btn-lg">حفظ بيانات المريض</button>
                                    <a href="{{ route('users.index') }}" class="btn bg-gradient-danger btn-lg">إلغاء</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
