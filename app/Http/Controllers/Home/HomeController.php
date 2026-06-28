<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProblemCategory;
use App\Models\Report;
use App\Models\Department;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        // INSTANSI FOOTER
        $departments = Department::all();

        // Total semua laporan
        $totalReports = Report::count();

        // Laporan diproses
        $processReports = Report::where('status', 'process')->count();

        // Laporan selesai
        $doneReports = Report::where('status', 'done')->count();

        // Total warga (anggap role = warga)
        $totalUsers = User::where('role', 'warga')->count();

        $problem_categories = ProblemCategory::withCount([
        'reports' => function ($query) {$query->where('status', '<>', 'done');}])->get();

        $reports = Report::with([
            'images',
            'category',
            'user',
            'comments',
            'updates'
        ])
        ->withCount(['comments', 'votes']) // 🔥 TAMBAH INI
        ->orderByDesc('votes_count')
        ->take(6)
        ->get()
        ->map(function ($report) {
            return [
                'id' => $report->id,
                'title' => $report->title,
                'description' => $report->description,
                'address' => $report->address,
                'latitude' => $report->latitude,   
                'longitude' => $report->longitude, 
                'votes_count' => $report->votes_count,
                'comments_count' => $report->comments_count,
                'status' => $report->status,

                'is_voted' => auth()->check()
                    ? $report->votes->contains('user_id', auth()->id())
                    : false,

                'category' => $report->category,
                'images' => $report->images,
                'created_at' => $report->created_at,
                'updates' => $report->updates->map(function ($update) {
                    return [
                        'note' => $update->note,
                        'status' => $update->status,
                        'created_at' => $update->created_at,
                    ];
                }),
            ];
        });

        return view('Home.index', [
            'problem_categories' => $problem_categories,
            'reports' => $reports,
            'departments' => $departments,
            'totalReports' => $totalReports,
            'processReports'=> $processReports,
            'doneReports' => $doneReports,
            'totalUsers' => $totalUsers
        ]);

    }

    public function about()
    {
        return view('Home.about');
    }

    public function statistics()
    {
        // ---------------------------------------------------
        // 1. RINGKASAN ANGKA
        // ---------------------------------------------------
        $totalLaporan   = Report::count();
        $totalAktif     = Report::where('status', 'active')->count();
        $totalProses    = Report::where('status', 'process')->count();
        $totalSelesai   = Report::where('status', 'done')->count();
        $totalDitolak   = Report::where('status', 'rejected')->count();
        $totalWarga     = User::where('role', 'warga')->count(); // sesuaikan nama role/kolom kalau beda
    
        // ---------------------------------------------------
        // 2. TREN 6 BULAN TERAKHIR
        // ---------------------------------------------------
        $trendRaw = Report::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as bulan, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->pluck('total', 'bulan');
    
        // pastikan 6 bulan selalu muncul walau datanya 0 (biar chart gak bolong)
        $trendLabels = [];
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $label = now()->subMonths($i)->translatedFormat('M Y'); // contoh: "Jan 2026"
            $trendLabels[] = $label;
            $trendData[] = $trendRaw[$key] ?? 0;
        }
    
        // ---------------------------------------------------
        // 3. BREAKDOWN PER KATEGORI
        // ---------------------------------------------------
        $kategoriStats = ProblemCategory::select('problem_categories.id', 'problem_categories.name', 'problem_categories.icon')
            ->withCount('reports') // pastikan relasi reports() ada di model ProblemCategory
            ->orderByDesc('reports_count')
            ->get();
    
        $kategoriLabels = $kategoriStats->pluck('name');
        $kategoriData   = $kategoriStats->pluck('reports_count');
    
        // ---------------------------------------------------
        // 4. LEADERBOARD WILAYAH (ekstrak dari address)
        // ---------------------------------------------------
        $allAddresses = Report::whereNotNull('address')->pluck('address');
    
        $wilayahCount = [];
    
        foreach ($allAddresses as $address) {
            $wilayah = $this->extractWilayahFromAddress($address);
    
            if ($wilayah) {
                $wilayahCount[$wilayah] = ($wilayahCount[$wilayah] ?? 0) + 1;
            }
        }
    
        // urutkan dari yang paling banyak, ambil top 5
        arsort($wilayahCount);
        $wilayahTop = array_slice($wilayahCount, 0, 5, true);
    
        // ---------------------------------------------------
        // 5. BREAKDOWN STATUS (untuk donut chart)
        // ---------------------------------------------------
        $statusBreakdown = [
            'active'   => $totalAktif,
            'process'  => $totalProses,
            'done'     => $totalSelesai,
            'rejected' => $totalDitolak,
        ];
    
        return view('Home.statistics', [
            'totalLaporan'     => $totalLaporan,
            'totalAktif'       => $totalAktif,
            'totalProses'      => $totalProses,
            'totalSelesai'     => $totalSelesai,
            'totalDitolak'     => $totalDitolak,
            'totalWarga'       => $totalWarga,
            'trendLabels'      => $trendLabels,
            'trendData'        => $trendData,
            'kategoriLabels'   => $kategoriLabels,
            'kategoriData'     => $kategoriData,
            'kategoriStats'    => $kategoriStats,
            'wilayahTop'       => $wilayahTop,
            'statusBreakdown'  => $statusBreakdown,
        ]);
    }
    
    private function extractWilayahFromAddress(string $address): ?string
    {
        $parts = array_map('trim', explode(',', $address));
    
        // buang "Indonesia" dan segmen yang full angka (kode pos)
        $parts = array_filter($parts, function ($p) {
            return $p !== '' && strcasecmp($p, 'Indonesia') !== 0 && !preg_match('/^\d+$/', $p);
        });
    
        $parts = array_values($parts);
        $count = count($parts);
    
        if ($count === 0) {
            return null;
        }
    
        if ($count >= 3) {
            return $parts[$count - 3];
        }
    
        return $parts[$count - 1];
    }

    public function caraLapor()
    {
        return view('Home.cara-lapor');
    }

    public function faq()
    {
        return view('Home.faq');
    }

    public function kebijakanPrivasi()
    {
        return view('Home.kebijakan-privasi');
    }

    public function syaratKetentuan()
    {
        return view('Home.syarat-ketentuan');
    }

    public function departments(Request $request)
    {
        $search = $request->get('search');
 
        $departments = Department::withCount(['reports', 'categories'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();
 
        $totalDepartments = Department::count();
        $totalReports     = \App\Models\Report::count();
 
        return view('Home.departments', compact('departments', 'totalDepartments', 'totalReports', 'search'));
    }
}
