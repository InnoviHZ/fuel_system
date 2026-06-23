@extends('layouts.app')

@section('title', 'Fuel Products')
@section('topbar_title', 'Fuel Products')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title">Fuel Products</h6>
        <a href="{{ route('fuel-products.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-droplet me-1"></i>Add Product
        </a>
    </div>
    <div class="card-body p-0">
        @if($fuelProducts->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Code</th>
                            <th>Current Price</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fuelProducts as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><span style="background: #f1f5f9; padding: 0.25rem 0.75rem; border-radius: 0.4rem; font-family: monospace; font-weight: 600;">{{ $product->code }}</span></td>
                                <td><strong>₦{{ number_format($product->current_price, 2) }}</strong></td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge badge-active"><i class="bi bi-check-circle me-1"></i>Active</span>
                                    @else
                                        <span class="badge badge-inactive"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $product->updated_at->format('M d, Y') }}</small></td>
                                <td class="text-end">
                                    <a href="{{ route('fuel-products.show', $product) }}" class="btn btn-icon btn-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('fuel-products.edit', $product) }}" class="btn btn-icon btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('fuel-products.destroy', $product) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-delete" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body border-top">
                {{ $fuelProducts->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                <div class="empty-state-title">No fuel products found</div>
                <div class="empty-state-text">Create your first fuel product to get started</div>
                <a href="{{ route('fuel-products.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-droplet me-1"></i>Add Product
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
