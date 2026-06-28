@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Statistik Overview')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="margin:0;">Statistik & Analitik</h1>
    <div style="display:flex; gap:8px; align-items:center;">
        {{-- PERIOD FILTER --}}
        <form method="GET" action="{{ route('admin.stats.overview') }}" style="display:flex; gap:8px;">
            <select name="period" onchange="this.form.submit()"
                    style="padding:8px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.88em; background:#fff;">
                <option value="7"   {{ $period=='7'   ? 'selected':'' }}>7 Hari</option>
                <option value="30"  {{ $period=='30'  ? 'selected':'' }}>30 Hari</option>
                <option value="90"  {{ $period=='90'  ? 'selected':'' }}>90 Hari</option>
                <option value="365" {{ $period=='365' ? 'selected':'' }}>1 Tahun</option>
            </select>
        </form>
        <a href="{{ route('admin.stats.export') }}"
           style="background:var(--primary-color); color:#fff; padding:8px 16px; border-radius:6px; text-decoration:none; font-size:0.88em; font-weight:600;">
            <i class="fas fa-download"></i> Export
        </a>
    </div>
</div>

{{-- NAV TABS --}}
@include('Admin.Stats._tabs', ['active' => 'overview'])

{{-- KPI ROW --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin-bottom:24px;">
    <div class="card" style="border-top:4px solid var(--primary-color);">
        <p style="font-size:0.78em;color:#aaa;text-transform:uppercase;font-weight:600;margin:0 0 6px;">Total Laporan</p>
        <p style="font-size:2.2em;font-weight:800;color:var(--text-dark);margin:0 0 4px;">{{ number_format($totalReports) }}</p>
        <p style="font-size:0.8em;color:#10b981;margin:0;"><i class="fas fa-arrow-up"></i> {{ $newThisPeriod }} baru dalam {{ $period }} hari</p>
    </div>
    <div class="card" style="border-top:4px solid #10b981;">
        <p style="font-size:0.78em;color:#aaa;text-transform:uppercase;font-weight:600;margin:0 0 6px;">Diselesaikan</p>
        <p style="font-size:2.2em;font-weight:800;color:#10b981;margin:0 0 4px;">{{ number_format($doneThisPeriod) }}</p>
        <p style="font-size:0.8em;color:#aaa;margin:0;">dalam {{ $period }} hari terakhir</p>
    </div>
    <div class="card" style="border-top:4px solid #3b82f6;">
        <p style="font-size:0.78em;color:#aaa;text-transform:uppercase;font-weight:600;margin:0 0 6px;">Rata-rata Selesai</p>
        <p style="font-size:2.2em;font-weight:800;color:#3b82f6;margin:0 0 4px;">{{ $avgResolutionDays ? round($avgResolutionDays, 1) : '—' }}</p>
        <p style="font-size:0.8em;color:#aaa;margin:0;">hari per laporan</p>
    </div>
    <div class="card" style="border-top:4px solid #8b5cf6;">
        <p style="font-size:0.78em;color:#aaa;text-transform:uppercase;font-weight:600;margin:0 0 6px;">Total Pengguna</p>
        <p style="font-size:2.2em;font-weight:800;color:#8b5cf6;margin:0 0 4px;">{{ number_format($totalUsers) }}</p>
        <p style="font-size:0.8em;color:#10b981;margin:0;"><i class="fas fa-arrow-up"></i> {{ $newUsers }} baru dalam {{ $period }} hari</p>
    </div>
</div>

{{-- CHARTS ROW 1 --}}
<div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:20px;">
    <div class="card">
        <h3 style="margin:0 0 16px;">Tren Laporan Masuk ({{ $period }} Hari)</h3>
        <canvas id="chartDaily" height="110"></canvas>
    </div>
    <div class="card">
        <h3 style="margin:0 0 16px;">Distribusi Status</h3>
        <canvas id="chartStatus" height="160"></canvas>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:6px; margin-top:14px;">
            @foreach([
                ['label'=>'Aktif','value'=>$statusChart['active'],'color'=>'#f59e0b'],
                ['label'=>'Diproses','value'=>$statusChart['process'],'color'=>'#3b82f6'],
                ['label'=>'Selesai','value'=>$statusChart['done'],'color'=>'#10b981'],
                ['label'=>'Ditolak','value'=>$statusChart['rejected'],'color'=>'#ef4444'],
            ] as $s)
                <div style="background:var(--background-light); padding:8px 10px; border-radius:6px; border-left:3px solid {{ $s['color'] }};">
                    <p style="font-size:0.75em;color:#777;margin:0;">{{ $s['label'] }}</p>
                    <p style="font-size:1.2em;font-weight:700;color:{{ $s['color'] }};margin:2px 0 0;">{{ number_format($s['value']) }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- CHARTS ROW 2 --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
    <div class="card">
        <h3 style="margin:0 0 16px;">Top Kategori</h3>
        <canvas id="chartCategories" height="160"></canvas>
    </div>
    <div class="card">
        <h3 style="margin:0 0 16px;">Pengguna berdasarkan Role</h3>
        <canvas id="chartUserRole" height="160"></canvas>
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-top:14px; text-align:center;">
            @foreach([
                ['label'=>'Admin','value'=>$usersByRole['admin'] ?? 0,'color'=>'#ef4444'],
                ['label'=>'Pemda','value'=>$usersByRole['pemda'] ?? 0,'color'=>'#3b82f6'],
                ['label'=>'Warga','value'=>$usersByRole['warga'] ?? 0,'color'=>'#10b981'],
            ] as $r)
                <div style="background:var(--background-light); padding:8px; border-radius:6px;">
                    <p style="font-size:0.75em;color:#777;margin:0;">{{ $r['label'] }}</p>
                    <p style="font-size:1.3em;font-weight:700;color:{{ $r['color'] }};margin:2px 0 0;">{{ $r['value'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ROW 3: DINAS PERFORMA --}}
<div class="card" style="margin-bottom:20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
        <h3 style="margin:0;">Performa Dinas</h3>
        <a href="{{ route('admin.stats.departments') }}" style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">Detail lengkap →</a>
    </div>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:0.88em;">
            <thead>
                <tr style="border-bottom:2px solid var(--background-light); text-align:left;">
                    <th style="padding:10px 8px;">Dinas</th>
                    <th style="padding:10px 8px; text-align:center;">Total</th>
                    <th style="padding:10px 8px; text-align:center;">Aktif</th>
                    <th style="padding:10px 8px; text-align:center;">Proses</th>
                    <th style="padding:10px 8px; text-align:center;">Selesai</th>
                    <th style="padding:10px 8px; text-align:center;">Ditolak</th>
                    <th style="padding:10px 8px;">% Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deptPerformance as $dept)
                    @php $barColor = $dept['pct'] >= 75 ? '#10b981' : ($dept['pct'] >= 40 ? '#f59e0b' : '#ef4444'); @endphp
                    <tr style="border-bottom:1px solid var(--background-light);">
                        <td style="padding:10px 8px;">
                            <span style="font-weight:700; font-size:0.85em; color:var(--primary-color);">{{ $dept['code'] }}</span>
                            <span style="font-size:0.82em; color:#777; margin-left:6px;">{{ $dept['name'] }}</span>
                        </td>
                        <td style="padding:10px 8px; text-align:center; font-weight:700;">{{ $dept['total'] }}</td>
                        <td style="padding:10px 8px; text-align:center; color:#f59e0b;">—</td>
                        <td style="padding:10px 8px; text-align:center; color:#3b82f6;">—</td>
                        <td style="padding:10px 8px; text-align:center; color:#10b981; font-weight:700;">{{ $dept['done'] }}</td>
                        <td style="padding:10px 8px; text-align:center; color:#ef4444;">—</td>
                        <td style="padding:10px 8px; min-width:140px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="flex:1; background:var(--background-light); border-radius:4px; height:6px;">
                                    <div style="width:{{ $dept['pct'] }}%; background:{{ $barColor }}; height:6px; border-radius:4px;"></div>
                                </div>
                                <span style="font-size:0.8em; color:{{ $barColor }}; font-weight:700; min-width:36px; text-align:right;">{{ $dept['pct'] }}%</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- ROW 4: TOP REPORTERS + TOP VOTED --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <h3 style="margin:0;">Top Pelapor</h3>
        </div>
        @foreach($topReporters as $i => $reporter)
            <div style="display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid var(--background-light);">
                <div style="width:28px; height:28px; border-radius:50%; background:{{ ['#f59e0b','#9ca3af','#b45309','var(--primary-color)','#6b7280'][$i] ?? 'var(--background-light)' }}; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:0.78em; flex-shrink:0;">
                    {{ $i + 1 }}
                </div>
                @php
                    $avatarPath = $reporter->avatar;

                    if ($avatarPath && !str_contains($avatarPath, 'http')) {
                        // kalau dia sudah ada 'storage/' jangan ditambah lagi
                        $avatarUrl = str_contains($avatarPath, 'storage/')
                            ? asset($avatarPath)
                            : asset('storage/' . $avatarPath);
                    } else {
                        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($reporter->name) . '&background=133a68&color=fff';
                    }
                @endphp
                <img src="{{ $avatarUrl }}" 
                style="width:34px; height:34px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                <div style="flex:1; min-width:0;">
                    <a href="{{ route('admin.users.show', $reporter->id) }}"
                       style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.88em;">
                        {{ $reporter->name }}
                    </a>
                    <div style="font-size:0.75em; color:#aaa;">{{ $reporter->email }}</div>
                </div>
                <span style="background:var(--primary-color); color:#fff; padding:3px 10px; border-radius:20px; font-size:0.8em; font-weight:700; flex-shrink:0;">
                    {{ $reporter->reports_count }} laporan
                </span>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
            <h3 style="margin:0;">Top Vote</h3>
            <a href="{{ route('admin.stats.top-votes') }}" style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">Lihat semua →</a>
        </div>
        @foreach($topVoted as $i => $report)
            @php
                $sc = match($report->status) {
                    'active' => '#f59e0b', 'process' => '#3b82f6',
                    'done' => '#10b981', 'rejected' => '#ef4444', default => '#777',
                };
            @endphp
            <div style="display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid var(--background-light);">
                <div style="width:28px; text-align:center; font-weight:800; font-size:1em; color:{{ ['#f59e0b','#9ca3af','#b45309','#aaa','#aaa'][$i] ?? '#aaa' }}; flex-shrink:0;">
                    #{{ $i + 1 }}
                </div>
                <div style="flex:1; min-width:0;">
                    <a href="{{ route('admin.reports.show', $report->id) }}"
                       style="color:var(--text-dark); text-decoration:none; font-weight:600; font-size:0.88em; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $report->title }}
                    </a>
                    <div style="font-size:0.75em; color:#aaa;">{{ $report->department->name ?? '—' }}</div>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:3px; flex-shrink:0;">
                    <span style="font-weight:800; color:var(--primary-color); font-size:0.9em;">
                        <i class="fas fa-arrow-up"></i> {{ $report->votes_count }}
                    </span>
                    <span style="background:{{ $sc }}1A; color:{{ $sc }}; padding:2px 7px; border-radius:20px; font-size:0.72em; font-weight:600;">
                        {{ $report->status }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const dailyRaw      = @json($dailyReports);
    const statusRaw     = @json($statusChart);
    const categoriesRaw = @json($topCategories);
    const userRoleRaw   = @json($usersByRole);

    // Line chart – tren harian
    new Chart(document.getElementById('chartDaily'), {
        type: 'line',
        data: {
            labels: dailyRaw.map(d => {
                const dt = new Date(d.date);
                return dt.toLocaleDateString('id-ID', { day:'2-digit', month:'short' });
            }),
            datasets: [{
                label: 'Laporan',
                data: dailyRaw.map(d => d.total),
                borderColor: '#AA0E0E',
                backgroundColor: 'rgba(170,14,14,0.08)',
                fill: true, tension: 0.3, pointRadius: 3,
                pointBackgroundColor: '#AA0E0E',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // Doughnut – status
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Aktif','Diproses','Selesai','Ditolak'],
            datasets: [{
                data: [statusRaw.active, statusRaw.process, statusRaw.done, statusRaw.rejected],
                backgroundColor: ['#f59e0b','#3b82f6','#10b981','#ef4444'],
                borderWidth: 0,
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } }, cutout: '65%' }
    });

    // Doughnut – kategori
    new Chart(document.getElementById('chartCategories'), {
        type: 'doughnut',
        data: {
            labels: categoriesRaw.map(c => c.name),
            datasets: [{
                data: categoriesRaw.map(c => c.reports_count),
                backgroundColor: ['#AA0E0E','#f59e0b','#3b82f6','#10b981','#8b5cf6','#06b6d4','#f97316','#ec4899'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
            cutout: '55%'
        }
    });

    // Doughnut – user role
    new Chart(document.getElementById('chartUserRole'), {
        type: 'doughnut',
        data: {
            labels: ['Admin','Pemda','Warga'],
            datasets: [{
                data: [userRoleRaw.admin ?? 0, userRoleRaw.pemda ?? 0, userRoleRaw.warga ?? 0],
                backgroundColor: ['#ef4444','#3b82f6','#10b981'],
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            cutout: '60%'
        }
    });
</script>
@endsection