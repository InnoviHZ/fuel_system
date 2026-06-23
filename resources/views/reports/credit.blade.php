@extends('layouts.app')

@section('title', 'Credit Report')
@section('topbar_title', 'Credit Report')

@section('content')
<!-- Filter Card -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>Filter Credit Customers
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('reports.credit') }}" class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All Customers</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Only</option>
                            <option value="near_limit" {{ request('status') == 'near_limit' ? 'selected' : '' }}>Near Limit (80%+)</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('reports.credit') }}" class="btn btn-outline-secondary btn-sm">
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
                        <small class="text-muted d-block fw-600">Active Customers</small>
                        <h4 class="mb-0 mt-1">{{ $summary['active_customers'] ?? 0 }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-people-fill"></i>
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
                        <small class="text-muted d-block fw-600">Total Credit Limit</small>
                        <h4 class="mb-0 mt-1">₦{{ number_format($summary['total_credit_limit'] ?? 0, 2) }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(34, 197, 94, 0.1); color: #22c55e;">
                        <i class="bi bi-wallet2"></i>
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
                        <small class="text-muted d-block fw-600">Total Outstanding</small>
                        <h4 class="mb-0 mt-1">₦{{ number_format($summary['total_outstanding'] ?? 0, 2) }}</h4>
                    </div>
                    <div class="stat-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Credit Customers Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>Credit Customer Details
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Credit Limit</th>
                                <th>Current Balance</th>
                                <th>Available Credit</th>
                                <th>Utilization</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                                <tr>
                                    <td>
                                        <strong>{{ $customer->name }}</strong>
                                    </td>
                                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                                    <td class="fw-600">₦{{ number_format($customer->credit_limit ?? 0, 2) }}</td>
                                    <td class="fw-600">₦{{ number_format($customer->current_balance ?? 0, 2) }}</td>
                                    <td>
                                        @php
                                            $available = ($customer->credit_limit ?? 0) - ($customer->current_balance ?? 0);
                                        @endphp
                                        <span class="{{ $available >= 0 ? 'text-success' : 'text-danger' }}">
                                            ₦{{ number_format($available, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $limit = $customer->credit_limit ?? 1;
                                            $balance = $customer->current_balance ?? 0;
                                            $utilization = ($balance / $limit) * 100;
                                            $utilization = min(100, max(0, $utilization));
                                        @endphp
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 20px; width: 100px;">
                                                <div class="progress-bar" role="progressbar" 
                                                    style="width: {{ $utilization }}%; background-color: {{ $utilization > 80 ? '#ef4444' : ($utilization > 50 ? '#f59e0b' : '#22c55e') }};">
                                                </div>
                                            </div>
                                            <span class="text-muted small">{{ round($utilization) }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($utilization >= 80)
                                            <span class="badge bg-danger">Near Limit</span>
                                        @elseif($utilization >= 50)
                                            <span class="badge bg-warning text-dark">Caution</span>
                                        @else
                                            <span class="badge bg-success">Good Standing</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No credit customers found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($customers->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }}
                        </div>
                        <nav>
                            {{ $customers->appends(request()->query())->links() }}
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
