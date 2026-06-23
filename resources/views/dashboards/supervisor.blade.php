@extends('layouts.app')

@section('title', 'Supervisor Dashboard')
@section('topbar_title', 'Supervisor Dashboard')

@section('content')
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted d-block">Today's Sales</small>
                        <h5 class="mb-2">{{ $todaySalesCount }}</h5>
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
                        <small class="text-muted d-block">Today's Litres</small>
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
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title">Pending Reconciliations</h6>
                <a href="{{ route('reconciliations.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                @if($pendingReconciliations->count())
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($pendingReconciliations as $recon)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $recon->shift->shift_date->format('M d') }}</small>
                                            <div>Shift #{{ $recon->shift->id }}</div>
                                        </td>
                                        <td>
                                            <small>{{ $recon->shift->shift_type }}</small>
                                            <div class="text-muted small">{{ $recon->reconciled_by_user->name }}</div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge badge-warning">{{ $recon->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted py-3 mb-0">All reconciliations are up to date</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title">Tank Stock Alerts</h6>
                <a href="{{ route('stock-alerts.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
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

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Tank Stock Levels</h6>
            </div>
            <div class="card-body p-0">
                @if($tankLevels->count())
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                @foreach($tankLevels as $tank)
                                    <tr>
                                        <td>
                                            <div>{{ $tank->name }}</div>
                                            <small class="text-muted">{{ $tank->fuelProduct->name }}</small>
                                        </td>
                                        <td class="text-end">
                                            <strong>{{ number_format($tank->current_stock_litres, 2) }}L</strong>
                                            <div class="small text-muted">Capacity: {{ $tank->capacity_litres }}L</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted py-3 mb-0">No tanks configured</p>
                @endif
            </div>
        </div>
    </div>

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
</div>
@endsection
