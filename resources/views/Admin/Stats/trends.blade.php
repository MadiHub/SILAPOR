@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Statistik Tren')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <h1 style="margin:0;">Tren & Pola Waktu</h1>
    <form method="GET" action="{{ route('admin.stats.trends') }}" style="display:flex; gap:8px;">
        <select name="period" onchange="this.form.submit()"
                style="padding:8px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.88em;">
            <option value="30"  {{ $period=='30'  ? 'selected':'' }}>30 Hari</option>
            <option value="90"  {{ $period=='90'  ? 'selected':'' }}>90 Hari</option>
            <option value="180" {{ $period=='180' ? 'selected':'' }}>6 Bulan</option>
            <option value="365" {{ $period=='365' ? 'selected':'' }}>1 Tahun</option>
        </select>
        <select name="group" onchange="this.form.submit()"
                style="padding:8px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.88em;">
            <option value="day"   {{ $groupBy=='day'   ? 'selected':'' }}>Per Hari</option>
            <option value="week"  {{ $groupBy=='week'  ? 'selected':'' }}>Per Minggu</option>
            <option value="month" {{ $groupBy=='month' ? 'selected':'' }}>Per Bulan</option>
        </select>
    </form>
</div>

@include('Admin.Stats._tabs', ['active' => 'trends'])

{{-- CHART 1: Laporan Masuk vs Selesai --}}
<div class="card" style="margin-bottom:20px;">
    <h3 style="margin:0 0 4px;">Laporan Masuk vs Diselesaikan</h3>
    <p style="font-size:0.82em; color:#aaa; margin:0 0 16px;">Perbandingan laporan baru yang masuk dengan laporan yang berhasil diselesaikan per periode.</p>
    <canvas id="chartInVsDone" height="90"></canvas>
</div>

{{-- CHART 2: Pengguna Baru + Vote --}}
<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
    <div class="card">
        <h3 style="margin:0 0 4px;">Pengguna Baru</h3>
        <p style="font-size:0.82em; color:#aaa; margin:0 0 16px;">Jumlah pengguna mendaftar per periode.</p>
        <canvas id="chartUsers" height="130"></canvas>
    </div>
    <div class="card">
        <h3 style="margin:0 0 4px;">Aktivitas Vote</h3>
        <p style="font-size:0.82em; color:#aaa; margin:0 0 16px;">Jumlah vote yang diberikan per periode.</p>
        <canvas id="chartVotes" height="130"></canvas>
    </div>
</div>

{{-- CHART 3: Distribusi Waktu --}}
<div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; margin-bottom:20px;">

    {{-- HOURLY HEATMAP (bar) --}}
    <div class="card">
        <h3 style="margin:0 0 4px;">Distribusi per Jam</h3>
        <p style="font-size:0.82em; color:#aaa; margin:0 0 16px;">Jam berapa warga paling banyak membuat laporan.</p>
        <canvas id="chartHourly" height="110"></canvas>
    </div>

    {{-- WEEKDAY --}}
    <div class="card">
        <h3 style="margin:0 0 4px;">Distribusi per Hari</h3>
        <p style="font-size:0.82em; color:#aaa; margin:0 0 16px;">Hari dalam seminggu paling aktif.</p>
        <canvas id="chartWeekday" height="190"></canvas>
    </div>

</div>

