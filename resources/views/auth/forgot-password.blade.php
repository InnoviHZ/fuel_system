@extends('auth.auth')

@section('content')
<div class="auth-card">
    <div class="auth-card-header">
        <h2><i class="fas fa-key me-2"></i>Reset Password</h2>
        <p>Enter your email to receive password reset link</p>
    </div>
    <div class="auth-card-body">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">Email Address</label>
                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                Send Reset Link
            </button>

            <!-- Back to Login -->
            <div class="text-center-link">
                <a class="auth-link" href="{{ route('login') }}">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
