@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Moderasi Laporan')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="margin:0;">Moderasi Laporan</h1>
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
    @foreach([
        ['label'=>'Total','value'=>$stats['total'],'color'=>'#8b5cf6','filter'=>''],
        ['label'=>'Aktif','value'=>$stats['active'],'color'=>'#f59e0b','filter'=>'active'],
        ['label'=>'Diproses','value'=>$stats['process'],'color'=>'#3b82f6','filter'=>'process'],
        ['label'=>'Selesai','value'=>$stats['done'],'color'=>'#10b981','filter'=>'done'],
        ['label'=>'Ditolak','value'=>$stats['rejected'],'color'=>'#ef4444','filter'=>'rejected'],
    ] as $s)
        <a href="{{ route('admin.reports.index', $s['filter'] ? ['status'=>$s['filter']] : []) }}"
           style="text-decoration:none;">
            <div class="card" style="border-left:5px solid {{ $s['color'] }}; {{ request('status')==$s['filter'] ? 'box-shadow:0 0 0 2px '.$s['color'].';' : '' }}">
                <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">{{ $s['label'] }}</h3>
                <p style="font-size:2em;font-weight:700;color:{{ $s['color'] }};margin:0;">{{ $s['value'] }}</p>
            </div>
        </a>
    @endforeach
</div>

