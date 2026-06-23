<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs
     */
    public function index(Request $request)
    {
        $query = AuditLog::with('user');

        // Filter by user
        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->action) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        $users = User::where('is_active', true)->get();
        $actions = AuditLog::distinct('action')->pluck('action');

        return view('audit-logs.index', compact('logs', 'users', 'actions', 'request'));
    }

    /**
     * Display the specified audit log
     */
    public function show(AuditLog $log)
    {
        $log->load('user');
        return view('audit-logs.show', compact('log'));
    }
}
