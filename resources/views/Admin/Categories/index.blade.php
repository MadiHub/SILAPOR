@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Manajemen Kategori')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="margin:0;">Manajemen Kategori</h1>
    <a href="{{ route('admin.categories.create') }}"
       style="background:var(--primary-color); color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600; font-size:0.9em;">
        <i class="fas fa-plus"></i> Tambah Kategori
    </a>
</div>

{{-- FLASH --}}
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
    <div class="card" style="border-left:5px solid var(--primary-color);">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Total Kategori</h3>
        <p style="font-size:2em;font-weight:700;color:var(--primary-color);margin:0;">{{ $stats['total'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #3b82f6;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Total Laporan</h3>
        <p style="font-size:2em;font-weight:700;color:#3b82f6;margin:0;">{{ $stats['total_reports'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #f59e0b;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Tanpa Dinas</h3>
        <p style="font-size:2em;font-weight:700;color:#f59e0b;margin:0;">{{ $stats['no_dept'] }}</p>
    </div>
    @if($stats['top'])
        <div class="card" style="border-left:5px solid #10b981;">
            <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Kategori Terbanyak</h3>
            <p style="font-size:1.1em;font-weight:700;color:#10b981;margin:0;">{{ $stats['top']->name }}</p>
            <p style="font-size:0.8em;color:#aaa;margin:2px 0 0;">{{ $stats['top']->reports_count }} laporan</p>
        </div>
    @endif
</div>

{{-- FILTER --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.categories.index') }}"
          style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:1; min-width:200px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama atau deskripsi kategori..."
                   style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box;">
        </div>
        <div style="min-width:200px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Dinas</label>
            <select name="department_id"
                    style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="">Semua Dinas</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id')==$dept->id ? 'selected':'' }}>
                        {{ $dept->name }} ({{ $dept->code }})
                    </option>
                @endforeach
            </select>
        </div>
        <div style="display:flex; gap:8px;">
            <button type="submit"
                    style="background:var(--primary-color); color:#fff; border:none; padding:9px 18px; border-radius:6px; cursor:pointer; font-size:0.9em;">
                <i class="fas fa-search"></i> Cari
            </button>
            @if(request()->hasAny(['search','department_id']))
                <a href="{{ route('admin.categories.index') }}"
                   style="background:var(--background-light); color:var(--text-dark); padding:9px 14px; border-radius:6px; text-decoration:none; font-size:0.9em;">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="card">
    @if($categories->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#aaa;">
            <i class="fas fa-tags" style="font-size:3em; margin-bottom:12px; display:block;"></i>
            <p style="margin:0;">Tidak ada kategori ditemukan.</p>
            <a href="{{ route('admin.categories.create') }}"
               style="display:inline-block; margin-top:14px; background:var(--primary-color); color:#fff; padding:9px 18px; border-radius:6px; text-decoration:none; font-size:0.9em; font-weight:600;">
                Tambah Kategori Pertama
            </a>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.9em;">
                <thead>
                    <tr style="border-bottom:2px solid var(--background-light); text-align:left;">
                        <th style="padding:12px 10px;">#</th>
                        <th style="padding:12px 10px;">Nama Kategori</th>
                        <th style="padding:12px 10px;">Dinas</th>
                        <th style="padding:12px 10px;">Deskripsi</th>
                        <th style="padding:12px 10px; text-align:center;">Laporan</th>
                        <th style="padding:12px 10px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                        <tr style="border-bottom:1px solid var(--background-light);">
                            <td style="padding:12px 10px; color:#aaa; font-size:0.85em;">{{ $cat->id }}</td>
                            <td style="padding:12px 10px;">
                                <a href="{{ route('admin.categories.show', $cat->id) }}"
                                   style="color:var(--text-dark); text-decoration:none; font-weight:600;">
                                    {{ $cat->name }}
                                </a>
                            </td>
                            <td style="padding:12px 10px;">
                                @if($cat->department)
                                    <a href="{{ route('admin.departments.show', $cat->department->id) }}"
                                       style="text-decoration:none;">
                                        <span style="background:var(--primary-color)1A; color:var(--primary-color); padding:4px 10px; border-radius:20px; font-size:0.8em; font-weight:600;">
                                            {{ $cat->department->code }}
                                        </span>
                                        <span style="font-size:0.85em; color:#777; margin-left:5px;">{{ $cat->department->name }}</span>
                                    </a>
                                @else
                                    <span style="color:#f59e0b; font-size:0.85em;"><i class="fas fa-exclamation-triangle"></i> Tanpa Dinas</span>
                                @endif
                            </td>
                            <td style="padding:12px 10px; color:#777; font-size:0.85em; max-width:220px;">
                                <span style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                                    {{ $cat->description ?: '—' }}
                                </span>
                            </td>
                            <td style="padding:12px 10px; text-align:center;">
                                <span style="background:var(--background-light); color:var(--text-dark); padding:4px 12px; border-radius:20px; font-size:0.85em; font-weight:700;">
                                    {{ $cat->reports_count }}
                                </span>
                            </td>
                            <td style="padding:12px 10px; text-align:center;">
                                <div style="display:flex; justify-content:center; gap:6px;">
                                    <a href="{{ route('admin.categories.show', $cat->id) }}"
                                       title="Detail"
                                       style="background:#f3f4f6; color:#374151; padding:6px 10px; border-radius:6px; text-decoration:none; font-size:0.85em;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.categories.edit', $cat->id) }}"
                                       title="Edit"
                                       style="background:#dbeafe; color:#1d4ed8; padding:6px 10px; border-radius:6px; text-decoration:none; font-size:0.85em;">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $cat->id) }}"
                                          onsubmit="return confirm('Hapus kategori \'{{ addslashes($cat->name) }}\'? Kategori yang masih memiliki laporan tidak dapat dihapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus"
                                                style="background:#fee2e2; color:#dc2626; border:none; padding:6px 10px; border-radius:6px; cursor:pointer; font-size:0.85em;">
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
            <span>Menampilkan {{ $categories->firstItem() }}–{{ $categories->lastItem() }} dari {{ $categories->total() }} kategori</span>
            {{ $categories->links() }}
        </div>
    @endif
</div>

@endsection