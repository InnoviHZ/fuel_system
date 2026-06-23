@extends('layouts.app')

@section('title', 'Sale Receipt')
@section('topbar_title', 'Sale Receipt')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-body">
                <div style="text-align: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid #e2e8f0;">
                    <div style="font-size: 1.5rem; font-weight: 700;">FUEL STATION</div>
                    <div style="color: #64748b; font-size: 0.9rem;">Sales Receipt</div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <div class="row mb-2">
                        <div class="col-6">
                            <small class="text-muted">Receipt #</small>
                            <div style="font-weight: 600;">{{ $sale->id }}</div>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">Date</small>
                            <div style="font-weight: 600;">{{ $sale->sale_time->format('M d, Y H:i') }}</div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <small class="text-muted">Shift</small>
                            <div style="font-weight: 600;">{{ ucfirst($sale->shift->shift_type) }}</div>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">Attendant</small>
                            <div style="font-weight: 600;">{{ $sale->attendant->name }}</div>
                        </div>
                    </div>
                </div>

                <hr>

                <div style="margin-bottom: 1.5rem;">
                    <div class="row mb-2">
                        <div class="col-6">
                            <small class="text-muted">Pump</small>
                            <div style="font-weight: 600;">{{ $sale->pump->name }}</div>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">Fuel Type</small>
                            <div style="font-weight: 600;">{{ $sale->fuelProduct->code }} - {{ $sale->fuelProduct->name }}</div>
                        </div>
                    </div>
                </div>

                <hr>

                <div style="margin-bottom: 1.5rem; padding: 1rem; background: #f1f5f9; border-radius: 0.6rem;">
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">QUANTITY</small>
                            <div style="font-size: 1.3rem; font-weight: 700;">{{ number_format($sale->quantity_litres, 2) }} L</div>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted">UNIT PRICE</small>
                            <div style="font-size: 1.3rem; font-weight: 700;">₦{{ number_format($sale->unit_price, 2) }}</div>
                        </div>
                    </div>
                </div>

                <div style="padding: 1rem; background: #e8f5e9; border-radius: 0.6rem; margin-bottom: 1.5rem;">
                    <small class="text-muted" style="display: block; margin-bottom: 0.5rem;">TOTAL AMOUNT</small>
                    <div style="font-size: 1.8rem; font-weight: 700; color: #22c55e;">₦{{ number_format($sale->total_amount, 2) }}</div>
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <small class="text-muted">Payment Method</small>
                    <div>
                        @php
                            $colors = [
                                'cash' => '#22c55e',
                                'pos' => '#3b82f6',
                                'bank_transfer' => '#f59e0b',
                                'credit' => '#ef4444'
                            ];
                        @endphp
                        <span class="badge" style="background: {{ $colors[$sale->payment_method] }}; color: white; padding: 0.5rem 1rem; font-size: 0.9rem; margin-top: 0.5rem;">
                            {{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}
                        </span>
                    </div>
                </div>

                @if($sale->creditCustomer)
                    <div style="margin-bottom: 1.5rem;">
                        <small class="text-muted">Credit Customer</small>
                        <div style="font-weight: 600;">{{ $sale->creditCustomer->name }}</div>
                    </div>
                @endif

                <hr>

                <div style="text-align: center; color: #64748b; font-size: 0.85rem;">
                    <div>Thank you for your purchase</div>
                    <div style="margin-top: 0.5rem;">Receipt printed: {{ $sale->created_at->format('M d, Y H:i:s') }}</div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-left me-1"></i>Back to Sales
            </a>
            <button onclick="window.print()" class="btn btn-primary w-100">
                <i class="bi bi-printer me-1"></i>Print
            </button>
        </div>
    </div>
</div>
@endsection
