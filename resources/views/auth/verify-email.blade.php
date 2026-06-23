@extends('auth.auth')

@section('content')
<div class="auth-card">
    <div class="auth-card-header">
        <h2><i class="fas fa-envelope me-2"></i>Verify Email</h2>
        <p>Verify your email address</p>
    </div>
    <div class="auth-card-body">
        @if (session('resent'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                A fresh verification link has been sent to your email address.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <p class="mb-3">
            Before proceeding, please check your email for a verification link.
            If you did not receive the email, we will gladly send you another.
        </p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                Resend Verification Email
            </button>
        </form>

        <!-- Logout Link -->
        <div class="text-center-link">
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="auth-link" style="background: none; border: none; cursor: pointer;">
                    Log out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
