@extends('layouts.app')

@section('title', 'Stock Alerts')
@section('topbar_title', 'Stock Alerts')

@section('content')
<div class="mb-4">
    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Back to Inventory
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="card-title">Unresolved Stock Alerts</h6>
    </div>
    <div class="card-body p-0">
        @if($alerts->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Tank</th>
                            <th>Fuel Type</th>
                            <th>Alert Type</th>
                            <th>Stock Level at Alert</th>
                            <th>Current Stock</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alerts as $alert)
                            @php
                                $alertColor = $alert->isCritical() ? '#ef4444' : '#f59e0b';
                                $currentStock = $alert->tank->current_stock_litres;
                                $thresholdExceeded = $alert->isCritical() 
                                    ? $currentStock <= $alert->tank->critical_stock_threshold
                                    : $currentStock <= $alert->tank->low_stock_threshold;
                            @endphp
                            <tr>
                                <td><strong>{{ $alert->tank->name }}</strong></td>
                                <td><span style="background: #f1f5f9; padding: 0.25rem 0.75rem; border-radius: 0.4rem;">{{ $alert->tank->fuelProduct->code }}</span></td>
                                <td>
                                    <span class="badge" style="background: {{ $alertColor }}; color: white;">
                                        @if($alert->isCritical())
                                            <i class="bi bi-exclamation-circle me-1"></i>Critical Stock
                                        @else
                                            <i class="bi bi-exclamation-triangle me-1"></i>Low Stock
                                        @endif
                                    </span>
                                </td>
                                <td>{{ number_format($alert->stock_level_at_alert, 2) }} L</td>
                                <td>
                                    <strong>{{ number_format($currentStock, 2) }} L</strong>
                                    @if($currentStock > ($alert->isCritical() ? $alert->tank->critical_stock_threshold : $alert->tank->low_stock_threshold))
                                        <span class="badge badge-success" style="margin-left: 0.5rem;">Resolved</span>
                                    @endif
                                </td>
                                <td>
                                    @if($thresholdExceeded)
                                        <span class="badge badge-danger"><i class="bi bi-exclamation-triangle me-1"></i>Ongoing</span>
                                    @else
                                        <span class="badge badge-success"><i class="bi bi-check-circle me-1"></i>Resolved</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $alert->created_at->format('M d, Y H:i') }}</small></td>
                                <td class="text-end">
                                    <a href="{{ route('tanks.show', $alert->tank) }}" class="btn btn-icon btn-view" title="View Tank">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body border-top">
                {{ $alerts->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-check-circle"></i></div>
                <div class="empty-state-title">No active alerts</div>
                <div class="empty-state-text">All tanks are at healthy stock levels</div>
                <a href="{{ route('inventory.index') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-arrow-right me-1"></i>View Inventory
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
