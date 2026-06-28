@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Riwayat Laporan – ' . $report->title)

@section('content')

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.audit-logs.index') }}" style="color:var(--primary-color); text-decoration:none;">Audit Log</a>
    <span style="margin:0 6px;">/</span>
    <a href="{{ route('admin.reports.show', $report->id) }}" style="color:var(--primary-color); text-decoration:none;">{{ Str::limit($report->title, 40) }}</a>
    <span style="margin:0 6px;">/</span> Riwayat
</div>

@php
    $sc = match($report->status) {
        'active'   => '#f59e0b',
        'process'  => '#3b82f6',
        'done'     => '#10b981',
        'rejected' => '#ef4444',
        default    => '#777',
    };
    $sl = match($report->status) {
        'active'   => 'Aktif', 'process' => 'Diproses',
        'done'     => 'Selesai', 'rejected' => 'Ditolak',
        default    => $report->status,
    };
@endphp

{{-- LAPORAN HEADER --}}
<div class="card" style="margin-bottom:24px;">
    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px;">
        <div style="flex:1;">
            <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:8px;">
                <span style="background:{{ $sc }}1A; color:{{ $sc }}; padding:4px 12px; border-radius:20px; font-size:0.8em; font-weight:700;">{{ $sl }}</span>
                @if($report->department)
                    <span style="background:var(--primary-color); color:#fff; padding:4px 12px; border-radius:20px; font-size:0.8em; font-weight:700;">{{ $report->department->code }}</span>
                @endif
                @if($report->category)
                    <span style="background:#f3f4f6; color:#555; padding:4px 12px; border-radius:20px; font-size:0.8em; font-weight:600;">
                        <i class="fas fa-tag"></i> {{ $report->category->name }}
                    </span>
                @endif
            </div>
            <h2 style="margin:0 0 6px; font-size:1.2em;">{{ $report->title }}</h2>
            <div style="display:flex; gap:16px; flex-wrap:wrap; font-size:0.82em; color:#aaa;">
                @if($report->user)
                    <span><i class="fas fa-user"></i>
                        <a href="{{ route('admin.users.show', $report->user->id) }}" style="color:#aaa; text-decoration:none;">{{ $report->user->name }}</a>
                    </span>
                @endif
                @if($report->address)
                    <span><i class="fas fa-map-marker-alt"></i> {{ Str::limit($report->address, 50) }}</span>
                @endif
                <span><i class="fas fa-calendar"></i> {{ $report->created_at->format('d M Y, H:i') }}</span>
                <span><i class="fas fa-arrow-up" style="color:var(--primary-color);"></i> {{ $report->votes_count }} vote</span>
            </div>
        </div>
        <div style="display:flex; gap:8px; flex-shrink:0;">
            <a href="{{ route('admin.reports.show', $report->id) }}"
               style="background:var(--primary-color); color:#fff; padding:8px 16px; border-radius:6px; text-decoration:none; font-size:0.85em; font-weight:600;">
                <i class="fas fa-eye"></i> Lihat Laporan
            </a>
        </div>
    </div>
</div>

