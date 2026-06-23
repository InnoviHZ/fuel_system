@extends('layouts.app')

@section('title', 'Review Reconciliation')
@section('topbar_title', 'Review Reconciliation')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Review Reconciliation - {{ $reconciliation->shift->shift_date->format('M d, Y') }}</h6>
            </div>
            <div class="card-body">
                <!-- Reconciliation Details Summary -->
                <div class="bg-light p-3 rounded mb-4">
                    <h6 class="mb-3">Reconciliation Summary</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Reconciled By:</strong> {{ $reconciliation->reconciledBy->name }}</p>
                            <p class="mb-2"><strong>Date:</strong> {{ $reconciliation->created_at->format('M d, Y H:i') }}</p>
                            <p class="mb-2"><strong>Status:</strong> 
                                @php
                                    $statusClass = 'badge-success';
                                    if ($reconciliation->status === 'surplus') {
                                        $statusClass = 'badge-info';
                                    } elseif ($reconciliation->status === 'shortage') {
                                        $statusClass = 'badge-danger';
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($reconciliation->status) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Total Litres Sold:</strong> {{ number_format($reconciliation->total_litres_sold, 2) }}L</p>
                            <p class="mb-2"><strong>Expected Cash:</strong> ${{ number_format($reconciliation->expected_cash, 2) }}</p>
                            <p class="mb-2"><strong>Total Collected:</strong> ${{ number_format($reconciliation->total_collected, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Collection Breakdown -->
                <h6 class="mb-3">Payment Collection Breakdown</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-sm table-bordered">
                        <tr>
                            <td>Cash Collected</td>
                            <td class="text-end">${{ number_format($reconciliation->actual_cash, 2) }}</td>
                        </tr>
                        <tr>
                            <td>POS Amount</td>
                            <td class="text-end">${{ number_format($reconciliation->actual_pos, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Bank Transfers</td>
                            <td class="text-end">${{ number_format($reconciliation->actual_bank_transfer, 2) }}</td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Total Collected</strong></td>
                            <td class="text-end"><strong>${{ number_format($reconciliation->total_collected, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Expected Amount</strong></td>
                            <td class="text-end"><strong>${{ number_format($reconciliation->expected_cash, 2) }}</strong></td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Difference</strong></td>
                            <td class="text-end">
                                <strong class="{{ $reconciliation->cash_difference >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $reconciliation->cash_difference >= 0 ? '+' : '' }}${{ number_format($reconciliation->cash_difference, 2) }}
                                </strong>
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Supervisor Notes -->
                @if($reconciliation->supervisor_note)
                    <div class="bg-light p-3 rounded mb-4">
                        <small class="text-muted d-block mb-2">Supervisor Note</small>
                        <p class="mb-0">{{ $reconciliation->supervisor_note }}</p>
                    </div>
                @endif

                <!-- Manager Review Form -->
                <h6 class="mb-3 border-top pt-3">Manager Review</h6>
                <form action="{{ route('reconciliations.submitReview', $reconciliation) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="managerNote" class="form-label">Manager Note (Optional)</label>
                        <textarea class="form-control @error('manager_note') is-invalid @enderror" id="managerNote" name="manager_note" rows="4" placeholder="Add your manager notes here...">{{ old('manager_note') }}</textarea>
                        @error('manager_note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>Mark as Reviewed
                        </button>
                        <a href="{{ route('reconciliations.show', $reconciliation) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
