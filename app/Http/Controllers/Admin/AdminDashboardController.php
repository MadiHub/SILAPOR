<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ReportUpdate;
use App\Models\Department;
use App\Models\User;
use App\Models\ProblemCategory;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ── Stat cards ──────────────────────────────────────────
        $totalReports     = Report::count();
        $active          = Report::where('status', 'active')->count();
        $process          = Report::where('status', 'process')->count();
        $done             = Report::where('status', 'done')->count();
        $rejected         = Report::where('status', 'rejected')->count();

        $totalUsers       = User::count();
        $totalDepartments = Department::count();
        $bannedUsers      = User::where('status', 'banned')->count();

        // ── Line chart: laporan per hari (30 hari terakhir) ─────
        $chartReports = Report::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // ── Doughnut: status ────────────────────────────────────
        $statusChartData = [
            'active'  => $active,
            'process'  => $process,
            'done'     => $done,
            'rejected' => $rejected,
        ];

        // ── Bar chart: laporan per dinas ────────────────────────
        $departmentChartData = Department::select(
                'departments.id',
                'departments.name',
                'departments.code',
                DB::raw('COUNT(reports.id) as total')
            )
            ->leftJoin('reports', 'reports.department_id', '=', 'departments.id')
            ->groupBy('departments.id', 'departments.name', 'departments.code')
            ->orderByDesc('total')
            ->get();

        // ── Doughnut: top 5 kategori ────────────────────────────
        $topCategories = ProblemCategory::withCount('reports')
            ->orderByDesc('reports_count')
            ->limit(5)
            ->get(['id', 'name', 'reports_count']);

        // ── Tabel: laporan terbaru (10) ─────────────────────────
        $latestReports = Report::with(['images', 'category', 'department'])
            ->latest()
            ->limit(10)
            ->get();

        // ── Laporan prioritas: top 5 votes ──────────────────────
        $priorityReports = Report::with(['category', 'department'])
            ->orderByDesc('votes_count')
            ->limit(5)
            ->get();

        // ── Performa per dinas ──────────────────────────────────
        $departmentStats = Department::select(
                'departments.id',
                'departments.name',
                'departments.code',
                DB::raw('COUNT(reports.id) as total_reports'),
                DB::raw('SUM(CASE WHEN reports.status = "done" THEN 1 ELSE 0 END) as done_reports')
            )
            ->leftJoin('reports', 'reports.department_id', '=', 'departments.id')
            ->groupBy('departments.id', 'departments.name', 'departments.code')
            ->orderByDesc('total_reports')
            ->get();

                // ── Pengguna terbaru (5) ────────────────────────────────
            $latestUsers = User::where('role', 'warga')
            ->latest()
            ->limit(5)
            ->get();

        // ── Audit log: report_updates terbaru (10) ───────────────
        $recentActivities = ReportUpdate::with(['report', 'updatedBy'])
            ->latest()
            ->limit(10)
            ->get();

        return view('Admin.dashboard', compact(
            'totalReports',
            'active',
            'process',
            'done',
            'rejected',
            'totalUsers',
            'totalDepartments',
            'bannedUsers',
            'chartReports',
            'statusChartData',
            'departmentChartData',
            'topCategories',
            'latestReports',
            'priorityReports',
            'departmentStats',
            'latestUsers',
            'recentActivities',
        ));
    }
}