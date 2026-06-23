@extends('layouts.app')

@section('title', 'Reports')
@section('topbar_title', 'Reports')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card h-100 cursor-pointer" onclick="window.location='{{ route('reports.sales') }}'">
            <div class="card-body text-center">
                <i class="bi bi-receipt" style="font-size: 3rem; color: #3b82f6;"></i>
                <h6 class="mt-3">Sales Report</h6>
                <p class="text-muted small">View and analyze sales transactions</p>
                <a href="{{ route('reports.sales') }}" class="btn btn-sm btn-outline-primary">View Report</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 cursor-pointer" onclick="window.location='{{ route('reports.inventory') }}'">
            <div class="card-body text-center">
                <i class="bi bi-box-seam" style="font-size: 3rem; color: #f59e0b;"></i>
                <h6 class="mt-3">Inventory Report</h6>
                <p class="text-muted small">Monitor tank stock levels and movement</p>
                <a href="{{ route('reports.inventory') }}" class="btn btn-sm btn-outline-primary">View Report</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 cursor-pointer" onclick="window.location='{{ route('reports.reconciliation') }}'">
            <div class="card-body text-center">
                <i class="bi bi-calculator" style="font-size: 3rem; color: #22c55e;"></i>
                <h6 class="mt-3">Reconciliation Report</h6>
                <p class="text-muted small">Review shift reconciliation summaries</p>
                <a href="{{ route('reports.reconciliation') }}" class="btn btn-sm btn-outline-primary">View Report</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 cursor-pointer" onclick="window.location='{{ route('reports.deliveries') }}'">
            <div class="card-body text-center">
                <i class="bi bi-truck" style="font-size: 3rem; color: #06b6d4;"></i>
                <h6 class="mt-3">Delivery Report</h6>
                <p class="text-muted small">Track fuel delivery history</p>
                <a href="{{ route('reports.deliveries') }}" class="btn btn-sm btn-outline-primary">View Report</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100 cursor-pointer" onclick="window.location='{{ route('reports.credit') }}'">
            <div class="card-body text-center">
                <i class="bi bi-credit-card" style="font-size: 3rem; color: #8b5cf6;"></i>
                <h6 class="mt-3">Credit Report</h6>
                <p class="text-muted small">View credit customer balances</p>
                <a href="{{ route('reports.credit') }}" class="btn btn-sm btn-outline-primary">View Report</a>
            </div>
        </div>
    </div>
</div>
@endsection
