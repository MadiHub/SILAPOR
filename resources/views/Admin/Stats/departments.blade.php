@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Statistik Performa Dinas')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="margin:0;">Performa Dinas</h1>
    <form method="GET" action="{{ route('admin.stats.departments') }}" style="display:flex; gap:8px;">
        <select name="period" onchange="this.form.submit()"
                style="padding:8px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.88em;">
            <option value="30"  {{ $period=='30'  ? 'selected':'' }}>30 Hari</option>
            <option value="90"  {{ $period=='90'  ? 'selected':'' }}>90 Hari</option>
            <option value="180" {{ $period=='180' ? 'selected':'' }}>6 Bulan</option>
            <option value="365" {{ $period=='365' ? 'selected':'' }}>1 Tahun</option>
        </select>
    </form>
</div>

@include('Admin.Stats._tabs', ['active' => 'departments'])

{{-- CHART: Laporan per Dinas --}}
<div style="display:grid; grid-template-columns:3fr 2fr; gap:20px; margin-bottom:20px;">
    <div class="card">
        <h3 style="margin:0 0 4px;">Total Laporan per Dinas</h3>
        <p style="font-size:0.82em; color:#aaa; margin:0 0 16px;">Perbandingan volume laporan antar dinas.</p>
        <canvas id="chartDeptBar" height="120"></canvas>
    </div>
    <div class="card">
        <h3 style="margin:0 0 4px;">% Penyelesaian per Dinas</h3>
        <p style="font-size:0.82em; color:#aaa; margin:0 0 16px;">Dinas dengan tingkat penyelesaian tertinggi.</p>
        <canvas id="chartDeptCompletion" height="120"></canvas>
    </div>
</div>

