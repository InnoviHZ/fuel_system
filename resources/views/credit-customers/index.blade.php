@extends('layouts.app')

@section('title', 'Credit Customers')
@section('topbar_title', 'Credit Customers')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title">Credit Customers</h6>
        <a href="{{ route('credit-customers.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Add Customer
        </a>
    </div>
    <div class="card-body p-0">
        @if($creditCustomers->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Credit Limit</th>
                            <th>Current Balance</th>
                            <th>Available Credit</th>
                            <th>Usage</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($creditCustomers as $customer)
                            @php
                                $utilization = $customer->credit_limit > 0 ? ($customer->current_balance / $customer->credit_limit) * 100 : 0;
                                if ($customer->credit_limit == 0) {
                                    $statusBadge = 'No Credit';
                                    $statusClass = 'badge-secondary';
                                } elseif ($utilization >= 100) {
                                    $statusBadge = 'Over Limit';
                                    $statusClass = 'badge-danger';
                                } elseif ($utilization >= 80) {
                                    $statusBadge = 'High Usage';
                                    $statusClass = 'badge-warning';
                                } else {
                                    $statusBadge = 'Active';
                                    $statusClass = 'badge-success';
                                }

                                $availableCredit = max(0, $customer->credit_limit - $customer->current_balance);
                            @endphp
                            <tr>
                                <td><strong>{{ $customer->name }}</strong></td>
                                <td>{{ $customer->phone }}</td>
                                <td>${{ number_format($customer->credit_limit, 2) }}</td>
                                <td class="{{ $utilization > 80 ? 'text-danger' : '' }}">
                                    <strong>${{ number_format($customer->current_balance, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="{{ $availableCredit > 0 ? 'text-success' : 'text-danger' }}">
                                        ${{ number_format($availableCredit, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px; min-width: 100px;">
                                        @php
                                            $progressColor = '#22c55e';
                                            if ($utilization >= 100) {
                                                $progressColor = '#ef4444';
                                            } elseif ($utilization >= 80) {
                                                $progressColor = '#f59e0b';
                                            }
                                        @endphp
                                        <div class="progress-bar" style="width: {{ min($utilization, 100) }}%; background-color: {{ $progressColor }};" title="{{ number_format($utilization, 1) }}%">
                                            @if($utilization > 10)
                                                <small class="text-white fw-bold">{{ number_format($utilization, 0) }}%</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $statusClass }}">{{ $statusBadge }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('credit-customers.show', $customer) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    <a href="{{ route('credit-customers.edit', $customer) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil me-1"></i>Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $creditCustomers->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted">No credit customers found</p>
            </div>
        @endif
    </div>
</div>
@endsection
