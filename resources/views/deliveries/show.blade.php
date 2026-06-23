@extends('layouts.app')

@section('title', 'Delivery Receipt')
@section('topbar_title', 'Delivery Receipt')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Fuel Delivery Receipt</h6>
            </div>
            <div class="card-body">
                <!-- Delivery Header -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Waybill Number:</strong>
                            <span class="badge badge-secondary">{{ $delivery->waybill_number }}</span>
                        </p>
                        <p class="mb-2">
                            <strong>Delivery Date:</strong>
                            {{ $delivery->delivery_date->format('M d, Y') }}
                        </p>
                        <p class="mb-2">
                            <strong>Recorded By:</strong>
                            {{ $delivery->deliveredBy->name }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Supplier:</strong>
                            {{ $delivery->supplier_name }}
                        </p>
                        <p class="mb-2">
                            <strong>Tank:</strong>
                            {{ $delivery->tank->name }}
                        </p>
                        <p class="mb-2">
                            <strong>Fuel Product:</strong>
                            {{ $delivery->fuelProduct->name }} ({{ $delivery->fuelProduct->code }})
                        </p>
                    </div>
                </div>

                <hr>

                <!-- Delivery Details -->
                <h6 class="mb-3">Delivery Details</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-borderless">
                        <tr>
                            <td>Quantity Delivered:</td>
                            <td class="text-end"><strong>{{ number_format($delivery->quantity_litres, 2) }} Litres</strong></td>
                        </tr>
                        <tr>
                            <td>Unit Cost:</td>
                            <td class="text-end"><strong>${{ number_format($delivery->unit_cost, 2) }} /L</strong></td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Total Cost:</strong></td>
                            <td class="text-end"><strong>${{ number_format($delivery->total_cost, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>

                <hr>

                <!-- Stock Summary -->
                <h6 class="mb-3">Stock Summary</h6>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="bg-light p-3 rounded text-center">
                            <small class="text-muted d-block mb-2">Stock Before</small>
                            <strong class="fs-5 d-block">{{ number_format($delivery->stock_before, 2) }}L</strong>
                            <small class="text-muted">of {{ number_format($delivery->tank->capacity_litres, 2) }}L</small>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                        <div class="text-center">
                            <i class="bi bi-plus-circle text-success" style="font-size: 1.5rem;"></i>
                            <p class="mt-2 mb-0 text-muted">
                                <strong>+{{ number_format($delivery->quantity_litres, 2) }}L</strong>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-success bg-opacity-10 p-3 rounded text-center">
                            <small class="text-muted d-block mb-2">Stock After</small>
                            <strong class="fs-5 d-block text-success">{{ number_format($delivery->stock_after, 2) }}L</strong>
                            <small class="text-muted">of {{ number_format($delivery->tank->capacity_litres, 2) }}L</small>
                        </div>
                    </div>
                </div>

                <!-- Stock Percentage -->
                <div class="mb-4">
                    @php
                        $beforePercentage = ($delivery->stock_before / $delivery->tank->capacity_litres) * 100;
                        $afterPercentage = ($delivery->stock_after / $delivery->tank->capacity_litres) * 100;
                    @endphp
                    <small class="d-block mb-2">Tank Capacity Utilization</small>
                    <div class="mb-2">
                        <small>Before: {{ number_format($beforePercentage, 1) }}%</small>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ $beforePercentage }}%; background-color: #f59e0b;"></div>
                        </div>
                    </div>
                    <div>
                        <small>After: {{ number_format($afterPercentage, 1) }}%</small>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ $afterPercentage }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                @if($delivery->notes)
                    <hr>
                    <h6 class="mb-3">Notes</h6>
                    <p class="text-muted">{{ $delivery->notes }}</p>
                @endif

                <!-- Record Info -->
                <div class="mt-4 pt-3 border-top">
                    <small class="text-muted">
                        <i class="bi bi-clock me-1"></i>
                        Recorded on {{ $delivery->created_at->format('M d, Y \a\t H:i A') }}
                    </small>
                </div>

                <!-- Actions -->
                <div class="mt-4">
                    <a href="{{ route('deliveries.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
