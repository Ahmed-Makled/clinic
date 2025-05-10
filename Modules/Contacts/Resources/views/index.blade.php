@extends('layouts.app')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <h2 class="text-primary">{{ __('Contact Us') }}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-5">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                @csrf
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="name">{{ __('Your Name') }}</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('Enter your name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="email">{{ __('Your Email') }}</label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('Enter your email address') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject">{{ __('Subject') }}</label>
                    <input type="text" name="subject" id="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="{{ __('Enter subject') }}">
                    @error('subject')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="message">{{ __('Your Message') }}</label>
                    <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="5" placeholder="{{ __('Enter your message') }}">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Send Message') }}</button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="bg-light p-4 rounded">
                <h4 class="text-primary mb-4">{{ __('Contact Information') }}</h4>
                <p><i class="fa fa-map-marker-alt mr-2"></i> {{ __('123 Main St, Cairo, Egypt') }}</p>
                <p><i class="fa fa-phone-alt mr-2"></i> {{ __('(+20) 123 456 789') }}</p>
                <p><i class="fa fa-envelope mr-2"></i> {{ __('info@clinic.com') }}</p>
                <div class="social mt-4">
                    <a href="#" class="mx-1"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="mx-1"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="mx-1"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="mx-1"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
