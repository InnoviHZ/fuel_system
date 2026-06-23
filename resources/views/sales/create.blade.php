@extends('layouts.app')

@section('title', 'Record Sale')
@section('topbar_title', 'Record New Sale')

@section('content')
<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Sale Recording Form</h6>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="alert alert-info mb-4" role="alert">
                    <div style="font-weight: 600; margin-bottom: 0.5rem;">Active Shift: {{ ucfirst($activeShift->shift_type) }} - {{ $activeShift->shift_date->format('M d, Y') }}</div>
                    <small>Opened by: {{ $activeShift->openedByUser->name }}</small>
                </div>

                <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="pump_id" class="form-label">Select Pump</label>
                        <select class="form-select form-select-lg @error('pump_id') is-invalid @enderror" 
                                id="pump_id" name="pump_id" required onchange="loadPumpInfo()">
                            <option value="">-- Select Assigned Pump --</option>
                            @foreach($assignedPumps as $pump)
                                <option value="{{ $pump->id }}" data-tank-id="{{ $pump->tank->id }}">
                                    {{ $pump->name }} - {{ $pump->tank->fuelProduct->name }} ({{ $pump->tank->fuelProduct->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('pump_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="fuel_type" class="form-label">Fuel Type</label>
                                <input type="text" class="form-control" id="fuel_type" readonly placeholder="Select pump">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="unit_price" class="form-label">Unit Price (₦)</label>
                                <input type="text" class="form-control" id="unit_price" readonly placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="quantity_litres" class="form-label">Quantity (Litres)</label>
                        <input type="number" step="0.01" class="form-control form-control-lg @error('quantity_litres') is-invalid @enderror" 
                               id="quantity_litres" name="quantity_litres" placeholder="0.00" required 
                               oninput="calculateTotal()">
                        <small class="text-muted">Tank stock: <span id="tank_stock">-</span> L</small>
                        @error('quantity_litres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card" style="background: #f1f5f9; border: none; margin-bottom: 1.5rem;">
                        <div class="card-body">
                            <div style="font-size: 0.75rem; color: #64748b; margin-bottom: 0.5rem;">TOTAL AMOUNT</div>
                            <div style="font-size: 2rem; font-weight: 700; color: #3b82f6;">₦<span id="total_amount">0.00</span></div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select class="form-select form-select-lg @error('payment_method') is-invalid @enderror" 
                                id="payment_method" name="payment_method" required onchange="toggleCreditCustomer()">
                            <option value="">-- Select Payment Method --</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="pos" {{ old('payment_method') === 'pos' ? 'selected' : '' }}>POS/Card</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="credit" {{ old('payment_method') === 'credit' ? 'selected' : '' }}>Credit Sale</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4" id="creditCustomerDiv" style="display: none;">
                        <label for="credit_customer_id" class="form-label">Credit Customer</label>
                        <select class="form-select form-select-lg @error('credit_customer_id') is-invalid @enderror" 
                                id="credit_customer_id" name="credit_customer_id">
                            <option value="">-- Select Customer --</option>
                            @foreach($creditCustomers as $customer)
                                @php
                                    $availableCredit = $customer->credit_limit - $customer->current_balance;
                                @endphp
                                <option value="{{ $customer->id }}" data-available="{{ $availableCredit }}" {{ old('credit_customer_id') == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }} - Balance: ${{ number_format($customer->current_balance, 2) }} / Limit: ${{ number_format($customer->credit_limit, 2) }}
                                </option>
                            @endforeach
                        </select>
                        <small id="creditWarning" class="text-danger" style="display: none;"></small>
                        @error('credit_customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-check-circle me-2"></i>Record Sale
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadPumpInfo() {
    const pumpId = document.getElementById('pump_id').value;
    if (!pumpId) {
        document.getElementById('fuel_type').value = '';
        document.getElementById('unit_price').value = '';
        document.getElementById('tank_stock').textContent = '-';
        return;
    }

    fetch(`/api/pumps/${pumpId}/info`)
        .then(response => {
            if (!response.ok) throw new Error('Failed to load pump info');
            return response.json();
        })
        .then(data => {
            document.getElementById('fuel_type').value = data.fuel_type + ' (' + data.fuel_code + ')';
            document.getElementById('unit_price').value = parseFloat(data.unit_price).toFixed(2);
            document.getElementById('tank_stock').textContent = parseFloat(data.tank_stock).toFixed(2);
            calculateTotal();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading pump information');
        });
}

function calculateTotal() {
    const quantity = parseFloat(document.getElementById('quantity_litres').value) || 0;
    const unitPrice = parseFloat(document.getElementById('unit_price').value) || 0;
    const total = quantity * unitPrice;
    document.getElementById('total_amount').textContent = total.toFixed(2);
}

function toggleCreditCustomer() {
    const method = document.getElementById('payment_method').value;
    document.getElementById('creditCustomerDiv').style.display = method === 'credit' ? 'block' : 'none';
}

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    toggleCreditCustomer();
});
</script>
@endsection
