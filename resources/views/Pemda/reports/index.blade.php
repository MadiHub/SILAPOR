@extends('Layouts.PemdaLayout.base_layout')

@section('title', 'Manajemen Laporan')

@section('content')

<h1>Manajemen Laporan</h1>

{{-- ================= STAT CARDS ================= --}}
<div class="dashboard-cards" style="margin-bottom: 30px;">
    <div class="card" style="border-left: 5px solid var(--primary-color);">
        <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Total</h3>
        <p style="font-size:2em; font-weight:700; color:var(--text-dark);">{{ $stats['total'] }}</p>
    </div>
    <div class="card" style="border-left: 5px solid #f59e0b;">
        <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Aktif</h3>
        <p style="font-size:2em; font-weight:700; color:#f59e0b;">{{ $stats['active'] }}</p>
    </div>
    <div class="card" style="border-left: 5px solid #3b82f6;">
        <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Diproses</h3>
        <p style="font-size:2em; font-weight:700; color:#3b82f6;">{{ $stats['process'] }}</p>
    </div>
    <div class="card" style="border-left: 5px solid #10b981;">
        <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Selesai</h3>
        <p style="font-size:2em; font-weight:700; color:#10b981;">{{ $stats['done'] }}</p>
    </div>
    <div class="card" style="border-left: 5px solid #ef4444;">
        <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Ditolak</h3>
        <p style="font-size:2em; font-weight:700; color:#ef4444;">{{ $stats['rejected'] }}</p>
    </div>
</div>

