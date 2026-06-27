@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Dashboard Admin')

@section('content')

    <h1>Dashboard Admin</h1>

    {{-- ================= STAT CARDS ================= --}}
    <div class="dashboard-cards" style="margin-bottom: 30px;">

        <div class="card" style="border-left: 5px solid var(--primary-color);">
            <h3 style="font-size:0.85em; color:#777; text-transform:uppercase;">Total Laporan</h3>
            <p style="font-size:2em; font-weight:700; color:var(--text-dark);">{{ $totalReports }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #f59e0b;">
            <h3 style="font-size:0.85em; color:#777; text-transform:uppercase;">active</h3>
            <p style="font-size:2em; font-weight:700; color:#f59e0b;">{{ $active }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #3b82f6;">
            <h3 style="font-size:0.85em; color:#777; text-transform:uppercase;">Diproses</h3>
            <p style="font-size:2em; font-weight:700; color:#3b82f6;">{{ $process }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #10b981;">
            <h3 style="font-size:0.85em; color:#777; text-transform:uppercase;">Selesai</h3>
            <p style="font-size:2em; font-weight:700; color:#10b981;">{{ $done }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #ef4444;">
            <h3 style="font-size:0.85em; color:#777; text-transform:uppercase;">Ditolak</h3>
            <p style="font-size:2em; font-weight:700; color:#ef4444;">{{ $rejected }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #8b5cf6;">
            <h3 style="font-size:0.85em; color:#777; text-transform:uppercase;">Total Pengguna</h3>
            <p style="font-size:2em; font-weight:700; color:#8b5cf6;">{{ $totalUsers }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #06b6d4;">
            <h3 style="font-size:0.85em; color:#777; text-transform:uppercase;">Total Dinas</h3>
            <p style="font-size:2em; font-weight:700; color:#06b6d4;">{{ $totalDepartments }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #f97316;">
            <h3 style="font-size:0.85em; color:#777; text-transform:uppercase;">User Banned</h3>
            <p style="font-size:2em; font-weight:700; color:#f97316;">{{ $bannedUsers }}</p>
        </div>

    </div>

    {{-- ================= CHARTS ROW 1 ================= --}}
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:25px; margin-bottom:25px;">

        <div class="card">
            <h3>Tren Laporan Masuk (30 Hari)</h3>
            <canvas id="chartReportsPerDay" height="120"></canvas>
        </div>

        <div class="card">
            <h3>Status Laporan</h3>
            <canvas id="chartStatus" height="160"></canvas>
        </div>

    </div>

    {{-- ================= CHARTS ROW 2 ================= --}}
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:25px; margin-bottom:25px;">

        <div class="card">
            <h3>Laporan per Dinas</h3>
            <canvas id="chartPerDepartment" height="160"></canvas>
        </div>

        <div class="card">
            <h3>Top Kategori</h3>
            <canvas id="chartTopCategories" height="160"></canvas>
        </div>

    </div>

    {{-- ================= ROW 3: TABEL + PRIORITAS ================= --}}
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:25px; margin-bottom:25px;">

        {{-- LAPORAN TERBARU --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="margin:0;">Laporan Terbaru</h3>
                <a href="{{ route('admin.reports.index') }}"
                   style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">
                    Lihat semua →
                </a>
            </div>

            @if($latestReports->isEmpty())
                <p style="color:#777;">Belum ada laporan masuk.</p>
            @else
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; font-size:0.9em;">
                        <thead>
                            <tr style="text-align:left; border-bottom:2px solid var(--background-light);">
                                <th style="padding:10px 8px;">Foto</th>
                                <th style="padding:10px 8px;">Judul</th>
                                <th style="padding:10px 8px;">Dinas</th>
                                <th style="padding:10px 8px;">Status</th>
                                <th style="padding:10px 8px;">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestReports as $report)
                                @php
                                    $statusColor = match($report->status) {
                                        'active'  => '#f59e0b',
                                        'process'  => '#3b82f6',
                                        'done'     => '#10b981',
                                        'rejected' => '#ef4444',
                                        default    => '#777',
                                    };
                                    $firstImage = $report->images->first();
                                @endphp
                                <tr style="border-bottom:1px solid var(--background-light);">
                                    <td style="padding:10px 8px;">
                                        @if($firstImage)
                                            <img src="{{ asset('storage/' . $firstImage->image_url) }}"
                                                 style="width:48px; height:48px; object-fit:cover; border-radius:6px;">
                                        @else
                                            <div style="width:48px; height:48px; border-radius:6px; background:var(--background-light); display:flex; align-items:center; justify-content:center; color:#aaa;">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding:10px 8px;">
                                        <a href="{{ route('admin.reports.show', $report->id) }}"
                                           style="color:var(--text-dark); text-decoration:none; font-weight:500;">
                                            {{ $report->title }}
                                        </a>
                                        <div style="font-size:0.8em; color:#999;">{{ $report->address }}</div>
                                    </td>
                                    <td style="padding:10px 8px; font-size:0.85em;">
                                        {{ $report->department->name ?? '-' }}
                                    </td>
                                    <td style="padding:10px 8px;">
                                        <span style="background:{{ $statusColor }}1A; color:{{ $statusColor }}; padding:4px 10px; border-radius:20px; font-size:0.8em; font-weight:600; text-transform:capitalize;">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td style="padding:10px 8px; color:#777; font-size:0.85em;">
                                        {{ $report->created_at->format('d M Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- LAPORAN PRIORITAS (TOP VOTES) --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
                <h3 style="margin:0;">Laporan Prioritas</h3>
                <a href="{{ route('admin.stats.top-votes') }}"
                   style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">
                    Lihat semua →
                </a>
            </div>
            <p style="font-size:0.8em; color:#999; margin-bottom:15px;">Diurutkan berdasarkan jumlah vote</p>

            @if($priorityReports->isEmpty())
                <p style="color:#777;">Belum ada laporan.</p>
            @else
                <ul style="list-style:none; padding:0; margin:0;">
                    @foreach($priorityReports as $report)
                        <li style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid var(--background-light);">
                            <div style="flex:1; min-width:0; padding-right:10px;">
                                <a href="{{ route('admin.reports.show', $report->id) }}"
                                   style="color:var(--text-dark); text-decoration:none; font-weight:500; font-size:0.9em;">
                                    {{ $report->title }}
                                </a>
                                <div style="font-size:0.8em; color:#999;">
                                    {{ $report->department->name ?? '-' }}
                                </div>
                            </div>
                            <span style="background:var(--primary-color); color:#fff; padding:4px 10px; border-radius:20px; font-size:0.8em; font-weight:600; white-space:nowrap; flex-shrink:0;">
                                <i class="fas fa-arrow-up"></i> {{ $report->votes_count }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

    {{-- ================= ROW 4: PERFORMA DINAS + USER BARU ================= --}}
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:25px; margin-bottom:25px;">

        {{-- PERFORMA DINAS --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="margin:0;">Performa Dinas</h3>
                <a href="{{ route('admin.stats.departments') }}"
                   style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">
                    Detail →
                </a>
            </div>
            @if($departmentStats->isEmpty())
                <p style="color:#777;">Belum ada data.</p>
            @else
                <table style="width:100%; border-collapse:collapse; font-size:0.88em;">
                    <thead>
                        <tr style="text-align:left; border-bottom:2px solid var(--background-light);">
                            <th style="padding:8px 6px;">Dinas</th>
                            <th style="padding:8px 6px; text-align:center;">Total</th>
                            <th style="padding:8px 6px; text-align:center;">Selesai</th>
                            <th style="padding:8px 6px; text-align:center;">% Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($departmentStats as $dept)
                            @php
                                $pct = $dept->total_reports > 0
                                    ? round(($dept->done_reports / $dept->total_reports) * 100)
                                    : 0;
                                $barColor = $pct >= 75 ? '#10b981' : ($pct >= 40 ? '#f59e0b' : '#ef4444');
                            @endphp
                            <tr style="border-bottom:1px solid var(--background-light);">
                                <td style="padding:10px 6px;">
                                    <div style="font-weight:500;">{{ $dept->code }}</div>
                                    <div style="font-size:0.8em; color:#999;">{{ $dept->name }}</div>
                                </td>
                                <td style="padding:10px 6px; text-align:center;">{{ $dept->total_reports }}</td>
                                <td style="padding:10px 6px; text-align:center; color:#10b981; font-weight:600;">{{ $dept->done_reports }}</td>
                                <td style="padding:10px 6px;">
                                    <div style="display:flex; align-items:center; gap:6px;">
                                        <div style="flex:1; background:var(--background-light); border-radius:4px; height:6px;">
                                            <div style="width:{{ $pct }}%; background:{{ $barColor }}; height:6px; border-radius:4px;"></div>
                                        </div>
                                        <span style="font-size:0.8em; color:{{ $barColor }}; font-weight:600; min-width:32px; text-align:right;">{{ $pct }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- PENGGUNA TERBARU --}}
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
                <h3 style="margin:0;">Pengguna Terbaru</h3>
                <a href="{{ route('admin.users.index') }}"
                   style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">
                    Lihat semua →
                </a>
            </div>
            @if($latestUsers->isEmpty())
                <p style="color:#777;">Belum ada pengguna.</p>
            @else
                <ul style="list-style:none; padding:0; margin:0;">
                    @foreach($latestUsers as $user)
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
                        @endphp
                        <li style="display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid var(--background-light);">
                            @php
                                $avatarPath = $user->avatar;

                                if ($avatarPath && !str_contains($avatarPath, 'http')) {
                                    // kalau dia sudah ada 'storage/' jangan ditambah lagi
                                    $avatarUrl = str_contains($avatarPath, 'storage/')
                                        ? asset($avatarPath)
                                        : asset('storage/' . $avatarPath);
                                } else {
                                    $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=133a68&color=fff';
                                }
                            @endphp

                            <img src="{{ $avatarUrl }}"
                                style="width:38px; height:38px; border-radius:50%; object-fit:cover; flex-shrink:0;">
                            <div style="flex:1; min-width:0;">
                                <a href="{{ route('admin.users.show', $user->id) }}"
                                   style="color:var(--text-dark); text-decoration:none; font-weight:500; font-size:0.9em;">
                                    {{ $user->name }}
                                </a>
                                <div style="font-size:0.8em; color:#999; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px; flex-shrink:0;">
                                <span style="background:{{ $roleColor }}1A; color:{{ $roleColor }}; padding:2px 8px; border-radius:20px; font-size:0.75em; font-weight:600; text-transform:capitalize;">
                                    {{ $user->role }}
                                </span>
                                <span style="background:{{ $statusColor }}1A; color:{{ $statusColor }}; padding:2px 8px; border-radius:20px; font-size:0.75em; font-weight:600; text-transform:capitalize;">
                                    {{ $user->status }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

    {{-- ================= ROW 5: AUDIT LOG TERBARU ================= --}}
    <div class="card" style="margin-bottom:25px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h3 style="margin:0;">Aktivitas Terbaru</h3>
            <a href="{{ route('admin.audit-logs.index') }}"
               style="font-size:0.85em; color:var(--primary-color); text-decoration:none;">
                Lihat semua →
            </a>
        </div>

        @if($recentActivities->isEmpty())
            <p style="color:#777;">Belum ada aktivitas.</p>
        @else
            <table style="width:100%; border-collapse:collapse; font-size:0.88em;">
                <thead>
                    <tr style="text-align:left; border-bottom:2px solid var(--background-light);">
                        <th style="padding:10px 8px;">Laporan</th>
                        <th style="padding:10px 8px;">Status Baru</th>
                        <th style="padding:10px 8px;">Catatan</th>
                        <th style="padding:10px 8px;">Diperbarui Oleh</th>
                        <th style="padding:10px 8px;">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentActivities as $activity)
                        @php
                            $statusColor = match($activity->status) {
                                'active'  => '#f59e0b',
                                'process'  => '#3b82f6',
                                'done'     => '#10b981',
                                'rejected' => '#ef4444',
                                default    => '#777',
                            };
                        @endphp
                        <tr style="border-bottom:1px solid var(--background-light);">
                            <td style="padding:10px 8px;">
                                <a href="{{ route('admin.reports.show', $activity->report_id) }}"
                                   style="color:var(--text-dark); text-decoration:none; font-weight:500;">
                                    {{ $activity->report->title ?? 'Laporan #' . $activity->report_id }}
                                </a>
                            </td>
                            <td style="padding:10px 8px;">
                                <span style="background:{{ $statusColor }}1A; color:{{ $statusColor }}; padding:4px 10px; border-radius:20px; font-size:0.8em; font-weight:600; text-transform:capitalize;">
                                    {{ $activity->status }}
                                </span>
                            </td>
                            <td style="padding:10px 8px; color:#777; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                {{ $activity->note ?? '-' }}
                            </td>
                            <td style="padding:10px 8px;">
                                {{ $activity->updatedBy->name ?? '-' }}
                            </td>
                            <td style="padding:10px 8px; color:#999; white-space:nowrap;">
                                {{ $activity->created_at->diffForHumans() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        const chartReportsRaw     = @json($chartReports);
        const topCategoriesRaw    = @json($topCategories);
        const departmentChartRaw  = @json($departmentChartData);
        const statusChartRaw      = @json($statusChartData);

        // ===== LINE CHART: LAPORAN PER HARI =====
        new Chart(document.getElementById('chartReportsPerDay'), {
            type: 'line',
            data: {
                labels: chartReportsRaw.map(item => {
                    const d = new Date(item.date);
                    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                }),
                datasets: [{
                    label: 'Laporan Masuk',
                    data: chartReportsRaw.map(item => item.total),
                    borderColor: '#AA0E0E',
                    backgroundColor: 'rgba(170, 14, 14, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 3,
                    pointBackgroundColor: '#AA0E0E',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        // ===== DOUGHNUT CHART: STATUS =====
        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Diproses', 'Selesai', 'Ditolak'],
                datasets: [{
                    data: [
                        statusChartRaw.active,
                        statusChartRaw.process,
                        statusChartRaw.done,
                        statusChartRaw.rejected,
                    ],
                    backgroundColor: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    }
                }
            }
        });

        // ===== BAR CHART: LAPORAN PER DINAS =====
        new Chart(document.getElementById('chartPerDepartment'), {
            type: 'bar',
            data: {
                labels: departmentChartRaw.map(d => d.code),
                datasets: [{
                    label: 'Total Laporan',
                    data: departmentChartRaw.map(d => d.total),
                    backgroundColor: '#AA0E0E',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });

        // ===== DOUGHNUT CHART: TOP KATEGORI =====
        new Chart(document.getElementById('chartTopCategories'), {
            type: 'doughnut',
            data: {
                labels: topCategoriesRaw.map(item => item.name),
                datasets: [{
                    data: topCategoriesRaw.map(item => item.reports_count),
                    backgroundColor: ['#AA0E0E', '#f59e0b', '#3b82f6', '#10b981', '#8b5cf6'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11 } }
                    }
                }
            }
        });
    </script>
@endsection