@extends('layouts.app')

@section('content')
<div class="container mt-5 py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <form method="POST" action="{{ route('contact.store') }}" class="card shadow-sm border-0">
                @csrf
                <div class="card-body p-4">
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="text-center mb-4">
                        <div class="icon-wrapper mb-3">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <h1 class="h3 mb-2">إتصل بنا</h1>
                        <p class="text-muted">إذا كان لديك أى تساؤلات، أرسل لنا رسالة</p>
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text border-end-0"><i class="bi bi-person"></i></span>
                            <input type="text" name="name"
                                class="form-control border-start-0 @error('name') is-invalid @enderror"
                                placeholder="أدخل إسمك" value="{{ old('name') }}" required />
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text border-end-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email"
                                class="form-control border-start-0 @error('email') is-invalid @enderror"
                                placeholder="أدخل بريدك الإلكترونى" value="{{ old('email') }}" required />
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text border-end-0"><i class="bi bi-chat-left-text"></i></span>
                            <input type="text" name="subject"
                                class="form-control border-start-0 @error('subject') is-invalid @enderror"
                                placeholder="موضوع الرسالة" value="{{ old('subject') }}" required />
                            @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="input-group">
                            <span class="input-group-text border-end-0"><i class="bi bi-chat-text"></i></span>
                            <textarea name="message" rows="4"
                                class="form-control border-start-0 @error('message') is-invalid @enderror"
                                placeholder="أدخل الرسالة" required>{{ old('message') }}</textarea>
                            @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send me-2"></i>أرسل الرسالة
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
