@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="text-center mb-4">
        <h1 class="h3 mb-2">Welcome Back</h1>
        <p class="text-muted">Please sign in to your account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
        </div>

        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary">Sign in</button>
        </div>

        <div class="text-center">
            <p class="text-muted">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-decoration-none">Register here</a>
            </p>
        </div>
    </form>
@endsection
