@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Detail Laporan – ' . $report->title)

@section('content')

@php
    $sc = match($report->status) {
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

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.reports.index') }}" style="color:var(--primary-color); text-decoration:none;">Laporan</a>
    <span style="margin:0 6px;">/</span>
    <span style="overflow:hidden; text-overflow:ellipsis;">{{ Str::limit($report->title, 50) }}</span>
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

{{-- HEADER ROW --}}
<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px; gap:16px;">
    <div style="flex:1; min-width:0;">
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-bottom:8px;">
            <span style="background:{{ $sc }}1A; color:{{ $sc }}; padding:5px 14px; border-radius:20px; font-size:0.82em; font-weight:700;">
                {{ $statusLabel }}
            </span>
            @if($report->department)
                <a href="{{ route('admin.departments.show', $report->department->id) }}"
                   style="background:var(--primary-color); color:#fff; padding:5px 12px; border-radius:20px; font-size:0.82em; font-weight:700; text-decoration:none;">
                    {{ $report->department->code }}
                </a>
            @endif
            @if($report->category)
                <span style="background:#f3f4f6; color:#555; padding:5px 12px; border-radius:20px; font-size:0.82em; font-weight:600;">
                    <i class="fas fa-tag"></i> {{ $report->category->name }}
                </span>
            @endif
            <span style="font-size:0.82em; color:#aaa;">
                <i class="fas fa-arrow-up" style="color:var(--primary-color);"></i> {{ $report->votes_count }} vote
            </span>
        </div>
        <h1 style="margin:0; font-size:1.5em; line-height:1.3;">{{ $report->title }}</h1>
        @if($report->address)
            <p style="margin:6px 0 0; font-size:0.85em; color:#777;">
                <i class="fas fa-map-marker-alt" style="color:var(--primary-color);"></i> {{ $report->address }}
            </p>
        @endif
    </div>
    <div style="display:flex; gap:8px; flex-shrink:0;">
        <form method="POST" action="{{ route('admin.reports.destroy', $report->id) }}"
              onsubmit="return confirm('Hapus laporan ini secara permanen?')">
            @csrf @method('DELETE')
            <button type="submit"
                    style="background:#fee2e2; color:#dc2626; border:none; padding:9px 16px; border-radius:8px; cursor:pointer; font-size:0.9em; font-weight:600;">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:24px; align-items:start;">

    {{-- ===== LEFT COLUMN ===== --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- FOTO --}}
        @if($report->images->isNotEmpty())
            <div class="card">
                <h3 style="margin:0 0 14px; font-size:0.95em;">Foto Laporan ({{ $report->images->count() }})</h3>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); gap:8px;">
                    @foreach($report->images as $img)
                        <img src="{{ asset('storage/' . $img->image_url) }}"
                             onclick="openGallery({{ $loop->index }})"
                             style="width:100%; aspect-ratio:1; object-fit:cover; border-radius:8px; cursor:pointer; transition:opacity 0.15s;"
                             onmouseover="this.style.opacity=0.8" onmouseout="this.style.opacity=1">
                    @endforeach
                </div>
            </div>
        @endif

        {{-- DESKRIPSI --}}
        <div class="card">
            <h3 style="margin:0 0 12px; font-size:0.95em;">Deskripsi</h3>
            <p style="color:#555; font-size:0.92em; line-height:1.7; margin:0; white-space:pre-wrap;">{{ $report->description }}</p>
        </div>

        {{-- MAP --}}
        @if($report->latitude && $report->longitude)
            <div class="card">
                <h3 style="margin:0 0 12px; font-size:0.95em;">Lokasi</h3>
                <div style="border-radius:8px; overflow:hidden; height:260px; background:var(--background-light);">
                    <iframe
                        src="https://maps.google.com/maps?q={{ $report->latitude }},{{ $report->longitude }}&z=16&output=embed"
                        style="width:100%; height:100%; border:none;"
                        loading="lazy">
                    </iframe>
                </div>
                <div style="margin-top:10px; font-size:0.82em; color:#aaa; display:flex; gap:16px;">
                    <span><i class="fas fa-crosshairs"></i> {{ $report->latitude }}, {{ $report->longitude }}</span>
                    <a href="https://maps.google.com/?q={{ $report->latitude }},{{ $report->longitude }}"
                    target="_blank"
                    style="color:var(--primary-color); text-decoration:none;">
                        Buka di Google Maps <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @endif

        {{-- TIMELINE PROGRESS --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 style="margin:0; font-size:0.95em;">Riwayat Progress</h3>
                <button onclick="document.getElementById('progress-form').classList.toggle('hidden')"
                        style="background:var(--primary-color); color:#fff; border:none; padding:7px 14px; border-radius:6px; cursor:pointer; font-size:0.82em; font-weight:600;">
                    <i class="fas fa-plus"></i> Tambah Update
                </button>
            </div>

            {{-- ADD PROGRESS FORM --}}
            <div id="progress-form" class="hidden"
                 style="background:var(--background-light); padding:14px; border-radius:8px; margin-bottom:16px;">
                <form method="POST" action="{{ route('admin.reports.progress', $report->id) }}">
                    @csrf
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
                        <div>
                            <label style="font-size:0.8em; color:#555; display:block; margin-bottom:4px; font-weight:500;">Status Baru <span style="color:#ef4444;">*</span></label>
                            <select name="status" required
                                    style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:6px; font-size:0.9em;">
                                <option value="active"   {{ $report->status=='active'   ? 'selected':'' }}>Aktif</option>
                                <option value="process"  {{ $report->status=='process'  ? 'selected':'' }}>Diproses</option>
                                <option value="done"     {{ $report->status=='done'     ? 'selected':'' }}>Selesai</option>
                                <option value="rejected" {{ $report->status=='rejected' ? 'selected':'' }}>Ditolak</option>
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom:12px;">
                        <label style="font-size:0.8em; color:#555; display:block; margin-bottom:4px; font-weight:500;">Catatan Progress <span style="color:#ef4444;">*</span></label>
                        <textarea name="note" rows="3" required
                                  placeholder="Tulis update perkembangan penanganan laporan ini..."
                                  style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:6px; font-size:0.9em; box-sizing:border-box; resize:vertical;"></textarea>
                    </div>
                    <div style="display:flex; gap:8px; justify-content:flex-end;">
                        <button type="button" onclick="document.getElementById('progress-form').classList.add('hidden')"
                                style="background:#fff; color:#777; border:1px solid #e5e7eb; padding:8px 14px; border-radius:6px; cursor:pointer; font-size:0.85em;">
                            Batal
                        </button>
                        <button type="submit"
                                style="background:var(--primary-color); color:#fff; border:none; padding:8px 18px; border-radius:6px; cursor:pointer; font-size:0.85em; font-weight:600;">
                            Simpan Update
                        </button>
                    </div>
                </form>
            </div>

            {{-- TIMELINE --}}
            @if($report->updates->isEmpty())
                <p style="color:#aaa; font-size:0.9em;">Belum ada riwayat progress.</p>
            @else
                <div style="position:relative;">
                    {{-- vertical line --}}
                    <div style="position:absolute; left:15px; top:0; bottom:0; width:2px; background:var(--background-light);"></div>
                    <div style="display:flex; flex-direction:column; gap:0;">
                        @foreach($report->updates as $update)
                            @php
                                $uc = match($update->status) {
                                    'active'   => '#f59e0b',
                                    'process'  => '#3b82f6',
                                    'done'     => '#10b981',
                                    'rejected' => '#ef4444',
                                    default    => '#aaa',
                                };
                                $ul = match($update->status) {
                                    'active'   => 'Aktif',
                                    'process'  => 'Diproses',
                                    'done'     => 'Selesai',
                                    'rejected' => 'Ditolak',
                                    default    => $update->status,
                                };
                            @endphp
                            <div style="display:flex; gap:14px; padding-bottom:20px; position:relative;">
                                {{-- DOT --}}
                                <div style="width:30px; height:30px; border-radius:50%; background:{{ $uc }}; display:flex; align-items:center; justify-content:center; flex-shrink:0; position:relative; z-index:1; border:2px solid #fff; box-shadow:0 0 0 2px {{ $uc }};">
                                    <i class="fas fa-{{ $update->status=='done' ? 'check' : ($update->status=='rejected' ? 'times' : ($update->status=='process' ? 'cog' : 'bell')) }}"
                                       style="color:#fff; font-size:0.7em;"></i>
                                </div>
                                {{-- CONTENT --}}
                                <div style="flex:1; background:var(--background-light); padding:10px 14px; border-radius:8px; min-width:0;">
                                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px; gap:8px; flex-wrap:wrap;">
                                        <span style="background:{{ $uc }}1A; color:{{ $uc }}; padding:2px 8px; border-radius:20px; font-size:0.75em; font-weight:700;">
                                            {{ $ul }}
                                        </span>
                                        <span style="font-size:0.75em; color:#aaa; white-space:nowrap;">
                                            {{ $update->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                    <p style="margin:4px 0 0; font-size:0.88em; color:#555; line-height:1.5;">{{ $update->note }}</p>
                                    @if($update->updatedBy)
                                        <p style="margin:6px 0 0; font-size:0.78em; color:#aaa;">
                                            <i class="fas fa-user"></i>
                                            <a href="{{ route('admin.users.show', $update->updatedBy->id) }}"
                                               style="color:#aaa; text-decoration:none;">{{ $update->updatedBy->name }}</a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- ===== RIGHT COLUMN ===== --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- INFO PELAPOR --}}
        <div class="card">
            <h3 style="margin:0 0 14px; font-size:0.95em;">Pelapor</h3>
            @if($report->user)
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                    @php
                        $avatarPath = $report->user->avatar;

                        if ($avatarPath && !str_contains($avatarPath, 'http')) {
                            // kalau dia sudah ada 'storage/' jangan ditambah lagi
                            $avatarUrl = str_contains($avatarPath, 'storage/')
                                ? asset($avatarPath)
                                : asset('storage/' . $avatarPath);
                        } else {
                            $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($report->user->name) . '&background=133a68&color=fff';
                        }
                    @endphp

                    <img src="{{ $avatarUrl }}"
                         style="width:42px; height:42px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                    <div>
                        <a href="{{ route('admin.users.show', $report->user->id) }}"
                           style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.9em;">
                            {{ $report->user->name }}
                        </a>
                        <div style="font-size:0.78em; color:#aaa;">{{ $report->user->email }}</div>
                    </div>
                </div>
                <div style="font-size:0.82em; color:#777; display:flex; flex-direction:column; gap:4px;">
                    <span><i class="fas fa-calendar" style="width:14px;"></i> Dilaporkan {{ $report->created_at->format('d M Y, H:i') }}</span>
                    <span><i class="fas fa-clock" style="width:14px;"></i> {{ $report->created_at->diffForHumans() }}</span>
                </div>
            @else
                <p style="color:#aaa; font-size:0.9em;">Pengguna telah dihapus.</p>
            @endif
        </div>

        {{-- OVERRIDE STATUS --}}
        <div class="card">
            <h3 style="margin:0 0 6px; font-size:0.95em;">Override Status</h3>
            <p style="font-size:0.8em; color:#aaa; margin:0 0 12px;">Ubah status laporan secara paksa tanpa catatan progress.</p>
            <form method="POST" action="{{ route('admin.reports.status', $report->id) }}">
                @csrf
                <div style="margin-bottom:10px;">
                    <select name="status" required
                            style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                        <option value="active"   {{ $report->status=='active'   ? 'selected':'' }}>Aktif</option>
                        <option value="process"  {{ $report->status=='process'  ? 'selected':'' }}>Diproses</option>
                        <option value="done"     {{ $report->status=='done'     ? 'selected':'' }}>Selesai</option>
                        <option value="rejected" {{ $report->status=='rejected' ? 'selected':'' }}>Ditolak</option>
                    </select>
                </div>
                <div style="margin-bottom:10px;">
                    <textarea name="note" rows="2"
                              placeholder="Catatan opsional untuk override ini..."
                              style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.85em; box-sizing:border-box; resize:none;"></textarea>
                </div>
                <button type="submit"
                        style="width:100%; background:#f59e0b; color:#fff; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.88em; font-weight:600;">
                    <i class="fas fa-bolt"></i> Override Status
                </button>
            </form>
        </div>

        {{-- REASSIGN DINAS --}}
        <div class="card">
            <h3 style="margin:0 0 6px; font-size:0.95em;">Reassign Dinas</h3>
            <p style="font-size:0.8em; color:#aaa; margin:0 0 12px;">
                Saat ini: <strong>{{ $report->department->name ?? 'Belum ada' }}</strong>
            </p>
            <form method="POST" action="{{ route('admin.reports.reassign', $report->id) }}">
                @csrf
                <div style="margin-bottom:10px;">
                    <select name="department_id" required
                            style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                        <option value="">-- Pilih Dinas --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}"
                                    {{ $report->department_id == $dept->id ? 'selected':'' }}>
                                {{ $dept->name }} ({{ $dept->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom:10px;">
                    <textarea name="note" rows="2"
                              placeholder="Alasan pemindahan (opsional)..."
                              style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.85em; box-sizing:border-box; resize:none;"></textarea>
                </div>
                <button type="submit"
                        onclick="return confirm('Pindahkan laporan ke dinas yang dipilih?')"
                        style="width:100%; background:var(--primary-color); color:#fff; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.88em; font-weight:600;">
                    <i class="fas fa-exchange-alt"></i> Pindahkan Dinas
                </button>
            </form>
        </div>

        {{-- VOTER LIST --}}
        <div class="card">
            <h3 style="margin:0 0 12px; font-size:0.95em;">
                Daftar Vote
                <span style="background:var(--primary-color); color:#fff; padding:2px 8px; border-radius:20px; font-size:0.8em; margin-left:6px;">
                    {{ $report->votes->count() }}
                </span>
            </h3>
            @if($report->votes->isEmpty())
                <p style="color:#aaa; font-size:0.85em;">Belum ada yang vote.</p>
            @else
                <div style="max-height:220px; overflow-y:auto; display:flex; flex-direction:column; gap:6px;">
                    @foreach($report->votes->take(20) as $vote)
                        <div style="display:flex; align-items:center; gap:8px;">
                            @if($vote->user)
                                <img src="{{ $vote->user->avatar_url }}"
                                     style="width:28px; height:28px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                                <a href="{{ route('admin.users.show', $vote->user->id) }}"
                                   style="font-size:0.83em; color:var(--text-dark); text-decoration:none;">
                                    {{ $vote->user->name }}
                                </a>
                            @else
                                <div style="width:28px; height:28px; border-radius:50%; background:var(--background-light); flex-shrink:0;"></div>
                                <span style="font-size:0.83em; color:#aaa;">Pengguna dihapus</span>
                            @endif
                        </div>
                    @endforeach
                    @if($report->votes->count() > 20)
                        <p style="font-size:0.78em; color:#aaa; margin:4px 0 0;">+{{ $report->votes->count() - 20 }} voter lainnya</p>
                    @endif
                </div>
            @endif
        </div>

    </div>
</div>

{{-- GALLERY MODAL --}}
<div id="gallery-modal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.9); z-index:9999; align-items:center; justify-content:center; flex-direction:column; gap:12px;"
     onclick="closeGallery()">
    <img id="gallery-img" src="" style="max-width:90vw; max-height:80vh; border-radius:8px; object-fit:contain;">
    <div style="display:flex; gap:8px;" onclick="event.stopPropagation()">
        <button onclick="changeGallery(-1)"
                style="background:rgba(255,255,255,0.15); color:#fff; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;">
            <i class="fas fa-chevron-left"></i>
        </button>
        <span id="gallery-counter" style="color:#fff; font-size:0.85em; align-self:center; min-width:60px; text-align:center;"></span>
        <button onclick="changeGallery(1)"
                style="background:rgba(255,255,255,0.15); color:#fff; border:none; padding:8px 16px; border-radius:6px; cursor:pointer;">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
    <p style="color:rgba(255,255,255,0.4); font-size:0.78em; margin:0;">Klik di luar untuk menutup</p>
</div>

@endsection

@section('scripts')
<style>.hidden { display:none !important; }</style>
<script>
    const images = @json($report->images->pluck('image_url'));
    const baseUrl = '{{ asset('storage/') }}/';
    let currentIdx = 0;

    function openGallery(idx) {
        currentIdx = idx;
        updateGallery();
        document.getElementById('gallery-modal').style.display = 'flex';
    }

    function closeGallery() {
        document.getElementById('gallery-modal').style.display = 'none';
    }

    function changeGallery(dir) {
        currentIdx = (currentIdx + dir + images.length) % images.length;
        updateGallery();
    }

    function updateGallery() {
        document.getElementById('gallery-img').src = baseUrl + images[currentIdx];
        document.getElementById('gallery-counter').textContent = (currentIdx + 1) + ' / ' + images.length;
    }

    document.addEventListener('keydown', e => {
        if (document.getElementById('gallery-modal').style.display === 'flex') {
            if (e.key === 'ArrowLeft')  changeGallery(-1);
            if (e.key === 'ArrowRight') changeGallery(1);
            if (e.key === 'Escape')     closeGallery();
        }
    });
</script>
@endsection