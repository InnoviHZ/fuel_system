@extends('layouts.app')

@section('title', 'Audit Log Details')
@section('topbar_title', 'Audit Log Details')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title">Log Information</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Date & Time</p>
                        <strong>{{ $log->created_at->format('M d, Y H:i:s') }}</strong>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">User</p>
                        <strong>{{ $log->user->name }}</strong> ({{ $log->user->email }})
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Action</p>
                        @php
                            $actionColors = [
                                'login' => 'badge-primary',
                                'logout' => 'badge-primary',
                                'created' => 'badge-success',
                                'updated' => 'badge-warning',
                                'deleted' => 'badge-danger',
                                'shift' => 'badge-info',
                                'sale' => 'badge-success',
                                'delivery' => 'badge-info',
                                'reconcile' => 'badge-dark'
                            ];
                            $color = $actionColors[$log->action] ?? 'badge-secondary';
                        @endphp
                        <span class="badge {{ $color }}">{{ ucfirst($log->action) }}</span>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Description</p>
                        <strong>{{ $log->description }}</strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Model Type</p>
                        <strong>{{ $log->model_type ?? 'N/A' }}</strong>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Model ID</p>
                        <strong>{{ $log->model_id ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        @if($log->old_values || $log->new_values)
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title\">Data Changes</h6>\n                </div>\n                <div class=\"card-body\">\n                    @if($log->old_values && $log->new_values)\n                        <div class=\"row\">\n                            <div class=\"col-md-6\">\n                                <h6 class=\"mb-3\">Previous Values</h6>\n                                <div class=\"bg-light p-3 rounded\">\n                                    @foreach($log->old_values as $key => $value)\n                                        <div class=\"mb-2\">\n                                            <small class=\"text-muted\">{{ ucwords(str_replace('_', ' ', $key)) }}</small>\n                                            <div><code>{{ $value }}</code></div>\n                                        </div>\n                                    @endforeach\n                                </div>\n                            </div>\n                            <div class=\"col-md-6\">\n                                <h6 class=\"mb-3\">New Values</h6>\n                                <div class=\"bg-light p-3 rounded\">\n                                    @foreach($log->new_values as $key => $value)\n                                        <div class=\"mb-2\">\n                                            <small class=\"text-muted\">{{ ucwords(str_replace('_', ' ', $key)) }}</small>\n                                            <div><code>{{ $value }}</code></div>\n                                        </div>\n                                    @endforeach\n                                </div>\n                            </div>\n                        </div>\n                    @elseif($log->new_values)\n                        <h6 class=\"mb-3\">Values</h6>\n                        <div class=\"bg-light p-3 rounded\">\n                            @foreach($log->new_values as $key => $value)\n                                <div class=\"mb-2\">\n                                    <small class=\"text-muted\">{{ ucwords(str_replace('_', ' ', $key)) }}</small>\n                                    <div><code>{{ $value }}</code></div>\n                                </div>\n                            @endforeach\n                        </div>\n                    @endif\n                </div>\n            </div>\n        @endif\n    </div>\n\n    <div class=\"col-lg-4\">\n        <div class=\"card\">\n            <div class=\"card-header\">\n                <h6 class=\"card-title\">System Information</h6>\n            </div>\n            <div class=\"card-body\">\n                <div class=\"mb-3\">\n                    <p class=\"text-muted small mb-1\">IP Address</p>\n                    <strong>{{ $log->ip_address }}</strong>\n                </div>\n                <div class=\"mb-3\">\n                    <p class=\"text-muted small mb-1\">User Agent</p>\n                    <small class=\"text-break\">{{ $log->user_agent }}</small>\n                </div>\n                <div>\n                    <a href=\"{{ route('audit-logs.index') }}\" class=\"btn btn-sm btn-outline-secondary w-100\">\n                        <i class=\"bi bi-arrow-left me-2\"></i>Back to Logs\n                    </a>\n                </div>\n            </div>\n        </div>\n    </div>\n</div>\n@endsection