@extends('layouts.auth')

@section('title', 'Sign In')

@section('content')
<div class="auth-card">
    <div class="auth-card-header">
        <i class="bi bi-fuel-pump me-2" style="font-size: 2rem;"></i>
        <h2>FuelHub</h2>
        <p>Digital Fuel Management System</p>
    </div>
    
    <div class="auth-card-body">
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Authentication Failed!</strong>
                <br>
                @foreach ($errors->all() as $error)
                    <small>{{ $error }}</small><br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="bi bi-envelope me-1"></i>Email Address
                </label>
                <input 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="Enter your email"
                >
                @error('email')
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="bi bi-lock me-1"></i>Password
                </label>
                <input 
                    id="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password"
                >
                @error('password')
                    <div class="error-message">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="checkbox-group">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="form-check-input" 
                    name="remember"
                >
                <label for="remember_me" class="form-check-label">
                    Remember me for 30 days
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In to Your Account
            </button>

            <!-- Forgot Password Link -->
            @if (Route::has('password.request'))
                <div class="text-center-link">
                    <a class="auth-link" href="{{ route('password.request') }}">
                        <i class="bi bi-question-circle me-1"></i>Forgot your password?
                    </a>
                </div>
            @endif

            <!-- Register Link -->
            <div class="text-center-link">
                Don't have an account?
                <a class="auth-link" href="{{ route('register') }}">
                    <i class="bi bi-person-plus me-1"></i>Register here
                </a>
            </div>
        </form>

        <!-- Demo Credentials -->
        <div class="auth-footer">
            <p><i class="bi bi-info-circle me-1"></i>Demo Credentials:</p>
            <div class="demo-credentials">
                📧 admin@fuelstation.com<br>
                🔑 password
            </div>
        </div>
    </div>
</div>
@endsection

