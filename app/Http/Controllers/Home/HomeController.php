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
            'comments'
        ])
        ->withCount(['comments', 'votes']) // 🔥 TAMBAH INI
        ->orderByDesc('votes_count')
        ->take(12)
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


}
