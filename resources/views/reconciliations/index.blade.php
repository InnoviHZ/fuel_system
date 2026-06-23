@extends('layouts.app')

@section('title', 'Shift Reconciliations')
@section('topbar_title', 'Shift Reconciliations')

@section('content')
<div class="card">
    <div class="card-header">
        <h6 class="card-title">Reconciliation History</h6>
    </div>
    <div class="card-body p-0">
        @if($reconciliations->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Shift Date</th>
                            <th>Shift Type</th>
                            <th>Litres Sold</th>
                            <th>Expected Cash</th>
                            <th>Total Collected</th>
                            <th>Difference</th>
                            <th>Status</th>
                            <th>Reconciled By</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reconciliations as $reconciliation)
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
                            <tr>
                                <td><strong>{{ $reconciliation->shift->shift_date->format('M d, Y') }}</strong></td>
                                <td>
                                    <span class="badge badge-primary">{{ ucfirst($reconciliation->shift->shift_type) }}</span>
                                </td>
                                <td>{{ number_format($reconciliation->total_litres_sold, 2) }}L</td>
                                <td>${{ number_format($reconciliation->expected_cash, 2) }}</td>
                                <td>${{ number_format($reconciliation->total_collected, 2) }}</td>
                                <td>
                                    <span class="{{ $reconciliation->cash_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $reconciliation->cash_difference >= 0 ? '+' : '' }}${{ number_format($reconciliation->cash_difference, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        <i class="bi bi-{{ $statusIcon }} me-1"></i>{{ ucfirst($reconciliation->status) }}
                                    </span>
                                </td>
                                <td>{{ $reconciliation->reconciledBy->name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('reconciliations.show', $reconciliation) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                    @if(!$reconciliation->reviewed_at && Auth::user()->hasRole('manager', 'admin'))
                                        <a href="{{ route('reconciliations.review', $reconciliation) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil me-1"></i>Review
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $reconciliations->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted">No reconciliations found</p>
            </div>
        @endif
    </div>
</div>
@endsection
