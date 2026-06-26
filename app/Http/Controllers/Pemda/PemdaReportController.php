<?php

namespace App\Http\Controllers\Pemda;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ProblemCategory;
use App\Models\ReportUpdate;
use Illuminate\Http\Request;

class PemdaReportController extends Controller
{
   private function getDepartmentId(): int
    {
        return auth()->user()->departments->first()->id;
    }
 
    // ─────────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $departmentId = $this->getDepartmentId();
 
        $query = Report::with(['category', 'images', 'user'])
            ->where('department_id', $departmentId);
 
        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
 
        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
 
        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
 
        // Search judul / alamat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
 
        // Sort
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'oldest'    => $query->oldest(),
            'votes'     => $query->orderByDesc('votes_count'),
            default     => $query->latest(),
        };
 
        $reports    = $query->paginate(10)->withQueryString();
        $categories = ProblemCategory::where('department_id', $departmentId)->get();
 
        // Stat ringkasan
        $baseQuery    = Report::where('department_id', $departmentId);
        $stats = [
            'total'    => (clone $baseQuery)->count(),
            'active'   => (clone $baseQuery)->where('status', 'active')->count(),
            'process'  => (clone $baseQuery)->where('status', 'process')->count(),
            'done'     => (clone $baseQuery)->where('status', 'done')->count(),
            'rejected' => (clone $baseQuery)->where('status', 'rejected')->count(),
        ];
 
        return view('Pemda.reports.index', compact('reports', 'categories', 'stats'));
    }
 
    // ─────────────────────────────────────────────
    //  SHOW
    // ─────────────────────────────────────────────
    public function show(int $id)
    {
        $departmentId = $this->getDepartmentId();
 
        $report = Report::with([
                'category',
                'user',
                'images',
                'votes',
                'comments.user',
                'updates',
            ])
            ->where('department_id', $departmentId)
            ->findOrFail($id);
 
        return view('Pemda.reports.show', compact('report'));
    }
 
    // ─────────────────────────────────────────────
    //  UPDATE STATUS
    // ─────────────────────────────────────────────
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', 'in:active,process,done,rejected'],
        ]);
 
        $departmentId = $this->getDepartmentId();
 
        $report = Report::where('department_id', $departmentId)->findOrFail($id);
        $report->update(['status' => $request->status]);
 
        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
 
    // ─────────────────────────────────────────────
    //  ADD PROGRESS / CATATAN UPDATE
    // ─────────────────────────────────────────────
    public function addProgress(Request $request, int $id)
    {
        $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);
 
        $departmentId = $this->getDepartmentId();
 
        $report = Report::where('department_id', $departmentId)->findOrFail($id);
 
        ReportUpdate::create([
            'report_id' => $report->id,
            'note'      => $request->note,
            'updated_by' => auth()->id()
        ]);
 
        return back()->with('success', 'Catatan progress berhasil ditambahkan.');
    }
}
