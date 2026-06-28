@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Detail Kategori – ' . $category->name)

@section('content')

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.categories.index') }}" style="color:var(--primary-color); text-decoration:none;">Kategori</a>
    <span style="margin:0 6px;">/</span>
    {{ $category->name }}
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

{{-- HEADER --}}
<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:24px;">
    <div>
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:6px;">
            <span style="background:#f3f4f6; color:#555; padding:4px 12px; border-radius:20px; font-size:0.82em; font-weight:600;">
                <i class="fas fa-tag"></i> Kategori
            </span>
            @if($category->department)
                <a href="{{ route('admin.departments.show', $category->department->id) }}"
                   style="background:var(--primary-color); color:#fff; padding:4px 12px; border-radius:20px; font-size:0.82em; font-weight:600; text-decoration:none;">
                    {{ $category->department->code }}
                </a>
            @else
                <span style="background:#fef3c7; color:#92400e; padding:4px 12px; border-radius:20px; font-size:0.82em; font-weight:600;">
                    <i class="fas fa-exclamation-triangle"></i> Tanpa Dinas
                </span>
            @endif
        </div>
        <h1 style="margin:0; font-size:1.6em;">{{ $category->name }}</h1>
        @if($category->description)
            <p style="color:#777; font-size:0.9em; margin:6px 0 0; max-width:600px;">{{ $category->description }}</p>
        @endif
    </div>
    <div style="display:flex; gap:8px; flex-shrink:0;">
        <a href="{{ route('admin.categories.edit', $category->id) }}"
           style="background:var(--primary-color); color:#fff; padding:9px 18px; border-radius:8px; text-decoration:none; font-weight:600; font-size:0.9em;">
            <i class="fas fa-pencil-alt"></i> Edit
        </a>
        <form method="POST" action="{{ route('admin.categories.destroy', $category->id) }}"
              onsubmit="return confirm('Hapus kategori \'{{ addslashes($category->name) }}\'?')">
            @csrf @method('DELETE')
            <button type="submit"
                    style="background:#fee2e2; color:#dc2626; border:none; padding:9px 16px; border-radius:8px; cursor:pointer; font-size:0.9em; font-weight:600;">
                <i class="fas fa-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>

{{-- STAT CARDS --}}
<div style="display:grid; grid-template-columns:repeat(5,1fr); gap:14px; margin-bottom:24px;">
    @foreach([
        ['label'=>'Total Laporan','value'=>$reportStats['total'],'color'=>'#8b5cf6'],
        ['label'=>'Aktif','value'=>$reportStats['active'],'color'=>'#f59e0b'],
        ['label'=>'Diproses','value'=>$reportStats['process'],'color'=>'#3b82f6'],
        ['label'=>'Selesai','value'=>$reportStats['done'],'color'=>'#10b981'],
        ['label'=>'Ditolak','value'=>$reportStats['rejected'],'color'=>'#ef4444'],
    ] as $s)
        <div class="card" style="border-top:3px solid {{ $s['color'] }}; text-align:center; padding:14px 10px;">
            <p style="font-size:1.8em; font-weight:700; color:{{ $s['color'] }}; margin:0 0 4px;">{{ $s['value'] }}</p>
            <p style="font-size:0.75em; color:#999; margin:0;">{{ $s['label'] }}</p>
        </div>
    @endforeach
</div>

<div style="display:grid; grid-template-columns: 1fr 2fr; gap:24px; align-items:start;">

    {{-- LEFT: INFO + REMAP --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- INFO CARD --}}
        <div class="card">
            <h3 style="margin:0 0 14px; font-size:0.95em;">Informasi</h3>
            <table style="width:100%; font-size:0.88em; border-collapse:collapse;">
                <tr>
                    <td style="padding:6px 0; color:#777; width:100px;">ID</td>
                    <td style="padding:6px 0; font-weight:500;">#{{ $category->id }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#777;">Dinas</td>
                    <td style="padding:6px 0;">
                        @if($category->department)
                            <a href="{{ route('admin.departments.show', $category->department->id) }}"
                               style="color:var(--primary-color); text-decoration:none; font-weight:500;">
                                {{ $category->department->name }}
                            </a>
                        @else
                            <span style="color:#f59e0b;">Belum ditetapkan</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        {{-- REMAP DINAS --}}
        <div class="card">
            <h3 style="margin:0 0 6px; font-size:0.95em;">Pindah ke Dinas Lain</h3>
            <p style="font-size:0.82em; color:#aaa; margin:0 0 14px;">Kategori dan semua laporan terkait akan dipindahkan ke dinas yang dipilih.</p>

            <form method="POST" action="{{ route('admin.categories.remap', $category->id) }}">
                @csrf
                <div style="margin-bottom:10px;">
                    <select name="department_id" required
                            style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                        <option value="">-- Pilih Dinas Tujuan --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}"
                                    {{ $category->department_id == $dept->id ? 'selected disabled':'' }}>
                                {{ $dept->name }} ({{ $dept->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                        onclick="return confirm('Yakin pindahkan kategori ini ke dinas yang dipilih?')"
                        style="width:100%; background:#f59e0b; color:#fff; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.88em; font-weight:600;">
                    <i class="fas fa-exchange-alt"></i> Pindahkan Kategori
                </button>
            </form>
        </div>

    </div>

    {{-- RIGHT: LAPORAN TERBARU --}}
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <h3 style="margin:0;">Laporan dalam Kategori Ini</h3>
            @if($reportStats['total'] > 10)
                <a href="{{ route('admin.reports.index', ['category_id' => $category->id]) }}"
                   style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">
                    Lihat semua {{ $reportStats['total'] }} →
                </a>
            @endif
        </div>

        @if($category->reports->isEmpty())
            <div style="text-align:center; padding:40px 20px; color:#aaa;">
                <i class="fas fa-file-alt" style="font-size:2.5em; margin-bottom:10px; display:block;"></i>
                <p style="margin:0; font-size:0.9em;">Belum ada laporan dalam kategori ini.</p>
            </div>
        @else
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($category->reports as $report)
                    @php
                        $sc = match($report->status) {
                            'active'   => '#f59e0b',
                            'process'  => '#3b82f6',
                            'done'     => '#10b981',
                            'rejected' => '#ef4444',
                            default    => '#777',
                        };
                        $firstImage = $report->images->first();
                    @endphp
                    <div style="display:flex; gap:10px; align-items:flex-start; padding:10px; border:1px solid var(--background-light); border-radius:8px;">
                        @if($firstImage)
                            <img src="{{ asset('storage/' . $firstImage->image_url) }}"
                                 style="width:50px; height:50px; object-fit:cover; border-radius:6px; flex-shrink:0;">
                        @else
                            <div style="width:50px; height:50px; border-radius:6px; background:var(--background-light); display:flex; align-items:center; justify-content:center; color:#ccc; flex-shrink:0;">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                        <div style="flex:1; min-width:0;">
                            <a href="{{ route('admin.reports.show', $report->id) }}"
                               style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.9em; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $report->title }}
                            </a>
                            <div style="font-size:0.78em; color:#aaa; margin:2px 0 5px;">
                                oleh {{ $report->user->name ?? 'Anonim' }} · {{ $report->created_at->format('d M Y') }}
                            </div>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="background:{{ $sc }}1A; color:{{ $sc }}; padding:2px 8px; border-radius:20px; font-size:0.75em; font-weight:600; text-transform:capitalize;">
                                    {{ $report->status }}
                                </span>
                                <span style="font-size:0.78em; color:#aaa;">
                                    <i class="fas fa-arrow-up" style="color:var(--primary-color);"></i> {{ $report->votes_count }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

@endsection