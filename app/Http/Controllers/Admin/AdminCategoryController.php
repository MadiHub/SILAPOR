<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProblemCategory;
use App\Models\Department;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ProblemCategory::with('department')
                                ->withCount('reports')
                                ->orderBy('name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($deptId = $request->input('department_id')) {
            $query->where('department_id', $deptId);
        }

        $categories  = $query->paginate(15)->withQueryString();
        $departments = Department::orderBy('name')->get();

        $stats = [
            'total'        => ProblemCategory::count(),
            'total_reports'=> \DB::table('reports')->count(),
            'no_dept'      => ProblemCategory::whereNull('department_id')->count(),
            'top'          => ProblemCategory::withCount('reports')->orderByDesc('reports_count')->first(),
        ];

        return view('Admin.Categories.index', compact('categories', 'departments', 'stats'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('Admin.Categories.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100|unique:problem_categories,name',
            'department_id' => 'required|exists:departments,id',
            'description'   => 'nullable|string',
        ]);

        $category = ProblemCategory::create($validated);

        return redirect()->route('admin.categories.show', $category->id)
                         ->with('success', "Kategori \"{$category->name}\" berhasil dibuat.");
    }

    public function show($id)
    {
        $category = ProblemCategory::with([
            'department',
            'reports' => fn ($q) => $q->with(['user', 'images'])->latest()->limit(10),
        ])->withCount('reports')->findOrFail($id);

        $reportStats = [
            'total'    => $category->reports()->count(),
            'active'   => $category->reports()->where('status', 'active')->count(),
            'process'  => $category->reports()->where('status', 'process')->count(),
            'done'     => $category->reports()->where('status', 'done')->count(),
            'rejected' => $category->reports()->where('status', 'rejected')->count(),
        ];

        $departments = Department::orderBy('name')->get();

        return view('Admin.Categories.show', compact('category', 'reportStats', 'departments'));
    }

    public function edit($id)
    {
        $category    = ProblemCategory::findOrFail($id);
        $departments = Department::orderBy('name')->get();
        return view('Admin.Categories.edit', compact('category', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $category = ProblemCategory::findOrFail($id);

        $validated = $request->validate([
            'name'          => "required|string|max:100|unique:problem_categories,name,{$id}",
            'department_id' => 'required|exists:departments,id',
            'description'   => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.show', $category->id)
                         ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $category = ProblemCategory::findOrFail($id);

        if ($category->reports()->exists()) {
            return back()->with('error', "Tidak dapat menghapus kategori \"{$category->name}\" karena masih memiliki laporan terkait.");
        }

        $name = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
                         ->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }

    public function remap(Request $request, $id)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
        ]);

        $category = ProblemCategory::findOrFail($id);
        $oldDept  = $category->department->name ?? '—';

        $category->update(['department_id' => $request->department_id]);

        $newDept = Department::find($request->department_id)->name;

        return back()->with('success', "Kategori \"{$category->name}\" dipindahkan dari {$oldDept} ke {$newDept}.");
    }
}