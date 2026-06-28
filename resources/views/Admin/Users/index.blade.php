@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Manajemen Pengguna')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="margin:0;">Manajemen Pengguna</h1>
    <a href="{{ route('admin.users.create') }}"
       style="background:var(--primary-color); color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600; font-size:0.9em;">
        <i class="fas fa-plus"></i> Tambah Pengguna
    </a>
</div>

{{-- FLASH MESSAGES --}}
@if(session('success'))
    <div style="background:#d1fae5; border:1px solid #10b981; color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:#fee2e2; border:1px solid #ef4444; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

{{-- STAT CARDS --}}
<div class="dashboard-cards" style="margin-bottom:24px;">
    <div class="card" style="border-left:5px solid #8b5cf6;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Total</h3>
        <p style="font-size:2em;font-weight:700;color:#8b5cf6;margin:0;">{{ $stats['total'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #10b981;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Aktif</h3>
        <p style="font-size:2em;font-weight:700;color:#10b981;margin:0;">{{ $stats['active'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #f59e0b;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Suspended</h3>
        <p style="font-size:2em;font-weight:700;color:#f59e0b;margin:0;">{{ $stats['inactive'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #ef4444;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Banned</h3>
        <p style="font-size:2em;font-weight:700;color:#ef4444;margin:0;">{{ $stats['banned'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid var(--primary-color);">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Admin</h3>
        <p style="font-size:2em;font-weight:700;color:var(--primary-color);margin:0;">{{ $stats['admin'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #3b82f6;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Pemda</h3>
        <p style="font-size:2em;font-weight:700;color:#3b82f6;margin:0;">{{ $stats['pemda'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #06b6d4;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Warga</h3>
        <p style="font-size:2em;font-weight:700;color:#06b6d4;margin:0;">{{ $stats['warga'] }}</p>
    </div>
</div>

{{-- FILTER & SEARCH --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.users.index') }}"
          style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:1; min-width:200px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama, email, atau telepon..."
                   style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box;">
        </div>
        <div style="min-width:130px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Role</label>
            <select name="role" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="">Semua Role</option>
                <option value="admin"  {{ request('role')=='admin'  ? 'selected':'' }}>Admin</option>
                <option value="pemda"  {{ request('role')=='pemda'  ? 'selected':'' }}>Pemda</option>
                <option value="warga"  {{ request('role')=='warga'  ? 'selected':'' }}>Warga</option>
            </select>
        </div>
        <div style="min-width:130px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Status</label>
            <select name="status" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status')=='active'   ? 'selected':'' }}>Aktif</option>
                <option value="inactive" {{ request('status')=='inactive' ? 'selected':'' }}>Suspended</option>
                <option value="banned"   {{ request('status')=='banned'   ? 'selected':'' }}>Banned</option>
            </select>
        </div>
        <div style="display:flex; gap:8px;">
            <button type="submit"
                    style="background:var(--primary-color); color:#fff; border:none; padding:9px 18px; border-radius:6px; cursor:pointer; font-size:0.9em;">
                <i class="fas fa-search"></i> Cari
            </button>
            @if(request()->hasAny(['search','role','status']))
                <a href="{{ route('admin.users.index') }}"
                   style="background:var(--background-light); color:var(--text-dark); padding:9px 14px; border-radius:6px; text-decoration:none; font-size:0.9em;">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="card">
    @if($users->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#aaa;">
            <i class="fas fa-users" style="font-size:3em; margin-bottom:12px; display:block;"></i>
            <p style="margin:0;">Tidak ada pengguna ditemukan.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.9em;">
                <thead>
                    <tr style="border-bottom:2px solid var(--background-light); text-align:left;">
                        <th style="padding:12px 10px;">Pengguna</th>
                        <th style="padding:12px 10px;">Role</th>
                        <th style="padding:12px 10px;">Status</th>
                        <th style="padding:12px 10px;">Dinas</th>
                        <th style="padding:12px 10px;">Login Terakhir</th>
                        <th style="padding:12px 10px;">Bergabung</th>
                        <th style="padding:12px 10px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        @php
                            $roleColor = match($user->role) {
                                'admin' => '#ef4444',
                                'pemda' => '#3b82f6',
                                default => '#10b981',
                            };
                            $statusColor = match($user->status) {
                                'active'   => '#10b981',
                                'inactive' => '#f59e0b',
                                'banned'   => '#ef4444',
                                default    => '#777',
                            };
                            $statusLabel = match($user->status) {
                                'active'   => 'Aktif',
                                'inactive' => 'Suspended',
                                'banned'   => 'Banned',
                                default    => $user->status,
                            };
                        @endphp
                        <tr style="border-bottom:1px solid var(--background-light);">
                            <td style="padding:12px 10px;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    @php
                                        $avatarPath = $user->avatar;

                                        if ($avatarPath && !str_contains($avatarPath, 'http')) {
                                            // kalau dia sudah ada 'storage/' jangan ditambah lagi
                                            $avatarUrl = str_contains($avatarPath, 'storage/')
                                                ? asset($avatarPath)
                                                : asset('storage/' . $avatarPath);
                                        } else {
                                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=133a68&color=fff';
                                        }
                                    @endphp
                                    <img src="{{ $avatarUrl }}"
                                         style="width:38px; height:38px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                                    <div>
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                           style="color:var(--text-dark); text-decoration:none; font-weight:600;">
                                            {{ $user->name }}
                                        </a>
                                        <div style="font-size:0.8em; color:#999;">{{ $user->email }}</div>
                                        @if($user->phone)
                                            <div style="font-size:0.78em; color:#bbb;">{{ $user->phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding:12px 10px;">
                                <span style="background:{{ $roleColor }}1A; color:{{ $roleColor }}; padding:4px 10px; border-radius:20px; font-size:0.8em; font-weight:600; text-transform:capitalize;">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td style="padding:12px 10px;">
                                <span style="background:{{ $statusColor }}1A; color:{{ $statusColor }}; padding:4px 10px; border-radius:20px; font-size:0.8em; font-weight:600;">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td style="padding:12px 10px; font-size:0.85em; color:#777;">
                                @if($user->departments->isNotEmpty())
                                    @foreach($user->departments->take(2) as $dept)
                                        <span style="display:inline-block; background:var(--background-light); padding:2px 7px; border-radius:4px; margin:2px 2px 2px 0; font-size:0.85em;">
                                            {{ $dept->code }}
                                        </span>
                                    @endforeach
                                    @if($user->departments->count() > 2)
                                        <span style="font-size:0.8em; color:#aaa;">+{{ $user->departments->count() - 2 }}</span>
                                    @endif
                                @else
                                    <span style="color:#ccc;">—</span>
                                @endif
                            </td>
                            <td style="padding:12px 10px; color:#999; font-size:0.85em;">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : '—' }}
                            </td>
                            <td style="padding:12px 10px; color:#999; font-size:0.85em;">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td style="padding:12px 10px; text-align:center;">
                                <div style="display:flex; justify-content:center; gap:6px;">
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                       title="Detail"
                                       style="background:#f3f4f6; color:#374151; padding:6px 10px; border-radius:6px; text-decoration:none; font-size:0.85em;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       title="Edit"
                                       style="background:#dbeafe; color:#1d4ed8; padding:6px 10px; border-radius:6px; text-decoration:none; font-size:0.85em;">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                                          onsubmit="return confirm('Hapus pengguna {{ addslashes($user->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus"
                                                style="background:#fee2e2; color:#dc2626; padding:6px 10px; border-radius:6px; border:none; cursor:pointer; font-size:0.85em;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; font-size:0.85em; color:#777;">
            <span>Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} pengguna</span>
            {{ $users->links() }}
        </div>
    @endif
</div>

@endsection