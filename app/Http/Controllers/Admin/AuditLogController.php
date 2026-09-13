<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $action = $request->get('action');

        $query = AuditLog::with('user')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($action) {
            $query->where('action', $action);
        }

        $auditLogs = $query->paginate(30)->withQueryString();
        $actions = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit-logs.index', compact('auditLogs', 'actions', 'search', 'action'));
    }
}
