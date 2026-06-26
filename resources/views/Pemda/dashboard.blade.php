@extends('Layouts.PemdaLayout.base_layout')

@section('title', 'Dashboard Pemda')

@section('content')

    <h1>Dashboard</h1>

    {{-- ================= STAT CARDS ================= --}}
    <div class="dashboard-cards" style="margin-bottom: 30px;">

        <div class="card" style="border-left: 5px solid var(--primary-color);">
            <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Total Laporan</h3>
            <p style="font-size:2em; font-weight:700; color:var(--text-dark);">{{ $totalReports }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #f59e0b;">
            <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Aktif</h3>
            <p style="font-size:2em; font-weight:700; color:#f59e0b;">{{ $active }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #3b82f6;">
            <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Diproses</h3>
            <p style="font-size:2em; font-weight:700; color:#3b82f6;">{{ $process }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #10b981;">
            <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Selesai</h3>
            <p style="font-size:2em; font-weight:700; color:#10b981;">{{ $done }}</p>
        </div>

        <div class="card" style="border-left: 5px solid #ef4444;">
            <h3 style="font-size:0.95em; color:#777; text-transform:uppercase;">Ditolak</h3>
            <p style="font-size:2em; font-weight:700; color:#ef4444;">{{ $rejected }}</p>
        </div>

    </div>

    {{-- ================= CHARTS ================= --}}
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:25px; margin-bottom:30px;">

        <div class="card">
            <h3>Tren Laporan Masuk</h3>
            <canvas id="chartReportsPerDay" height="120"></canvas>
        </div>

        <div class="card">
            <h3>Top Kategori</h3>
            <canvas id="chartTopCategories" height="160"></canvas>
        </div>

    </div>

    {{-- ================= LATEST + PRIORITY ================= --}}
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:25px;">

        {{-- LAPORAN TERBARU --}}
        <div class="card">
            <h3>Laporan Terbaru</h3>

            @if($latestReports->isEmpty())
                <p style="color:#777;">Belum ada laporan masuk.</p>
            @else
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; font-size:0.9em;">
                        <thead>
                            <tr style="text-align:left; border-bottom:2px solid var(--background-light);">
                                <th style="padding:10px 8px;">Foto</th>
                                <th style="padding:10px 8px;">Judul</th>
                                <th style="padding:10px 8px;">Kategori</th>
                                <th style="padding:10px 8px;">Status</th>
                                <th style="padding:10px 8px;">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestReports as $report)
                                @php
                                    $statusColor = match($report->status) {
                                        'active' => '#f59e0b',
                                        'process' => '#3b82f6',
                                        'done' => '#10b981',
                                        'rejected' => '#ef4444',
                                        default => '#777',
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
                                        <a href="{{ route('pemda.reports.show', $report->id) }}" style="color:var(--text-dark); text-decoration:none; font-weight:500;">
                                            {{ $report->title }}
                                        </a>
                                        <div style="font-size:0.8em; color:#999;">{{ $report->address }}</div>
                                    </td>
                                    <td style="padding:10px 8px;">{{ $report->category->name ?? '-' }}</td>
                                    <td style="padding:10px 8px;">
                                        <span style="background:{{ $statusColor }}1A; color:{{ $statusColor }}; padding:4px 10px; border-radius:20px; font-size:0.8em; font-weight:600; text-transform:capitalize;">
                                            {{ $report->status }}
                                        </span>
                                    </td>
                                    <td style="padding:10px 8px; color:#777;">{{ $report->created_at->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- LAPORAN PRIORITAS --}}
        <div class="card">
            <h3>Laporan Prioritas</h3>
            <p style="font-size:0.8em; color:#999; margin-top:-10px; margin-bottom:15px;">Diurutkan berdasarkan jumlah vote</p>

            @if($priorityReports->isEmpty())
                <p style="color:#777;">Belum ada laporan.</p>
            @else
                <ul style="list-style:none;">
                    @foreach($priorityReports as $report)
                        <li style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid var(--background-light);">
                            <div>
                                <a href="{{ route('pemda.reports.show', $report->id) }}" style="color:var(--text-dark); text-decoration:none; font-weight:500; font-size:0.9em;">
                                    {{ $report->title }}
                                </a>
                                <div style="font-size:0.8em; color:#999;">{{ $report->category->name ?? '-' }}</div>
                            </div>
                            <span style="background:var(--primary-color); color:#fff; padding:4px 10px; border-radius:20px; font-size:0.8em; font-weight:600; white-space:nowrap;">
                                <i class="fas fa-arrow-up"></i> {{ $report->votes_count }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        // ===== DATA DARI CONTROLLER =====
        const chartReportsRaw = @json($chartReports);
        const topCategoriesRaw = @json($topCategories);

        // ===== LINE CHART: LAPORAN PER HARI =====
        const reportLabels = chartReportsRaw.map(item => {
            const d = new Date(item.date);
            return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
        });
        const reportTotals = chartReportsRaw.map(item => item.total);

        new Chart(document.getElementById('chartReportsPerDay'), {
            type: 'line',
            data: {
                labels: reportLabels,
                datasets: [{
                    label: 'Laporan Masuk',
                    data: reportTotals,
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
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });

        // ===== PIE CHART: TOP KATEGORI =====
        const categoryLabels = topCategoriesRaw.map(item => item.name);
        const categoryCounts = topCategoriesRaw.map(item => item.reports_count);
        const categoryColors = ['#AA0E0E', '#f59e0b', '#3b82f6', '#10b981', '#8b5cf6'];

        new Chart(document.getElementById('chartTopCategories'), {
            type: 'doughnut',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryCounts,
                    backgroundColor: categoryColors,
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