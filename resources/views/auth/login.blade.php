
@extends('layouts.app')
@section('content')

    <div class="login mt-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-4">
                    <div class="pt-5"></div>
                    <form method="POST" action="{{ route('login') }}" class="box bg-white shadow-sm rounded-xl p-4 mt-5">
                        @csrf

                        @if ($errors->has('login'))
                            <div class="alert alert-info">{{ $errors->first('login') }}</div>
                        @endif

                        <div class="text-center mb-4">
                            <h1 class="h2">تسجيل الدخول</h1>
                            <p>سجل دخولك لتتمكن من الحجز وميزات أخرى.</p>
                        </div>

                        <div class="form-group">
                            <input type="email" name="email" class="form-control rounded-pill @error('email') is-invalid @enderror"
                                placeholder="أدخل بريدك الإلكترونى" value="{{ old('email') }}" required autofocus />
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <input type="password" name="password" class="form-control rounded-pill @error('password') is-invalid @enderror"
                                placeholder="أدخل كلمة السر" required />
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 btn-block">
                                تسجيل الدخول <i class="uil-angle-down"></i>
                            </button>
                        </div>

                        <!-- <div class="text-center">أو</div>

                        <button class="btn btn-danger rounded-pill my-3 btn-block disabled">تسجيل الدخول بواسطة جوجل</button>
                        <button class="btn btn-primary rounded-pill my-3 btn-block disabled">تسجيل الدخول بواسطة فيسبوك</button> -->

                        <div class="text-center">
                            ليس لديك حساب؟ <a href="{{ route('register') }}">أنشئ حساباً.</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