{{-- SUMMARY TABLE --}}
<div class="card">
    <h3 style="margin:0 0 16px;">Ringkasan Data Tren</h3>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:0.88em;">
            <thead>
                <tr style="border-bottom:2px solid var(--background-light); text-align:left;">
                    <th style="padding:10px 10px;">Periode</th>
                    <th style="padding:10px 10px; text-align:center;">Laporan Masuk</th>
                    <th style="padding:10px 10px; text-align:center;">Diselesaikan</th>
                    <th style="padding:10px 10px; text-align:center;">Pengguna Baru</th>
                    <th style="padding:10px 10px; text-align:center;">Vote</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $inMap   = $incomingTrend->pluck('total', 'label');
                    $doneMap = $doneTrend->pluck('total', 'label');
                    $userMap = $userTrend->pluck('total', 'label');
                    $voteMap = $voteTrend->pluck('total', 'label');
                    $allKeys = $incomingTrend->pluck('label')->toArray();
                @endphp
                @forelse(array_reverse($allKeys) as $key)
                    <tr style="border-bottom:1px solid var(--background-light);">
                        <td style="padding:10px 10px; font-weight:500;">{{ $key }}</td>
                        <td style="padding:10px 10px; text-align:center; font-weight:700; color:var(--primary-color);">{{ $inMap[$key] ?? 0 }}</td>
                        <td style="padding:10px 10px; text-align:center; color:#10b981; font-weight:700;">{{ $doneMap[$key] ?? 0 }}</td>
                        <td style="padding:10px 10px; text-align:center; color:#8b5cf6;">{{ $userMap[$key] ?? 0 }}</td>
                        <td style="padding:10px 10px; text-align:center; color:#f59e0b;">{{ $voteMap[$key] ?? 0 }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center; padding:30px; color:#aaa;">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const incomingRaw = @json($incomingTrend);
    const doneRaw     = @json($doneTrend);
    const userRaw     = @json($userTrend);
    const voteRaw     = @json($voteTrend);
    const hourlyRaw   = @json($hourly);
    const weekdayRaw  = @json($weekday);

    const sharedLabels = incomingRaw.map(d => d.label);

    // Map done/user/vote ke label yang sama
    const doneMap = Object.fromEntries(doneRaw.map(d => [d.label, d.total]));
    const userMap = Object.fromEntries(userRaw.map(d => [d.label, d.total]));
    const voteMap = Object.fromEntries(voteRaw.map(d => [d.label, d.total]));

    // Laporan Masuk vs Selesai
    new Chart(document.getElementById('chartInVsDone'), {
        type: 'line',
        data: {
            labels: sharedLabels,
            datasets: [
                {
                    label: 'Laporan Masuk',
                    data: incomingRaw.map(d => d.total),
                    borderColor: '#AA0E0E',
                    backgroundColor: 'rgba(170,14,14,0.06)',
                    fill: true, tension: 0.3, pointRadius: 3,
                },
                {
                    label: 'Diselesaikan',
                    data: sharedLabels.map(l => doneMap[l] ?? 0),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16,185,129,0.06)',
                    fill: true, tension: 0.3, pointRadius: 3,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // Pengguna Baru
    new Chart(document.getElementById('chartUsers'), {
        type: 'bar',
        data: {
            labels: sharedLabels,
            datasets: [{
                label: 'Pengguna Baru',
                data: sharedLabels.map(l => userMap[l] ?? 0),
                backgroundColor: '#8b5cf6',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // Vote
    new Chart(document.getElementById('chartVotes'), {
        type: 'bar',
        data: {
            labels: sharedLabels,
            datasets: [{
                label: 'Vote',
                data: sharedLabels.map(l => voteMap[l] ?? 0),
                backgroundColor: '#f59e0b',
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // Distribusi per Jam
    new Chart(document.getElementById('chartHourly'), {
        type: 'bar',
        data: {
            labels: hourlyRaw.map(h => h.label),
            datasets: [{
                label: 'Laporan',
                data: hourlyRaw.map(h => h.total),
                backgroundColor: hourlyRaw.map(h => {
                    const max = Math.max(...hourlyRaw.map(x => x.total));
                    const intensity = max > 0 ? h.total / max : 0;
                    return `rgba(170,14,14,${0.15 + intensity * 0.85})`;
                }),
                borderRadius: 3,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false },
                tooltip: { callbacks: { label: ctx => ctx.parsed.y + ' laporan' } }
            },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });

    // Distribusi per Hari
    new Chart(document.getElementById('chartWeekday'), {
        type: 'bar',
        data: {
            labels: weekdayRaw.map(d => d.label),
            datasets: [{
                label: 'Laporan',
                data: weekdayRaw.map(d => d.total),
                backgroundColor: '#3b82f6',
                borderRadius: 4,
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
</script>
@endsection