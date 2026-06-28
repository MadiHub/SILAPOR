<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportUpdate;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ReportUpdate::with(['report', 'updatedBy'])
                             ->latest('created_at');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('note', 'like', "%{$search}%")
                  ->orWhereHas('report', fn ($r) => $r->where('title', 'like', "%{$search}%"))
                  ->orWhereHas('updatedBy', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($userId = $request->input('user_id')) {
            $query->where('updated_by', $userId);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $logs = $query->paginate(20)->withQueryString();

        $stats = [
            'total'        => ReportUpdate::count(),
            'today'        => ReportUpdate::whereDate('created_at', today())->count(),
            'this_week'    => ReportUpdate::where('created_at', '>=', now()->startOfWeek())->count(),
            'active_staff' => ReportUpdate::distinct('updated_by')
                                          ->whereDate('created_at', today())
                                          ->count('updated_by'),
        ];

        $staffList = User::whereIn('id',
            ReportUpdate::distinct()->pluck('updated_by')->filter()
        )->orderBy('name')->get();

        return view('Admin.AuditLogs.index', compact('logs', 'stats', 'staffList'));
    }

    public function byUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $query = ReportUpdate::with(['report', 'updatedBy'])
                             ->where('updated_by', $id)
                             ->latest('created_at');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $logs = $query->paginate(20)->withQueryString();

        $userStats = [
            'total'     => ReportUpdate::where('updated_by', $id)->count(),
            'today'     => ReportUpdate::where('updated_by', $id)->whereDate('created_at', today())->count(),
            'done'      => ReportUpdate::where('updated_by', $id)->where('status', 'done')->count(),
            'rejected'  => ReportUpdate::where('updated_by', $id)->where('status', 'rejected')->count(),
        ];

        return view('Admin.AuditLogs.by_user', compact('user', 'logs', 'userStats'));
    }

    public function byReport(Request $request, $id)
    {
        $report = Report::with(['user', 'department', 'category'])->findOrFail($id);

        $logs = ReportUpdate::with('updatedBy')
                            ->where('report_id', $id)
                            ->oldest('created_at')
                            ->get();

        return view('Admin.AuditLogs.by_report', compact('report', 'logs'));
    }
}