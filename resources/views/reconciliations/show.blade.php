@extends('layouts.app')

@section('title', 'Reconciliation Details')
@section('topbar_title', 'Reconciliation Details')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title">Shift Reconciliation - {{ $reconciliation->shift->shift_date->format('M d, Y') }}</h6>
                @php
                    $statusClass = 'badge-success';
                    $statusIcon = 'check-circle';
                    if ($reconciliation->status === 'surplus') {
                        $statusClass = 'badge-info';
                        $statusIcon = 'arrow-up-circle';
                    } elseif ($reconciliation->status === 'shortage') {
                        $statusClass = 'badge-danger';
                        $statusIcon = 'arrow-down-circle';
                    }
                @endphp
                <span class="badge {{ $statusClass }} fs-6">
                    <i class="bi bi-{{ $statusIcon }} me-1"></i>{{ ucfirst($reconciliation->status) }}
                </span>
            </div>
            <div class="card-body">
                <!-- Overview Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Shift Type</small>
                            <strong>{{ ucfirst($reconciliation->shift->shift_type) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Reconciled By</small>
                            <strong>{{ $reconciliation->reconciledBy->name }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="bg-light p-3 rounded">
                            <small class="text-muted d-block">Reconciled At</small>
                            <strong>{{ $reconciliation->created_at->format('M d, Y H:i') }}</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @if($reconciliation->reviewed_at)
                            <div class="bg-light p-3 rounded">
                                <small class="text-muted d-block">Reviewed By</small>
                                <strong>{{ $reconciliation->reviewedBy->name ?? 'N/A' }}</strong>
                            </div>
                        @else
                            <div class="bg-warning bg-opacity-10 p-3 rounded">
                                <small class="text-muted d-block">Status</small>
                                <strong class="text-warning">Pending Review</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Financial Summary -->
                <h6 class="mb-3 border-bottom pb-2">Financial Summary</h6>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="bg-light p-3 rounded mb-3">
                            <small class="text-muted d-block">Litres Sold</small>
                            <strong class="fs-5">{{ number_format($reconciliation->total_litres_sold, 2) }}L</strong>
                        </div>
                        <div class="bg-light p-3 rounded mb-3">
                            <small class="text-muted d-block">Expected Cash</small>
                            <strong class="fs-5">${{ number_format($reconciliation->expected_cash, 2) }}</strong>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td>Actual Cash Collected:</td>
                                    <td class="text-end"><strong>${{ number_format($reconciliation->actual_cash, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>POS Transactions:</td>
                                    <td class="text-end"><strong>${{ number_format($reconciliation->actual_pos, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Bank Transfers:</td>
                                    <td class="text-end"><strong>${{ number_format($reconciliation->actual_bank_transfer, 2) }}</strong></td>
                                </tr>
                                <tr class="border-top">
                                    <td><strong>Total Collected</strong></td>
                                    <td class="text-end"><strong>${{ number_format($reconciliation->total_collected, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>{{ $reconciliation->cash_difference >= 0 ? 'Surplus' : 'Shortage' }}</strong>
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $reconciliation->cash_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $reconciliation->cash_difference >= 0 ? '+' : '' }}${{ number_format($reconciliation->cash_difference, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sales by Pump -->
                <h6 class="mb-3 border-bottom pb-2">Sales Breakdown by Pump</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Pump</th>
                                <th>Transactions</th>
                                <th>Litres</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesByPump as $pumpName => $pumpSales)
                                <tr>
                                    <td>{{ $pumpName }}</td>
                                    <td>{{ $pumpSales->count() }}</td>
                                    <td>{{ number_format($pumpSales->sum('quantity_litres'), 2) }}L</td>
                                    <td>${{ number_format($pumpSales->sum('total_amount'), 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Notes Section -->
                @if($reconciliation->supervisor_note || $reconciliation->manager_note)
                    <h6 class="mb-3 border-bottom pb-2">Notes</h6>
                    @if($reconciliation->supervisor_note)
                        <div class="bg-light p-3 rounded mb-3">
                            <small class="text-muted d-block">Supervisor Note</small>
                            <p class="mb-0">{{ $reconciliation->supervisor_note }}</p>
                        </div>
                    @endif
                    @if($reconciliation->manager_note)
                        <div class="bg-light p-3 rounded mb-3">
                            <small class="text-muted d-block">Manager Note</small>
                            <p class="mb-0">{{ $reconciliation->manager_note }}</p>
                        </div>
                    @endif
                @endif

                <!-- Actions -->
                <div class="mt-4">
                    @if(!$reconciliation->reviewed_at && Auth::user()->hasRole('manager', 'admin'))
                        <a href="{{ route('reconciliations.review', $reconciliation) }}" class="btn btn-primary">
                            <i class="bi bi-pencil me-1"></i>Review This Reconciliation
                        </a>
                    @endif
                    <a href="{{ route('reconciliations.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
