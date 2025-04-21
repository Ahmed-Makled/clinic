@extends('layouts.app')
@section('content')
    <div class="login mt-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-4">
                    <form method="POST" action="{{ route('register') }}" class="box bg-white shadow-sm p-4 mt-5">
                        @csrf

                        <div class="text-center mb-4">
                            <h1 class="h2">إنشاء حساب.</h1>
                            <p>أنشئ حساباً لتتمكن من الحجز وميزات أخرى.</p>
                        </div>

                        <div class="form-group">
                            <input type="text" name="name" class="form-control rounded-pill @error('name') is-invalid @enderror"
                                placeholder="أدخل إسمك" value="{{ old('name') }}" required autofocus />
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" class="form-control rounded-pill @error('email') is-invalid @enderror"
                                placeholder="أدخل بريدك الإلكترونى" value="{{ old('email') }}" required />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="text" name="phone_number" class="form-control rounded-pill @error('phone_number') is-invalid @enderror"
                                placeholder="أدخل رقم الهاتف" value="{{ old('phone_number') }}" required />
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" class="form-control rounded-pill @error('password') is-invalid @enderror"
                                placeholder="أدخل كلمة مرور" required />
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="password" name="password_confirmation" class="form-control rounded-pill"
                                placeholder="تأكيد كلمة المرور" required />
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 btn-block">أنشئ الحساب</button>
                        </div>

                        <div class="text-center">
                            تمتلك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول.</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