{{-- ================= FILTER & SEARCH ================= --}}
<div class="card" style="margin-bottom: 25px;">
    <form method="GET" action="{{ route('pemda.reports.index') }}">
        <div style="display:grid; grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto; gap:12px; align-items:end;">

            {{-- Search --}}
            <div>
                <label style="font-size:0.8em; color:#777; display:block; margin-bottom:4px;">Cari Laporan</label>
                <div style="position:relative;">
                    <i class="fas fa-search" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#aaa;"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Judul atau alamat..."
                           style="width:100%; padding:9px 12px 9px 32px; border:1px solid #e2e8f0; border-radius:8px; font-size:0.9em; box-sizing:border-box; outline:none;">
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label style="font-size:0.8em; color:#777; display:block; margin-bottom:4px;">Status</label>
                <select name="status" style="width:100%; padding:9px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:0.9em; background:#fff;">
                    <option value="">Semua Status</option>
                    <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                    <option value="process"  {{ request('status') === 'process'  ? 'selected' : '' }}>Diproses</option>
                    <option value="done"     {{ request('status') === 'done'     ? 'selected' : '' }}>Selesai</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Kategori --}}
            <div>
                <label style="font-size:0.8em; color:#777; display:block; margin-bottom:4px;">Kategori</label>
                <select name="category_id" style="width:100%; padding:9px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:0.9em; background:#fff;">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Sort --}}
            <div>
                <label style="font-size:0.8em; color:#777; display:block; margin-bottom:4px;">Urutkan</label>
                <select name="sort" style="width:100%; padding:9px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:0.9em; background:#fff;">
                    <option value="latest"  {{ request('sort', 'latest') === 'latest'  ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest"  {{ request('sort') === 'oldest'  ? 'selected' : '' }}>Terlama</option>
                    <option value="votes"   {{ request('sort') === 'votes'   ? 'selected' : '' }}>Terbanyak Vote</option>
                </select>
            </div>

            {{-- Tanggal --}}
            <div>
                <label style="font-size:0.8em; color:#777; display:block; margin-bottom:4px;">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       style="width:100%; padding:9px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:0.9em; box-sizing:border-box;">
            </div>

            {{-- Tombol --}}
            <div style="display:flex; gap:8px;">
                <button type="submit"
                        style="padding:9px 18px; background:var(--primary-color); color:#fff; border:none; border-radius:8px; font-size:0.9em; cursor:pointer; white-space:nowrap;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <a href="{{ route('pemda.reports.index') }}"
                   style="padding:9px 14px; background:#f1f5f9; color:#555; border-radius:8px; font-size:0.9em; text-decoration:none; white-space:nowrap;">
                    Reset
                </a>
            </div>

        </div>
    </form>
</div>

{{-- ================= TABLE ================= --}}
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:10px;">
        <h3 style="margin:0;">Daftar Laporan</h3>
        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <span style="font-size:0.85em; color:#777;">
                {{ $reports->total() }} laporan ditemukan
            </span>
            <a href="{{ route('pemda.reports.export.excel', request()->query()) }}"
            style="padding:7px 14px; background:#10b981; color:#fff; border-radius:8px;
                    font-size:0.82em; text-decoration:none; white-space:nowrap;">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ route('pemda.reports.export.pdf', request()->query()) }}"
            target="_blank"
            style="padding:7px 14px; background:#ef4444; color:#fff; border-radius:8px;
                    font-size:0.82em; text-decoration:none; white-space:nowrap;">
                <i class="fas fa-file-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>

    @if($reports->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#aaa;">
            <i class="fas fa-folder-open" style="font-size:3em; margin-bottom:12px; display:block;"></i>
            <p style="font-size:1em;">Tidak ada laporan yang sesuai filter.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.9em;">
                <thead>
                    <tr style="text-align:left; border-bottom:2px solid var(--background-light); background:#fafafa;">
                        <th style="padding:12px 10px;">#</th>
                        <th style="padding:12px 10px;">Foto</th>
                        <th style="padding:12px 10px;">Laporan</th>
                        <th style="padding:12px 10px;">Pelapor</th>
                        <th style="padding:12px 10px;">Kategori</th>
                        <th style="padding:12px 10px;">Status</th>
                        <th style="padding:12px 10px; text-align:center;">Vote</th>
                        <th style="padding:12px 10px;">Tanggal</th>
                        <th style="padding:12px 10px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $index => $report)
                        @php
                            $statusColor = match($report->status) {
                                'active'   => '#f59e0b',
                                'process'  => '#3b82f6',
                                'done'     => '#10b981',
                                'rejected' => '#ef4444',
                                default    => '#777',
                            };
                            $statusLabel = match($report->status) {
                                'active'   => 'Aktif',
                                'process'  => 'Diproses',
                                'done'     => 'Selesai',
                                'rejected' => 'Ditolak',
                                default    => $report->status,
                            };
                            $firstImage = $report->images->first();
                        @endphp
                        <tr style="border-bottom:1px solid var(--background-light); transition:background 0.15s;"
                            onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
                            <td style="padding:12px 10px; color:#aaa; font-size:0.85em;">
                                {{ $reports->firstItem() + $index }}
                            </td>
                            <td style="padding:12px 10px;">
                                @if($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage->image_url) }}"
                                         style="width:50px; height:50px; object-fit:cover; border-radius:8px; display:block;">
                                @else
                                    <div style="width:50px; height:50px; border-radius:8px; background:var(--background-light);
                                                display:flex; align-items:center; justify-content:center; color:#ccc;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="padding:12px 10px; max-width:220px;">
                                <div style="font-weight:600; color:var(--text-dark); margin-bottom:2px;">
                                    {{ Str::limit($report->title, 50) }}
                                </div>
                                <div style="font-size:0.8em; color:#999;">
                                    <i class="fas fa-map-marker-alt" style="margin-right:3px;"></i>
                                    {{ Str::limit($report->address, 40) }}
                                </div>
                            </td>
                            <td style="padding:12px 10px;">
                                <div style="font-size:0.9em; color:var(--text-dark);">{{ $report->user->name ?? '-' }}</div>
                            </td>
                            <td style="padding:12px 10px;">
                                <span style="font-size:0.85em; color:#555;">
                                    {{ $report->category->name ?? '-' }}
                                </span>
                            </td>
                            <td style="padding:12px 10px;">
                                <span style="background:{{ $statusColor }}1A; color:{{ $statusColor }};
                                             padding:4px 12px; border-radius:20px; font-size:0.8em; font-weight:600;">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td style="padding:12px 10px; text-align:center;">
                                <span style="font-size:0.9em; font-weight:600; color:var(--primary-color);">
                                    <i class="fas fa-arrow-up" style="font-size:0.75em;"></i>
                                    {{ $report->votes_count }}
                                </span>
                            </td>
                            <td style="padding:12px 10px; color:#777; font-size:0.85em; white-space:nowrap;">
                                {{ $report->created_at->format('d M Y') }}
                            </td>
                            <td style="padding:12px 10px; text-align:center;">
                                <a href="{{ route('pemda.reports.show', $report->id) }}"
                                   style="display:inline-block; padding:6px 14px; background:var(--primary-color);
                                          color:#fff; border-radius:6px; font-size:0.82em; text-decoration:none;">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div style="margin-top:20px; display:flex; justify-content:flex-end; align-items:center; gap:6px; flex-wrap:wrap;">
            @if($reports->onFirstPage())
                <span style="padding:6px 12px; border-radius:6px; background:#f1f5f9; color:#aaa; font-size:0.85em;">&laquo;</span>
            @else
                <a href="{{ $reports->previousPageUrl() }}"
                   style="padding:6px 12px; border-radius:6px; background:#f1f5f9; color:#555; font-size:0.85em; text-decoration:none;">&laquo;</a>
            @endif

            @foreach($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                @if($page == $reports->currentPage())
                    <span style="padding:6px 12px; border-radius:6px; background:var(--primary-color); color:#fff; font-size:0.85em;">{{ $page }}</span>
                @else
                    <a href="{{ $url }}"
                       style="padding:6px 12px; border-radius:6px; background:#f1f5f9; color:#555; font-size:0.85em; text-decoration:none;">{{ $page }}</a>
                @endif
            @endforeach

            @if($reports->hasMorePages())
                <a href="{{ $reports->nextPageUrl() }}"
                   style="padding:6px 12px; border-radius:6px; background:#f1f5f9; color:#555; font-size:0.85em; text-decoration:none;">&raquo;</a>
            @else
                <span style="padding:6px 12px; border-radius:6px; background:#f1f5f9; color:#aaa; font-size:0.85em;">&raquo;</span>
            @endif
        </div>
    @endif
</div>

@endsection