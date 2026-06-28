<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    // -------------------------------------------------------
    // INDEX  –  list + filter + search
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $query = User::with('departments')->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name',  'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $users = $query->paginate(15)->withQueryString();

        $stats = [
            'total'    => User::count(),
            'active'   => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'banned'   => User::where('status', 'banned')->count(),
            'admin'    => User::where('role', 'admin')->count(),
            'pemda'    => User::where('role', 'pemda')->count(),
            'warga'    => User::where('role', 'warga')->count(),
        ];

        return view('Admin.Users.index', compact('users', 'stats'));
    }

    // -------------------------------------------------------
    // CREATE
    // -------------------------------------------------------
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('Admin.Users.create', compact('departments'));
    }

    // -------------------------------------------------------
    // STORE
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:150',
            'email'           => 'required|email|max:150|unique:users,email',
            'phone'           => 'nullable|string|max:20',
            'password'        => ['required', Password::min(8)],
            'role'            => 'required|in:admin,warga,pemda',
            'status'          => 'required|in:active,inactive,banned',
            'avatar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'department_ids'  => 'nullable|array',
            'department_ids.*'=> 'exists:departments,id',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        if (!empty($validated['department_ids'])) {
            foreach ($validated['department_ids'] as $deptId) {
                $user->departments()->attach($deptId);
            }
        }

        return redirect()->route('admin.users.show', $user->id)
                         ->with('success', 'Pengguna berhasil dibuat.');
    }

    // -------------------------------------------------------
    // SHOW
    // -------------------------------------------------------
    public function show($id)
    {
        $user = User::with([
            'departments',
            'reports' => fn ($q) => $q->latest()->limit(5),
            'reports.department',
        ])->findOrFail($id);

        $reportStats = [
            'total'    => $user->reports()->count(),
            'active'   => $user->reports()->where('status', 'active')->count(),
            'process'  => $user->reports()->where('status', 'process')->count(),
            'done'     => $user->reports()->where('status', 'done')->count(),
            'rejected' => $user->reports()->where('status', 'rejected')->count(),
        ];

        $departments = Department::orderBy('name')->get();

        return view('Admin.Users.show', compact('user', 'reportStats', 'departments'));
    }

    public function edit($id)
    {
        $user        = User::with('departments')->findOrFail($id);
        $departments = Department::orderBy('name')->get();
        return view('Admin.Users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'            => 'required|string|max:150',
            'email'           => "required|email|max:150|unique:users,email,{$id}",
            'phone'           => 'nullable|string|max:20',
            'password'        => ['nullable', Password::min(8)],
            'role'            => 'required|in:admin,warga,pemda',
            'status'          => 'required|in:active,inactive,banned',
            'avatar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'department_ids'  => 'nullable|array',
            'department_ids.*'=> 'exists:departments,id',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && !str_contains($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        $user->departments()->detach();
        if (!empty($validated['department_ids'])) {
            foreach ($validated['department_ids'] as $deptId) {
                $user->departments()->attach($deptId);
            }
        }

        return redirect()->route('admin.users.show', $user->id)
                         ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->avatar && !str_contains($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'Pengguna berhasil dihapus.');
    }

    public function ban($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'banned']);
        return back()->with('success', "Pengguna {$user->name} telah dibanned.");
    }

    public function unban($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);
        return back()->with('success', "Ban pengguna {$user->name} telah dicabut.");
    }

    public function suspend($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'inactive']);
        return back()->with('success', "Pengguna {$user->name} telah disuspend.");
    }

    public function changeRole(Request $request, $id)
    {
        $request->validate(['role' => 'required|in:admin,warga,pemda']);

        $user = User::findOrFail($id);
        $user->update(['role' => $request->role]);

        return back()->with('success', "Role pengguna {$user->name} diubah menjadi {$request->role}.");
    }

    public function assignDepartment(Request $request, $id)
    {
        $request->validate(['department_id' => 'required|exists:departments,id']);

        $user = User::findOrFail($id);

        if ($user->departments()->where('department_id', $request->department_id)->exists()) {
            return back()->with('error', 'Pengguna sudah terdaftar di dinas ini.');
        }

        $user->departments()->attach($request->department_id);

        return back()->with('success', 'Dinas berhasil ditambahkan.');
    }

    public function removeDepartment($id, $deptId)
    {
        $user = User::findOrFail($id);
        $user->departments()->detach($deptId);

        return back()->with('success', 'Dinas berhasil dihapus dari pengguna.');
    }
}