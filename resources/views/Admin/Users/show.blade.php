@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Detail Pengguna – ' . $user->name)

@section('content')

@php
    $roleColor = match($user->role) {
        'admin' => '#ef4444',
        'pemda' => '#3b82f6',
        default => '#10b981',
    };
    $statusColor = match($user->status) {
        'active'   => '#10b981',
        'inactive' => '#f59e0b',
        'banned'   => '#ef4444',
        default    => '#777',
    };
    $statusLabel = match($user->status) {
        'active'   => 'Aktif',
        'inactive' => 'Suspended',
        'banned'   => 'Banned',
        default    => $user->status,
    };
@endphp

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.users.index') }}" style="color:var(--primary-color); text-decoration:none;">Pengguna</a>
    <span style="margin:0 6px;">/</span>
    {{ $user->name }}
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

<div style="display:grid; grid-template-columns: 1fr 2fr; gap:24px; align-items:start;">

    {{-- LEFT COLUMN: Profile Card + Quick Actions --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- PROFILE CARD --}}
        <div class="card" style="text-align:center; padding-top:30px;">
            <img src="{{ $user->avatar_url }}"
     style="width:90px; height:90px; border-radius:50%; object-fit:cover; border:3px solid var(--background-light); margin:0 auto 14px; display:block;">
            <h2 style="margin:0 0 6px; font-size:1.2em;">{{ $user->name }}</h2>
            <p style="color:#999; font-size:0.85em; margin:0 0 12px;">{{ $user->email }}</p>

            <div style="display:flex; justify-content:center; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
                <span style="background:{{ $roleColor }}1A; color:{{ $roleColor }}; padding:5px 14px; border-radius:20px; font-size:0.82em; font-weight:600; text-transform:capitalize;">
                    {{ $user->role }}
                </span>
                <span style="background:{{ $statusColor }}1A; color:{{ $statusColor }}; padding:5px 14px; border-radius:20px; font-size:0.82em; font-weight:600;">
                    {{ $statusLabel }}
                </span>
            </div>

            @if($user->phone)
                <p style="color:#777; font-size:0.85em; margin:0 0 8px;"><i class="fas fa-phone" style="width:16px;"></i> {{ $user->phone }}</p>
            @endif
            <p style="color:#aaa; font-size:0.8em; margin:0 0 4px;"><i class="fas fa-calendar-plus" style="width:16px;"></i> Bergabung {{ $user->created_at->format('d M Y') }}</p>
            <p style="color:#aaa; font-size:0.8em; margin:0;"><i class="fas fa-clock" style="width:16px;"></i>
                Login terakhir: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
            </p>

            <div style="border-top:1px solid var(--background-light); margin:20px 0 16px;"></div>

            <a href="{{ route('admin.users.edit', $user->id) }}"
               style="display:block; background:var(--primary-color); color:#fff; padding:10px; border-radius:8px; text-decoration:none; font-weight:600; font-size:0.9em; margin-bottom:8px;">
                <i class="fas fa-pencil-alt"></i> Edit Profil
            </a>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="card">
            <h3 style="margin:0 0 14px; font-size:0.95em;">Aksi Cepat</h3>

            {{-- STATUS ACTIONS --}}
            <p style="font-size:0.78em; color:#aaa; text-transform:uppercase; margin:0 0 8px; font-weight:600;">Ubah Status</p>
            <div style="display:flex; flex-direction:column; gap:8px; margin-bottom:16px;">
                @if($user->status !== 'active')
                    <form method="POST" action="{{ route('admin.users.unban', $user->id) }}">
                        @csrf
                        <button type="submit"
                                style="width:100%; background:#d1fae5; color:#065f46; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.85em; font-weight:600;">
                            <i class="fas fa-check-circle"></i> Aktifkan Akun
                        </button>
                    </form>
                @endif
                @if($user->status !== 'inactive')
                    <form method="POST" action="{{ route('admin.users.suspend', $user->id) }}">
                        @csrf
                        <button type="submit"
                                style="width:100%; background:#fef3c7; color:#92400e; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.85em; font-weight:600;">
                            <i class="fas fa-pause-circle"></i> Suspend Akun
                        </button>
                    </form>
                @endif
                @if($user->status !== 'banned')
                    <form method="POST" action="{{ route('admin.users.ban', $user->id) }}"
                          onsubmit="return confirm('Yakin ingin ban pengguna {{ addslashes($user->name) }}?')">
                        @csrf
                        <button type="submit"
                                style="width:100%; background:#fee2e2; color:#991b1b; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.85em; font-weight:600;">
                            <i class="fas fa-ban"></i> Ban Pengguna
                        </button>
                    </form>
                @endif
            </div>

            {{-- ROLE CHANGE --}}
            <p style="font-size:0.78em; color:#aaa; text-transform:uppercase; margin:0 0 8px; font-weight:600;">Ubah Role</p>
            <form method="POST" action="{{ route('admin.users.role', $user->id) }}"
                  style="display:flex; gap:8px;">
                @csrf
                <select name="role" style="flex:1; padding:8px 10px; border:1px solid var(--background-light); border-radius:6px; font-size:0.85em;">
                    <option value="admin"  {{ $user->role=='admin'  ? 'selected':'' }}>Admin</option>
                    <option value="pemda"  {{ $user->role=='pemda'  ? 'selected':'' }}>Pemda</option>
                    <option value="warga"  {{ $user->role=='warga'  ? 'selected':'' }}>Warga</option>
                </select>
                <button type="submit"
                        style="background:var(--primary-color); color:#fff; border:none; padding:8px 14px; border-radius:6px; cursor:pointer; font-size:0.85em; font-weight:600;">
                    Simpan
                </button>
            </form>

            <div style="border-top:1px solid var(--background-light); margin:16px 0;"></div>

            {{-- DELETE --}}
            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}"
                  onsubmit="return confirm('HAPUS pengguna {{ addslashes($user->name) }} secara permanen?')">
                @csrf @method('DELETE')
                <button type="submit"
                        style="width:100%; background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; padding:9px; border-radius:6px; cursor:pointer; font-size:0.85em; font-weight:600;">
                    <i class="fas fa-trash"></i> Hapus Pengguna
                </button>
            </form>
        </div>

    </div>

    {{-- RIGHT COLUMN --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- REPORT STATS --}}
        <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:12px;">
            @foreach([
                ['label'=>'Total','value'=>$reportStats['total'],'color'=>'#8b5cf6'],
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

        {{-- DINAS MANAGEMENT --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h3 style="margin:0;">Dinas yang Dikelola</h3>
                <button onclick="document.getElementById('assign-dept-form').classList.toggle('hidden')"
                        style="background:var(--primary-color); color:#fff; border:none; padding:7px 14px; border-radius:6px; cursor:pointer; font-size:0.82em; font-weight:600;">
                    <i class="fas fa-plus"></i> Tambah Dinas
                </button>
            </div>

            {{-- ASSIGN FORM --}}
            <div id="assign-dept-form" class="hidden"
                 style="background:var(--background-light); padding:14px; border-radius:8px; margin-bottom:14px;">
                <form method="POST" action="{{ route('admin.users.departments.assign', $user->id) }}"
                      style="display:flex; gap:10px; align-items:flex-end;">
                    @csrf
                    <div style="flex:1;">
                        <label style="font-size:0.8em; color:#777; display:block; margin-bottom:4px;">Pilih Dinas</label>
                        <select name="department_id" required
                                style="width:100%; padding:9px 12px; border:1px solid #e5e7eb; border-radius:6px; font-size:0.9em;">
                            <option value="">-- Pilih Dinas --</option>
                            @foreach($departments as $dept)
                                @if(!$user->departments->contains($dept->id))
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                            style="background:var(--primary-color); color:#fff; border:none; padding:9px 18px; border-radius:6px; cursor:pointer; font-weight:600; white-space:nowrap;">
                        Tambah
                    </button>
                </form>
            </div>

            @if($user->departments->isEmpty())
                <p style="color:#aaa; font-size:0.9em;">Pengguna ini belum memiliki dinas.</p>
            @else
                <div style="display:flex; flex-direction:column; gap:8px;">
                    @foreach($user->departments as $dept)
                        <div style="display:flex; align-items:center; justify-content:space-between; background:var(--background-light); padding:10px 14px; border-radius:8px;">
                            <div>
                                <span style="font-weight:600; font-size:0.9em;">{{ $dept->name }}</span>
                                <span style="margin-left:8px; background:#fff; color:var(--primary-color); padding:2px 8px; border-radius:4px; font-size:0.78em; font-weight:600;">{{ $dept->code }}</span>
                            </div>
                            <form method="POST" action="{{ route('admin.users.departments.remove', [$user->id, $dept->id]) }}"
                                  onsubmit="return confirm('Hapus dinas ini dari pengguna?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="background:#fee2e2; color:#dc2626; border:none; padding:5px 10px; border-radius:5px; cursor:pointer; font-size:0.8em;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- LAPORAN TERBARU PENGGUNA --}}
        <div class="card">
            <h3 style="margin:0 0 14px;">Laporan Terbaru</h3>

            @if($user->reports->isEmpty())
                <p style="color:#aaa; font-size:0.9em;">Belum ada laporan dari pengguna ini.</p>
            @else
                <table style="width:100%; border-collapse:collapse; font-size:0.88em;">
                    <thead>
                        <tr style="border-bottom:2px solid var(--background-light); text-align:left;">
                            <th style="padding:8px 8px;">Judul</th>
                            <th style="padding:8px 8px;">Dinas</th>
                            <th style="padding:8px 8px;">Status</th>
                            <th style="padding:8px 8px;">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->reports as $report)
                            @php
                                $sc = match($report->status) {
                                    'active'   => '#f59e0b',
                                    'process'  => '#3b82f6',
                                    'done'     => '#10b981',
                                    'rejected' => '#ef4444',
                                    default    => '#777',
                                };
                            @endphp
                            <tr style="border-bottom:1px solid var(--background-light);">
                                <td style="padding:10px 8px;">
                                    <a href="{{ route('admin.reports.show', $report->id) }}"
                                       style="color:var(--text-dark); text-decoration:none; font-weight:500;">
                                        {{ $report->title }}
                                    </a>
                                </td>
                                <td style="padding:10px 8px; color:#777;">
                                    {{ $report->department->name ?? '—' }}
                                </td>
                                <td style="padding:10px 8px;">
                                    <span style="background:{{ $sc }}1A; color:{{ $sc }}; padding:3px 9px; border-radius:20px; font-size:0.8em; font-weight:600; text-transform:capitalize;">
                                        {{ $report->status }}
                                    </span>
                                </td>
                                <td style="padding:10px 8px; color:#999; white-space:nowrap;">
                                    {{ $report->created_at->format('d M Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($reportStats['total'] > 5)
                    <div style="margin-top:12px; text-align:center;">
                        <a href="{{ route('admin.reports.index', ['user_id' => $user->id]) }}"
                           style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">
                            Lihat semua {{ $reportStats['total'] }} laporan →
                        </a>
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>

@endsection

@section('scripts')
<style>
    .hidden { display: none !important; }
</style>
@endsection