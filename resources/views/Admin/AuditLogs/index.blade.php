@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Audit Log Aktivitas')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="margin:0;">Audit Log Aktivitas</h1>
    <a href="{{ route('admin.stats.export', ['type'=>'reports','period'=>30]) }}"
       style="background:var(--primary-color); color:#fff; padding:9px 18px; border-radius:8px; text-decoration:none; font-size:0.88em; font-weight:600;">
        <i class="fas fa-download"></i> Export
    </a>
</div>

{{-- FLASH --}}
@if(session('success'))
    <div style="background:#d1fae5; border:1px solid #10b981; color:#065f46; padding:12px 16px; border-radius:8px; margin-bottom:20px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

{{-- STAT CARDS --}}
<div class="dashboard-cards" style="margin-bottom:24px;">
    <div class="card" style="border-left:5px solid var(--primary-color);">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Total Log</h3>
        <p style="font-size:2em;font-weight:700;color:var(--primary-color);margin:0;">{{ number_format($stats['total']) }}</p>
    </div>
    <div class="card" style="border-left:5px solid #10b981;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Hari Ini</h3>
        <p style="font-size:2em;font-weight:700;color:#10b981;margin:0;">{{ $stats['today'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #3b82f6;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Minggu Ini</h3>
        <p style="font-size:2em;font-weight:700;color:#3b82f6;margin:0;">{{ $stats['this_week'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #8b5cf6;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Staf Aktif Hari Ini</h3>
        <p style="font-size:2em;font-weight:700;color:#8b5cf6;margin:0;">{{ $stats['active_staff'] }}</p>
    </div>
</div>

{{-- FILTER --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.audit-logs.index') }}"
          style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">

        <div style="flex:2; min-width:200px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Catatan, judul laporan, atau nama staf..."
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
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Diperbarui Oleh</label>
            <select name="user_id" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="">Semua Staf</option>
                @foreach($staffList as $staff)
                    <option value="{{ $staff->id }}" {{ request('user_id')==$staff->id ? 'selected':'' }}>
                        {{ $staff->name }}
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

        <div style="display:flex; gap:8px;">
            <button type="submit"
                    style="background:var(--primary-color); color:#fff; border:none; padding:9px 18px; border-radius:6px; cursor:pointer; font-size:0.9em;">
                <i class="fas fa-search"></i> Cari
            </button>
            @if(request()->hasAny(['search','status','user_id','date_from','date_to']))
                <a href="{{ route('admin.audit-logs.index') }}"
                   style="background:var(--background-light); color:var(--text-dark); padding:9px 14px; border-radius:6px; text-decoration:none; font-size:0.9em;">
                    <i class="fas fa-times"></i>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- TABLE --}}
<div class="card">
    @if($logs->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#aaa;">
            <i class="fas fa-history" style="font-size:3em; margin-bottom:12px; display:block;"></i>
            <p style="margin:0;">Tidak ada log aktivitas ditemukan.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.88em;">
                <thead>
                    <tr style="border-bottom:2px solid var(--background-light); text-align:left;">
                        <th style="padding:12px 10px;">#</th>
                        <th style="padding:12px 10px;">Laporan</th>
                        <th style="padding:12px 10px; text-align:center;">Status Baru</th>
                        <th style="padding:12px 10px;">Catatan</th>
                        <th style="padding:12px 10px;">Diperbarui Oleh</th>
                        <th style="padding:12px 10px;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                        @php
                            $sc = match($log->status) {
                                'active'   => '#f59e0b',
                                'process'  => '#3b82f6',
                                'done'     => '#10b981',
                                'rejected' => '#ef4444',
                                default    => '#777',
                            };
                            $sl = match($log->status) {
                                'active'   => 'Aktif',
                                'process'  => 'Diproses',
                                'done'     => 'Selesai',
                                'rejected' => 'Ditolak',
                                default    => $log->status,
                            };
                        @endphp
                        <tr style="border-bottom:1px solid var(--background-light);">

                            <td style="padding:12px 10px; color:#aaa; font-size:0.82em;">{{ $log->id }}</td>

                            <td style="padding:12px 10px; max-width:220px;">
                                @if($log->report)
                                    <a href="{{ route('admin.reports.show', $log->report_id) }}"
                                       style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.9em; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        {{ $log->report->title }}
                                    </a>
                                    <a href="{{ route('admin.audit-logs.by-report', $log->report_id) }}"
                                       style="font-size:0.75em; color:var(--primary-color); text-decoration:none;">
                                        Lihat riwayat laporan →
                                    </a>
                                @else
                                    <span style="color:#aaa; font-size:0.88em;">Laporan #{{ $log->report_id }} (dihapus)</span>
                                @endif
                            </td>

                            <td style="padding:12px 10px; text-align:center;">
                                <span style="background:{{ $sc }}1A; color:{{ $sc }}; padding:4px 12px; border-radius:20px; font-size:0.78em; font-weight:700; white-space:nowrap;">
                                    {{ $sl }}
                                </span>
                            </td>

                            <td style="padding:12px 10px; color:#555; max-width:240px;">
                                <span style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; font-size:0.88em; line-height:1.4;">
                                    {{ $log->note ?? '—' }}
                                </span>
                            </td>

                            <td style="padding:12px 10px;">
                                @if($log->updatedBy)
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <img src="{{ $log->updatedBy->avatar_url }}"
                                             style="width:28px; height:28px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                                        <div>
                                            <a href="{{ route('admin.audit-logs.by-user', $log->updated_by) }}"
                                               style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.85em; display:block;">
                                                {{ $log->updatedBy->name }}
                                            </a>
                                            <span style="font-size:0.72em; color:#aaa; text-transform:capitalize;">{{ $log->updatedBy->role }}</span>
                                        </div>
                                    </div>
                                @else
                                    <span style="color:#aaa; font-size:0.85em;">—</span>
                                @endif
                            </td>

                            <td style="padding:12px 10px; color:#aaa; font-size:0.82em; white-space:nowrap;">
                                <div>{{ $log->created_at->format('d M Y') }}</div>
                                <div style="font-size:0.9em;">{{ $log->created_at->format('H:i') }}</div>
                                <div style="font-size:0.85em; color:#ccc;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; font-size:0.85em; color:#777;">
            <span>Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} log</span>
            {{ $logs->links() }}
        </div>
    @endif
</div>

@endsection