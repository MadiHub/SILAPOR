<?php

namespace App\Http\Controllers\Pemda;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\ProblemCategory;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PemdaDashboardController extends Controller
{

    public function dashboard() {
        $user = auth()->user();
        $departmentId = $user->departments->first()->id;

        $reportsQuery = Report::where('department_id', $departmentId);

        $totalReports = (clone $reportsQuery)->count();
        $active = (clone $reportsQuery)->where('status', 'active')->count();
        $pending = (clone $reportsQuery)->where('status', 'pending')->count();
        $process = (clone $reportsQuery)->where('status', 'process')->count();
        $done = (clone $reportsQuery)->where('status', 'done')->count();
        $rejected = (clone $reportsQuery)->where('status', 'rejected')->count();

        $latestReports = Report::with(['category', 'images'])
            ->where('department_id', $departmentId)
            ->latest()
            ->take(5)
            ->get();

        $priorityReports = Report::with(['category'])
            ->where('department_id', $departmentId)
            ->orderByDesc('votes_count')
            ->take(5)
            ->get();

        $chartReports = Report::where('department_id', $departmentId)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topCategories = ProblemCategory::withCount(['reports' => function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        }])
        ->orderByDesc('reports_count')
        ->take(5)
        ->get();

        return view('Pemda.dashboard', compact(
            'totalReports',
            'active',
            'pending',
            'process',
            'done',
            'rejected',
            'latestReports',
            'priorityReports',
            'chartReports',
            'topCategories'
        ));
    }
}
