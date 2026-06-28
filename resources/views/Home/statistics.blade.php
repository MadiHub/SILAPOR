@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Statistik')

@section('content')

<style>
.stat-summary-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}
@media (min-width: 768px) {
    .stat-summary-grid {
        grid-template-columns: repeat(5, 1fr);
    }
}

.stat-summary-card {
    background-color: var(--text-white);
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.stat-summary-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.06);
}

.stat-summary-card .stat-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 14px;
    font-size: 15px;
}

.stat-summary-card .stat-number {
    font-size: 24px;
    font-weight: 700;
    color: var(--primary-blue);
    line-height: 1;
}

.stat-summary-card .stat-label {
    font-size: 12.5px;
    color: #718096;
    margin-top: 4px;
}

.icon-total   { background-color: rgba(47,128,237,0.1);  color: var(--sys-blue); }
.icon-aktif   { background-color: rgba(235,87,87,0.1);   color: var(--sys-red); }
.icon-proses  { background-color: rgba(242,153,74,0.1);  color: var(--sys-orange); }
.icon-selesai { background-color: rgba(39,174,96,0.1);   color: var(--sys-green); }
.icon-warga   { background-color: rgba(11,34,64,0.08);   color: var(--primary-blue); }

/* Card pembungkus chart, senada dengan .map-content-container */
.stat-chart-card {
    background-color: var(--text-white);
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 28px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.03);
}

.stat-chart-card h3 {
    color: var(--primary-blue);
    font-size: 15px;
    font-weight: 700;
    margin-bottom: 4px;
}

.stat-chart-card p.chart-sub {
    color: #718096;
    font-size: 12.5px;
    margin-bottom: 18px;
}

.stat-grid-2col {
    display: grid;
    grid-template-columns: 1fr;
    gap: 24px;
}
@media (min-width: 1024px) {
    .stat-grid-2col {
        grid-template-columns: 1.4fr 1fr;
    }
}

/* Leaderboard wilayah */
.leaderboard-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 13px 0;
    border-bottom: 1px solid #f0f4f8;
}
.leaderboard-item:last-child { border-bottom: none; }

.leaderboard-rank {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background-color: #eef2f7;
    color: #718096;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.leaderboard-rank.rank-1 {
    background-color: var(--brand-orange);
    color: var(--text-white);
}

.leaderboard-info {
    flex: 1;
    min-width: 0;
}
.leaderboard-name {
    color: #2d3748;
    font-size: 13.5px;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.leaderboard-bar-track {
    height: 5px;
    background-color: #eef2f7;
    border-radius: 4px;
    margin-top: 6px;
    overflow: hidden;
}
.leaderboard-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--brand-orange), #ff944d);
    border-radius: 4px;
}
.leaderboard-count {
    color: var(--primary-blue);
    font-size: 14px;
    font-weight: 700;
    flex-shrink: 0;
}

/* Daftar kategori */
.category-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 0;
    border-bottom: 1px solid #f0f4f8;
}
.category-row:last-child { border-bottom: none; }
.category-row i {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background-color: rgba(255,118,27,0.1);
    color: var(--brand-orange);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    flex-shrink: 0;
}
.category-row .cat-name {
    flex: 1;
    color: #2d3748;
    font-size: 13.5px;
    font-weight: 500;
}
.category-row .cat-count {
    color: var(--primary-blue);
    font-weight: 700;
    font-size: 13px;
}

.empty-note {
    color: #a0aec0;
    font-size: 13px;
    text-align: center;
    padding: 24px 0;
}
</style>

