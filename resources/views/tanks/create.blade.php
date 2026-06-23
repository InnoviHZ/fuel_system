@extends('layouts.app')

@section('title', 'Create Fuel Tank')
@section('topbar_title', 'Add New Fuel Tank')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Tank Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('tanks.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Tank Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="e.g., Main Tank 1" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fuel_product_id" class="form-label">Fuel Type</label>
                        <select class="form-select @error('fuel_product_id') is-invalid @enderror" id="fuel_product_id" name="fuel_product_id" required>
                            <option value="">Select a fuel type</option>
                            @foreach($fuelProducts as $product)
                                <option value="{{ $product->id }}" {{ old('fuel_product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} ({{ $product->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('fuel_product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="capacity_litres" class="form-label">Tank Capacity (Litres)</label>
                        <input type="number" class="form-control @error('capacity_litres') is-invalid @enderror" id="capacity_litres" name="capacity_litres" placeholder="45000" value="{{ old('capacity_litres') }}" required>
                        @error('capacity_litres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="current_stock_litres" class="form-label">Initial Stock (Litres)</label>
                        <input type="number" class="form-control @error('current_stock_litres') is-invalid @enderror" id="current_stock_litres" name="current_stock_litres" placeholder="0" value="{{ old('current_stock_litres', 0) }}" required>
                        @error('current_stock_litres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="low_stock_threshold" class="form-label">Low Stock Threshold (Litres)</label>
                        <input type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" id="low_stock_threshold" name="low_stock_threshold" placeholder="11250" value="{{ old('low_stock_threshold') }}" required>
                        @error('low_stock_threshold')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="critical_stock_threshold" class="form-label">Critical Stock Threshold (Litres)</label>
                        <input type="number" class="form-control @error('critical_stock_threshold') is-invalid @enderror" id="critical_stock_threshold" name="critical_stock_threshold" placeholder="5625" value="{{ old('critical_stock_threshold') }}" required>
                        @error('critical_stock_threshold')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Tank
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create Tank
                        </button>
                        <a href="{{ route('tanks.index') }}" class="btn btn-outline-secondary">
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
                <h6 class="card-title">Tank Configuration Guide</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div style="font-weight: 600; color: #3b82f6; margin-bottom: 0.5rem;">Capacity</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">The maximum amount of fuel the tank can hold. This is used to calculate stock percentage.</p>
                </div>
                <hr>
                <div class="mb-3">
                    <div style="font-weight: 600; color: #f59e0b; margin-bottom: 0.5rem;">Low Stock Threshold</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">When stock falls below this level, the tank enters warning state (25-50% capacity).</p>
                </div>
                <hr>
                <div class="mb-0">
                    <div style="font-weight: 600; color: #ef4444; margin-bottom: 0.5rem;">Critical Stock Threshold</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">When stock falls below this level, the tank is critical and requires immediate refill (below 25% capacity).</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
