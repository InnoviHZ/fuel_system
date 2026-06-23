@extends('layouts.app')

@section('title', 'Reconciliation Report')
@section('topbar_title', 'Reconciliation Report')

@section('content')
<!-- Filter Card -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>Filter Reconciliations
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.reconciliation') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from', \Carbon\Carbon::now()->startOfMonth()->toDateString()) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to', \Carbon\Carbon::now()->toDateString()) }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.reconciliation') }}" class="btn btn-outline-secondary btn-sm">
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
                        <small class="text-muted d-block fw-600">Total Shifts</small>
                        <h4 class="mb-0 mt-1">{{ $summary['total_shifts'] ?? 0 }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-clock"></i>
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
                        <small class="text-muted d-block fw-600">Expected Amount</small>
                        <h4 class="mb-0 mt-1">₦{{ number_format($summary['total_expected'] ?? 0, 2) }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(34, 197, 94, 0.1); color: #22c55e;">
                        <i class="bi bi-calculator"></i>
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
                        <small class="text-muted d-block fw-600">Collected Amount</small>
                        <h4 class="mb-0 mt-1">₦{{ number_format($summary['total_collected'] ?? 0, 2) }}</h4>
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
                        <small class="text-muted d-block fw-600">Variance</small>
                        @php
                            $variance = ($summary['total_collected'] ?? 0) - ($summary['total_expected'] ?? 0);
                        @endphp
                        <h4 class="mb-0 mt-1">
                            @if($variance > 0)
                                <span class="text-success">+₦{{ number_format($variance, 2) }}</span>
                            @elseif($variance < 0)
                                <span class="text-danger">-₦{{ number_format(abs($variance), 2) }}</span>
                            @else
                                <span>₦0.00</span>
                            @endif
                        </h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="bi bi-percent"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reconciliation Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Reconciliation Details
                </h5>
                <a href="{{ route('reports.export', array_merge(request()->all(), ['type' => 'reconciliation'])) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-file-pdf me-1"></i>Export PDF
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Shift Type</th>
                                <th>Expected</th>
                                <th>Collected</th>
                                <th>Variance</th>
                                <th>Reconciled By</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reconciliations as $rec)
                                <tr>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($rec->created_at)->format('M d, Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $rec->shift->shift_type ?? 'N/A' }}</span>
                                    </td>
                                    <td class="fw-600">₦{{ number_format($rec->expected_amount ?? 0, 2) }}</td>
                                    <td class="fw-600">₦{{ number_format($rec->collected_amount ?? 0, 2) }}</td>
                                    <td>
                                        @php
                                            $var = ($rec->collected_amount ?? 0) - ($rec->expected_amount ?? 0);
                                        @endphp
                                        <span class="{{ $var >= 0 ? 'text-success' : 'text-danger' }} fw-600">
                                            {{ $var >= 0 ? '+' : '' }}₦{{ number_format($var, 2) }}
                                        </span>
                                    </td>
                                    <td>{{ $rec->reconciled_by_user->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($rec->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($rec->status == 'submitted')
                                            <span class="badge bg-info">Submitted</span>
                                        @elseif($rec->status == 'reviewed')
                                            <span class="badge bg-success">Reviewed</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $rec->notes ?? '-' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No reconciliations found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($reconciliations->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing {{ $reconciliations->firstItem() }} to {{ $reconciliations->lastItem() }} of {{ $reconciliations->total() }}
                        </div>
                        <nav>
                            {{ $reconciliations->appends(request()->query())->links() }}
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
