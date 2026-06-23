@extends('layouts.app')

@section('title', 'Fuel Deliveries')
@section('topbar_title', 'Fuel Deliveries')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title">Fuel Deliveries</h6>
        @if(Auth::user()->hasRole('supervisor', 'admin'))
            <a href="{{ route('deliveries.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle me-1"></i>Record Delivery
            </a>
        @endif
    </div>
    <div class="card-body p-0">
        @if($deliveries->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Delivery Date</th>
                            <th>Tank</th>
                            <th>Fuel Type</th>
                            <th>Supplier</th>
                            <th>Waybill</th>
                            <th>Quantity</th>
                            <th>Total Cost</th>
                            <th>Stock Before</th>
                            <th>Stock After</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deliveries as $delivery)
                            <tr>
                                <td>{{ $delivery->delivery_date->format('M d, Y') }}</td>
                                <td><strong>{{ $delivery->tank->name }}</strong></td>
                                <td>{{ $delivery->fuelProduct->name }} ({{ $delivery->fuelProduct->code }})</td>
                                <td>{{ $delivery->supplier_name }}</td>
                                <td>
                                    <span class="badge badge-secondary">{{ $delivery->waybill_number }}</span>
                                </td>
                                <td>{{ number_format($delivery->quantity_litres, 2) }}L</td>
                                <td>${{ number_format($delivery->total_cost, 2) }}</td>
                                <td>
                                    <small class="text-muted">{{ number_format($delivery->stock_before, 2) }}L</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted">{{ number_format($delivery->stock_after, 2) }}L</small>
                                        <i class="bi bi-arrow-right text-success"></i>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('deliveries.show', $delivery) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $deliveries->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted">No deliveries recorded yet</p>
            </div>
        @endif
    </div>
</div>
@endsection
