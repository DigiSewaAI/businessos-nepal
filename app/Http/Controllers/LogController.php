<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Schema; // 👈 यो line थप्नुहोस्

class LogController extends Controller
{
    public function index()
    {
        $logs = AuditLog::with(['user', 'organization'])
            ->latest()
            ->paginate(50);

        $logTypes = [];
        if (Schema::hasColumn('audit_logs', 'action')) {
            $logTypes = AuditLog::select('action')->distinct()->pluck('action');
        }

        return view('admin.logs.index', compact('logs', 'logTypes'));
    }
}