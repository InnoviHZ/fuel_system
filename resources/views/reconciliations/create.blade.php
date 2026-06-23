@extends('layouts.app')

@section('title', 'Create Reconciliation')
@section('topbar_title', 'Create Reconciliation')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Reconciliation for {{ $shift->shift_date->format('M d, Y') }} - {{ ucfirst($shift->shift_type) }} Shift</h6>
            </div>
            <div class="card-body">
                <!-- Shift Summary -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Opened By</small>
                            <strong>{{ $shift->openedBy->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Closed By</small>
                            <strong>{{ $shift->closedBy->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Total Sales</small>
                            <strong>{{ $sales->count() }} transactions</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Litres Sold</small>
                            <strong>{{ number_format($totalLitresSold, 2) }}L</strong>
                        </div>
                    </div>
                </div>

                <!-- Sales by Pump -->
                <h6 class="mb-3">Sales Breakdown by Pump</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Pump</th>
                                <th>Transactions</th>
                                <th>Litres</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesByPump as $pumpName => $pumpSales)
                                <tr>
                                    <td>{{ $pumpName }}</td>
                                    <td>{{ $pumpSales->count() }}</td>
                                    <td>{{ number_format($pumpSales->sum('quantity_litres'), 2) }}L</td>
                                    <td>${{ number_format($pumpSales->sum('total_amount'), 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Payment Method Summary -->
                <h6 class="mb-3">Sales by Payment Method</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Payment Method</th>
                                <th>Count</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(['cash' => 'Cash', 'pos' => 'POS', 'bank_transfer' => 'Bank Transfer', 'credit' => 'Credit'] as $method => $label)
                                @php
                                    $methodSales = $salesByPaymentMethod->get($method, collect());
                                @endphp
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ $methodSales->count() }}</td>
                                    <td>${{ number_format($methodSales->sum('total_amount'), 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Reconciliation Form -->
                <form action="{{ route('reconciliations.store') }}" method="POST" id="reconciliationForm">
                    @csrf

                    <input type="hidden" name="shift_id" value="{{ $shift->id }}">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expectedCash" class="form-label">Expected Cash (from sales)</label>
                                <input type="text" class="form-control" id="expectedCash" value="${{ number_format($expectedCash, 2) }}" readonly>
                                <input type="hidden" id="expectedCashValue" value="{{ $expectedCash }}">
                            </div>

                            <div class="mb-3">
                                <label for="actualCash" class="form-label">Actual Cash Collected *</label>
                                <input type="number" class="form-control @error('actual_cash') is-invalid @enderror" id="actualCash" name="actual_cash" step="0.01" min="0" value="{{ old('actual_cash') }}" required>
                                @error('actual_cash')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="actualPos" class="form-label">Actual POS Amount *</label>
                                <input type="number" class="form-control @error('actual_pos') is-invalid @enderror" id="actualPos" name="actual_pos" step="0.01" min="0" value="{{ old('actual_pos') }}" required>
                                @error('actual_pos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="actualBankTransfer" class="form-label">Actual Bank Transfer *</label>
                                <input type="number" class="form-control @error('actual_bank_transfer') is-invalid @enderror" id="actualBankTransfer" name="actual_bank_transfer" step="0.01" min="0" value="{{ old('actual_bank_transfer') }}" required>
                                @error('actual_bank_transfer')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="bg-light p-3 rounded mb-3">
                                <h6>Summary</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Total Collected:</span>
                                    <strong id="totalCollected">$0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Expected Cash:</span>
                                    <strong>${{ number_format($expectedCash, 2) }}</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span>Difference:</span>
                                    <strong id="difference" class="text-success">$0.00</strong>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="supervisorNote" class="form-label">Supervisor Note</label>
                                <textarea class="form-control @error('supervisor_note') is-invalid @enderror" id="supervisorNote" name="supervisor_note" rows="4" placeholder="Any discrepancies or notes about this reconciliation...">{{ old('supervisor_note') }}</textarea>
                                @error('supervisor_note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Complete Reconciliation
                        </button>
                        <a href="{{ route('reconciliations.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const actualCashInput = document.getElementById('actualCash');
    const actualPosInput = document.getElementById('actualPos');
    const actualBankTransferInput = document.getElementById('actualBankTransfer');
    const expectedCashValue = parseFloat(document.getElementById('expectedCashValue').value);
    const totalCollectedDisplay = document.getElementById('totalCollected');
    const differenceDisplay = document.getElementById('difference');

    function calculateTotals() {
        const actualCash = parseFloat(actualCashInput.value) || 0;
        const actualPos = parseFloat(actualPosInput.value) || 0;
        const actualBankTransfer = parseFloat(actualBankTransferInput.value) || 0;
        const totalCollected = actualCash + actualPos + actualBankTransfer;
        const difference = totalCollected - expectedCashValue;

        totalCollectedDisplay.textContent = '$' + totalCollected.toFixed(2);
        
        if (difference >= 0) {
            differenceDisplay.textContent = '$' + difference.toFixed(2);
            differenceDisplay.className = 'text-success';
        } else {
            differenceDisplay.textContent = '$' + difference.toFixed(2);
            differenceDisplay.className = 'text-danger';
        }
    }

    actualCashInput.addEventListener('input', calculateTotals);
    actualPosInput.addEventListener('input', calculateTotals);
    actualBankTransferInput.addEventListener('input', calculateTotals);
});
</script>
@endsection
