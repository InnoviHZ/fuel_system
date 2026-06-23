@extends('layouts.app')

@section('title', 'Create Fuel Product')
@section('topbar_title', 'Add New Fuel Product')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Product Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('fuel-products.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="e.g., Premium Gasoline" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="code" class="form-label">Product Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="e.g., PMS" value="{{ old('code') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="current_price" class="form-label">Current Price (₦)</label>
                        <input type="number" class="form-control @error('current_price') is-invalid @enderror" id="current_price" name="current_price" placeholder="0.00" step="0.01" value="{{ old('current_price') }}" required>
                        @error('current_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Product
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create Product
                        </button>
                        <a href="{{ route('fuel-products.index') }}" class="btn btn-outline-secondary">
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
                <h6 class="card-title">Product Types</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div style="font-weight: 600; color: #3b82f6;">PMS (Petrol)</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Premium Motor Spirit - For gasoline vehicles</p>
                </div>
                <hr>
                <div class="mb-3">
                    <div style="font-weight: 600; color: #22c55e;">AGO (Diesel)</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Automotive Gas Oil - For diesel vehicles</p>
                </div>
                <hr>
                <div class="mb-0">
                    <div style="font-weight: 600; color: #f59e0b;">DPK (Kerosene)</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Dual Purpose Kerosene - For heating and lighting</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
