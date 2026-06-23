@extends('layouts.app')

@section('title', 'Shifts')
@section('topbar_title', 'Shift Management')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="card-title">All Shifts</h6>
        <a href="{{ route('shifts.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Open New Shift
        </a>
    </div>
    <div class="card-body p-0">
        @if($shifts->count())
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Shift Type</th>
                            <th>Status</th>
                            <th>Opened By</th>
                            <th>Opened At</th>
                            <th>Closed By</th>
                            <th>Pumps</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shifts as $shift)
                            <tr>
                                <td><strong>{{ $shift->shift_date->format('M d, Y') }}</strong></td>
                                <td>
                                    <span class="badge" style="background: {{ $shift->shift_type === 'morning' ? '#3b82f6' : '#8b5cf6' }}; color: white;">
                                        {{ ucfirst($shift->shift_type) }}
                                    </span>
                                </td>
                                <td>
                                    @if($shift->status === 'open')
                                        <span class="badge badge-success"><i class="bi bi-play-circle me-1"></i>Open</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="bi bi-stop-circle me-1"></i>Closed</span>
                                    @endif
                                </td>
                                <td>{{ $shift->openedByUser->name }}</td>
                                <td><small class="text-muted">{{ $shift->opened_at->format('M d H:i') }}</small></td>
                                <td>
                                    @if($shift->closed_by)
                                        {{ $shift->closedByUser->name }}
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td><span class="badge badge-info">{{ $shift->pumpReadings->count() }} pumps</span></td>
                                <td class="text-end">
                                    <a href="{{ route('shifts.show', $shift) }}" class="btn btn-icon btn-view" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($shift->status === 'open' && Auth::user()->hasAnyRole(['supervisor', 'admin']))
                                        <a href="{{ route('shifts.close', $shift) }}" class="btn btn-icon btn-edit" title="Close">
                                            <i class="bi bi-stop-circle"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-body border-top">
                {{ $shifts->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                <div class="empty-state-title">No shifts found</div>
                <div class="empty-state-text">Open your first shift to get started</div>
                <a href="{{ route('shifts.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>Open Shift
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
