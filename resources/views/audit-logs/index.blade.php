@extends('layouts.app')

@section('title', 'Audit Logs')
@section('topbar_title', 'Audit Logs')

@section('content')
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title">Filter Audit Logs</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('audit-logs.index') }}" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label small">Date From</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from', \Carbon\Carbon::now()->subDays(7)->toDateString()) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Date To</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to', \Carbon\Carbon::now()->toDateString()) }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">User</label>
                        <select name="user_id" class="form-control form-control-sm">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Action</label>
                        <select name="action" class="form-control form-control-sm">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                        <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-secondary btn-sm\">Reset</a>\n                    </div>\n                </form>\n            </div>\n        </div>\n    </div>\n</div>\n\n<div class=\"row\">\n    <div class=\"col-lg-12\">\n        <div class=\"card\">\n            <div class=\"card-header\">\n                <h6 class=\"card-title\">Activity Log</h6>\n            </div>\n            <div class=\"card-body p-0\">\n                @if($logs->count())\n                    <div class=\"table-responsive\">\n                        <table class=\"table table-sm mb-0\">\n                            <thead class=\"table-light\">\n                                <tr>\n                                    <th>Date & Time</th>\n                                    <th>User</th>\n                                    <th>Action</th>\n                                    <th>Description</th>\n                                    <th>Model</th>\n                                    <th>IP Address</th>\n                                    <th>Action</th>\n                                </tr>\n                            </thead>\n                            <tbody>\n                                @foreach($logs as $log)\n                                    <tr>\n                                        <td><small class=\"text-muted\">{{ $log->created_at->format('M d, Y H:i') }}</small></td>\n                                        <td>{{ $log->user->name }}</td>\n                                        <td>\n                                            @php\n                                                $actionColors = [\n                                                    'login' => 'badge-primary',\n                                                    'logout' => 'badge-primary',\n                                                    'created' => 'badge-success',\n                                                    'updated' => 'badge-warning',\n                                                    'deleted' => 'badge-danger',\n                                                    'shift' => 'badge-info',\n                                                    'sale' => 'badge-success',\n                                                    'delivery' => 'badge-info',\n                                                    'reconcile' => 'badge-dark'\n                                                ];\n                                                $color = $actionColors[$log->action] ?? 'badge-secondary';\n                                            @endphp\n                                            <span class=\"badge {{ $color }}\">{{ ucfirst($log->action) }}</span>\n                                        </td>\n                                        <td><small>{{ $log->description }}</small></td>\n                                        <td><small class=\"text-muted\">{{ $log->model_type ?? '-' }}</small></td>\n                                        <td><small class=\"text-muted\">{{ $log->ip_address }}</small></td>\n                                        <td>\n                                            <a href=\"{{ route('audit-logs.show', $log) }}\" class=\"btn btn-xs btn-outline-primary\">View</a>\n                                        </td>\n                                    </tr>\n                                @endforeach\n                            </tbody>\n                        </table>\n                    </div>\n                @else\n                    <p class=\"text-center text-muted py-4 mb-0\">No audit logs found</p>\n                @endif\n            </div>\n        </div>\n\n        <!-- Pagination -->\n        <div class=\"d-flex justify-content-center mt-3\">\n            {{ $logs->links() }}\n        </div>\n    </div>\n</div>\n@endsection