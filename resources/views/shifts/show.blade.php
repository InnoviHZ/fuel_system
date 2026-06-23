@extends('layouts.app')

@section('title', 'Shift Details')
@section('topbar_title', 'Shift: ' . $shift->shift_date->format('M d, Y') . ' - ' . ucfirst($shift->shift_type))

@section('content')
<div class="row mb-4">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">STATUS</div>
                @if($shift->status === 'open')
                    <div style="font-size: 1.5rem; font-weight: 700; color: #22c55e;">OPEN</div>
                @else
                    <div style="font-size: 1.5rem; font-weight: 700; color: #64748b;">CLOSED</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">TOTAL SALES</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #3b82f6;">₦{{ number_format($totalRevenue, 2) }}</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">LITRES SOLD</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #8b5cf6;">{{ number_format($totalLitresSold, 2) }} L</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">PUMPS</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #f59e0b;">{{ $shift->pumpReadings->count() }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h6 class="card-title">Pump Readings & Sales</h6>
    </div>
    <div class="card-body p-0">
        @forelse($shift->pumpReadings as $reading)
            <div style="border-bottom: 1px solid #e2e8f0; padding: 1.5rem;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label style="font-size: 0.75rem; color: #64748b; font-weight: 500;">PUMP</label>
                            <div style="font-weight: 600;">{{ $reading->pump->name }}<span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.4rem; margin-left: 0.5rem; font-size: 0.85rem;">{{ $reading->pump->tank->fuelProduct->code }}</span></div>
                        </div>
                        <div>
                            <label style="font-size: 0.75rem; color: #64748b; font-weight: 500;">ATTENDANT</label>
                            <div style="font-weight: 600;">{{ $reading->attendant->name }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-6">
                                <label style="font-size: 0.75rem; color: #64748b; font-weight: 500;">OPENING METER</label>
                                <div style="font-weight: 600;">{{ number_format($reading->opening_meter, 2) }} km</div>
                            </div>
                            <div class="col-6">
                                <label style="font-size: 0.75rem; color: #64748b; font-weight: 500;">CLOSING METER</label>
                                <div style="font-weight: 600;">{{ $reading->closing_meter ? number_format($reading->closing_meter, 2) . ' km' : '-' }}</div>
                            </div>
                        </div>
                        @if($reading->litres_sold)
                            <div>
                                <label style="font-size: 0.75rem; color: #64748b; font-weight: 500; margin-top: 0.5rem;">LITRES SOLD</label>
                                <div style="font-weight: 600; color: #22c55e;">{{ number_format($reading->litres_sold, 2) }} L</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-4 text-muted">No pump readings</div>
        @endforelse
    </div>
</div>

@if($shift->sales->count())
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Sales in This Shift</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Pump</th>
                            <th>Fuel Type</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Attendant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shift->sales as $sale)
                            <tr>
                                <td><small class="text-muted">{{ $sale->sale_time->format('H:i') }}</small></td>
                                <td><strong>{{ $sale->pump->name }}</strong></td>
                                <td><span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.4rem;">{{ $sale->fuelProduct->code }}</span></td>
                                <td>{{ number_format($sale->quantity_litres, 2) }} L</td>
                                <td>₦{{ number_format($sale->unit_price, 2) }}</td>
                                <td><strong>₦{{ number_format($sale->total_amount, 2) }}</strong></td>
                                <td>
                                    @php
                                        $colors = [
                                            'cash' => '#22c55e',
                                            'pos' => '#3b82f6',
                                            'bank_transfer' => '#f59e0b',
                                            'credit' => '#ef4444'
                                        ];
                                    @endphp
                                    <span class="badge" style="background: {{ $colors[$sale->payment_method] }}; color: white;">
                                        {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}
                                    </span>
                                </td>
                                <td>{{ $sale->attendant->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<div class="mt-4 d-flex gap-2">
    @if($shift->status === 'open' && Auth::user()->hasAnyRole(['supervisor', 'admin']))
        <a href="{{ route('shifts.close', $shift) }}" class="btn btn-warning">
            <i class="bi bi-stop-circle me-1"></i>Close Shift
        </a>
    @endif
    <a href="{{ route('shifts.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Shifts
    </a>
</div>
@endsection
