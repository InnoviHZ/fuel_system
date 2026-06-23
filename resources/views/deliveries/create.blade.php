@extends('layouts.app')

@section('title', 'Record Fuel Delivery')
@section('topbar_title', 'Record Fuel Delivery')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Record New Fuel Delivery</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('deliveries.store') }}" method="POST" id="deliveryForm">
                    @csrf

                    <div class="mb-3">
                        <label for="tankId" class="form-label">Tank *</label>
                        <select class="form-select @error('tank_id') is-invalid @enderror" id="tankId" name="tank_id" required>
                            <option value="">-- Select Tank --</option>
                            @foreach($tanks as $tank)
                                <option value="{{ $tank->id }}" data-fuel-id="{{ $tank->fuel_product_id }}" {{ old('tank_id') == $tank->id ? 'selected' : '' }}>
                                    {{ $tank->name }} ({{ $tank->fuelProduct->name }}) - Current: {{ number_format($tank->current_stock_litres, 2) }}L / {{ number_format($tank->capacity_litres, 2) }}L
                                </option>
                            @endforeach
                        </select>
                        @error('tank_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fuelProductId" class="form-label">Fuel Product *</label>
                        <input type="text" class="form-control" id="fuelProductName" placeholder="Auto-filled from tank selection" readonly>
                        <input type="hidden" id="fuelProductId" name="fuel_product_id" value="{{ old('fuel_product_id') }}">
                        @error('fuel_product_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplierName" class="form-label">Supplier Name *</label>
                                <input type="text" class="form-control @error('supplier_name') is-invalid @enderror" id="supplierName" name="supplier_name" value="{{ old('supplier_name') }}" placeholder="e.g., Petro Ltd" required>
                                @error('supplier_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="waybillNumber" class="form-label">Waybill Number *</label>
                                <input type="text" class="form-control @error('waybill_number') is-invalid @enderror" id="waybillNumber" name="waybill_number" value="{{ old('waybill_number') }}" placeholder="e.g., WB-2024-001" required>
                                @error('waybill_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantityLitres" class="form-label">Quantity (Litres) *</label>
                                <input type="number" class="form-control @error('quantity_litres') is-invalid @enderror" id="quantityLitres" name="quantity_litres" step="0.01" min="0" value="{{ old('quantity_litres') }}" placeholder="0.00" required>
                                @error('quantity_litres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="unitCost" class="form-label">Unit Cost ($/L) *</label>
                                <input type="number" class="form-control @error('unit_cost') is-invalid @enderror" id="unitCost" name="unit_cost" step="0.01" min="0" value="{{ old('unit_cost') }}" placeholder="0.00" required>
                                @error('unit_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="totalCost" class="form-label">Total Cost</label>
                        <input type="text" class="form-control" id="totalCost" value="$0.00" readonly>
                    </div>

                    <hr>

                    <div class="bg-light p-3 rounded mb-3">
                        <h6>Stock Summary</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <small class="text-muted d-block">Tank Capacity</small>
                                    <strong id="tankCapacity">-</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <small class="text-muted d-block">Stock Before</small>
                                    <strong id="stockBefore">-</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <small class="text-muted d-block">Stock After</small>
                                    <strong id="stockAfter">-</strong>
                                </div>
                            </div>
                        </div>
                        <div id="capacityWarning" class="alert alert-warning mt-2 mb-0" style="display: none;">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <span id="warningText"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deliveryDate" class="form-label">Delivery Date *</label>
                        <input type="date" class="form-control @error('delivery_date') is-invalid @enderror" id="deliveryDate" name="delivery_date" value="{{ old('delivery_date', today()) }}" required>
                        @error('delivery_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3" placeholder="Any additional notes about this delivery...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="bi bi-check-circle me-1"></i>Record Delivery
                        </button>
                        <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tankIdSelect = document.getElementById('tankId');
    const fuelProductNameInput = document.getElementById('fuelProductName');
    const fuelProductIdInput = document.getElementById('fuelProductId');
    const quantityInput = document.getElementById('quantityLitres');
    const unitCostInput = document.getElementById('unitCost');
    const totalCostInput = document.getElementById('totalCost');
    const stockBeforeDisplay = document.getElementById('stockBefore');
    const stockAfterDisplay = document.getElementById('stockAfter');
    const tankCapacityDisplay = document.getElementById('tankCapacity');
    const capacityWarning = document.getElementById('capacityWarning');
    const warningText = document.getElementById('warningText');
    const submitBtn = document.getElementById('submitBtn');

    function loadTankInfo() {
        const selectedOption = tankIdSelect.options[tankIdSelect.selectedIndex];
        if (selectedOption.value) {
            fetch(`/api/tanks/${selectedOption.value}/info`)
                .then(response => response.json())
                .then(data => {
                    fuelProductNameInput.value = data.fuel_product_name + ' (' + data.fuel_code + ')';
                    fuelProductIdInput.value = selectedOption.getAttribute('data-fuel-id');
                    stockBeforeDisplay.textContent = number_format(data.current_stock, 2) + 'L';
                    tankCapacityDisplay.textContent = number_format(data.capacity, 2) + 'L';
                    calculateStockAfter();
                })
                .catch(error => {
                    console.error('Error loading tank info:', error);
                    alert('Error loading tank information');
                });
        } else {
            fuelProductNameInput.value = '';
            fuelProductIdInput.value = '';
            stockBeforeDisplay.textContent = '-';
            stockAfterDisplay.textContent = '-';
            tankCapacityDisplay.textContent = '-';
            capacityWarning.style.display = 'none';
        }
    }

    function calculateTotalCost() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitCost = parseFloat(unitCostInput.value) || 0;
        const total = quantity * unitCost;
        totalCostInput.value = '$' + total.toFixed(2);
        calculateStockAfter();
    }

    function calculateStockAfter() {
        const stockBefore = parseFloat(stockBeforeDisplay.textContent) || 0;
        const quantity = parseFloat(quantityInput.value) || 0;
        const stockAfter = stockBefore + quantity;
        const capacity = parseFloat(tankCapacityDisplay.textContent) || 0;

        stockAfterDisplay.textContent = number_format(stockAfter, 2) + 'L';

        // Check if exceeds capacity
        if (stockAfter > capacity && capacity > 0) {
            capacityWarning.style.display = 'block';
            warningText.textContent = `Stock after delivery (${number_format(stockAfter, 2)}L) will exceed tank capacity (${number_format(capacity, 2)}L)`;
            submitBtn.disabled = true;
        } else {
            capacityWarning.style.display = 'none';
            submitBtn.disabled = false;
        }
    }

    function number_format(number, decimals) {
        return parseFloat(number).toFixed(decimals);
    }

    tankIdSelect.addEventListener('change', loadTankInfo);
    quantityInput.addEventListener('input', calculateTotalCost);
    unitCostInput.addEventListener('input', calculateTotalCost);

    // Load tank info on page load if tank is pre-selected
    if (tankIdSelect.value) {
        loadTankInfo();
    }
});
</script>
@endsection
