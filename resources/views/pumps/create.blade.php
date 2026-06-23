@extends('layouts.app')

@section('title', 'Create Pump')
@section('topbar_title', 'Add New Fuel Pump')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Pump Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('pumps.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Pump Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="e.g., Pump 01" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="tank_id" class="form-label">Assigned Tank</label>
                        <select class="form-select @error('tank_id') is-invalid @enderror" id="tank_id" name="tank_id" required>
                            <option value="">Select a tank</option>
                            @foreach($tanks as $tank)
                                <option value="{{ $tank->id }}" {{ old('tank_id') == $tank->id ? 'selected' : '' }}>
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
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active Pump
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Create Pump
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
                <h6 class="card-title">Pump Configuration</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div style="font-weight: 600; color: #3b82f6; margin-bottom: 0.5rem;">Pump Name</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">A unique identifier for the pump (e.g., Pump 01, Pump A1, Terminal 02)</p>
                </div>
                <hr>
                <div class="mb-3">
                    <div style="font-weight: 600; color: #8b5cf6; margin-bottom: 0.5rem;">Assigned Tank</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Select which fuel tank this pump will dispense fuel from. The fuel type is determined by the tank's configuration.</p>
                </div>
                <hr>
                <div class="mb-0">
                    <div style="font-weight: 600; color: #22c55e; margin-bottom: 0.5rem;">Active Status</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Enable or disable this pump. Inactive pumps won't dispense fuel.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
