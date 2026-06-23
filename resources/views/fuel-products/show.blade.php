@extends('layouts.app')

@section('title', $fuelProduct->name)
@section('topbar_title', $fuelProduct->name)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title">Product Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted" style="font-size: 0.85rem;">Product Name</label>
                            <div style="font-weight: 600; font-size: 1.1rem;">{{ $fuelProduct->name }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted" style="font-size: 0.85rem;">Product Code</label>
                            <div style="font-weight: 600; font-family: monospace; font-size: 1.1rem;">{{ $fuelProduct->code }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="text-muted" style="font-size: 0.85rem;">Current Price</label>
                            <div style="font-weight: 700; font-size: 1.5rem; color: #3b82f6;">₦{{ number_format($fuelProduct->current_price, 2) }}</div>
                        </div>
                        <div class="mb-0">
                            <label class="text-muted" style="font-size: 0.85rem;">Status</label>
                            <div>
                                @if($fuelProduct->is_active)
                                    <span class="badge badge-active badge-lg"><i class="bi bi-check-circle me-1"></i>Active</span>
                                @else
                                    <span class="badge badge-inactive badge-lg"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Price History</h6>
            </div>
            <div class="card-body p-0">
                @if($priceHistories->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Old Price</th>
                                    <th>New Price</th>
                                    <th>Changed By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($priceHistories as $history)
                                    <tr>
                                        <td><strong>₦{{ number_format($history->old_price, 2) }}</strong></td>
                                        <td><strong style="color: #3b82f6;">₦{{ number_format($history->new_price, 2) }}</strong></td>
                                        <td>{{ $history->changedBy->name }}</td>
                                        <td><small class="text-muted">{{ $history->created_at->format('M d, Y H:i') }}</small></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body border-top">
                        {{ $priceHistories->links() }}
                    </div>
                @else
                    <div class="empty-state py-4">
                        <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                        <div class="empty-state-title">No price changes yet</div>
                        <div class="empty-state-text">This is the initial price for this product</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Actions</h6>
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('fuel-products.edit', $fuelProduct) }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-2"></i>Edit Product
                </a>
                <form action="{{ route('fuel-products.destroy', $fuelProduct) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash me-2"></i>Delete Product
                    </button>
                </form>
                <a href="{{ route('fuel-products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="card-title">Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted" style="font-size: 0.85rem;">Created</label>
                    <div style="font-weight: 600;">{{ $fuelProduct->created_at->format('F d, Y') }}</div>
                </div>
                <div class="mb-0">
                    <label class="text-muted" style="font-size: 0.85rem;">Last Updated</label>
                    <div style="font-weight: 600;">{{ $fuelProduct->updated_at->format('F d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
