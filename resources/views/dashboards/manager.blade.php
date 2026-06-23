@extends('layouts.app')

@section('title', 'Manager Dashboard')
@section('topbar_title', 'Manager Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Top Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block">Today's Sales</small>
                            <h5 class="mb-2">{{ $todaySalesCount }} <span class="fs-6 text-muted">sales</span></h5>
                            <strong class="text-success">${{ number_format($todaySalesAmount, 2) }}</strong>
                        </div>
                        <i class="bi bi-receipt" style="font-size: 1.5rem; color: #3b82f6;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block">Today's Litres Sold</small>
                            <h5 class="mb-2">{{ number_format($todayLitresSold, 2) }}</h5>
                            <small class="text-muted">litres</small>
                        </div>
                        <i class="bi bi-droplet-fill" style="font-size: 1.5rem; color: #f59e0b;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block">Active Shifts</small>
                            <h5 class="mb-2">{{ $activeShifts }}</h5>
                            <small class="text-muted">open now</small>
                        </div>
                        <i class="bi bi-clock" style="font-size: 1.5rem; color: #10b981;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block">Stock Alerts</small>
                            <h5 class="mb-2">{{ $unresolvedAlerts }}</h5>
                            <small class="text-muted">unresolved</small>
                        </div>
                        <i class="bi bi-exclamation-triangle" style="font-size: 1.5rem; color: #ef4444;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block">Credit Outstanding</small>
                            <h5 class="mb-2">${{ number_format($creditBalance, 2) }}</h5>
                            <small class="text-muted">total balance</small>
                        </div>
                        <i class="bi bi-credit-card" style="font-size: 1.5rem; color: #8b5cf6;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block">Today's Deliveries</small>
                            <h5 class="mb-2">{{ $todayDeliveries }}</h5>
                            <small class="text-muted">deliveries</small>
                        </div>
                        <i class="bi bi-truck" style="font-size: 1.5rem; color: #06b6d4;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block">Active Users</small>
                            <h5 class="mb-2">{{ $activeUsers }}</h5>
                            <small class="text-muted">logged in today</small>
                        </div>
                        <i class="bi bi-people-fill" style="font-size: 1.5rem; color: #ec4899;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <small class="text-muted d-block">Monthly Revenue</small>
                            <h5 class="mb-2">${{ number_format($monthlyRevenue, 2) }}</h5>
                            <small class="text-muted">this month</small>
                        </div>
                        <i class="bi bi-graph-up" style="font-size: 1.5rem; color: #22c55e;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Daily Sales (Last 7 Days)</h6>
                </div>
                <div class="card-body">
                    <canvas id="dailySalesChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Sales by Fuel Type (Today)</h6>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <canvas id="fuelTypeChart" style="max-width: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Tank Stock Levels</h6>
                </div>
                <div class="card-body">
                    <canvas id="tankStockChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Monthly Revenue Trend (Last 6 Months)</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Recent Sales</h6>
                </div>
                <div class="card-body p-0">
                    @if($recentSales->count())
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    @foreach($recentSales as $sale)
                                        <tr>
                                            <td>
                                                <small class="text-muted">{{ $sale->sale_time->format('H:i') }}</small>
                                                <div>{{ $sale->attendant->name }}</div>
                                            </td>
                                            <td>
                                                <small>{{ $sale->pump->name }}</small>
                                                <div class="text-muted small">{{ $sale->fuelProduct->name }}</div>
                                            </td>
                                            <td class="text-end">
                                                <strong>${{ number_format($sale->total_amount, 2) }}</strong>
                                                <div class="small text-muted">{{ number_format($sale->quantity_litres, 2) }}L</div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-3 mb-0">No recent sales</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title">Recent Deliveries</h6>
                </div>
                <div class="card-body p-0">
                    @if($recentDeliveries->count())
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    @foreach($recentDeliveries as $delivery)
                                        <tr>
                                            <td>
                                                <small class="text-muted">{{ $delivery->delivery_date->format('M d') }}</small>
                                                <div>{{ $delivery->tank->name }}</div>
                                            </td>
                                            <td>
                                                <small>{{ number_format($delivery->quantity_litres, 2) }}L</small>
                                                <div class="text-muted small">{{ $delivery->supplier_name }}</div>
                                            </td>
                                            <td class="text-end">
                                                <strong>${{{ number_format($delivery->total_cost, 2) }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-3 mb-0">No recent deliveries</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Active Stock Alerts</h6>
                </div>
                <div class="card-body p-0">
                    @if($activeAlerts->count())
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <tbody>
                                    @foreach($activeAlerts as $alert)
                                        <tr>
                                            <td>
                                                <div>{{ $alert->tank->name }}</div>
                                                <small class="text-muted">{{ $alert->alert_type }}</small>
                                            </td>
                                            <td class="text-end">
                                                @if($alert->alert_type === 'critical_stock')
                                                    <span class="badge badge-danger">{{ number_format($alert->stock_level_at_alert, 2) }}L</span>
                                                @else
                                                    <span class="badge badge-warning">{{ number_format($alert->stock_level_at_alert, 2) }}L</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted py-3 mb-0">No active alerts</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Sales Chart
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    new Chart(dailySalesCtx, {
        type: 'line',
        data: {!! $dailySalesChart !!},
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toFixed(0);
                        }
                    }
                }
            }
        }
    });

    // Fuel Type Chart
    const fuelTypeCtx = document.getElementById('fuelTypeChart').getContext('2d');
    new Chart(fuelTypeCtx, {
        type: 'doughnut',
        data: {!! $fuelTypeChart !!},
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
            }
        }
    });

    // Tank Stock Chart
    const tankStockCtx = document.getElementById('tankStockChart').getContext('2d');
    new Chart(tankStockCtx, {
        type: 'bar',
        data: {!! $tankStockChart !!},
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                x: {
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(0) + 'L';
                        }
                    }
                }
            }
        }
    });

    // Monthly Revenue Chart
    const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
    new Chart(monthlyRevenueCtx, {
        type: 'bar',
        data: {!! $monthlyRevenueChart !!},
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toFixed(0);
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection
