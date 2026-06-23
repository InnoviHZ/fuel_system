@extends('layouts.app')

@section('title', 'Edit Fuel Tank')
@section('topbar_title', 'Edit Fuel Tank')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Tank Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('tanks.update', $tank) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">Tank Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $tank->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="fuel_product_id" class="form-label">Fuel Type</label>
                        <select class="form-select @error('fuel_product_id') is-invalid @enderror" id="fuel_product_id" name="fuel_product_id" required>
                            @foreach($fuelProducts as $product)
                                <option value="{{ $product->id }}" {{ old('fuel_product_id', $tank->fuel_product_id) == $product->id ? 'selected' : '' }}>
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
                        <input type="number" class="form-control @error('capacity_litres') is-invalid @enderror" id="capacity_litres" name="capacity_litres" value="{{ old('capacity_litres', $tank->capacity_litres) }}" required>
                        @error('capacity_litres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="current_stock_litres" class="form-label">Current Stock (Litres)</label>
                        <input type="number" class="form-control @error('current_stock_litres') is-invalid @enderror" id="current_stock_litres" name="current_stock_litres" value="{{ old('current_stock_litres', $tank->current_stock_litres) }}" required>
                        @error('current_stock_litres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="low_stock_threshold" class="form-label">Low Stock Threshold (Litres)</label>
                        <input type="number" class="form-control @error('low_stock_threshold') is-invalid @enderror" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $tank->low_stock_threshold) }}" required>
                        @error('low_stock_threshold')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="critical_stock_threshold" class="form-label">Critical Stock Threshold (Litres)</label>
                        <input type="number" class="form-control @error('critical_stock_threshold') is-invalid @enderror" id="critical_stock_threshold" name="critical_stock_threshold" value="{{ old('critical_stock_threshold', $tank->critical_stock_threshold) }}" required>
                        @error('critical_stock_threshold')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $tank->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Tank
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Tank
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
                <h6 class="card-title">Tank Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Fuel Type</label>
                    <div style="font-weight: 600;">{{ $tank->fuelProduct->name }} <span style="background: #f1f5f9; padding: 0.25rem 0.75rem; border-radius: 0.4rem; margin-left: 0.5rem;">{{ $tank->fuelProduct->code }}</span></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Capacity</label>
                    <div style="font-weight: 600;">{{ number_format($tank->capacity_litres) }} Litres</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Current Stock</label>
                    <div style="font-weight: 600;">{{ number_format($tank->current_stock_litres) }} L ({{ number_format(($tank->current_stock_litres / $tank->capacity_litres) * 100, 1) }}%)</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Status</label>
                    <div>
                        @if($tank->is_active)
                            <span class="badge badge-active"><i class="bi bi-check-circle me-1"></i>Active</span>
                        @else
                            <span class="badge badge-inactive"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Created</label>
                    <div style="font-weight: 600;">{{ $tank->created_at->format('F d, Y') }}</div>
                </div>
                <div class="mb-0">
                    <label class="text-muted" style="font-size: 0.85rem;">Last Updated</label>
                    <div style="font-weight: 600;">{{ $tank->updated_at->format('F d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
