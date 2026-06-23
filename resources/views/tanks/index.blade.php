@extends('layouts.app')

@section('title', 'Fuel Tanks')
@section('topbar_title', 'Fuel Tanks')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title">Fuel Tanks</h6>
        @if(Auth::user()->hasRole('admin'))
            <a href="{{ route('tanks.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Add Tank
            </a>
        @endif
    </div>
    <div class="card-body p-0">
        @if($tanks->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Tank Name</th>
                            <th>Fuel Type</th>
                            <th>Capacity</th>
                            <th>Current Stock</th>
                            <th>Stock Status</th>
                            <th>Thresholds</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tanks as $tank)
                            @php
                                $stockPercentage = ($tank->current_stock_litres / $tank->capacity_litres) * 100;
                                if ($stockPercentage >= 50) {
                                    $progressColor = '#22c55e';
                                    $statusBadge = 'Active';
                                    $statusClass = 'badge-success';
                                } elseif ($stockPercentage >= 25) {
                                    $progressColor = '#f59e0b';
                                    $statusBadge = 'Low';
                                    $statusClass = 'badge-warning';
                                } else {
                                    $progressColor = '#ef4444';
                                    $statusBadge = 'Critical';
                                    $statusClass = 'badge-danger';
                                }
                            @endphp
                            <tr>
                                <td><strong>{{ $tank->name }}</strong></td>
                                <td>
                                    <span style="background: #f1f5f9; padding: 0.25rem 0.75rem; border-radius: 0.4rem; font-weight: 600;">
                                        {{ $tank->fuelProduct->code }}
                                    </span>
                                </td>
                                <td><small class="text-muted">{{ number_format($tank->capacity_litres) }} L</small></td>
                                <td>
                                    <strong>{{ number_format($tank->current_stock_litres) }} L</strong>
                                    <div style="font-size: 0.75rem; color: #64748b;">{{ number_format($stockPercentage, 1) }}%</div>
                                </td>
                                <td>
                                    <div style="background: #e2e8f0; border-radius: 0.4rem; height: 24px; overflow: hidden;">
                                        <div style="background: {{ $progressColor }}; height: 100%; width: {{ $stockPercentage }}%; transition: width 0.3s ease;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-size: 0.75rem;">
                                        <div><span class="text-muted">Low:</span> {{ number_format($tank->low_stock_threshold) }} L</div>
                                        <div><span class="text-muted">Critical:</span> {{ number_format($tank->critical_stock_threshold) }} L</div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('tanks.show', $tank) }}" class="btn btn-icon btn-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(Auth::user()->hasRole('admin'))
                                        <a href="{{ route('tanks.edit', $tank) }}" class="btn btn-icon btn-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('tanks.destroy', $tank) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-icon btn-delete" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body border-top">
                {{ $tanks->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                <div class="empty-state-title">No tanks found</div>
                <div class="empty-state-text">Create your first fuel tank to get started</div>
                @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('tanks.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Add Tank
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
