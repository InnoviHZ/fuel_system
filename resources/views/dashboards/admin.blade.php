@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('topbar_title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Top Stats Row -->
    <div class="row mb-4">
    <div class="col-md-6 col-lg-3">
        <div class="stat-card primary">
            <div class="stat-content">
                <div class="stat-label">Total Users</div>
                <div class="stat-value">{{ \App\Models\User::count() }}</div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-people-fill"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card success">
            <div class="stat-content">
                <div class="stat-label">Fuel Products</div>
                <div class="stat-value">{{ \App\Models\FuelProduct::count() }}</div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-droplet-fill"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card warning">
            <div class="stat-content">
                <div class="stat-label">Total Tanks</div>
                <div class="stat-value">{{ \App\Models\Tank::count() }}</div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-database"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="stat-card danger">
            <div class="stat-content">
                <div class="stat-label">Active Pumps</div>
                <div class="stat-value">{{ \App\Models\Pump::where('is_active', true)->count() }}</div>
            </div>
            <div class="stat-icon">
                <i class="bi bi-lightning-fill"></i>
            </div>
        </div>
    </div>
</div>

<!-- Welcome Card -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Welcome, {{ auth()->user()->name }}! 👋</h5>
                <p class="text-muted mb-3">You are logged in as an <strong>Administrator</strong>. You have full access to all system features.</p>
                <p class="text-muted small">Use the sidebar navigation to manage users, fuel products, tanks, and pumps. Monitor inventory levels and track sales transactions.</p>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">Quick Actions</h6>
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm w-100 mb-2">
                    <i class="bi bi-person-plus me-2"></i>Add New User
                </a>
                <a href="{{ route('fuel-products.create') }}" class="btn btn-success btn-sm w-100 mb-2">
                    <i class="bi bi-droplet me-2"></i>Add Fuel Product
                </a>
                <a href="{{ route('tanks.create') }}" class="btn btn-warning btn-sm w-100 mb-2">
                    <i class="bi bi-database me-2"></i>Add Tank
                </a>
                <a href="{{ route('pumps.create') }}" class="btn btn-info btn-sm w-100">
                    <i class="bi bi-lightning me-2"></i>Add Pump
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data Section -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Recent Users</h6>
            </div>
            <div class="card-body p-0">
                @php $recentUsers = \App\Models\User::latest()->limit(5)->get(); @endphp
                @if($recentUsers->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @foreach($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div style="width: 2rem; height: 2rem; border-radius: 50%; background: linear-gradient(135deg, #3b82f6, #8b5cf6); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.75rem; margin-right: 0.75rem;">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div style="font-weight: 600; font-size: 0.9rem;">{{ $user->name }}</div>
                                                    <div style="font-size: 0.8rem; color: #64748b;">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge badge-{{ $user->role }}">{{ $user->role }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                        <div class="empty-state-title">No users yet</div>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">Create First User</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Fuel Products Overview</h6>
            </div>
            <div class="card-body p-0">
                @php $products = \App\Models\FuelProduct::all(); @endphp
                @if($products->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>
                                            <div style="font-weight: 600;">{{ $product->name }}</div>
                                            <div style="font-size: 0.8rem; color: #64748b;">{{ $product->code }}</div>
                                        </td>
                                        <td class="text-end">
                                            <div style="font-weight: 600;">₦{{ number_format($product->current_price, 2) }}</div>
                                            <div style="font-size: 0.8rem; color: #64748b;">Current Price</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                        <div class="empty-state-title">No products yet</div>
                        <a href="{{ route('fuel-products.create') }}" class="btn btn-primary btn-sm">Create First Product</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
