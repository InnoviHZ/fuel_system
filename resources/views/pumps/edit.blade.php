@extends('layouts.app')

@section('title', 'Edit Pump')
@section('topbar_title', 'Edit Fuel Pump')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Pump Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pumps.update', $pump) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">Pump Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $pump->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tank_id" class="form-label">Assigned Tank</label>
                        <select class="form-select @error('tank_id') is-invalid @enderror" id="tank_id" name="tank_id" required>
                            @foreach($tanks as $tank)
                                <option value="{{ $tank->id }}" {{ old('tank_id', $pump->tank_id) == $tank->id ? 'selected' : '' }}>
                                    {{ $tank->name }} - {{ $tank->fuelProduct->name }} ({{ $tank->fuelProduct->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('tank_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $pump->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Pump
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Update Pump
                        </button>
                        <a href="{{ route('pumps.index') }}" class="btn btn-outline-secondary">
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
                <h6 class="card-title">Pump Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Assigned Tank</label>
                    <div style="font-weight: 600;">
                        <a href="{{ route('tanks.show', $pump->tank) }}" style="color: #3b82f6; text-decoration: none;">
                            {{ $pump->tank->name }}
                        </a>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Fuel Type</label>
                    <div style="font-weight: 600;">{{ $pump->tank->fuelProduct->name }} <span style="background: #f1f5f9; padding: 0.25rem 0.75rem; border-radius: 0.4rem; margin-left: 0.5rem;">{{ $pump->tank->fuelProduct->code }}</span></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Current Status</label>
                    <div>
                        @if($pump->is_active)
                            <span class="badge badge-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                        @else
                            <span class="badge badge-danger"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Created</label>
                    <div style="font-weight: 600;">{{ $pump->created_at->format('F d, Y') }}</div>
                </div>
                <div class="mb-0">
                    <label class="text-muted" style="font-size: 0.85rem;">Last Updated</label>
                    <div style="font-weight: 600;">{{ $pump->updated_at->format('F d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
