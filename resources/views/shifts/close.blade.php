@extends('layouts.app')

@section('title', 'Close Shift')
@section('topbar_title', 'Close Shift: ' . $shift->shift_date->format('M d, Y') . ' - ' . ucfirst($shift->shift_type))

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="card-title">Enter Closing Meters</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('shifts.closeShift', $shift) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="table-responsive mb-4">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Pump</th>
                            <th>Fuel Type</th>
                            <th>Attendant</th>
                            <th>Opening Meter</th>
                            <th>Closing Meter</th>
                            <th>Litres Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shift->pumpReadings as $index => $reading)
                            <tr>
                                <td>
                                    <strong>{{ $reading->pump->name }}</strong>
                                    <input type="hidden" name="pump_readings[{{ $index }}][pump_id]" value="{{ $reading->pump->id }}">
                                </td>
                                <td><span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.4rem;">{{ $reading->pump->tank->fuelProduct->code }}</span></td>
                                <td>{{ $reading->attendant->name }}</td>
                                <td><strong>{{ number_format($reading->opening_meter, 2) }} km</strong></td>
                                <td>
                                    <input type="number" step="0.01" class="form-control closing-meter" 
                                           name="pump_readings[{{ $index }}][closing_meter]" 
                                           placeholder="0.00" 
                                           data-opening="{{ $reading->opening_meter }}"
                                           data-index="{{ $index }}"
                                           required>
                                </td>
                                <td>
                                    <div class="litres-sold litres-sold-{{ $index }}" style="font-weight: 600; color: #22c55e;">
                                        0.00 L
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="form-group mb-4">
                <label for="notes" class="form-label">Closing Notes (Optional)</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any notes when closing this shift..."></textarea>
            </div>

            <div class="alert alert-info" role="alert">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Important:</strong> Closing meter must be greater than or equal to opening meter for each pump.
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i>Close Shift
                </button>
                <a href="{{ route('shifts.show', $shift) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.closing-meter').forEach(input => {
    input.addEventListener('input', function() {
        const opening = parseFloat(this.dataset.opening);
        const closing = parseFloat(this.value) || 0;
        const litresSold = closing - opening;
        const index = this.dataset.index;
        
        document.querySelector(`.litres-sold-${index}`).textContent = 
            litresSold.toFixed(2) + ' L';
    });
});
</script>
@endsection
