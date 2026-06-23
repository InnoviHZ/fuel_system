@extends('layouts.app')

@section('title', 'Edit Credit Customer')
@section('topbar_title', 'Edit Credit Customer')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Edit Credit Customer - {{ $creditCustomer->name }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('credit-customers.update', $creditCustomer) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Customer Name *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $creditCustomer->name) }}" placeholder="Enter customer name" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $creditCustomer->phone) }}" placeholder="e.g., +1-800-555-0123" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Customer's physical address">{{ old('address', $creditCustomer->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="creditLimit" class="form-label">Credit Limit ($) *</label>
                        <input type="number" class="form-control @error('credit_limit') is-invalid @enderror" id="creditLimit" name="credit_limit" step="0.01" min="0" value="{{ old('credit_limit', $creditCustomer->credit_limit) }}" placeholder="0.00" required>
                        <small class="text-muted">Set to 0 to disable credit for this customer</small>
                        @error('credit_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="isActive" class="form-label">Status</label>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="isActive" name="is_active" value="1" {{ old('is_active', $creditCustomer->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">
                                    Active (Customer can make credit purchases)
                                </label>
                            </div>
                            @error('is_active')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded mb-4">
                        <h6>Current Balance Information</h6>
                        <p class="mb-2">
                            <strong>Current Balance:</strong>
                            <span class="badge badge-primary">${{ number_format($creditCustomer->current_balance, 2) }}</span>
                        </p>
                        <p class="mb-0">
                            <strong>Available Credit:</strong>
                            <span class="badge badge-success">${{ number_format(max(0, $creditCustomer->credit_limit - $creditCustomer->current_balance), 2) }}</span>
                        </p>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>Update Customer
                        </button>
                        <a href="{{ route('credit-customers.show', $creditCustomer) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
