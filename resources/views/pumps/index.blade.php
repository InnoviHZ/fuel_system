@extends('layouts.app')

@section('title', 'Fuel Pumps')
@section('topbar_title', 'Fuel Pumps')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title">Fuel Pumps</h6>
        <a href="{{ route('pumps.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Add Pump
        </a>
    </div>
    <div class="card-body p-0">
        @if($pumps->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Pump Name</th>
                            <th>Assigned Tank</th>
                            <th>Fuel Type</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pumps as $pump)
                            <tr>
                                <td><strong>{{ $pump->name }}</strong></td>
                                <td>
                                    <a href="{{ route('tanks.show', $pump->tank) }}" style="color: #3b82f6; text-decoration: none;">
                                        {{ $pump->tank->name }}
                                    </a>
                                </td>
                                <td>
                                    <span style="background: #f1f5f9; padding: 0.25rem 0.75rem; border-radius: 0.4rem; font-weight: 600;">
                                        {{ $pump->tank->fuelProduct->code }}
                                    </span>
                                </td>
                                <td>
                                    @if($pump->is_active)
                                        <span class="badge badge-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                                    @else
                                        <span class="badge badge-danger"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $pump->created_at->format('M d, Y') }}</small></td>
                                <td class="text-end">
                                    <a href="{{ route('pumps.edit', $pump) }}" class="btn btn-icon btn-edit" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('pumps.destroy', $pump) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
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
                {{ $pumps->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                <div class="empty-state-title">No pumps found</div>
                <div class="empty-state-text">Create your first fuel pump to get started</div>
                <a href="{{ route('pumps.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Add Pump
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