{{-- TABLE PERFORMA LENGKAP --}}
<div class="card" style="margin-bottom:20px;">
    <h3 style="margin:0 0 16px;">Detail Performa Semua Dinas</h3>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:0.87em;">
            <thead>
                <tr style="border-bottom:2px solid var(--background-light); text-align:left; background:var(--background-light);">
                    <th style="padding:12px 10px;">Dinas</th>
                    <th style="padding:12px 10px; text-align:center;">Total</th>
                    <th style="padding:12px 10px; text-align:center; color:#f59e0b;">Aktif</th>
                    <th style="padding:12px 10px; text-align:center; color:#3b82f6;">Diproses</th>
                    <th style="padding:12px 10px; text-align:center; color:#10b981;">Selesai</th>
                    <th style="padding:12px 10px; text-align:center; color:#ef4444;">Ditolak</th>
                    <th style="padding:12px 10px; text-align:center;">Staf</th>
                    <th style="padding:12px 10px; text-align:center;">Kategori</th>
                    <th style="padding:12px 10px; text-align:center;">Rata-rata Selesai</th>
                    <th style="padding:12px 10px; min-width:160px;">% Penyelesaian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $dept)
                    @php
                        $pct      = $dept->completion_rate;
                        $barColor = $pct >= 75 ? '#10b981' : ($pct >= 40 ? '#f59e0b' : '#ef4444');
                        $avgDays  = $dept->avg_days ? round($dept->avg_days, 1) . ' hari' : '—';
                    @endphp
                    <tr style="border-bottom:1px solid var(--background-light);">
                        <td style="padding:12px 10px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="background:var(--primary-color); color:#fff; padding:2px 8px; border-radius:4px; font-size:0.78em; font-weight:700; white-space:nowrap;">
                                    {{ $dept->code }}
                                </span>
                                <a href="{{ route('admin.departments.show', $dept->id) }}"
                                   style="color:var(--text-dark); text-decoration:none; font-weight:500;">
                                    {{ $dept->name }}
                                </a>
                            </div>
                        </td>
                        <td style="padding:12px 10px; text-align:center; font-weight:700; font-size:1em;">{{ $dept->reports_count }}</td>
                        <td style="padding:12px 10px; text-align:center; color:#f59e0b; font-weight:600;">{{ $dept->active_count }}</td>
                        <td style="padding:12px 10px; text-align:center; color:#3b82f6; font-weight:600;">{{ $dept->process_count }}</td>
                        <td style="padding:12px 10px; text-align:center; color:#10b981; font-weight:700;">{{ $dept->done_count }}</td>
                        <td style="padding:12px 10px; text-align:center; color:#ef4444; font-weight:600;">{{ $dept->rejected_count }}</td>
                        <td style="padding:12px 10px; text-align:center; color:#8b5cf6;">{{ $dept->users_count }}</td>
                        <td style="padding:12px 10px; text-align:center; color:#f59e0b;">{{ $dept->categories_count }}</td>
                        <td style="padding:12px 10px; text-align:center; color:#aaa; font-size:0.85em;">{{ $avgDays }}</td>
                        <td style="padding:12px 10px;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <div style="flex:1; background:var(--background-light); border-radius:4px; height:8px;">
                                    <div style="width:{{ $pct }}%; background:{{ $barColor }}; height:8px; border-radius:4px; transition:width 0.4s;"></div>
                                </div>
                                <span style="font-size:0.82em; color:{{ $barColor }}; font-weight:700; min-width:38px; text-align:right;">{{ $pct }}%</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" style="text-align:center; padding:30px; color:#aaa;">Belum ada data dinas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- CARD GRID: DINAS HIGHLIGHT --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px;">

    {{-- Best performer --}}
    @php $best = $departments->sortByDesc('completion_rate')->first(); @endphp
    <div class="card" style="border-top:4px solid #10b981; text-align:center;">
        <p style="font-size:0.75em; color:#aaa; text-transform:uppercase; font-weight:600; margin:0 0 8px;">🏆 Penyelesaian Terbaik</p>
        @if($best && $best->reports_count > 0)
            <span style="background:var(--primary-color); color:#fff; padding:4px 12px; border-radius:4px; font-weight:700; font-size:0.85em;">{{ $best->code }}</span>
            <p style="font-weight:600; margin:8px 0 4px; font-size:0.9em;">{{ $best->name }}</p>
            <p style="font-size:1.8em; font-weight:800; color:#10b981; margin:0;">{{ $best->completion_rate }}%</p>
        @else
            <p style="color:#aaa;">—</p>
        @endif
    </div>

    {{-- Highest volume --}}
    @php $busiest = $departments->sortByDesc('reports_count')->first(); @endphp
    <div class="card" style="border-top:4px solid #3b82f6; text-align:center;">
        <p style="font-size:0.75em; color:#aaa; text-transform:uppercase; font-weight:600; margin:0 0 8px;">📋 Volume Terbanyak</p>
        @if($busiest)
            <span style="background:var(--primary-color); color:#fff; padding:4px 12px; border-radius:4px; font-weight:700; font-size:0.85em;">{{ $busiest->code }}</span>
            <p style="font-weight:600; margin:8px 0 4px; font-size:0.9em;">{{ $busiest->name }}</p>
            <p style="font-size:1.8em; font-weight:800; color:#3b82f6; margin:0;">{{ $busiest->reports_count }} laporan</p>
        @else
            <p style="color:#aaa;">—</p>
        @endif
    </div>

    {{-- Fastest resolution --}}
    @php $fastest = $departments->filter(fn($d) => $d->avg_days > 0)->sortBy('avg_days')->first(); @endphp
    <div class="card" style="border-top:4px solid #8b5cf6; text-align:center;">
        <p style="font-size:0.75em; color:#aaa; text-transform:uppercase; font-weight:600; margin:0 0 8px;">⚡ Penanganan Tercepat</p>
        @if($fastest)
            <span style="background:var(--primary-color); color:#fff; padding:4px 12px; border-radius:4px; font-weight:700; font-size:0.85em;">{{ $fastest->code }}</span>
            <p style="font-weight:600; margin:8px 0 4px; font-size:0.9em;">{{ $fastest->name }}</p>
            <p style="font-size:1.8em; font-weight:800; color:#8b5cf6; margin:0;">{{ round($fastest->avg_days, 1) }} hari</p>
        @else
            <p style="color:#aaa;">—</p>
        @endif
    </div>

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    @php
        $deptsChart = $departments->values()->map(function ($d) {
            return [
                'name'       => $d->name,
                'code'       => $d->code,
                'total'      => $d->reports_count,
                'done'       => $d->done_count,
                'completion' => $d->completion_rate,
            ];
        })->values()->all();
    @endphp

    const depts = @json($deptsChart);

    const colors = ['#AA0E0E','#f59e0b','#3b82f6','#10b981','#8b5cf6','#06b6d4','#f97316','#ec4899','#6366f1','#14b8a6'];

    // Bar – Total laporan
    new Chart(document.getElementById('chartDeptBar'), {
        type: 'bar',
        data: {
            labels: depts.map(d => d.code),
            datasets: [
                {
                    label: 'Total',
                    data: depts.map(d => d.total),
                    backgroundColor: '#AA0E0E',
                    borderRadius: 4,
                },
                {
                    label: 'Selesai',
                    data: depts.map(d => d.done),
                    backgroundColor: '#10b981',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // Horizontal bar – Completion rate
    new Chart(document.getElementById('chartDeptCompletion'), {
        type: 'bar',
        data: {
            labels: depts.map(d => d.code),
            datasets: [{
                label: '% Selesai',
                data: depts.map(d => d.completion),
                backgroundColor: depts.map(d =>
                    d.completion >= 75 ? '#10b981' : d.completion >= 40 ? '#f59e0b' : '#ef4444'
                ),
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } } }
        }
    });
</script>
@endsection