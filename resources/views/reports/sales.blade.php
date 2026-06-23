@extends('layouts.app')

@section('title', 'Sales Report')
@section('topbar_title', 'Sales Report')

@section('content')
<!-- Filter Card -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>Filter Sales
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.sales') }}" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from', \Carbon\Carbon::now()->startOfMonth()->toDateString()) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to', \Carbon\Carbon::now()->toDateString()) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Fuel Product</label>
                        <select name="fuel_product_id" class="form-control form-control-sm">
                            <option value="">All Products</option>
                            @foreach($fuels as $fuel)
                                <option value="{{ $fuel->id }}" {{ request('fuel_product_id') == $fuel->id ? 'selected' : '' }}>
                                    {{ $fuel->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-control form-control-sm">
                            <option value="">All Methods</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="pos" {{ request('payment_method') == 'pos' ? 'selected' : '' }}>POS</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="credit" {{ request('payment_method') == 'credit' ? 'selected' : '' }}>Credit</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Attendant</label>
                        <select name="attendant_id" class="form-control form-control-sm">
                            <option value="">All Attendants</option>
                            @foreach($attendants as $attendant)
                                <option value="{{ $attendant->id }}" {{ request('attendant_id') == $attendant->id ? 'selected' : '' }}>
                                    {{ $attendant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.sales') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-left-primary">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block fw-600">Total Sales</small>
                        <h4 class="mb-0 mt-1">{{ $summary['total_sales'] }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block fw-600">Total Litres</small>
                        <h4 class="mb-0 mt-1">{{ number_format($summary['total_litres'] ?? 0, 2) }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(34, 197, 94, 0.1); color: #22c55e;">
                        <i class="bi bi-droplet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-warning">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block fw-600">Total Amount</small>
                        <h4 class="mb-0 mt-1">₦{{ number_format($summary['total_amount'] ?? 0, 2) }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-left-danger">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block fw-600">Cash Sales</small>
                        <h4 class="mb-0 mt-1">₦{{ number_format($summary['cash_sales'] ?? 0, 2) }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Breakdown -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Payment Method Breakdown
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <h6 class="text-muted">POS Sales</h6>
                            <h4>₦{{ number_format($summary['pos_sales'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <h6 class="text-muted">Bank Transfers</h6>
                            <h4>₦{{ number_format($summary['bank_sales'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <h6 class="text-muted">Credit Sales</h6>
                            <h4>₦{{ number_format($summary['credit_sales'] ?? 0, 2) }}</h4>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <h6 class="text-muted">Average per Sale</h6>
                            <h4>₦{{ number_format($summary['total_sales'] > 0 ? $summary['total_amount'] / $summary['total_sales'] : 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Sales Details
                </h5>
                <a href="{{ route('reports.export', array_merge(request()->all(), ['type' => 'sales'])) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-file-pdf me-1"></i>Export PDF
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Attendant</th>
                                <th>Pump</th>
                                <th>Fuel Type</th>
                                <th>Quantity (L)</th>
                                <th>Price/L</th>
                                <th>Total Amount</th>
                                <th>Payment</th>
                                <th>Shift</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                                <tr>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($sale->sale_time)->format('M d, Y H:i') }}</small>
                                    </td>
                                    <td>{{ $sale->attendant->name ?? 'N/A' }}</td>
                                    <td>{{ $sale->pump->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $sale->fuelProduct->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ number_format($sale->quantity_litres, 2) }}</td>
                                    <td>₦{{ number_format($sale->price_per_litre, 2) }}</td>
                                    <td class="fw-600">₦{{ number_format($sale->total_amount, 2) }}</td>
                                    <td>
                                        @if($sale->payment_method == 'cash')
                                            <span class="badge bg-success">Cash</span>
                                        @elseif($sale->payment_method == 'pos')
                                            <span class="badge bg-info">POS</span>
                                        @elseif($sale->payment_method == 'bank_transfer')
                                            <span class="badge bg-warning text-dark">Bank</span>
                                        @elseif($sale->payment_method == 'credit')
                                            <span class="badge bg-secondary">Credit</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $sale->shift->shift_type ?? 'N/A' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No sales found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-600">
                                <td colspan="4" class="text-end">TOTALS:</td>
                                <td>{{ number_format($sales->sum('quantity_litres'), 2) }}</td>
                                <td>-</td>
                                <td>₦{{ number_format($sales->sum('total_amount'), 2) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Showing {{ $sales->firstItem() ?? 0 }} to {{ $sales->lastItem() ?? 0 }} of {{ $sales->total() }} sales
                    </div>
                    <nav>
                        {{ $sales->appends(request()->query())->links() }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 4px solid #3b82f6;
    }
    .border-left-success {
        border-left: 4px solid #22c55e;
    }
    .border-left-warning {
        border-left: 4px solid #f59e0b;
    }
    .border-left-danger {
        border-left: 4px solid #8b5cf6;
    }
    .stat-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .fw-600 {
        font-weight: 600;
    }
</style>
@endsection