<section class="popular-reports-section" id="statistics-section">
    <div class="section-header row-header">
        <div>
            <span class="sub-title">STATISTIK</span>
            <h2 class="main-title">Data &amp; Tren Pelaporan Warga</h2>
            <p class="section-desc">Gambaran menyeluruh aktivitas pelaporan infrastruktur dan bencana di Kota Bekasi.</p>
        </div>
    </div>

    <!-- RINGKASAN ANGKA -->
    <div class="stat-summary-grid">
        <div class="stat-summary-card">
            <div class="stat-icon icon-total"><i class="fa-solid fa-file-lines"></i></div>
            <div class="stat-number">{{ $totalLaporan }}</div>
            <div class="stat-label">Total Laporan</div>
        </div>
        <div class="stat-summary-card">
            <div class="stat-icon icon-aktif"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="stat-number">{{ $totalAktif }}</div>
            <div class="stat-label">Aktif</div>
        </div>
        <div class="stat-summary-card">
            <div class="stat-icon icon-proses"><i class="fa-solid fa-spinner"></i></div>
            <div class="stat-number">{{ $totalProses }}</div>
            <div class="stat-label">Diproses</div>
        </div>
        <div class="stat-summary-card">
            <div class="stat-icon icon-selesai"><i class="fa-solid fa-circle-check"></i></div>
            <div class="stat-number">{{ $totalSelesai }}</div>
            <div class="stat-label">Terselesaikan</div>
        </div>
        <div class="stat-summary-card">
            <div class="stat-icon icon-warga"><i class="fa-solid fa-users"></i></div>
            <div class="stat-number">{{ $totalWarga }}</div>
            <div class="stat-label">Total Warga</div>
        </div>
    </div>

    <!-- GRAFIK TREN -->
    <div class="stat-chart-card">
        <h3>Tren Laporan 6 Bulan Terakhir</h3>
        <p class="chart-sub">Jumlah laporan yang masuk setiap bulan</p>
        <canvas id="trendChart" height="90"></canvas>
    </div>

    <div class="stat-grid-2col">
        <!-- KATEGORI -->
        <div class="stat-chart-card">
            <h3>Kategori Masalah Paling Sering Dilaporkan</h3>
            <p class="chart-sub">Distribusi laporan berdasarkan jenis masalah</p>
            <canvas id="kategoriChart" height="200"></canvas>

            <div style="margin-top:22px;">
                @forelse ($kategoriStats as $kat)
                    <div class="category-row">
                        <i class="{{ $kat->icon }}"></i>
                        <span class="cat-name">{{ $kat->name }}</span>
                        <span class="cat-count">{{ $kat->reports_count }}</span>
                    </div>
                @empty
                    <p class="empty-note">Belum ada data kategori.</p>
                @endforelse
            </div>
        </div>

        <!-- STATUS + WILAYAH -->
        <div>
            <div class="stat-chart-card">
                <h3>Perbandingan Status Laporan</h3>
                <p class="chart-sub">Aktif vs Diproses vs Selesai vs Ditolak</p>
                <canvas id="statusChart" height="220"></canvas>
            </div>

            <div class="stat-chart-card">
                <h3>Wilayah Paling Banyak Melapor</h3>
                <p class="chart-sub">5 wilayah dengan jumlah laporan terbanyak</p>

                @if (count($wilayahTop) > 0)
                    @php $maxWilayah = max($wilayahTop); @endphp
                    @foreach ($wilayahTop as $nama => $jumlah)
                        <div class="leaderboard-item">
                            <div class="leaderboard-rank {{ $loop->first ? 'rank-1' : '' }}">{{ $loop->iteration }}</div>
                            <div class="leaderboard-info">
                                <div class="leaderboard-name">{{ $nama }}</div>
                                <div class="leaderboard-bar-track">
                                    <div class="leaderboard-bar-fill" style="width: {{ $maxWilayah > 0 ? ($jumlah / $maxWilayah * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="leaderboard-count">{{ $jumlah }}</div>
                        </div>
                    @endforeach
                @else
                    <p class="empty-note">Belum ada data wilayah yang cukup.</p>
                @endif
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>

/* Warna diambil dari variabel CSS yang sudah didefinisikan di style.css */
const rootStyles = getComputedStyle(document.documentElement);
const cBrandOrange = rootStyles.getPropertyValue('--brand-orange').trim() || '#ff761b';
const cSysRed    = rootStyles.getPropertyValue('--sys-red').trim()    || '#eb5757';
const cSysOrange = rootStyles.getPropertyValue('--sys-orange').trim() || '#f2994a';
const cSysGreen  = rootStyles.getPropertyValue('--sys-green').trim()  || '#27ae60';
const cSysGray   = rootStyles.getPropertyValue('--sys-gray').trim()   || '#4f4f4f';
const cAccentBlue = rootStyles.getPropertyValue('--accent-blue').trim() || '#133a68';

Chart.defaults.color = '#718096';
Chart.defaults.font.family = "'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, sans-serif";

/* ============================
   TREN 6 BULAN
============================ */
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: @json($trendLabels),
        datasets: [{
            label: 'Laporan',
            data: @json($trendData),
            borderColor: cBrandOrange,
            backgroundColor: 'rgba(255,118,27,0.08)',
            fill: true,
            tension: 0.35,
            pointBackgroundColor: cBrandOrange,
            pointRadius: 4,
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f0f4f8' } }
        }
    }
});

/* ============================
   KATEGORI (Bar)
============================ */
new Chart(document.getElementById('kategoriChart'), {
    type: 'bar',
    data: {
        labels: @json($kategoriLabels),
        datasets: [{
            label: 'Jumlah Laporan',
            data: @json($kategoriData),
            backgroundColor: cBrandOrange,
            borderRadius: 6,
            maxBarThickness: 36,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f0f4f8' } }
        }
    }
});

/* ============================
   STATUS (Donut)
============================ */
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Aktif', 'Diproses', 'Selesai', 'Ditolak'],
        datasets: [{
            data: [
                {{ $statusBreakdown['active'] }},
                {{ $statusBreakdown['process'] }},
                {{ $statusBreakdown['done'] }},
                {{ $statusBreakdown['rejected'] }}
            ],
            backgroundColor: [cSysRed, cSysOrange, cSysGreen, cSysGray],
            borderWidth: 0,
        }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 10, boxHeight: 10, padding: 16, color: '#4a5568' }
            }
        }
    }
});

</script>
@endsection