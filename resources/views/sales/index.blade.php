@extends('layouts.app')

@section('title', 'Sales')
@section('topbar_title', 'Sales Records')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title">Sales</h6>
        @if(Auth::user()->hasRole('attendant'))
            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Record Sale
            </a>
        @endif
    </div>
    <div class="card-body p-0">
        @if($sales->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Shift</th>
                            <th>Pump</th>
                            <th>Fuel Type</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Attendant</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td><small class="text-muted">{{ $sale->sale_time->format('M d, Y H:i') }}</small></td>
                                <td>
                                    <span class="badge" style="background: {{ $sale->shift->shift_type === 'morning' ? '#3b82f6' : '#8b5cf6' }}; color: white;">
                                        {{ ucfirst($sale->shift->shift_type) }}
                                    </span>
                                </td>
                                <td><strong>{{ $sale->pump->name }}</strong></td>
                                <td><span style="background: #f1f5f9; padding: 0.25rem 0.5rem; border-radius: 0.4rem;">{{ $sale->fuelProduct->code }}</span></td>
                                <td>{{ number_format($sale->quantity_litres, 2) }} L</td>
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
                                <td class="text-end">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-icon btn-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body border-top">
                {{ $sales->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                <div class="empty-state-title">No sales recorded</div>
                <div class="empty-state-text">Start recording sales from the dashboard</div>
                @if(Auth::user()->hasRole('attendant'))
                    <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Record Sale
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
