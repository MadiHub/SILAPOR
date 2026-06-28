<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Department;
use App\Models\ReportUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminReportController extends Controller
{
    // -------------------------------------------------------
    // INDEX  –  list + filter + search
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $query = Report::with(['user', 'department', 'category', 'images'])
                       ->withCount('votes')
                       ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title',       'like', "%{$search}%")
                  ->orWhere('description','like', "%{$search}%")
                  ->orWhere('address',   'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($deptId = $request->input('department_id')) {
            $query->where('department_id', $deptId);
        }

        if ($catId = $request->input('category_id')) {
            $query->where('category_id', $catId);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // sort
        $sort = $request->input('sort', 'latest');
        match ($sort) {
            'votes'   => $query->orderByDesc('votes_count'),
            'oldest'  => $query->oldest(),
            default   => $query->latest(),
        };

        $reports     = $query->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        $stats = [
            'total'    => Report::count(),
            'active'   => Report::where('status', 'active')->count(),
            'process'  => Report::where('status', 'process')->count(),
            'done'     => Report::where('status', 'done')->count(),
            'rejected' => Report::where('status', 'rejected')->count(),
        ];

        return view('Admin.Reports.index', compact('reports', 'departments', 'stats'));
    }

    // -------------------------------------------------------
    // SHOW
    // -------------------------------------------------------
    public function show($id)
    {
        $report = Report::with([
            'user',
            'department',
            'category',
            'images',
            'updates'         => fn ($q) => $q->with('updatedBy')->latest(),
            'votes.user',
        ])->findOrFail($id);

        $departments = Department::orderBy('name')->get();

        return view('Admin.Reports.show', compact('report', 'departments'));
    }

    // -------------------------------------------------------
    // DESTROY
    // -------------------------------------------------------
    public function destroy($id)
    {
        $report = Report::with('images')->findOrFail($id);

        // Hapus semua gambar dari storage
        foreach ($report->images as $img) {
            Storage::disk('public')->delete($img->image_url);
        }

        $title = $report->title;
        $report->delete();

        return redirect()->route('admin.reports.index')
                         ->with('success', "Laporan \"{$title}\" berhasil dihapus.");
    }

    // -------------------------------------------------------
    // OVERRIDE STATUS  →  tabel: report_updates
    // -------------------------------------------------------
    public function overrideStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,process,done,rejected',
            'note'   => 'nullable|string|max:1000',
        ]);

        $report = Report::findOrFail($id);

        $report->update(['status' => $request->status]);

        ReportUpdate::create([
            'report_id'  => $report->id,
            'status'     => $request->status,
            'note'       => $request->note ?? 'Status diubah oleh admin.',
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', "Status laporan diubah ke \"{$request->status}\".");
    }

    // -------------------------------------------------------
    // REASSIGN DINAS  →  kolom: reports.department_id
    // -------------------------------------------------------
    public function reassign(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'note'          => 'nullable|string|max:500',
        ]);

        $report  = Report::with('department')->findOrFail($id);
        $oldDept = $report->department->name ?? '—';
        $newDept = Department::find($request->department_id);

        $report->update(['department_id' => $request->department_id]);

        ReportUpdate::create([
            'report_id'  => $report->id,
            'status'     => $report->status,
            'note'       => $request->note ?? "Laporan dipindahkan dari {$oldDept} ke {$newDept->name} oleh admin.",
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', "Laporan dipindahkan ke dinas {$newDept->name}.");
    }

    // -------------------------------------------------------
    // ADD PROGRESS  →  tabel: report_updates
    // -------------------------------------------------------
    public function addProgress(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,process,done,rejected',
            'note'   => 'required|string|max:1000',
        ]);

        $report = Report::findOrFail($id);

        $report->update(['status' => $request->status]);

        ReportUpdate::create([
            'report_id'  => $report->id,
            'status'     => $request->status,
            'note'       => $request->note,
            'updated_by' => Auth::id(),
        ]);

        return back()->with('success', 'Progress laporan berhasil ditambahkan.');
    }
}