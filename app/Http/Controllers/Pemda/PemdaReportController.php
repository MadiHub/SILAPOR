<?php

namespace App\Http\Controllers\Pemda;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\ProblemCategory;
use App\Models\ReportUpdate;
use Illuminate\Http\Request;
use App\Exports\ReportsExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;


class PemdaReportController extends Controller
{
   private function getDepartmentId(): int
    {
        return auth()->user()->departments->first()->id;
    }
 
    public function index(Request $request)
    {
        $departmentId = $this->getDepartmentId();
 
        $query = Report::with(['category', 'images', 'user'])
            ->where('department_id', $departmentId);
 
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
 
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
 
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
 
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
 
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'oldest'    => $query->oldest(),
            'votes'     => $query->orderByDesc('votes_count'),
            default     => $query->latest(),
        };
 
        $reports    = $query->paginate(10)->withQueryString();
        $categories = ProblemCategory::where('department_id', $departmentId)->get();
 
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

    private function buildReportQuery(Request $request)
    {
        $q = Report::with(['user', 'category', 'images'])
            ->withCount('votes');

        if ($request->filled('search')) {
            $q->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $q->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $q->whereDate('created_at', '>=', $request->date_from);
        }

        match ($request->get('sort', 'latest')) {
            'oldest' => $q->oldest(),
            'votes'  => $q->orderByDesc('votes_count'),
            default  => $q->latest(),
        };

        return $q;
    }

    // ---- Export Excel ----
    public function exportExcel(Request $request)
    {
        $filters  = $request->only(['search', 'status', 'category_id', 'date_from', 'sort']);
        $filename = 'laporan-pengaduan-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(new ReportsExport($filters), $filename);
    }

    // ---- Export PDF ----
    public function exportPdf(Request $request)
    {
        $reports = $this->buildReportQuery($request)->get();

        // Hitung stats dari seluruh data (tanpa filter) untuk kartu ringkasan
        $allStats = Report::selectRaw("
            COUNT(*) as total,
            SUM(status = 'active')   as active,
            SUM(status = 'process')  as process,
            SUM(status = 'done')     as done,
            SUM(status = 'rejected') as rejected
        ")->first();

        $stats = [
            'total'    => $allStats->total,
            'active'   => $allStats->active,
            'process'  => $allStats->process,
            'done'     => $allStats->done,
            'rejected' => $allStats->rejected,
        ];

        // Teks filter aktif
        $filterParts = [];
        if ($request->filled('search'))      $filterParts[] = 'Kata kunci: "' . $request->search . '"';
        if ($request->filled('status'))      $filterParts[] = 'Status: ' . $request->status;
        if ($request->filled('category_id')) $filterParts[] = 'Kategori ID: ' . $request->category_id;
        if ($request->filled('date_from'))   $filterParts[] = 'Dari tanggal: ' . $request->date_from;
        $filterText = implode(' · ', $filterParts);

        $pdf = Pdf::loadView('Pemda.reports.export_pdf', compact('reports', 'stats', 'filterText'))
                ->setPaper('a4', 'landscape');

        $filename = 'laporan-pengaduan-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

}
