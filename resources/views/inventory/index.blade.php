@extends('layouts.app')

@section('title', 'Inventory')
@section('topbar_title', 'Inventory Management')

@section('content')
<div class="mb-4">
    <a href="{{ route('inventory.alerts') }}" class="btn btn-warning">
        <i class="bi bi-exclamation-triangle me-1"></i>View Alerts
    </a>
</div>

<div class="row">
    @forelse($tanks as $tank)
        @php
            $stockPercentage = ($tank->current_stock_litres / $tank->capacity_litres) * 100;
            if ($stockPercentage >= 50) {
                $statusColor = '#22c55e';
                $statusLabel = 'Healthy';
            } elseif ($stockPercentage >= 25) {
                $statusColor = '#f59e0b';
                $statusLabel = 'Low Stock';
            } else {
                $statusColor = '#ef4444';
                $statusLabel = 'Critical';
            }
        @endphp
        <div class="col-lg-4 mb-4">
            <div class="card" style="position: relative;">
                @if($tank->activeAlerts->count())
                    <div style="position: absolute; top: 1rem; right: 1rem; background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: 600;">
                        ⚠️ {{ $tank->activeAlerts->count() }} Alert{{ $tank->activeAlerts->count() > 1 ? 's' : '' }}
                    </div>
                @endif

                <div class="card-body">
                    <div class="mb-3">
                        <h5 class="card-title">{{ $tank->name }}</h5>
                        <p class="text-muted mb-0">
                            <span style="background: #f1f5f9; padding: 0.25rem 0.75rem; border-radius: 0.4rem;">
                                {{ $tank->fuelProduct->code }} - {{ $tank->fuelProduct->name }}
                            </span>
                        </p>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">STOCK LEVEL</span>
                            <span style="font-size: 1rem; font-weight: 700; color: {{ $statusColor }};">{{ number_format($stockPercentage, 1) }}%</span>
                        </div>
                        <div style="background: #e2e8f0; border-radius: 0.4rem; height: 24px; overflow: hidden;">
                            <div style="background: {{ $statusColor }}; height: 100%; width: {{ $stockPercentage }}%; transition: width 0.3s ease;"></div>
                        </div>
                    </div>

                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 0.6rem; margin-bottom: 1rem;">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Current</small>
                                <div style="font-weight: 600;">{{ number_format($tank->current_stock_litres, 2) }} L</div>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">Capacity</small>
                                <div style="font-weight: 600;">{{ number_format($tank->capacity_litres, 2) }} L</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div style="font-size: 0.75rem; color: #64748b; font-weight: 500; margin-bottom: 0.5rem;">THRESHOLDS</div>
                        <div style="background: #fff3cd; padding: 0.75rem; border-radius: 0.4rem; font-size: 0.85rem; margin-bottom: 0.5rem;">
                            <strong>Low:</strong> {{ number_format($tank->low_stock_threshold, 2) }} L
                        </div>
                        <div style="background: #f8d7da; padding: 0.75rem; border-radius: 0.4rem; font-size: 0.85rem;">
                            <strong>Critical:</strong> {{ number_format($tank->critical_stock_threshold, 2) }} L
                        </div>
                    </div>

                    <div style="text-align: center; padding: 0.75rem; background: {{ $statusColor }}20; border-radius: 0.4rem; color: {{ $statusColor }}; font-weight: 600; font-size: 0.9rem;">
                        Status: {{ $statusLabel }}
                    </div>
                </div>

                <div class="card-footer" style="background: white; border-top: 1px solid #e2e8f0;">
                    <a href="{{ route('tanks.show', $tank) }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-arrow-right me-1"></i>View Details
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                <div class="empty-state-title">No tanks available</div>
                <div class="empty-state-text">Create fuel tanks to start inventory tracking</div>
            </div>
        </div>
    @endforelse
</div>

@if($tanks->hasPages())
    <div class="mt-4">
        {{ $tanks->links() }}
    </div>
@endif
@endsection
