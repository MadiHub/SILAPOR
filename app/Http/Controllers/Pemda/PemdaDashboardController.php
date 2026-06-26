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

        // 🔢 Statistik
        $reportsQuery = Report::where('department_id', $departmentId);

        $totalReports = (clone $reportsQuery)->count();
        $active = (clone $reportsQuery)->where('status', 'active')->count();
        $pending = (clone $reportsQuery)->where('status', 'pending')->count();
        $process = (clone $reportsQuery)->where('status', 'process')->count();
        $done = (clone $reportsQuery)->where('status', 'done')->count();
        $rejected = (clone $reportsQuery)->where('status', 'rejected')->count();

        // 📋 Laporan terbaru (with relasi biar hemat query)
        $latestReports = Report::with(['category', 'images'])
            ->where('department_id', $departmentId)
            ->latest()
            ->take(5)
            ->get();

        // 🔥 Laporan prioritas (votes terbanyak)
        $priorityReports = Report::with(['category'])
            ->where('department_id', $departmentId)
            ->orderByDesc('votes_count')
            ->take(5)
            ->get();

        // 📊 Grafik per hari
        $chartReports = Report::where('department_id', $departmentId)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 🏆 Top kategori (ini keren banget 🔥)
        $topCategories = ProblemCategory::withCount(['reports' => function ($q) use ($departmentId) {
            $q->where('department_id', $departmentId);
        }])
        ->orderByDesc('reports_count')
        ->take(5)
        ->get();

// dd([
//     'department_DLH' => DB::table('departments')->where('code', 'DLH')->first(),
//     'department_id_8' => DB::table('departments')->where('id', 8)->first(),
//     'department_id_10' => DB::table('departments')->where('id', 10)->first(),
//     'department_id_17' => DB::table('departments')->where('id', 17)->first(),
//     'report_dept_ids' => DB::table('reports')->pluck('department_id', 'id'),
// ]);
//         dd([
//             'user_id' => $user->id,
//             'departments' => $user->departments, // cek isinya
//             'departmentId' => $departmentId,
//             'total_report_di_db' => Report::count(), // total semua report tanpa filter
//             'report_department_ids' => Report::pluck('department_id')->unique(), // liat department_id apa aja yg ada
//         ]);

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
