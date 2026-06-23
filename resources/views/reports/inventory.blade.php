@extends('layouts.app')

@section('title', 'Inventory Report')
@section('topbar_title', 'Inventory Report')

@section('content')
<!-- Filter Card -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>Filter Inventory
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.inventory') }}" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tank</label>
                        <select name="tank_id" class="form-control form-control-sm">
                            <option value="">All Tanks</option>
                            @foreach(\App\Models\Tank::where('is_active', true)->get() as $tank)
                                <option value="{{ $tank->id }}" {{ request('tank_id') == $tank->id ? 'selected' : '' }}>
                                    {{ $tank->name }} - {{ $tank->fuelProduct->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.inventory') }}" class="btn btn-outline-secondary btn-sm">
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
    <div class="col-md-4">
        <div class="card border-left-primary">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block fw-600">Total Current Stock</small>
                        <h4 class="mb-0 mt-1">{{ number_format($totalStock ?? 0, 2) }} L</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-droplet"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-left-success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block fw-600">Deliveries This Month</small>
                        <h4 class="mb-0 mt-1">{{ number_format($deliveriesThisMonth ?? 0, 2) }} L</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(34, 197, 94, 0.1); color: #22c55e;">
                        <i class="bi bi-truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-left-warning">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block fw-600">Sales This Month</small>
                        <h4 class="mb-0 mt-1">{{ number_format($salesThisMonth ?? 0, 2) }} L</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="bi bi-graph-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Tank Inventory Analysis
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tank</th>
                                <th>Fuel Type</th>
                                <th>Current Stock</th>
                                <th>Capacity</th>
                                <th>% Full</th>
                                <th>This Month Deliveries</th>
                                <th>This Month Sales</th>
                                <th>Net Movement</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventory as $item)
                                <tr>
                                    <td><strong>{{ $item['tank']->name }}</strong></td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item['tank']->fuelProduct->name ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ number_format($item['tank']->current_stock_litres ?? 0, 2) }} L</td>
                                    <td>{{ number_format($item['tank']->capacity_litres ?? 0, 2) }} L</td>
                                    <td>
                                        @php
                                            $capacity = $item['tank']->capacity_litres ?? 1;
                                            $current = $item['tank']->current_stock_litres ?? 0;
                                            $percentage = ($current / $capacity) * 100;
                                            $percentage = min(100, max(0, $percentage));
                                        @endphp
                                        <div class="progress" style="height: 20px; width: 100px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $percentage > 80 ? '#22c55e' : ($percentage > 50 ? '#f59e0b' : '#ef4444') }};">
                                                {{ round($percentage) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item['deliveries_this_month'] ?? 0, 2) }} L</td>
                                    <td>{{ number_format($item['sales_this_month'] ?? 0, 2) }} L</td>
                                    <td>
                                        @php
                                            $net = ($item['deliveries_this_month'] ?? 0) - ($item['sales_this_month'] ?? 0);
                                        @endphp
                                        <span class="{{ $net >= 0 ? 'text-success' : 'text-danger' }} fw-600">
                                            {{ $net >= 0 ? '+' : '' }}{{ number_format($net, 2) }} L
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No inventory data found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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
