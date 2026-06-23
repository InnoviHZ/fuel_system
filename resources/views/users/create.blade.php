@extends('layouts.app')

@section('title', 'Create User')
@section('topbar_title', 'Create New User')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">User Information</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select a role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                            <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="supervisor" {{ old('role') === 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                            <option value="attendant" {{ old('role') === 'attendant' ? 'selected' : '' }}>Attendant</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active User
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create User
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Role Descriptions</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div style="font-weight: 600; color: #3b82f6;">Administrator</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Full system access. Can manage users, products, tanks, pumps, and view reports.</p>
                </div>
                <hr>
                <div class="mb-3">
                    <div style="font-weight: 600; color: #8b5cf6;">Manager</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage fuel products and inventory. View tanks and monitor sales.</p>
                </div>
                <hr>
                <div class="mb-3">
                    <div style="font-weight: 600; color: #f59e0b;">Supervisor</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Monitor daily operations, manage shifts, and reconciliation.</p>
                </div>
                <hr>
                <div class="mb-0">
                    <div style="font-weight: 600; color: #22c55e;">Attendant</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Record fuel sales and manage pump operations.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
