<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Department;
use App\Models\User;
use App\Models\ProblemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminStatsController extends Controller
{
    public function overview(Request $request)
    {
        $period = $request->input('period', '30'); // hari

        $totalReports   = Report::count();
        $newThisPeriod  = Report::where('created_at', '>=', now()->subDays($period))->count();
        $doneThisPeriod = Report::where('status', 'done')
                                ->where('updated_at', '>=', now()->subDays($period))->count();

        $statusCounts = Report::selectRaw('status, COUNT(*) as total')
                              ->groupBy('status')
                              ->pluck('total', 'status');

        $avgResolutionDays = DB::table('report_updates as ru')
            ->join('reports as r', 'r.id', '=', 'ru.report_id')
            ->where('ru.status', 'done')
            ->selectRaw('AVG(DATEDIFF(ru.created_at, r.created_at)) as avg_days')
            ->value('avg_days');

        $dailyReports = Report::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $statusChart = [
            'active'   => $statusCounts['active']   ?? 0,
            'process'  => $statusCounts['process']  ?? 0,
            'done'     => $statusCounts['done']      ?? 0,
            'rejected' => $statusCounts['rejected']  ?? 0,
        ];

        $totalUsers     = User::count();
        $activeUsers    = User::where('status', 'active')->count();
        $newUsers       = User::where('created_at', '>=', now()->subDays($period))->count();
        $bannedUsers    = User::where('status', 'banned')->count();

        $usersByRole = User::selectRaw('role, COUNT(*) as total')
                           ->groupBy('role')
                           ->pluck('total', 'role');

        $topReporters = User::withCount('reports')
                            ->having('reports_count', '>', 0)
                            ->orderByDesc('reports_count')
                            ->limit(5)
                            ->get();

        $topVoted = Report::with('department')
                          ->orderByDesc('votes_count')
                          ->limit(5)
                          ->get();

        $deptPerformance = Department::withCount([
            'reports',
            'reports as done_count' => fn ($q) => $q->where('status', 'done'),
        ])->having('reports_count', '>', 0)
          ->orderByDesc('reports_count')
          ->limit(8)
          ->get()
          ->map(fn ($d) => [
              'name'  => $d->name,
              'code'  => $d->code,
              'total' => $d->reports_count,
              'done'  => $d->done_count,
              'pct'   => $d->reports_count > 0 ? round($d->done_count / $d->reports_count * 100) : 0,
          ]);

        $topCategories = ProblemCategory::withCount('reports')
                                        ->having('reports_count', '>', 0)
                                        ->orderByDesc('reports_count')
                                        ->limit(8)
                                        ->get();

        return view('Admin.Stats.overview', compact(
            'period', 'totalReports', 'newThisPeriod', 'doneThisPeriod',
            'avgResolutionDays', 'statusChart', 'dailyReports',
            'totalUsers', 'activeUsers', 'newUsers', 'bannedUsers',
            'usersByRole', 'topReporters', 'topVoted',
            'deptPerformance', 'topCategories'
        ));
    }

    public function trends(Request $request)
    {
        $period    = $request->input('period', '90');
        $groupBy   = $request->input('group', 'day'); 
        $format = match ($groupBy) {
            'week'  => '%Y-%u',
            'month' => '%Y-%m',
            default => '%Y-%m-%d',
        };
        $labelFormat = match ($groupBy) {
            'week'  => '%Y W%u',
            'month' => '%b %Y',
            default => '%d %b',
        };

        $incomingTrend = Report::selectRaw("DATE_FORMAT(created_at, '{$format}') as period_key,
                                            DATE_FORMAT(created_at, '{$labelFormat}') as label,
                                            COUNT(*) as total")
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('period_key', 'label')
            ->orderBy('period_key')
            ->get();

        $doneTrend = Report::selectRaw("DATE_FORMAT(updated_at, '{$format}') as period_key,
                                        DATE_FORMAT(updated_at, '{$labelFormat}') as label,
                                        COUNT(*) as total")
            ->where('status', 'done')
            ->where('updated_at', '>=', now()->subDays($period))
            ->groupBy('period_key', 'label')
            ->orderBy('period_key')
            ->get();

        $userTrend = User::selectRaw("DATE_FORMAT(created_at, '{$format}') as period_key,
                                      DATE_FORMAT(created_at, '{$labelFormat}') as label,
                                      COUNT(*) as total")
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('period_key', 'label')
            ->orderBy('period_key')
            ->get();

        $voteTrend = DB::table('report_votes')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as period_key,
                         DATE_FORMAT(created_at, '{$labelFormat}') as label,
                         COUNT(*) as total")
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('period_key', 'label')
            ->orderBy('period_key')
            ->get();

        $hourlyDistribution = Report::selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('hour')
            ->orderBy('hour')
            ->pluck('total', 'hour');

        $hourly = collect(range(0, 23))->map(fn ($h) => [
            'hour'  => $h,
            'label' => sprintf('%02d:00', $h),
            'total' => $hourlyDistribution[$h] ?? 0,
        ]);

        $weekdayDistribution = Report::selectRaw('DAYOFWEEK(created_at) as dow, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays($period))
            ->groupBy('dow')
            ->pluck('total', 'dow');

        $dowLabels = [1=>'Min',2=>'Sen',3=>'Sel',4=>'Rab',5=>'Kam',6=>'Jum',7=>'Sab'];
        $weekday   = collect(range(1,7))->map(fn ($d) => [
            'label' => $dowLabels[$d],
            'total' => $weekdayDistribution[$d] ?? 0,
        ]);

        return view('Admin.Stats.trends', compact(
            'period', 'groupBy',
            'incomingTrend', 'doneTrend', 'userTrend', 'voteTrend',
            'hourly', 'weekday'
        ));
    }

    public function departments(Request $request)
    {
        $period = $request->input('period', '90');

        $departments = Department::withCount([
            'reports',
            'reports as active_count'   => fn ($q) => $q->where('status', 'active'),
            'reports as process_count'  => fn ($q) => $q->where('status', 'process'),
            'reports as done_count'     => fn ($q) => $q->where('status', 'done'),
            'reports as rejected_count' => fn ($q) => $q->where('status', 'rejected'),
            'reports as period_count'   => fn ($q) => $q->where('created_at', '>=', now()->subDays($period)),
            'users',
            'categories',
        ])->get()->map(function ($dept) {
            $dept->completion_rate = $dept->reports_count > 0
                ? round($dept->done_count / $dept->reports_count * 100)
                : 0;

            $dept->avg_days = DB::table('report_updates as ru')
                ->join('reports as r', 'r.id', '=', 'ru.report_id')
                ->where('r.department_id', $dept->id)
                ->where('ru.status', 'done')
                ->selectRaw('AVG(DATEDIFF(ru.created_at, r.created_at)) as avg_days')
                ->value('avg_days');

            return $dept;
        })->sortByDesc('reports_count');

        $topDeptIds = $departments->take(5)->pluck('id');

        $deptTrend = Report::selectRaw('department_id, DATE(created_at) as date, COUNT(*) as total')
            ->whereIn('department_id', $topDeptIds)
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('department_id', 'date')
            ->orderBy('date')
            ->get()
            ->groupBy('department_id');

        return view('Admin.Stats.departments', compact('departments', 'period', 'deptTrend'));
    }

    public function topVotes(Request $request)
    {
        $status   = $request->input('status', '');
        $deptId   = $request->input('department_id', '');
        $limit    = $request->input('limit', 20);

        $query = Report::with(['user', 'department', 'category', 'images'])
                       ->orderByDesc('votes_count');

        if ($status) $query->where('status', $status);
        if ($deptId) $query->where('department_id', $deptId);

        $reports     = $query->paginate($limit)->withQueryString();
        $departments = Department::orderBy('name')->get();

        $totalVotes    = DB::table('report_votes')->count();
        $uniqueVoters  = DB::table('report_votes')->distinct('user_id')->count('user_id');
        $avgVotesPerReport = $totalVotes > 0 && Report::count() > 0
            ? round($totalVotes / Report::count(), 1)
            : 0;
        $mostVoted = Report::orderByDesc('votes_count')->first();

        return view('Admin.Stats.top_votes', compact(
            'reports', 'departments', 'status', 'deptId',
            'totalVotes', 'uniqueVoters', 'avgVotesPerReport', 'mostVoted'
        ));
    }

    public function export(Request $request)
    {
        $type   = $request->input('type', 'reports');
        $period = $request->input('period', '30');
        $status = $request->input('status', '');

        $filename = "{$type}_export_" . now()->format('Ymd_His') . ".csv";

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = match ($type) {
            'reports'     => fn () => $this->streamReportsCsv($period, $status),
            'users'       => fn () => $this->streamUsersCsv($period),
            'departments' => fn () => $this->streamDepartmentsCsv(),
            'votes'       => fn () => $this->streamVotesCsv($period),
            default       => fn () => $this->streamReportsCsv($period, $status),
        };

        return response()->stream($callback, 200, $headers);
    }

    private function streamReportsCsv(string $period, string $status): void
    {
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($out, ['ID','Judul','Pelapor','Email Pelapor','Dinas','Kategori','Status','Vote','Alamat','Tanggal']);

        Report::with(['user','department','category'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->where('created_at', '>=', now()->subDays($period))
            ->orderByDesc('created_at')
            ->chunk(500, function ($reports) use ($out) {
                foreach ($reports as $r) {
                    fputcsv($out, [
                        $r->id,
                        $r->title,
                        $r->user->name    ?? '—',
                        $r->user->email   ?? '—',
                        $r->department->name ?? '—',
                        $r->category->name   ?? '—',
                        $r->status,
                        $r->votes_count,
                        $r->address,
                        $r->created_at->format('Y-m-d H:i'),
                    ]);
                }
            });

        fclose($out);
    }

    private function streamUsersCsv(string $period): void
    {
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($out, ['ID','Nama','Email','Telepon','Role','Status','Total Laporan','Login Terakhir','Bergabung']);

        User::withCount('reports')
            ->where('created_at', '>=', now()->subDays($period))
            ->orderByDesc('created_at')
            ->chunk(500, function ($users) use ($out) {
                foreach ($users as $u) {
                    fputcsv($out, [
                        $u->id,
                        $u->name,
                        $u->email,
                        $u->phone    ?? '—',
                        $u->role,
                        $u->status,
                        $u->reports_count,
                        $u->last_login_at?->format('Y-m-d H:i') ?? '—',
                        $u->created_at->format('Y-m-d H:i'),
                    ]);
                }
            });

        fclose($out);
    }

    private function streamDepartmentsCsv(): void
    {
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($out, ['ID','Nama','Kode','Total Laporan','Aktif','Diproses','Selesai','Ditolak','% Selesai','Total Staf','Total Kategori']);

        Department::withCount([
            'reports',
            'reports as active_count'   => fn ($q) => $q->where('status','active'),
            'reports as process_count'  => fn ($q) => $q->where('status','process'),
            'reports as done_count'     => fn ($q) => $q->where('status','done'),
            'reports as rejected_count' => fn ($q) => $q->where('status','rejected'),
            'users',
            'categories',
        ])->orderBy('name')->chunk(100, function ($depts) use ($out) {
            foreach ($depts as $d) {
                $pct = $d->reports_count > 0
                    ? round($d->done_count / $d->reports_count * 100)
                    : 0;
                fputcsv($out, [
                    $d->id, $d->name, $d->code,
                    $d->reports_count, $d->active_count, $d->process_count,
                    $d->done_count, $d->rejected_count, $pct . '%',
                    $d->users_count, $d->categories_count,
                ]);
            }
        });

        fclose($out);
    }

    private function streamVotesCsv(string $period): void
    {
        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($out, ['ID Vote','Nama Voter','Email Voter','ID Laporan','Judul Laporan','Tanggal Vote']);

        DB::table('report_votes as rv')
            ->join('users as u', 'u.id', '=', 'rv.user_id')
            ->join('reports as r', 'r.id', '=', 'rv.report_id')
            ->where('rv.created_at', '>=', now()->subDays($period))
            ->select('rv.id','u.name','u.email','r.id as report_id','r.title','rv.created_at')
            ->orderByDesc('rv.created_at')
            ->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->id, $row->name, $row->email,
                        $row->report_id, $row->title,
                        $row->created_at,
                    ]);
                }
            });

        fclose($out);
    }
}