{{-- TIMELINE --}}
<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h3 style="margin:0;">Riwayat Perubahan Status ({{ $logs->count() }} log)</h3>
    </div>

    @if($logs->isEmpty())
        <div style="text-align:center; padding:40px; color:#aaa;">
            <i class="fas fa-history" style="font-size:2.5em; margin-bottom:10px; display:block;"></i>
            <p style="margin:0;">Belum ada riwayat perubahan.</p>
        </div>
    @else
        {{-- SUMMARY BAR --}}
        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:24px; padding:14px; background:var(--background-light); border-radius:8px;">
            <div style="font-size:0.82em; color:#555; font-weight:600; margin-right:4px; align-self:center;">Ringkasan:</div>
            @php
                $statusCounts = $logs->groupBy('status')->map->count();
            @endphp
            @foreach([
                ['key'=>'active','label'=>'Aktif','color'=>'#f59e0b'],
                ['key'=>'process','label'=>'Diproses','color'=>'#3b82f6'],
                ['key'=>'done','label'=>'Selesai','color'=>'#10b981'],
                ['key'=>'rejected','label'=>'Ditolak','color'=>'#ef4444'],
            ] as $s)
                @if(($statusCounts[$s['key']] ?? 0) > 0)
                    <span style="background:{{ $s['color'] }}1A; color:{{ $s['color'] }}; padding:4px 12px; border-radius:20px; font-size:0.8em; font-weight:600;">
                        {{ $s['label'] }}: {{ $statusCounts[$s['key']] }}x
                    </span>
                @endif
            @endforeach
            <span style="margin-left:auto; font-size:0.8em; color:#aaa; align-self:center;">
                Durasi total: {{ $logs->first()?->created_at->diffForHumans($logs->last()?->created_at, true) ?? '—' }}
            </span>
        </div>

        {{-- TIMELINE ITEMS --}}
        <div style="position:relative; padding-left:50px;">
            {{-- vertical line --}}
            <div style="position:absolute; left:18px; top:0; bottom:0; width:2px; background:var(--background-light);"></div>

            @foreach($logs as $log)
                @php
                    $lc = match($log->status) {
                        'active'   => '#f59e0b',
                        'process'  => '#3b82f6',
                        'done'     => '#10b981',
                        'rejected' => '#ef4444',
                        default    => '#aaa',
                    };
                    $ll = match($log->status) {
                        'active'   => 'Aktif',
                        'process'  => 'Diproses',
                        'done'     => 'Selesai',
                        'rejected' => 'Ditolak',
                        default    => $log->status,
                    };
                    $icon = match($log->status) {
                        'done'     => 'fa-check',
                        'rejected' => 'fa-times',
                        'process'  => 'fa-cog',
                        default    => 'fa-bell',
                    };
                @endphp

                <div style="position:relative; padding-bottom:28px; {{ $loop->last ? 'padding-bottom:0' : '' }}">
                    {{-- DOT --}}
                    <div style="position:absolute; left:-42px; top:2px; width:34px; height:34px; border-radius:50%; background:{{ $lc }}; display:flex; align-items:center; justify-content:center; border:3px solid #fff; box-shadow:0 0 0 2px {{ $lc }}; z-index:1;">
                        <i class="fas {{ $icon }}" style="color:#fff; font-size:0.75em;"></i>
                    </div>

                    {{-- CONTENT --}}
                    <div style="background:var(--background-light); border-radius:10px; padding:14px 16px; border-left:3px solid {{ $lc }};">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:8px; flex-wrap:wrap;">
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="background:{{ $lc }}1A; color:{{ $lc }}; padding:3px 10px; border-radius:20px; font-size:0.78em; font-weight:700;">
                                    {{ $ll }}
                                </span>
                                @if($log->updatedBy)
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <img src="{{ $log->updatedBy->avatar_url }}"
                                             style="width:22px; height:22px; border-radius:50%; object-fit:cover;">
                                        <a href="{{ route('admin.audit-logs.by-user', $log->updated_by) }}"
                                           style="font-size:0.8em; color:#555; text-decoration:none; font-weight:600;">
                                            {{ $log->updatedBy->name }}
                                        </a>
                                        <span style="font-size:0.72em; color:#aaa; text-transform:capitalize;">({{ $log->updatedBy->role }})</span>
                                    </div>
                                @endif
                            </div>
                            <div style="text-align:right; flex-shrink:0;">
                                <div style="font-size:0.82em; color:#555; font-weight:500;">{{ $log->created_at->format('d M Y, H:i') }}</div>
                                <div style="font-size:0.75em; color:#aaa;">{{ $log->created_at->diffForHumans() }}</div>
                            </div>
                        </div>

                        @if($log->note)
                            <p style="margin:0; font-size:0.88em; color:#555; line-height:1.6; white-space:pre-wrap;">{{ $log->note }}</p>
                        @else
                            <p style="margin:0; font-size:0.85em; color:#aaa; font-style:italic;">Tidak ada catatan.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@endsection