@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Log Aktivitas – ' . $user->name)

@section('content')

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.audit-logs.index') }}" style="color:var(--primary-color); text-decoration:none;">Audit Log</a>
    <span style="margin:0 6px;">/</span>
    {{ $user->name }}
</div>

{{-- HEADER --}}
<div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
    <img src="{{ $user->avatar_url }}"
         style="width:56px; height:56px; border-radius:50%; object-fit:cover; border:3px solid var(--background-light); flex-shrink:0;">
    <div>
        <h1 style="margin:0; font-size:1.4em;">{{ $user->name }}</h1>
        <div style="display:flex; gap:8px; margin-top:6px; flex-wrap:wrap;">
            <span style="font-size:0.82em; color:#777;">{{ $user->email }}</span>
            @php
                $roleColor = match($user->role) { 'admin'=>'#ef4444','pemda'=>'#3b82f6',default=>'#10b981' };
                $statusColor = match($user->status) { 'active'=>'#10b981','inactive'=>'#f59e0b','banned'=>'#ef4444',default=>'#777' };
            @endphp
            <span style="background:{{ $roleColor }}1A; color:{{ $roleColor }}; padding:2px 8px; border-radius:20px; font-size:0.78em; font-weight:600; text-transform:capitalize;">{{ $user->role }}</span>
            <span style="background:{{ $statusColor }}1A; color:{{ $statusColor }}; padding:2px 8px; border-radius:20px; font-size:0.78em; font-weight:600; text-transform:capitalize;">{{ $user->status }}</span>
        </div>
    </div>
    <div style="margin-left:auto; display:flex; gap:8px;">
        <a href="{{ route('admin.users.show', $user->id) }}"
           style="background:var(--background-light); color:var(--text-dark); padding:8px 16px; border-radius:6px; text-decoration:none; font-size:0.85em; font-weight:600;">
            <i class="fas fa-user"></i> Profil
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="dashboard-cards" style="margin-bottom:24px;">
    <div class="card" style="border-left:5px solid var(--primary-color);">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Total Aktivitas</h3>
        <p style="font-size:2em;font-weight:700;color:var(--primary-color);margin:0;">{{ number_format($userStats['total']) }}</p>
    </div>
    <div class="card" style="border-left:5px solid #3b82f6;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Hari Ini</h3>
        <p style="font-size:2em;font-weight:700;color:#3b82f6;margin:0;">{{ $userStats['today'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #10b981;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Diselesaikan</h3>
        <p style="font-size:2em;font-weight:700;color:#10b981;margin:0;">{{ $userStats['done'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #ef4444;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Ditolak</h3>
        <p style="font-size:2em;font-weight:700;color:#ef4444;margin:0;">{{ $userStats['rejected'] }}</p>
    </div>
    <div class="card" style="border-left:5px solid #aaa;">
        <h3 style="font-size:0.8em;color:#777;text-transform:uppercase;margin:0 0 6px;">Login Terakhir</h3>
        <p style="font-size:1em;font-weight:600;color:#555;margin:0; padding-top:6px;">
            {{ $user->last_login_at ? $user->last_login_at->format('d M Y H:i') : '—' }}
        </p>
        @if($user->last_login_at)
            <p style="font-size:0.78em;color:#aaa;margin:2px 0 0;">{{ $user->last_login_at->diffForHumans() }}</p>
        @endif
    </div>
</div>

{{-- FILTER STATUS --}}
<div class="card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.audit-logs.by-user', $user->id) }}"
          style="display:flex; gap:10px; align-items:flex-end;">
        <div style="min-width:160px;">
            <label style="font-size:0.8em;color:#777;display:block;margin-bottom:4px;">Filter Status</label>
            <select name="status" style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status')=='active'   ? 'selected':'' }}>Aktif</option>
                <option value="process"  {{ request('status')=='process'  ? 'selected':'' }}>Diproses</option>
                <option value="done"     {{ request('status')=='done'     ? 'selected':'' }}>Selesai</option>
                <option value="rejected" {{ request('status')=='rejected' ? 'selected':'' }}>Ditolak</option>
            </select>
        </div>
        <button type="submit"
                style="background:var(--primary-color); color:#fff; border:none; padding:9px 18px; border-radius:6px; cursor:pointer; font-size:0.9em;">
            <i class="fas fa-filter"></i> Filter
        </button>
        @if(request('status'))
            <a href="{{ route('admin.audit-logs.by-user', $user->id) }}"
               style="background:var(--background-light); color:var(--text-dark); padding:9px 14px; border-radius:6px; text-decoration:none; font-size:0.9em;">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </form>
</div>

{{-- LOG TABLE --}}
<div class="card">
    @if($logs->isEmpty())
        <div style="text-align:center; padding:60px 20px; color:#aaa;">
            <i class="fas fa-history" style="font-size:3em; margin-bottom:12px; display:block;"></i>
            <p style="margin:0;">Pengguna ini belum memiliki aktivitas.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.88em;">
                <thead>
                    <tr style="border-bottom:2px solid var(--background-light); text-align:left;">
                        <th style="padding:12px 10px;">#</th>
                        <th style="padding:12px 10px;">Laporan</th>
                        <th style="padding:12px 10px; text-align:center;">Status</th>
                        <th style="padding:12px 10px;">Catatan</th>
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
                            <td style="padding:12px 10px; max-width:260px;">
                                @if($log->report)
                                    <a href="{{ route('admin.reports.show', $log->report_id) }}"
                                       style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.9em; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                        {{ $log->report->title }}
                                    </a>
                                    <a href="{{ route('admin.audit-logs.by-report', $log->report_id) }}"
                                       style="font-size:0.75em; color:var(--primary-color); text-decoration:none;">
                                        Riwayat laporan →
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
                            <td style="padding:12px 10px; color:#555; max-width:260px;">
                                <span style="display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; font-size:0.88em; line-height:1.4;">
                                    {{ $log->note ?? '—' }}
                                </span>
                            </td>
                            <td style="padding:12px 10px; color:#aaa; font-size:0.82em; white-space:nowrap;">
                                <div>{{ $log->created_at->format('d M Y') }}</div>
                                <div>{{ $log->created_at->format('H:i') }}</div>
                                <div style="font-size:0.85em; color:#ccc;">{{ $log->created_at->diffForHumans() }}</div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px; display:flex; justify-content:space-between; align-items:center; font-size:0.85em; color:#777;">
            <span>Menampilkan {{ $logs->firstItem() }}–{{ $logs->lastItem() }} dari {{ $logs->total() }} log</span>
            {{ $logs->links() }}
        </div>
    @endif
</div>

@endsection