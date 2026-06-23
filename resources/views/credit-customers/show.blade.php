@extends('layouts.app')

@section('title', 'Customer Profile')
@section('topbar_title', 'Customer Profile')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Customer Info Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">{{ $creditCustomer->name }}</h5>
                
                <div class="mb-3">
                    <small class="text-muted d-block">Phone</small>
                    <strong>{{ $creditCustomer->phone }}</strong>
                </div>

                @if($creditCustomer->address)
                    <div class="mb-3">
                        <small class="text-muted d-block">Address</small>
                        <small>{{ $creditCustomer->address }}</small>
                    </div>
                @endif

                <hr>

                <!-- Credit Status -->
                <div class="mb-3">
                    <small class="text-muted d-block mb-2">Credit Limit</small>
                    <strong class="fs-5">${{ number_format($creditCustomer->credit_limit, 2) }}</strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block mb-2">Current Balance</small>
                    <strong class="fs-5 text-danger">${{ number_format($creditCustomer->current_balance, 2) }}</strong>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block mb-2">Available Credit</small>
                    <strong class="fs-5 text-success">${{ number_format(max(0, $creditCustomer->credit_limit - $creditCustomer->current_balance), 2) }}</strong>
                </div>

                <!-- Usage Progress -->
                @php
                    $utilization = $creditCustomer->credit_limit > 0 ? ($creditCustomer->current_balance / $creditCustomer->credit_limit) * 100 : 0;
                    $progressColor = '#22c55e';
                    if ($utilization >= 100) {
                        $progressColor = '#ef4444';
                    } elseif ($utilization >= 80) {
                        $progressColor = '#f59e0b';
                    }
                @endphp
                <small class="text-muted d-block mb-2">Credit Usage</small>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar" style="width: {{ min($utilization, 100) }}%; background-color: {{ $progressColor }};">
                        @if($utilization > 10)
                            <small class="text-white fw-bold">{{ number_format($utilization, 0) }}%</small>
                        @endif
                    </div>
                </div>

                <hr>

                <!-- Status -->
                <div class="mb-3">
                    <small class="text-muted d-block">Account Status</small>
                    @if($creditCustomer->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </div>

                <!-- Actions -->
                <div class="d-grid gap-2">
                    <a href="{{ route('credit-customers.edit', $creditCustomer) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-pencil me-1"></i>Edit Details
                    </a>
                    <a href="{{ route('credit-customers.repayment', $creditCustomer) }}" class="btn btn-primary btn-sm" {{ $creditCustomer->current_balance <= 0 ? 'disabled' : '' }}>
                        <i class="bi bi-cash-coin me-1"></i>Record Repayment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Sales History -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title">Sales History</h6>
            </div>
            <div class="card-body p-0">
                @if($sales->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Fuel</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Amount</th>
                                    <th>Attendant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->sale_time->format('M d, Y H:i') }}</td>
                                        <td>{{ $sale->fuelProduct->name }}</td>
                                        <td>{{ number_format($sale->quantity_litres, 2) }}L</td>
                                        <td>${{ number_format($sale->unit_price, 2) }}</td>
                                        <td><strong>${{ number_format($sale->total_amount, 2) }}</strong></td>
                                        <td><small>{{ $sale->attendant->name }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">No sales found for this customer</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Repayment History -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Repayment History</h6>
            </div>
            <div class="card-body p-0">
                @if($repayments->count())
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Received By</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($repayments as $repayment)
                                    <tr>
                                        <td>{{ $repayment->repayment_date->format('M d, Y') }}</td>
                                        <td><strong>${{ number_format($repayment->amount, 2) }}</strong></td>
                                        <td>
                                            <span class="badge badge-info">
                                                @if($repayment->payment_method === 'cash')
                                                    💵 Cash
                                                @elseif($repayment->payment_method === 'pos')
                                                    🏪 POS
                                                @elseif($repayment->payment_method === 'bank_transfer')
                                                    🏦 Bank
                                                @else
                                                    {{ ucfirst($repayment->payment_method) }}
                                                @endif
                                            </span>
                                        </td>
                                        <td><small>{{ $repayment->receivedBy->name }}</small></td>
                                        <td><small class="text-muted">{{ $repayment->notes ?? '-' }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted">No repayments recorded yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('credit-customers.index') }}" class="btn btn-secondary">Back to Customers</a>
</div>
@endsection
