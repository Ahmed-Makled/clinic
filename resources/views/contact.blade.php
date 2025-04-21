@extends('layouts.app')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-4">
            <form method="POST" action="{{ route('contact.store') }}" class="shadow-sm p-4 mt-5">
                @csrf

                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                <div class="text-center mb-4">
                    <h1 class="h2 mb-3">إتصل بنا</h1>
                    <p>إذا كان لديك أى تساؤلات، أرسل لنا رسالة</p>
                </div>

                <div class="form-group">
                    <input type="text" name="name" class="form-control rounded-pill @error('name') is-invalid @enderror"
                        placeholder="أدخل إسمك" value="{{ old('name') }}" required />
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
                    <input type="text" name="subject" class="form-control rounded-pill @error('subject') is-invalid @enderror"
                        placeholder="موضوع الرسالة" value="{{ old('subject') }}" required />
                    @error('subject')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <textarea name="message" rows="3" class="form-control @error('message') is-invalid @enderror"
                        style="border-radius: .5rem" placeholder="أدخل الرسالة" required>{{ old('message') }}</textarea>
                    @error('message')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-block">
                        أرسل الرسالة <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
