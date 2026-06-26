@extends('Layouts.PemdaLayout.base_layout')

@section('title', 'Detail Laporan')

@section('content')

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
@endphp

{{-- ===== BREADCRUMB ===== --}}
<div style="display:flex; align-items:center; gap:8px; font-size:0.88em; color:#999; margin-bottom:20px;">
    <a href="{{ route('pemda.reports.index') }}" style="color:var(--primary-color); text-decoration:none;">Laporan</a>
    <i class="fas fa-chevron-right" style="font-size:0.7em;"></i>
    <span style="color:var(--text-dark);">{{ Str::limit($report->title, 50) }}</span>
</div>

{{-- ===== HEADER ===== --}}
<div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:25px;">
    <div>
        <h1 style="margin:0 0 6px 0; font-size:1.5em;">{{ $report->title }}</h1>
        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <span style="background:{{ $statusColor }}1A; color:{{ $statusColor }};
                         padding:4px 14px; border-radius:20px; font-size:0.85em; font-weight:600;">
                {{ $statusLabel }}
            </span>
            <span style="font-size:0.85em; color:#999;">
                <i class="fas fa-calendar" style="margin-right:4px;"></i>{{ $report->created_at->format('d M Y, H:i') }}
            </span>
            <span style="font-size:0.85em; color:var(--primary-color); font-weight:600;">
                <i class="fas fa-arrow-up" style="margin-right:3px;"></i>{{ $report->votes_count }} vote
            </span>
        </div>
    </div>
</div>

@if(session('success'))
    <div style="background:#ecfdf5; border-left:4px solid #10b981; padding:12px 16px; border-radius:8px; color:#065f46; margin-bottom:20px; font-size:0.9em;">
        <i class="fas fa-check-circle" style="margin-right:6px;"></i> {{ session('success') }}
    </div>
