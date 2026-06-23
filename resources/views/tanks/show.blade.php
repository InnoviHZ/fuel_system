@extends('layouts.app')

@section('title', $tank->name)
@section('topbar_title', $tank->name)

@section('content')
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
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title">Tank Overview</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted" style="font-size: 0.85rem;">Tank Name</label>
                            <div style="font-weight: 600; font-size: 1.1rem;">{{ $tank->name }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted" style="font-size: 0.85rem;">Fuel Type</label>
                            <div style="font-weight: 600;">{{ $tank->fuelProduct->name }} <span style="background: #f1f5f9; padding: 0.25rem 0.75rem; border-radius: 0.4rem; margin-left: 0.5rem;">{{ $tank->fuelProduct->code }}</span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted" style="font-size: 0.85rem;">Tank Status</label>
                            <div>
                                <span class="badge badge-lg" style="background: {{ $statusColor }}; color: white; padding: 0.5rem 1rem; font-size: 0.9rem;">
                                    <i class="bi bi-fuel-pump me-1"></i>{{ $statusLabel }}
                                </span>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted" style="font-size: 0.85rem;">Capacity Status</label>
                            <div style="font-weight: 600; font-size: 1rem;">{{ number_format($stockPercentage, 1) }}%</div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <label class="text-muted" style="font-size: 0.85rem; display: block; margin-bottom: 0.5rem;">Stock Level</label>
                    <div style="background: #e2e8f0; border-radius: 0.6rem; height: 40px; overflow: hidden; display: flex; align-items: center; position: relative;">
                        <div style="background: {{ $statusColor }}; height: 100%; width: {{ $stockPercentage }}%; transition: width 0.3s ease; display: flex; align-items: center; padding: 0 1rem;">
                            <span style="color: white; font-weight: 600; font-size: 0.9rem;">{{ number_format($tank->current_stock_litres) }} L</span>
                        </div>
                        <span style="position: absolute; right: 1rem; color: #64748b; font-weight: 600;">{{ number_format($tank->capacity_litres) }} L</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-0">
                            <label class="text-muted" style="font-size: 0.85rem;">Low Stock Threshold</label>
                            <div style="font-weight: 600; font-size: 1rem;">{{ number_format($tank->low_stock_threshold) }} L</div>
                            <div style="font-size: 0.8rem; color: #f59e0b;">⚠️ Alert at this level</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-0">
                            <label class="text-muted" style="font-size: 0.85rem;">Critical Threshold</label>
                            <div style="font-weight: 600; font-size: 1rem;">{{ number_format($tank->critical_stock_threshold) }} L</div>
                            <div style="font-size: 0.8rem; color: #ef4444;">🚨 Emergency refill required</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Assigned Pumps</h6>
            </div>
            <div class="card-body p-0">
                @if($tank->pumps->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Pump Name</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tank->pumps as $pump)
                                    <tr>
                                        <td><strong>{{ $pump->name }}</strong></td>
                                        <td>
                                            @if($pump->is_active)
                                                <span class="badge badge-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                                            @else
                                                <span class="badge badge-danger"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                                            @endif
                                        </td>
                                        <td><small class="text-muted">{{ $pump->created_at->format('M d, Y') }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state py-4">
                        <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                        <div class="empty-state-title">No pumps assigned</div>
                        <div class="empty-state-text">This tank has no pumps yet</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Actions</h6>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('tanks.edit', $tank) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Tank
                </a>
                <form action="{{ route('tanks.destroy', $tank) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tank?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash me-2"></i>Delete Tank
                    </button>
                </form>
                <a href="{{ route('tanks.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Tanks
                </a>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title">Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Total Capacity</label>
                    <div style="font-weight: 600;">{{ number_format($tank->capacity_litres) }} Litres</div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Current Status</label>
                    <div>
                        @if($tank->is_active)
                            <span class="badge badge-active"><i class="bi bi-check-circle me-1"></i>Active</span>
                        @else
                            <span class="badge badge-inactive"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Created</label>
                    <div style="font-weight: 600;">{{ $tank->created_at->format('F d, Y') }}</div>
                </div>
                <div class="mb-0">
                    <label class="text-muted" style="font-size: 0.85rem;">Last Updated</label>
                    <div style="font-weight: 600;">{{ $tank->updated_at->format('F d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
