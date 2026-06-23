@extends('layouts.app')

@section('title', 'Open Shift')
@section('topbar_title', 'Open New Shift')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Shift Details</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('shifts.store') }}" method="POST" id="shiftForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shift_date" class="form-label">Shift Date</label>
                                <input type="date" class="form-control @error('shift_date') is-invalid @enderror" 
                                       id="shift_date" name="shift_date" value="{{ old('shift_date', today()) }}" required>
                                @error('shift_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="shift_type" class="form-label">Shift Type</label>
                                <select class="form-select @error('shift_type') is-invalid @enderror" 
                                        id="shift_type" name="shift_type" required>
                                    <option value="">Select shift type</option>
                                    <option value="morning" {{ old('shift_type') === 'morning' ? 'selected' : '' }}>Morning (6 AM - 2 PM)</option>
                                    <option value="evening" {{ old('shift_type') === 'evening' ? 'selected' : '' }}>Evening (2 PM - 10 PM)</option>
                                </select>
                                @error('shift_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes" class="form-label">Shift Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any notes about this shift...">{{ old('notes') }}</textarea>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="form-label">Pump Assignments & Opening Meters</h6>
                        <p class="text-muted" style="font-size: 0.85rem;">Assign an attendant to each pump and enter opening meter reading</p>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pump</th>
                                    <th>Fuel Type</th>
                                    <th>Opening Meter (km)</th>
                                    <th>Assigned Attendant</th>
                                </tr>
                            </thead>
                            <tbody id="pumpTable">
                                @forelse($pumps as $index => $pump)
                                    <tr>
                                        <td>
                                            <strong>{{ $pump->name }}</strong>
                                            <input type="hidden" name="pump_readings[{{ $index }}][pump_id]" value="{{ $pump->id }}">
                                        </td>
                                        <td><span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.4rem;">{{ $pump->tank->fuelProduct->code }}</span></td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control" 
                                                   name="pump_readings[{{ $index }}][opening_meter]" 
                                                   placeholder="0.00" required>
                                        </td>
                                        <td>
                                            <select class="form-select" name="pump_readings[{{ $index }}][attendant_id]" required>
                                                <option value="">Select attendant</option>
                                                @foreach($attendants as $attendant)
                                                    <option value="{{ $attendant->id }}">{{ $attendant->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No active pumps available. Please contact administrator.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($pumps->isEmpty())
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            No active pumps available to open a shift.
                        </div>
                    @else
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>Open Shift
                            </button>
                            <a href="{{ route('shifts.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Instructions</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div style="font-weight: 600; color: #3b82f6; margin-bottom: 0.5rem;">Date & Type</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Select the shift date and type. Only one shift of each type can be open per day.</p>
                </div>
                <hr>
                <div class="mb-3">
                    <div style="font-weight: 600; color: #8b5cf6; margin-bottom: 0.5rem;">Opening Meters</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Record the current meter reading for each pump. This must be greater than or equal to the previous closing meter.</p>
                </div>
                <hr>
                <div class="mb-0">
                    <div style="font-weight: 600; color: #22c55e; margin-bottom: 0.5rem;">Attendants</div>
                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Assign one attendant to each pump. They can only record sales from pumps assigned to them.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
