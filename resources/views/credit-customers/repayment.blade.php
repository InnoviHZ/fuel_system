@extends('layouts.app')

@section('title', 'Record Repayment')
@section('topbar_title', 'Record Repayment')

@section('content')
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Record Credit Repayment</h6>
            </div>
            <div class="card-body">
                <!-- Customer Information -->
                <div class="bg-light p-3 rounded mb-4">
                    <h6>Customer Information</h6>
                    <p class="mb-2">
                        <strong>Name:</strong>
                        {{ $creditCustomer->name }}
                    </p>
                    <p class="mb-2">
                        <strong>Phone:</strong>
                        {{ $creditCustomer->phone }}
                    </p>
                    <p class="mb-0">
                        <strong>Current Balance:</strong>
                        <span class="badge badge-danger fs-6">${{ number_format($creditCustomer->current_balance, 2) }}</span>
                    </p>
                </div>

                <form action="{{ route('credit-customers.repayment', $creditCustomer) }}" method="POST">
                    @csrf

                    <input type="hidden" name="credit_customer_id" value="{{ $creditCustomer->id }}">

                    <div class="mb-3">
                        <label for="amount" class="form-label">Repayment Amount ($) *</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" step="0.01" min="0.01" max="{{ $creditCustomer->current_balance }}" value="{{ old('amount') }}" placeholder="0.00" required>
                        <small class="text-muted">Maximum: ${{ number_format($creditCustomer->current_balance, 2) }}</small>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="paymentMethod" class="form-label">Payment Method *</label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" id="paymentMethod" name="payment_method" required>
                            <option value="">-- Select Payment Method --</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>💵 Cash</option>
                            <option value="pos" {{ old('payment_method') === 'pos' ? 'selected' : '' }}>🏪 POS / Card</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="repaymentDate" class="form-label">Repayment Date *</label>
                        <input type="date" class="form-control @error('repayment_date') is-invalid @enderror" id="repaymentDate" name="repayment_date" value="{{ old('repayment_date', today()) }}" required>
                        @error('repayment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Any additional notes about this repayment...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="bg-light p-3 rounded mb-4">
                        <small class="text-muted d-block mb-2">Remaining Balance After Repayment</small>
                        <strong class="fs-5" id="remainingBalance">${{ number_format($creditCustomer->current_balance, 2) }}</strong>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Record Repayment
                        </button>
                        <a href="{{ route('credit-customers.show', $creditCustomer) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const remainingBalance = document.getElementById('remainingBalance');
    const currentBalance = parseFloat('{{ $creditCustomer->current_balance }}');

    function updateRemainingBalance() {
        const amount = parseFloat(amountInput.value) || 0;
        const remaining = currentBalance - amount;
        remainingBalance.textContent = '$' + remaining.toFixed(2);
    }

    amountInput.addEventListener('input', updateRemainingBalance);
});
</script>
@endsection
