@extends('layouts.app')

@section('title', 'System Settings')
@section('topbar_title', 'System Settings')

@section('content')
<div class="row">
    <div class="col-lg-10">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Station Information Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-building me-2" style="color: #3b82f6;"></i>
                        Station Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Station Name</label>
                            <input type="text" name="station_name" class="form-control @error('station_name') is-invalid @enderror" value="{{ $settings['station_name'] ?? '' }}" required>
                            @error('station_name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Station Phone</label>
                            <input type="tel" name="station_phone" class="form-control @error('station_phone') is-invalid @enderror" value="{{ $settings['station_phone'] ?? '' }}" required>
                            @error('station_phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-600">Station Address</label>
                            <textarea name="station_address" class="form-control @error('station_address') is-invalid @enderror" rows="3" required>{{ $settings['station_address'] ?? '' }}</textarea>
                            @error('station_address')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shift Configuration Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2" style="color: #f59e0b;"></i>
                        Shift Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Morning Shift Time</label>
                            <input type="time" name="morning_shift_time" class="form-control @error('morning_shift_time') is-invalid @enderror" value="{{ $settings['morning_shift_time'] ?? '' }}" required>
                            <small class="text-muted">Start time for morning shift</small>
                            @error('morning_shift_time')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-600">Evening Shift Time</label>
                            <input type="time" name="evening_shift_time" class="form-control @error('evening_shift_time') is-invalid @enderror" value="{{ $settings['evening_shift_time'] ?? '' }}" required>
                            <small class="text-muted">Start time for evening shift</small>
                            @error('evening_shift_time')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Settings Section -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-coin me-2" style="color: #22c55e;"></i>
                        Financial Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">\n                            <label class="form-label fw-600">Cash Tolerance Threshold ($)</label>
                            <input type="number" name="cash_tolerance_threshold" class="form-control @error('cash_tolerance_threshold') is-invalid @enderror" value="{{ $settings['cash_tolerance_threshold'] ?? '' }}" step="0.01" min="0" required>
                            <small class="text-muted">Maximum cash variance allowed during reconciliation</small>
                            @error('cash_tolerance_threshold')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Save Changes
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .form-label {
        color: #1e293b;
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.6rem 0.875rem;
        font-size: 0.9375rem;
        transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .text-muted {
        color: #64748b !important;
        font-size: 0.85rem;
    }

    .card-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-title {
        color: #1e293b;
        font-size: 1.1rem;
    }

    .btn {
        padding: 0.625rem 1.5rem;
        font-weight: 600;
        transition: all 0.2s ease;
        border-radius: 0.5rem;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background-color: #1e40af;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-outline-secondary {
        border: 1px solid #e2e8f0;
        color: #1e293b;
    }

    .btn-outline-secondary:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e1;
    }
</style>
@endsection