@endif

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:25px; align-items:start;">

    {{-- ===== KOLOM KIRI ===== --}}
    <div style="display:flex; flex-direction:column; gap:25px;">

        {{-- INFO UTAMA --}}
        <div class="card">
            <h3 style="margin-top:0;">Informasi Laporan</h3>

            <p style="color:#444; line-height:1.7; margin-bottom:20px;">{{ $report->description }}</p>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px;">
                <div>
                    <div style="font-size:0.78em; color:#999; text-transform:uppercase; margin-bottom:3px;">Pelapor</div>
                    <div style="font-weight:600; color:var(--text-dark);">{{ $report->user->name ?? '-' }}</div>
                </div>
                <div>
                    <div style="font-size:0.78em; color:#999; text-transform:uppercase; margin-bottom:3px;">Kategori</div>
                    <div style="font-weight:600; color:var(--text-dark);">{{ $report->category->name ?? '-' }}</div>
                </div>
                <div style="grid-column: 1/-1;">
                    <div style="font-size:0.78em; color:#999; text-transform:uppercase; margin-bottom:3px;">Lokasi</div>
                    <div style="font-weight:500; color:var(--text-dark);">
                        <i class="fas fa-map-marker-alt" style="color:var(--primary-color); margin-right:5px;"></i>
                        {{ $report->address }}
                    </div>
                    @if($report->latitude && $report->longitude)
                        <a href="https://maps.google.com/?q={{ $report->latitude }},{{ $report->longitude }}"
                           target="_blank"
                           style="display:inline-block; margin-top:8px; font-size:0.82em; color:var(--primary-color); text-decoration:none;">
                            <i class="fas fa-external-link-alt" style="margin-right:4px;"></i>Buka di Google Maps
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- FOTO --}}
        @if($report->images->isNotEmpty())
            <div class="card">
                <h3 style="margin-top:0;">Foto Laporan</h3>
                <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap:10px;">
                    @foreach($report->images as $img)
                        <a href="{{ asset('storage/' . $img->image_url) }}" target="_blank">
                            <img src="{{ asset('storage/' . $img->image_url) }}"
                                 style="width:100%; height:120px; object-fit:cover; border-radius:8px; display:block; transition:opacity 0.2s;"
                                 onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- RIWAYAT PROGRESS --}}
        <div class="card">
            <h3 style="margin-top:0;">Riwayat Progress</h3>

            @if($report->updates->isEmpty())
                <p style="color:#aaa; font-size:0.9em;">Belum ada catatan progress.</p>
            @else
                <div style="position:relative; padding-left:20px;">
                    <div style="position:absolute; left:7px; top:0; bottom:0; width:2px; background:var(--background-light);"></div>
                    @foreach($report->updates->sortByDesc('created_at') as $update)
                        <div style="position:relative; margin-bottom:20px;">
                            <div style="position:absolute; left:-16px; top:4px; width:10px; height:10px;
                                        background:var(--primary-color); border-radius:50%; border:2px solid #fff;
                                        box-shadow:0 0 0 2px var(--primary-color);"></div>
                            <div style="background:#fafafa; border-radius:8px; padding:12px 14px;">
                                <div style="font-size:0.8em; color:#999; margin-bottom:4px;">
                                    {{ \Carbon\Carbon::parse($update->created_at)->format('d M Y, H:i') }}                                </div>
                                <div style="font-size:0.9em; color:#444; line-height:1.6;">{{ $update->note }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Form tambah progress --}}
            <form method="POST" action="{{ route('pemda.reports.progress', $report->id) }}" style="margin-top:16px;">
                @csrf
                <label style="font-size:0.82em; color:#777; display:block; margin-bottom:6px;">Tambah Catatan Progress</label>
                <textarea name="note" rows="3"
                          placeholder="Tulis perkembangan penanganan laporan..."
                          style="width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                 font-size:0.9em; resize:vertical; box-sizing:border-box; font-family:inherit;">{{ old('note') }}</textarea>
                @error('note')
                    <div style="color:#ef4444; font-size:0.8em; margin-top:4px;">{{ $message }}</div>
                @enderror
                <button type="submit"
                        style="margin-top:10px; padding:9px 20px; background:var(--primary-color); color:#fff;
                               border:none; border-radius:8px; font-size:0.9em; cursor:pointer;">
                    <i class="fas fa-plus"></i> Tambah Progress
                </button>
            </form>
        </div>

        {{-- KOMENTAR --}}
        @if($report->comments->isNotEmpty())
            <div class="card">
                <h3 style="margin-top:0;">Komentar Publik <span style="font-size:0.7em; font-weight:400; color:#999;">({{ $report->comments->count() }})</span></h3>
                @foreach($report->comments->sortByDesc('created_at') as $comment)
                    <div style="display:flex; gap:12px; padding:12px 0; border-bottom:1px solid var(--background-light);">
                        <div style="width:36px; height:36px; border-radius:50%; background:var(--primary-color);
                                    display:flex; align-items:center; justify-content:center; color:#fff;
                                    font-weight:700; font-size:0.9em; flex-shrink:0;">
                            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div style="flex:1;">
                            <div style="font-weight:600; font-size:0.88em; color:var(--text-dark);">{{ $comment->user->name ?? 'Anonim' }}</div>
                            <div style="font-size:0.8em; color:#aaa; margin-bottom:4px;">{{ $comment->created_at->diffForHumans() }}</div>
                            <div style="font-size:0.9em; color:#444; line-height:1.6;">{{ $comment->comment }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>

    {{-- ===== KOLOM KANAN ===== --}}
    <div style="display:flex; flex-direction:column; gap:25px;">

        {{-- UPDATE STATUS --}}
        <div class="card">
            <h3 style="margin-top:0;">Ubah Status</h3>
            <form method="POST" action="{{ route('pemda.reports.updateStatus', $report->id) }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <label style="font-size:0.82em; color:#777; display:block; margin-bottom:6px;">Status saat ini</label>
                    <select name="status"
                            style="width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                   font-size:0.9em; background:#fff; color:var(--text-dark);">
                        <option value="active"   {{ $report->status === 'active'   ? 'selected' : '' }}>Aktif</option>
                        <option value="process"  {{ $report->status === 'process'  ? 'selected' : '' }}>Diproses</option>
                        <option value="done"     {{ $report->status === 'done'     ? 'selected' : '' }}>Selesai</option>
                        <option value="rejected" {{ $report->status === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>
                <button type="submit"
                        style="width:100%; padding:10px; background:var(--primary-color); color:#fff;
                               border:none; border-radius:8px; font-size:0.9em; cursor:pointer; font-weight:600;">
                    <i class="fas fa-save"></i> Simpan Status
                </button>
            </form>
        </div>

        {{-- RINGKASAN --}}
        <div class="card">
            <h3 style="margin-top:0;">Ringkasan</h3>
            <ul style="list-style:none; padding:0; margin:0; font-size:0.9em;">
                <li style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--background-light);">
                    <span style="color:#777;"><i class="fas fa-images" style="width:16px; margin-right:6px;"></i>Foto</span>
                    <span style="font-weight:600;">{{ $report->images->count() }}</span>
                </li>
                <li style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--background-light);">
                    <span style="color:#777;"><i class="fas fa-comments" style="width:16px; margin-right:6px;"></i>Komentar</span>
                    <span style="font-weight:600;">{{ $report->comments->count() }}</span>
                </li>
                <li style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--background-light);">
                    <span style="color:#777;"><i class="fas fa-tasks" style="width:16px; margin-right:6px;"></i>Progress</span>
                    <span style="font-weight:600;">{{ $report->updates->count() }}</span>
                </li>
                <li style="display:flex; justify-content:space-between; padding:10px 0;">
                    <span style="color:#777;"><i class="fas fa-arrow-up" style="width:16px; margin-right:6px;"></i>Vote</span>
                    <span style="font-weight:600; color:var(--primary-color);">{{ $report->votes_count }}</span>
                </li>
            </ul>
        </div>

        {{-- BACK --}}
        <a href="{{ route('pemda.reports.index') }}"
           style="display:block; text-align:center; padding:10px; background:#f1f5f9; color:#555;
                  border-radius:8px; font-size:0.9em; text-decoration:none;">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>

    </div>
</div>

@endsection