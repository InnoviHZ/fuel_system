@extends('layouts.app')

@section('title', 'Edit Fuel Product')
@section('topbar_title', 'Edit Fuel Product')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Product Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('fuel-products.update', $fuelProduct) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $fuelProduct->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="code" class="form-label">Product Code</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $fuelProduct->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="current_price" class="form-label">Current Price (₦)</label>
                        <input type="number" class="form-control @error('current_price') is-invalid @enderror" id="current_price" name="current_price" step="0.01" value="{{ old('current_price', $fuelProduct->current_price) }}" required>
                        @error('current_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $fuelProduct->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Product
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Product
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
                <h6 class="card-title">Product Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Product Code</label>
                    <div style="font-weight: 600; font-family: monospace;">{{ $fuelProduct->code }}</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Current Status</label>
                    <div>
                        @if($fuelProduct->is_active)
                            <span class="badge badge-active"><i class="bi bi-check-circle me-1"></i>Active</span>
                        @else
                            <span class="badge badge-inactive"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Created</label>
                    <div style="font-weight: 600;">{{ $fuelProduct->created_at->format('F d, Y') }}</div>
                </div>
                <div class="mb-0">
                    <label class="text-muted" style="font-size: 0.85rem;">Last Updated</label>
                    <div style="font-weight: 600;">{{ $fuelProduct->updated_at->format('F d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
