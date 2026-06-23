@extends('layouts.app')

@section('title', 'Delivery Report')
@section('topbar_title', 'Delivery Report')

@section('content')
<!-- Filter Card -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>Filter Deliveries
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.deliveries') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from', \Carbon\Carbon::now()->startOfMonth()->toDateString()) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to', \Carbon\Carbon::now()->toDateString()) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tank</label>
                        <select name="tank_id" class="form-control form-control-sm">
                            <option value="">All Tanks</option>
                            @foreach(\App\Models\Tank::where('is_active', true)->get() as $tank)
                                <option value="{{ $tank->id }}" {{ request('tank_id') == $tank->id ? 'selected' : '' }}>
                                    {{ $tank->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.deliveries') }}" class="btn btn-outline-secondary btn-sm">
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
                        <small class="text-muted d-block fw-600">Total Deliveries</small>
                        <h4 class="mb-0 mt-1">{{ $summary['delivery_count'] ?? 0 }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-truck"></i>
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
                        <h4 class="mb-0 mt-1">{{ number_format($summary['total_litres'] ?? 0, 2) }} L</h4>
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
                        <small class="text-muted d-block fw-600">Total Cost</small>
                        <h4 class="mb-0 mt-1">₦{{ number_format($summary['total_cost'] ?? 0, 2) }}</h4>
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
                        <small class="text-muted d-block fw-600">Avg Cost per Litre</small>
                        <h4 class="mb-0 mt-1">₦{{ number_format($summary['avg_cost_per_litre'] ?? 0, 3) }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delivery Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Delivery Details
                </h5>
                <a href="{{ route('reports.export', array_merge(request()->all(), ['type' => 'delivery'])) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-file-pdf me-1"></i>Export PDF
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Tank</th>
                                <th>Fuel Type</th>
                                <th>Quantity (L)</th>
                                <th>Cost per Litre</th>
                                <th>Total Cost</th>
                                <th>Supplier</th>
                                <th>Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($deliveries as $delivery)
                                <tr>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($delivery->delivery_date)->format('M d, Y') }}</small>
                                    </td>
                                    <td>{{ $delivery->tank->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $delivery->tank->fuelProduct->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ number_format($delivery->quantity_litres ?? 0, 2) }} L</td>
                                    <td>₦{{ number_format($delivery->cost_per_litre ?? 0, 2) }}</td>
                                    <td class="fw-600">₦{{ number_format($delivery->total_cost ?? 0, 2) }}</td>
                                    <td>{{ $delivery->supplier_name ?? 'N/A' }}</td>
                                    <td>
                                        <small>{{ $delivery->reference_number ?? '-' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No deliveries found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-600">
                                <td colspan="3" class="text-end">TOTALS:</td>
                                <td>{{ number_format($deliveries->sum('quantity_litres'), 2) }} L</td>
                                <td>-</td>
                                <td>₦{{ number_format($deliveries->sum('total_cost'), 2) }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                @if($deliveries->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing {{ $deliveries->firstItem() }} to {{ $deliveries->lastItem() }} of {{ $deliveries->total() }}
                        </div>
                        <nav>
                            {{ $deliveries->appends(request()->query())->links() }}
                        </nav>
                    </div>
                @endif
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
