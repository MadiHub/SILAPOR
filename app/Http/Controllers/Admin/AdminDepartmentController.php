<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::withCount(['reports', 'users', 'categories'])
                           ->with('categories')
                           ->orderBy('name');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $departments = $query->paginate(12)->withQueryString();

        $stats = [
            'total'         => Department::count(),
            'total_reports' => \DB::table('reports')->count(),
            'total_staff'   => \DB::table('user_departments')->distinct('user_id')->count('user_id'),
            'total_cats'    => \DB::table('problem_categories')->count(),
        ];

        return view('Admin.Departments.index', compact('departments', 'stats'));
    }

    public function create()
    {
        return view('Admin.Departments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:150|unique:departments,name',
            'code'        => 'required|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
        ]);

        $validated['code'] = strtoupper($validated['code']);

        $department = Department::create($validated);

        return redirect()->route('admin.departments.show', $department->id)
                         ->with('success', "Dinas {$department->name} berhasil dibuat.");
    }

    public function show($id)
    {
        $department = Department::with([
            'categories',
            'users' => fn ($q) => $q->orderBy('name'),
            'reports' => fn ($q) => $q->latest()->limit(8),
            'reports.images',
        ])->withCount(['reports', 'users', 'categories'])->findOrFail($id);

        $reportStats = [
            'total'    => $department->reports()->count(),
            'active'   => $department->reports()->where('status', 'active')->count(),
            'process'  => $department->reports()->where('status', 'process')->count(),
            'done'     => $department->reports()->where('status', 'done')->count(),
            'rejected' => $department->reports()->where('status', 'rejected')->count(),
        ];

        $completionRate = $reportStats['total'] > 0
            ? round(($reportStats['done'] / $reportStats['total']) * 100)
            : 0;

        $availableStaff = User::where('role', 'pemda')
            ->whereDoesntHave('departments', fn ($q) => $q->where('department_id', $id))
            ->orderBy('name')
            ->get();

        return view('Admin.Departments.show', compact(
            'department', 'reportStats', 'completionRate', 'availableStaff'
        ));
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('Admin.Departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validated = $request->validate([
            'name'        => "required|string|max:150|unique:departments,name,{$id}",
            'code'        => "required|string|max:50|unique:departments,code,{$id}",
            'description' => 'nullable|string',
        ]);

        $validated['code'] = strtoupper($validated['code']);

        $department->update($validated);

        return redirect()->route('admin.departments.show', $department->id)
                         ->with('success', 'Data dinas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);

        // Guard: jangan hapus kalau masih ada laporan aktif
        if ($department->reports()->whereIn('status', ['active', 'process'])->exists()) {
            return back()->with('error', 'Tidak dapat menghapus dinas yang masih memiliki laporan aktif atau sedang diproses.');
        }

        $name = $department->name;
        $department->delete();

        return redirect()->route('admin.departments.index')
                         ->with('success', "Dinas {$name} berhasil dihapus.");
    }
}