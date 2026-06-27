@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Manajemen Dinas')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="margin:0;">Manajemen Dinas</h1>
    <a href="{{ route('admin.departments.create') }}"
       style="background:var(--primary-color); color:#fff; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600; font-size:0.9em;">
        <i class="fas fa-plus"></i> Tambah Dinas
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
    <div class="card" style="border-left:5px solid var(--primary-color);">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Total Dinas</h3>
        <p style="font-size:2em;font-weight:700;color:var(--primary-color);margin:0;">{{ $stats['total'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #3b82f6;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Total Laporan</h3>
        <p style="font-size:2em;font-weight:700;color:#3b82f6;margin:0;">{{ $stats['total_reports'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #8b5cf6;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Total Staf</h3>
        <p style="font-size:2em;font-weight:700;color:#8b5cf6;margin:0;">{{ $stats['total_staff'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #f59e0b;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Total Kategori</h3>
        <p style="font-size:2em;font-weight:700;color:#f59e0b;margin:0;">{{ $stats['total_cats'] }}</p>
    </div>
</div>

{{-- SEARCH --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.departments.index') }}"
          style="display:flex; gap:12px; align-items:flex-end;">
        <div style="flex:1;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Nama atau kode dinas..."
                   style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box;">
        </div>
        <div style="display:flex; gap:8px;">
            <button type="submit"
                    style="background:var(--primary-color); color:#fff; border:none; padding:9px 18px; border-radius:6px; cursor:pointer; font-size:0.9em;">
                <i class="fas fa-search"></i> Cari
            </button>
            @if(request('search'))
                <a href="{{ route('admin.departments.index') }}"
                   style="background:var(--background-light); color:var(--text-dark); padding:9px 14px; border-radius:6px; text-decoration:none; font-size:0.9em;">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- DEPARTMENT CARDS GRID --}}
@if($departments->isEmpty())
    <div class="card" style="text-align:center; padding:60px 20px; color:#aaa;">
        <i class="fas fa-building" style="font-size:3em; margin-bottom:12px; display:block;"></i>
        <p style="margin:0;">Tidak ada dinas ditemukan.</p>
        <a href="{{ route('admin.departments.create') }}"
           style="display:inline-block; margin-top:14px; background:var(--primary-color); color:#fff; padding:9px 18px; border-radius:6px; text-decoration:none; font-size:0.9em; font-weight:600;">
            Tambah Dinas Pertama
        </a>
    </div>
@else
    <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(320px,1fr)); gap:18px; margin-bottom:24px;">
        @foreach($departments as $dept)
            @php
                $total    = $dept->reports_count;
                $done     = $dept->reports()->where('status','done')->count();
                $pct      = $total > 0 ? round(($done / $total) * 100) : 0;
                $barColor = $pct >= 75 ? '#10b981' : ($pct >= 40 ? '#f59e0b' : '#ef4444');
            @endphp
            <div class="card" style="position:relative; padding-bottom:16px;">

                {{-- HEADER --}}
                <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:12px;">
                    <div>
                        <div style="display:inline-block; background:var(--primary-color); color:#fff; padding:4px 10px; border-radius:6px; font-size:0.78em; font-weight:700; letter-spacing:0.05em; margin-bottom:6px;">
                            {{ $dept->code }}
                        </div>
                        <h3 style="margin:0; font-size:1em; line-height:1.3;">{{ $dept->name }}</h3>
                    </div>
                    <div style="display:flex; gap:6px; flex-shrink:0; margin-left:10px;">
                        <a href="{{ route('admin.departments.edit', $dept->id) }}"
                           title="Edit"
                           style="background:#dbeafe; color:#1d4ed8; padding:6px 9px; border-radius:6px; text-decoration:none; font-size:0.82em;">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.departments.destroy', $dept->id) }}"
                              onsubmit="return confirm('Hapus dinas {{ addslashes($dept->name) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Hapus"
                                    style="background:#fee2e2; color:#dc2626; border:none; padding:6px 9px; border-radius:6px; cursor:pointer; font-size:0.82em;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                @if($dept->description)
                    <p style="font-size:0.82em; color:#777; margin:0 0 12px; line-height:1.4; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                        {{ $dept->description }}
                    </p>
                @endif

                {{-- STATS ROW --}}
                <div style="display:flex; gap:12px; margin-bottom:12px;">
                    <div style="text-align:center;">
                        <p style="font-size:1.4em; font-weight:700; color:var(--text-dark); margin:0;">{{ $dept->reports_count }}</p>
                        <p style="font-size:0.72em; color:#aaa; margin:0;">Laporan</p>
                    </div>
                    <div style="text-align:center;">
                        <p style="font-size:1.4em; font-weight:700; color:#8b5cf6; margin:0;">{{ $dept->users_count }}</p>
                        <p style="font-size:0.72em; color:#aaa; margin:0;">Staf</p>
                    </div>
                    <div style="text-align:center;">
                        <p style="font-size:1.4em; font-weight:700; color:#f59e0b; margin:0;">{{ $dept->categories_count }}</p>
                        <p style="font-size:0.72em; color:#aaa; margin:0;">Kategori</p>
                    </div>
                    <div style="flex:1;"></div>
                    {{-- COMPLETION --}}
                    <div style="text-align:right;">
                        <p style="font-size:1.4em; font-weight:700; color:{{ $barColor }}; margin:0;">{{ $pct }}%</p>
                        <p style="font-size:0.72em; color:#aaa; margin:0;">Selesai</p>
                    </div>
                </div>

                {{-- PROGRESS BAR --}}
                <div style="background:var(--background-light); border-radius:4px; height:6px; margin-bottom:14px;">
                    <div style="width:{{ $pct }}%; background:{{ $barColor }}; height:6px; border-radius:4px; transition:width 0.3s;"></div>
                </div>

                {{-- CATEGORIES PREVIEW --}}
                @if($dept->categories->isNotEmpty())
                    <div style="display:flex; flex-wrap:wrap; gap:5px; margin-bottom:12px;">
                        @foreach($dept->categories->take(4) as $cat)
                            <span style="background:var(--background-light); color:#555; padding:2px 8px; border-radius:4px; font-size:0.75em;">{{ $cat->name }}</span>
                        @endforeach
                        @if($dept->categories->count() > 4)
                            <span style="color:#aaa; font-size:0.75em; padding:2px 4px;">+{{ $dept->categories->count() - 4 }} lainnya</span>
                        @endif
                    </div>
                @endif

                <a href="{{ route('admin.departments.show', $dept->id) }}"
                   style="display:block; text-align:center; background:var(--background-light); color:var(--text-dark); padding:8px; border-radius:6px; text-decoration:none; font-size:0.85em; font-weight:600;">
                    Lihat Detail →
                </a>
            </div>
        @endforeach
    </div>

    {{-- PAGINATION --}}
    <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.85em; color:#777;">
        <span>Menampilkan {{ $departments->firstItem() }}–{{ $departments->lastItem() }} dari {{ $departments->total() }} dinas</span>
        {{ $departments->links() }}
    </div>
@endif

@endsection