{{-- FILTER --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.reports.index') }}"
          style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">

        <div style="flex:2; min-width:200px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Judul, deskripsi, atau alamat..."
                   style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box;">
        </div>

        <div style="min-width:140px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Status</label>
            <select name="status" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status')=='active'   ? 'selected':'' }}>Aktif</option>
                <option value="process"  {{ request('status')=='process'  ? 'selected':'' }}>Diproses</option>
                <option value="done"     {{ request('status')=='done'     ? 'selected':'' }}>Selesai</option>
                <option value="rejected" {{ request('status')=='rejected' ? 'selected':'' }}>Ditolak</option>
            </select>
        </div>

        <div style="min-width:180px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Dinas</label>
            <select name="department_id" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="">Semua Dinas</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id')==$dept->id ? 'selected':'' }}>
                        {{ $dept->code }} – {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="min-width:130px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box;">
        </div>

        <div style="min-width:130px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box;">
        </div>

        <div style="min-width:130px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Urutkan</label>
            <select name="sort" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="latest" {{ request('sort','latest')=='latest' ? 'selected':'' }}>Terbaru</option>
                <option value="oldest" {{ request('sort')=='oldest' ? 'selected':'' }}>Terlama</option>
                <option value="votes"  {{ request('sort')=='votes'  ? 'selected':'' }}>Terbanyak Vote</option>
            </select>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="submit"
                    style="background:var(--primary-color); color:#fff; border:none; padding:9px 18px; border-radius:6px; cursor:pointer; font-size:0.9em;">
                <i class="fas fa-search"></i> Cari
            </button>
            @if(request()->hasAny(['search','status','department_id','category_id','date_from','date_to','sort']))
                <a href="{{ route('admin.reports.index') }}"
                   style="background:var(--background-light); color:var(--text-dark); padding:9px 14px; border-radius:6px; text-decoration:none; font-size:0.9em;">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="card">
    @if($reports->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#aaa;">
            <i class="fas fa-file-alt" style="font-size:3em; margin-bottom:12px; display:block;"></i>
            <p style="margin:0;">Tidak ada laporan ditemukan.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.88em;">
                <thead>
                    <tr style="border-bottom:2px solid var(--background-light); text-align:left;">
                        <th style="padding:12px 10px;">Foto</th>
                        <th style="padding:12px 10px;">Laporan</th>
                        <th style="padding:12px 10px;">Pelapor</th>
                        <th style="padding:12px 10px;">Dinas</th>
                        <th style="padding:12px 10px;">Kategori</th>
                        <th style="padding:12px 10px; text-align:center;">Vote</th>
                        <th style="padding:12px 10px; text-align:center;">Status</th>
                        <th style="padding:12px 10px; text-align:center;">Tanggal</th>
                        <th style="padding:12px 10px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                        @php
                            $sc = match($report->status) {
                                'active'   => '#f59e0b',
                                'process'  => '#3b82f6',
                                'done'     => '#10b981',
                                'rejected' => '#ef4444',
                                default    => '#777',
                            };
                            $firstImg = $report->images->first();
                        @endphp
                        <tr style="border-bottom:1px solid var(--background-light);">

                            {{-- FOTO --}}
                            <td style="padding:10px 10px;">
                                @if($firstImg)
                                    <img src="{{ asset('storage/' . $firstImg->image_url) }}"
                                         style="width:52px; height:52px; object-fit:cover; border-radius:6px; cursor:pointer;"
                                         onclick="openImageModal('{{ asset('storage/' . $firstImg->image_url) }}')">
                                @else
                                    <div style="width:52px; height:52px; border-radius:6px; background:var(--background-light); display:flex; align-items:center; justify-content:center; color:#ccc;">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            {{-- JUDUL --}}
                            <td style="padding:10px 10px; max-width:220px;">
                                <a href="{{ route('admin.reports.show', $report->id) }}"
                                   style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.95em; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $report->title }}
                                </a>
                                @if($report->address)
                                    <div style="font-size:0.78em; color:#aaa; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; margin-top:2px;">
                                        <i class="fas fa-map-marker-alt"></i> {{ $report->address }}
                                    </div>
                                @endif
                            </td>

                            {{-- PELAPOR --}}
                            <td style="padding:10px 10px;">
                                @if($report->user)
                                    <a href="{{ route('admin.users.show', $report->user->id) }}"
                                       style="color:var(--text-dark); text-decoration:none; font-size:0.85em; font-weight:500;">
                                        {{ $report->user->name }}
                                    </a>
                                @else
                                    <span style="color:#aaa; font-size:0.85em;">Anonim</span>
                                @endif
                            </td>

                            {{-- DINAS --}}
                            <td style="padding:10px 10px;">
                                @if($report->department)
                                    <span style="background:var(--primary-color)1A; color:var(--primary-color); padding:3px 8px; border-radius:4px; font-size:0.78em; font-weight:700;">
                                        {{ $report->department->code }}
                                    </span>
                                @else
                                    <span style="color:#f59e0b; font-size:0.82em;"><i class="fas fa-exclamation-triangle"></i> Belum</span>
                                @endif
                            </td>

                            {{-- KATEGORI --}}
                            <td style="padding:10px 10px; font-size:0.82em; color:#777;">
                                {{ $report->category->name ?? '—' }}
                            </td>

                            {{-- VOTE --}}
                            <td style="padding:10px 10px; text-align:center;">
                                <span style="font-weight:700; color:var(--primary-color); font-size:0.9em;">
                                    <i class="fas fa-arrow-up" style="font-size:0.75em;"></i> {{ $report->votes_count }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td style="padding:10px 10px; text-align:center;">
                                <span style="background:{{ $sc }}1A; color:{{ $sc }}; padding:4px 10px; border-radius:20px; font-size:0.78em; font-weight:600; text-transform:capitalize; white-space:nowrap;">
                                    {{ $report->status_label }}
                                </span>
                            </td>

                            {{-- TANGGAL --}}
                            <td style="padding:10px 10px; text-align:center; color:#aaa; font-size:0.82em; white-space:nowrap;">
                                {{ $report->created_at->format('d M Y') }}
                            </td>

                            {{-- AKSI --}}
                            <td style="padding:10px 10px; text-align:center;">
                                <div style="display:flex; justify-content:center; gap:6px;">
                                    <a href="{{ route('admin.reports.show', $report->id) }}"
                                       title="Detail"
                                       style="background:#f3f4f6; color:#374151; padding:6px 10px; border-radius:6px; text-decoration:none; font-size:0.85em;">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.reports.destroy', $report->id) }}"
                                          onsubmit="return confirm('Hapus laporan \'{{ addslashes($report->title) }}\' secara permanen?')">
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
            <span>Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }} dari {{ $reports->total() }} laporan</span>
            {{ $reports->links() }}
        </div>
    @endif
</div>

{{-- IMAGE MODAL --}}
<div id="image-modal"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:9999; align-items:center; justify-content:center;"
     onclick="this.style.display='none'">
    <img id="modal-img" src="" style="max-width:90vw; max-height:90vh; border-radius:8px; object-fit:contain;">
</div>

@endsection

@section('scripts')
<script>
    function openImageModal(url) {
        document.getElementById('modal-img').src = url;
        document.getElementById('image-modal').style.display = 'flex';
    }
</script>
@endsection