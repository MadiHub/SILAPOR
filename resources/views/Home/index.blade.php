@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Beranda')

@section('content')
<style type="text/tailwindcss">
    /* ===== CARA LAPOR ===== */
    .how-section {
        background: #fff;
        padding: 80px 60px;
    }

    .how-section .section-header {
        text-align: center;
        margin-bottom: 56px;
    }

    .steps-container {
        position: relative;
        max-width: 960px;
        margin: 0 auto;
    }

    .steps-connector {
        position: absolute;
        top: 28px;
        left: calc(12.5% + 28px);
        right: calc(12.5% + 28px);
        height: 2px;
        background: #e2e8f0;
        z-index: 0;
    }

    .steps-connector-fill {
        height: 100%;
        width: 75%;
        background: var(--brand-orange);
    }

    .steps-wrapper {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        position: relative;
        z-index: 1;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 0 20px;
    }

    .step-num {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #94a3b8;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        flex-shrink: 0;
        transition: background 0.2s;
    }

    .step-num.active {
        background: var(--primary-blue);
        color: #fff;
    }

    .step-badge {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1px;
        color: #94a3b8;
        background: #f5f8fc;
        border: 1px solid #e2e8f0;
        padding: 2px 8px;
        border-radius: 20px;
        margin-bottom: 12px;
    }

    .step-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 12px;
        background: #fff3ec;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 14px;
        font-size: 20px;
        color: var(--brand-orange);
    }

    .step-item h3 {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 8px;
    }

    .step-item p {
        font-size: 13px;
        color: #64748b;
        line-height: 1.7;
    }

    .how-cta {
        text-align: center;
        margin-top: 52px;
    }

    /* ===== FAQ ===== */
    .faq-section {
        background: var(--bg-light);
        padding: 80px 60px;
    }

    .faq-section .section-header {
        text-align: center;
        margin-bottom: 48px;
    }

    .faq-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        max-width: 960px;
        margin: 0 auto;
    }

    .faq-item {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        transition: border-color 0.2s;
    }

    .faq-item.active {
        border-color: var(--brand-orange);
    }

    .faq-q {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 20px;
        cursor: pointer;
        gap: 12px;
    }

    .faq-q span {
        font-size: 14px;
        font-weight: 600;
        color: var(--primary-blue);
        line-height: 1.4;
    }

    .faq-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f5f8fc;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 12px;
        color: var(--primary-blue);
        transition: transform 0.25s, background 0.2s, border-color 0.2s, color 0.2s;
    }

    .faq-icon.open {
        transform: rotate(45deg);
        background: #fff3ec;
        border-color: var(--brand-orange);
        color: var(--brand-orange);
    }

    .faq-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 0 20px;
    }

    .faq-a {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        font-size: 13.5px;
        color: #64748b;
        line-height: 1.7;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }

    .faq-a.show {
        max-height: 200px;
        padding: 14px 20px 18px;
    }

    .faq-contact-bar {
        max-width: 960px;
        margin: 32px auto 0;
        background: var(--primary-blue);
        border-radius: 12px;
        padding: 28px 36px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
    }

    .faq-contact-bar h3 {
        font-size: 17px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
    }

    .faq-contact-bar p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.65);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .steps-wrapper { grid-template-columns: repeat(2, 1fr); gap: 32px; }
        .steps-connector { display: none; }
        .faq-grid { grid-template-columns: 1fr; }
        .faq-contact-bar { flex-direction: column; text-align: center; }
        .how-section, .faq-section { padding: 56px 24px; }
    }
</style>
<section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="status-pill"><span class="indicator"></span> Sistem Aktif - Bekasi</div>
                <h2 class="hero-title">Laporkan Masalah<br><span class="highlight">Infrastruktur & Bencana</span> Kota
                </h2>
                <p class="hero-desc">Platform digital terintegrasi untuk warga melaporkan kerusakan jalan, jembatan,
                    saluran air, banjir, dan bencana ringan langsung ke instansi terkait secara real-time.</p>
                <div class="hero-cta-group">
                    <a href="{{ route('reports.create') }}" class="btn-cta-primary">Buat Laporan Sekarang <i
                            class="fa-solid fa-arrow-right"></i></a>
                    <a href="#map-section" class="btn-cta-secondary"><i class="fa-solid fa-map-location-dot"></i> Lihat
                        Peta Insiden</a>
                </div>
            </div>

            <div class="hero-grid-cards">
                @foreach($problem_categories as $category)
                    @php
                        $cardColor = 'orange-card';
                        $suffix = 'laporan';
                        $hasPill = '';

                        if (str_contains($category->icon, 'cloud-showers-heavy')) {
                            $cardColor = 'blue-card';
                        } elseif (str_contains($category->icon, 'lightbulb')) {
                            $cardColor = 'yellow-card';
                        } elseif (str_contains($category->icon, 'bridge')) {
                            $cardColor = 'gray-card';
                        } elseif (str_contains($category->icon, 'trash')) {
                            $cardColor = 'green-card';
                        } elseif (str_contains($category->icon, 'house-chimney-crack') || str_contains($category->icon, 'mountain')) {
                            $cardColor = 'red-card';
                        }
                    @endphp

                    <div class="stat-card {{ $cardColor }} {{ $hasPill }}">
                        <div class="card-icon">
                            <i class="{{ $category->icon }}"></i>
                        </div>
                        
                        <h3>{{ $category->name }}</h3>
                        
                        <p class="count">
                            {{ $category->reports_count }} <span>{{ $suffix }}</span>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="summary-stats-bar">
            <div class="stat-box">
                <div class="sb-icon"><i class="fa-regular fa-file-lines"></i></div>
                <div class="sb-text">
                    <h3>{{ number_format($totalReports) }}</h3>
                    <p>Total Laporan</p>
                </div>
            </div>

            <div class="stat-box">
                <div class="sb-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="sb-text">
                    <h3>{{ number_format($processReports) }}</h3>
                    <p>Diproses</p>
                </div>
            </div>

            <div class="stat-box">
                <div class="sb-icon"><i class="fa-regular fa-circle-check"></i></div>
                <div class="sb-text">
                    <h3>{{ number_format($doneReports) }}</h3>
                    <p>Terselesaikan</p>
                </div>
            </div>

            <div class="stat-box">
                <div class="sb-icon"><i class="fa-solid fa-users"></i></div>
                <div class="sb-text">
                    <h3>{{ number_format($totalUsers) }}</h3>
                    <p>Total Warga</p>
                </div>
            </div>
        </div>
    </section>

    <section class="map-section-wrapper" id="map-section">
        <div class="section-header">
            <span class="sub-title">PETA INSIDEN REAL-TIME</span>
            <h2 class="main-title">Pantau Kondisi Bekasi</h2>
        </div>

        <div class="map-filters">
            <div class="filter-buttons">
                <button class="btn-filter active" data-category="semua">
                    <i class="fa-solid fa-book-open"></i> Semua
                </button>

                @foreach ($problem_categories as $category)
                    <button class="btn-filter" data-category="{{ $category->name }}">
                        <i class="{{ $category->icon }}"></i>
                        {{ $category->name }}
                        ({{ $category->reports_count }})
                    </button>
                @endforeach
            </div>
            <div class="status-legend">
                <span class="legend-item"><span class="dot red"></span> Aktif</span>
                <span class="legend-item"><span class="dot orange"></span> Proses</span>
                <span class="legend-item"><span class="dot green"></span> Selesai</span>
                <button class="btn-refresh" id="refreshMap"><i class="fa-solid fa-rotate-right"></i></button>
            </div>
        </div>

        <div class="map-content-container">
            <div id="map-container" class="map-canvas"></div>
            <div class="incident-panel">
                <div class="panel-header">
                    <h3>Semua Insiden <span id="incident-count">(5 titik)</span></h3>
                </div>
                <div class="incident-list" id="incidentListContainer">
                </div>
            </div>
        </div>
    </section>

    <section class="popular-reports-section" id="laporan-terpopuler">
        <div class="section-header row-header">
            <div>
                <span class="sub-title">LAPORAN TERPOPULER</span>
                <h2 class="main-title">Masalah yang Paling Banyak Disuarakan</h2>
                <p class="section-desc">Dukung laporan warga lain dengan memberikan suara untuk mempercepat penanganan.
                </p>
            </div>
            <span class="refresh-indicator"><i class="fa-solid fa-wave-square"></i></span>
        </div>

        <div class="feed-tabs">
            <button class="tab-btn active" data-status="all">Semua</button>
            <button class="tab-btn" data-status="active">Aktif</button>
            <button class="tab-btn" data-status="process">Diproses</button>
            <button class="tab-btn" data-status="done">Selesai</button>
        </div>

        <div class="reports-grid" id="reportsGridContainer">
        </div>

        <div class="center-action">
            <a href="{{ route('reports.index') }}" class="btn-load-more">Lihat Semua Laporan <i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </section>

    {{-- ===================================================== --}}
{{-- SECTION: CARA MEMBUAT LAPORAN                        --}}
{{-- ===================================================== --}}
<section class="how-section">
    <div class="section-header">
        <span class="sub-title">PANDUAN PENGGUNAAN</span>
        <h2 class="main-title">Cara Membuat Laporan</h2>
        <p class="section-desc">Ikuti 4 langkah mudah untuk melaporkan masalah infrastruktur di sekitar Anda.</p>
    </div>

    <div class="steps-container">
        <div class="steps-connector"><div class="steps-connector-fill"></div></div>

        <div class="steps-wrapper">

            <div class="step-item">
                <div class="step-num active">1</div>
                <span class="step-badge">MULAI</span>
                <div class="step-icon-wrap">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h3>Buat Akun / Masuk</h3>
                <p>Daftar menggunakan email atau masuk jika sudah punya akun. Proses registrasi kurang dari 1 menit.</p>
            </div>

            <div class="step-item">
                <div class="step-num active">2</div>
                    <span class="step-badge">ISI DATA</span>
                <div class="step-icon-wrap">
                    <i class="fa-solid fa-map-location-dot"></i>
                </div>
                  <h3>Pilih Kategori & Cek Lokasi</h3>
                    <p>Pilih kategori masalah yang sesuai, kemudian pastikan lokasi Anda terdeteksi dengan GPS aktif untuk validasi wilayah.</p>
            </div>

            <div class="step-item">
                <div class="step-num active">3</div>
                <span class="step-badge">UNGGAH</span>
                <div class="step-icon-wrap">
                    <i class="fa-solid fa-camera"></i>
                </div>
                <h3>Tambah Foto & Deskripsi</h3>
                <p>Unggah foto kondisi lapangan dan tulis deskripsi singkat agar petugas dapat menindaklanjuti dengan tepat.</p>
            </div>

            <div class="step-item">
                <div class="step-num">4</div>
                <span class="step-badge">SELESAI</span>
                <div class="step-icon-wrap">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h3>Kirim & Pantau Status</h3>
                <p>Kirim laporan dan pantau perkembangannya secara real-time. Anda akan mendapat notifikasi setiap ada pembaruan.</p>
            </div>

        </div>
    </div>
    </section>

    {{-- ===================================================== --}}
    {{-- SECTION: FAQ                                         --}}
    {{-- ===================================================== --}}
    <section class="faq-section">
        <div class="section-header">
            <span class="sub-title">PERTANYAAN UMUM</span>
            <h2 class="main-title">Ada yang Ingin Ditanyakan?</h2>
            <p class="section-desc">Temukan jawaban atas pertanyaan yang paling sering diajukan warga.</p>
        </div>

        <div class="faq-grid">

            <div class="faq-item active">
                <div class="faq-q">
                    <span>Apakah perlu mendaftar untuk membuat laporan?</span>
                    <div class="faq-icon open"><i class="fa-solid fa-plus"></i></div>
                </div>
                <div class="faq-divider"></div>
                <div class="faq-a show">
                    Ya, pendaftaran diperlukan agar laporan dapat ditindaklanjuti dan Anda dapat memantau
                    status laporan secara real-time. Proses daftar hanya membutuhkan email dan password.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-q">
                    <span>Berapa lama laporan akan ditindaklanjuti?</span>
                    <div class="faq-icon"><i class="fa-solid fa-plus"></i></div>
                </div>
                <div class="faq-divider"></div>
                <div class="faq-a">
                    Waktu respons bervariasi tergantung kategori. Laporan darurat seperti bencana
                    ditargetkan respons dalam 1×24 jam, sedangkan masalah infrastruktur umum dalam 3–7 hari kerja.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-q">
                    <span>Apakah saya bisa melaporkan masalah di luar Bekasi?</span>
                    <div class="faq-icon"><i class="fa-solid fa-plus"></i></div>
                </div>
                <div class="faq-divider"></div>
                <div class="faq-a">
                    Saat ini platform ini hanya melayani wilayah Kota dan Kabupaten Bekasi.
                    Laporan di luar wilayah tersebut tidak dapat diproses oleh instansi terkait.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-q">
                    <span>Bagaimana cara mendukung laporan warga lain?</span>
                    <div class="faq-icon"><i class="fa-solid fa-plus"></i></div>
                </div>
                <div class="faq-divider"></div>
                <div class="faq-a">
                    Anda dapat memberikan vote pada laporan yang dianggap penting. Semakin banyak
                    vote, laporan akan mendapat prioritas lebih tinggi untuk ditangani oleh instansi terkait.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-q">
                    <span>Foto apa saja yang perlu dilampirkan?</span>
                    <div class="faq-icon"><i class="fa-solid fa-plus"></i></div>
                </div>
                <div class="faq-divider"></div>
                <div class="faq-a">
                    Lampirkan foto yang jelas menunjukkan kondisi masalah dari beberapa sudut.
                    Format yang didukung: JPG, PNG, dan WEBP dengan ukuran maksimal 5MB per foto.
                    Minimal 1 foto wajib dilampirkan.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-q">
                    <span>Apakah identitas pelapor dijaga kerahasiaannya?</span>
                    <div class="faq-icon"><i class="fa-solid fa-plus"></i></div>
                </div>
                <div class="faq-divider"></div>
                <div class="faq-a">
                    Ya. Identitas pelapor hanya diketahui oleh admin dan instansi terkait.
                    Data pribadi Anda tidak akan ditampilkan secara publik di halaman laporan manapun.
                </div>
            </div>

        </div>

        <div class="faq-contact-bar">
            <div>
                <h3>Masih punya pertanyaan lain?</h3>
                <p>Tim kami siap membantu Anda setiap hari kerja pukul 08.00–16.00 WIB.</p>
            </div>
            <a href="mailto:pengaduan@bekasi.go.id" class="btn-cta-secondary">
                <i class="fa-solid fa-envelope"></i> Hubungi Kami
            </a>
        </div>
    </section>

    <div class="modal-overlay" id="reportModal">
        <div class="modal-box">
            <button class="modal-close-btn" id="closeModal">&times;</button>
            <div class="modal-body" id="modalTargetBody">
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        const reportsFromBackend = @json($reports);
        // console.log(reportsFromBackend);
    </script>

    <script>

    // ============================================================
    //  UTILITY FUNCTIONS
    // ============================================================

    function escapeHTML(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function mapStatusToLabel(status) {
        const map = {
            active:   'Aktif',
            process:  'Diproses',
            done:     'Selesai',
            rejected: 'Ditolak',
        };
        return map[status] ?? 'Unknown';
    }

    function getStatusConfig(status) {
        const config = {
            active:   { label: 'Aktif',    class: 'bg-red-500/10 text-red-400' },
            process:  { label: 'Diproses', class: 'bg-orange-500/10 text-orange-400' },
            done:     { label: 'Selesai',  class: 'bg-green-500/10 text-green-400' },
            rejected: { label: 'Ditolak',  class: 'bg-gray-500/10 text-gray-400' },
        };
        return config[status] ?? { label: 'Unknown', class: 'bg-gray-500/10 text-gray-400' };
    }

    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now  = new Date();
        const diff = Math.floor((now - date) / 1000); // selisih dalam detik

        const menit = Math.floor(diff / 60);
        const jam   = Math.floor(diff / 3600);
        const hari  = Math.floor(diff / 86400);

        if (diff < 60)   return 'Baru saja';
        if (menit < 60)  return `${menit} menit lalu`;
        if (jam < 24)    return `${jam} jam lalu`;
        if (hari < 7)    return `${hari} hari lalu`;

        return date.toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric'
        });
    }


    // ============================================================
    //  DATA MAPPING
    // ============================================================

    const datasetInsiden = reportsFromBackend.map(item => ({
        id        : item.id,
        kategori  : item.category?.name  ?? 'umum',
        icon      : item.category?.icon  ?? 'umum',
        judul     : item.title,
        deskripsi : item.description,
        lokasi    : item.address         ?? 'Lokasi tidak diketahui',
        koordinat : [
            parseFloat(item.latitude)  || 0,
            parseFloat(item.longitude) || 0,
        ],
        status      : item.status,
        votes_count : item.votes_count   ?? 0,
        is_voted    : item.is_voted      ?? false,
        komentar    : item.comments_count ?? 0,
        updates     : item.updates       ?? [],
        waktu       : formatTimeAgo(item.created_at),
        gambar      : item.images?.map(img => `/storage/${img.image_url}`) ?? [],
        thumbnail   : item.images?.[0]?.image_url
            ? `/storage/${item.images[0].image_url}`
            : 'https://via.placeholder.com/600x400',
    }));


    // ============================================================
    //  PETA (LEAFLET)
    // ============================================================

    let map;
    let markerGroup;

    const defaultLocation = [-6.2349, 106.9896];

    // Koordinat default: Alun-Alun Kota Bogor
    // const defaultLocation = [-6.5950, 106.8166];
    
    function initMapEngine() {
        map = L.map('map-container', {
            center          : defaultLocation,
            zoom            : 12,
            scrollWheelZoom : false,
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        markerGroup = L.layerGroup().addTo(map);
    }

    function getMarkerColor(status) {
        const colors = {
            active  : '#eb5757',
            process : '#f2994a',
            done    : '#27ae60',
        };
        return colors[status] ?? '#999999';
    }

    function renderMapMarkers(kategoriFilter = 'semua') {
        markerGroup.clearLayers();

        const filtered = datasetInsiden.filter(item =>
            kategoriFilter === 'semua' || item.kategori === kategoriFilter
        );

        filtered.forEach(item => {
            const color = getMarkerColor(item.status);

            const customIcon = L.divIcon({
                html      : `<div style="background:${color}; width:14px; height:14px; border-radius:50%; border:2px solid white; box-shadow:0 0 6px rgba(0,0,0,0.4);"></div>`,
                className : 'custom-map-pin',
                iconSize  : [14, 14],
                iconAnchor: [7, 7],
            });

            const marker = L.marker(item.koordinat, { icon: customIcon }).addTo(markerGroup);

            marker.bindPopup(`
                <div style="font-family:'Segoe UI',sans-serif; padding:2px;">
                    <h4 style="margin:0 0 4px; color:#0b2240; font-size:13px;">
                        ${escapeHTML(item.judul)}
                    </h4>
                    <p style="margin:0 0 6px; font-size:11px; color:#666;">
                        ${escapeHTML(item.lokasi)}
                    </p>
                    <button
                        onclick="bukaModalDetailLaporan(${item.id})"
                        style="background:#133a68; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:10px; cursor:pointer; width:100%;">
                        Lihat Detail
                    </button>
                </div>
            `);
        });
    }

    function renderSideIncidentPanel(kategoriFilter = 'semua') {
        const container  = document.getElementById('incidentListContainer');
        const countLabel = document.getElementById('incident-count');
        container.innerHTML = '';

        const filtered = datasetInsiden.filter(item =>
            kategoriFilter === 'semua' || item.kategori === kategoriFilter
        );

        countLabel.textContent = `(${filtered.length} titik)`;

        if (filtered.length === 0) {
            container.innerHTML = `
                <p style="padding:20px; color:#888; text-align:center; font-size:13px;">
                    Tidak ada laporan untuk kategori ini.
                </p>`;
            return;
        }

        filtered.forEach(item => {
            const status = getStatusConfig(item.status);
            const div    = document.createElement('div');
            div.className = 'incident-item';
            div.dataset.id = item.id;

            div.innerHTML = `
                <div class="incident-item-title">${escapeHTML(item.judul)}</div>
                <div class="incident-item-meta">
                    <i class="fa-solid fa-location-dot"></i> ${escapeHTML(item.lokasi)}
                </div>
                <div class="incident-status-row">
                    <span class="status-badge px-2 py-1 text-xs rounded-full ${status.class}">
                        ${status.label.toUpperCase()}
                    </span>
                    <span class="incident-time">${escapeHTML(item.waktu)}</span>
                </div>
            `;

            div.addEventListener('click', () => {
                document.querySelectorAll('.incident-item')
                    .forEach(el => el.classList.remove('selected'));
                div.classList.add('selected');

                map.setView(item.koordinat, 14, { animate: true, duration: 0.6 });

                markerGroup.eachLayer(layer => {
                    const latlng = layer.getLatLng();
                    if (latlng.lat === item.koordinat[0] && latlng.lng === item.koordinat[1]) {
                        layer.openPopup();
                    }
                });
            });

            container.appendChild(div);
        });
    }


    // ============================================================
    //  GRID LAPORAN TERPOPULER
    // ============================================================

    function renderPopularReportsGrid() {
        const container = document.getElementById('reportsGridContainer');
        container.innerHTML = '';

        datasetInsiden.forEach(item => {
            const card = document.createElement('div');
            card.className   = 'report-card';
            card.dataset.status = item.status; 

            card.innerHTML = `
                <div class="card-img-wrapper">
                    <img src="${item.thumbnail}" alt="Gambar Insiden" class="card-img-placeholder">
                    <div class="card-badges">
                        <span class="badge-status-dot status-${item.status}">
                            ${mapStatusToLabel(item.status)}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card-category">${escapeHTML(item.kategori)}</div>
                    <div class="card-title">${escapeHTML(item.judul)}</div>
                    <p class="card-text">${escapeHTML(item.deskripsi)}</p>
                    <div class="card-location">
                        <i class="fa-solid fa-location-dot"></i> ${escapeHTML(item.lokasi)}
                    </div>

                    <div class="card-footer-metrics" data-id="${item.id}">
                        <div class="metrics-left-group">
                            <!--
                                PENTING: Tidak ada onclick di sini.
                                Vote ditangani oleh delegate listener (document.addEventListener)
                                agar tidak double-trigger.
                            -->
                            <button
                                class="metric-item-btn btn-upvote ${item.is_voted ? 'upvoted' : ''}"
                                data-id="${item.id}">
                                <i class="fas fa-arrow-up"></i>
                                <span class="vote-count">${item.votes_count}</span>
                            </button>
                        </div>
                        <span class="metric-time">${escapeHTML(item.waktu)}</span>
                    </div>
                </div>
            `;

            card.addEventListener('click', (e) => {
                if (e.target.closest('.btn-upvote')) return;
                bukaModalDetailLaporan(item.id);
            });

            container.appendChild(card);
        });
    }


    // ============================================================
    //  VOTE SYSTEM
    // ============================================================

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-upvote, .btn-lapor');
        if (!btn) return;

        const id = parseInt(btn.dataset.id, 10);
        if (!id) return;

        voteReport(id, btn);
    });

    async function voteReport(id, btn) {
        if (btn.classList.contains('loading')) return;
        btn.classList.add('loading');

        try {
            const res = await fetch(`/reports/${id}/vote`, {
                method : 'POST',
                headers: {
                    'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type' : 'application/json',
                    'Accept'       : 'application/json',
                },
            });

            if (res.status === 401) {
                const data = await res.json();
                modalOverlay.classList.remove('open');
                Swal.fire({
                    icon             : 'warning',
                    title            : 'Oops...',
                    text             : data.message || 'Harus login dulu!',
                    confirmButtonText: 'Login',
                }).then(() => {
                    window.location.href = '/auth';
                });
                return;
            }

            const data = await res.json();

            const item = datasetInsiden.find(x => x.id === id);
            if (item) {
                item.votes_count = data.votes;
                item.is_voted    = data.status === 'voted';
            }

            refreshVoteUI(id);

        } catch (err) {
            Swal.fire({
                icon : 'error',
                title: 'Error',
                text : 'Terjadi kesalahan, coba lagi!',
            });
        } finally {
            btn.classList.remove('loading');
        }
    }

    function refreshVoteUI(id) {
        const item = datasetInsiden.find(x => x.id === id);
        if (!item) return;

        document.querySelectorAll(`.card-footer-metrics[data-id="${id}"] .btn-upvote`)
            .forEach(btn => {
                btn.classList.toggle('upvoted', item.is_voted);
                btn.querySelector('.vote-count').textContent = item.votes_count;
            });

        const modalBtn = document.querySelector(`#reportModal .btn-lapor[data-id="${id}"]`);
        if (modalBtn) {
            modalBtn.classList.toggle('not-voted', !item.is_voted);
            modalBtn.querySelector('.vote-count').textContent = item.votes_count;
        }
    }


    // ============================================================
    //  MODAL DETAIL LAPORAN
    // ============================================================

    const modalOverlay  = document.getElementById('reportModal');
    const closeModalBtn = document.getElementById('closeModal');
    const modalBody     = document.getElementById('modalTargetBody');

    let swiperInstance = null;

    function bukaModalDetailLaporan(id) {
        const item = datasetInsiden.find(obj => obj.id === id);
        if (!item) return;

        const status = getStatusConfig(item.status);
        const images = item.gambar.length
            ? item.gambar
            : ['https://via.placeholder.com/600x400'];

        modalBody.innerHTML = `
            <div class="modal-scroll">
                <div style="max-height:80vh; overflow-y:auto; padding-right:4px;">

                    <!-- Galeri gambar dengan Swiper -->
                    <div class="swiper mySwiper rounded-xl overflow-hidden mb-4">
                        <div class="swiper-wrapper">
                            ${images.map(img => `
                                <div class="swiper-slide">
                                    <img src="${img}" class="modal-img w-full h-[260px] object-cover">
                                </div>
                            `).join('')}
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>

                    <!-- Judul & badge status -->
                    <div style="display:flex; justify-content:space-between; align-items:start; gap:10px;">
                        <h3 class="modal-title" style="margin:0;">
                            ${escapeHTML(item.judul)}
                        </h3>
                        <span class="status-badge px-3 py-1 text-xs rounded-full whitespace-nowrap ${status.class}">
                            ${status.label}
                        </span>
                    </div>

                    <!-- Meta info: kategori, lokasi, waktu -->
                    <div style="margin-top:10px; display:flex; flex-direction:column; gap:6px; font-size:13px; color:#aaa;">
                        <span><i class="${item.icon}"></i> ${escapeHTML(item.kategori.toUpperCase())}</span>
                        <span><i class="fa-solid fa-location-dot"></i> ${escapeHTML(item.lokasi)}</span>
                        <span><i class="fa-solid fa-clock"></i> ${escapeHTML(item.waktu)}</span>
                    </div>

                    <!-- Deskripsi laporan -->
                    <p style="margin-top:15px; line-height:1.6;">
                        ${escapeHTML(item.deskripsi)}
                    </p>

                    <!-- Riwayat progress (maks. 3 update terbaru) -->
                    ${item.updates.length > 0 ? `
                        <div style="margin-top:20px;">
                            <h4 style="font-size:14px; margin-bottom:10px;">Riwayat Progress</h4>
                            <div style="position:relative; padding-left:18px;">
                                <!-- Garis vertikal timeline -->
                                <div style="position:absolute; left:6px; top:0; bottom:0; width:2px; background:#eee;"></div>

                                ${item.updates.slice(0, 3).map(update => `
                                    <div style="position:relative; margin-bottom:15px;">
                                        <!-- Titik timeline -->
                                        <div style="position:absolute; left:-12px; top:4px; width:8px; height:8px;
                                            background:#3b82f6; border-radius:50%;"></div>

                                        <div style="background:#f9fafb; padding:10px; border-radius:6px;">
                                            <div style="font-size:11px; color:#999; margin-bottom:3px;">
                                                ${formatTimeAgo(update.created_at)}
                                            </div>
                                            <div style="font-size:13px; color:#444;">
                                                ${escapeHTML(update.note)}
                                            </div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    ` : `
                        <p style="margin-top:20px; font-size:12px; color:#aaa;">
                            Belum ada progress dari pihak terkait.
                        </p>
                    `}

                    <!-- Tombol aksi: dukung & tutup -->
                    <div style="margin-top:25px; display:flex; gap:10px; flex-wrap:wrap;">
                        
                        <button
                            class="btn-lapor ${item.is_voted ? '' : 'not-voted'}"
                            data-id="${item.id}">
                            <i class="fas fa-arrow-up"></i>
                            Dukung (<span class="vote-count">${item.votes_count}</span>)
                        </button>

                        <a 
                            href="https://www.google.com/maps?q=${item.koordinat[0]},${item.koordinat[1]}"
                            target="_blank"
                            class="btn-load-more"
                            style="margin-top:0; text-decoration:none;">
                            <i class="fas fa-map-marker-alt"></i>
                            Buka di Maps
                        </a>

                        <button
                            class="btn-load-more"
                            style="margin-top:0;"
                            onclick="document.getElementById('reportModal').classList.remove('open')">
                            Tutup
                        </button>
                    </div>

                </div>
            </div>
        `;

        modalOverlay.classList.add('open');

        if (swiperInstance) {
            swiperInstance.destroy(true, true);
            swiperInstance = null;
        }

        swiperInstance = new Swiper('.mySwiper', {
            loop       : true,
            spaceBetween: 10,
            pagination : { el: '.swiper-pagination', clickable: true },
            autoplay   : { delay: 3000, disableOnInteraction: false },
        });
    }


    // ============================================================
    //  FILTER PETA
    // ============================================================

    function setupFilterListeners() {
        const filterButtons = document.querySelectorAll('.btn-filter');

        filterButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                filterButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const category = this.dataset.category;
                renderMapMarkers(category);
                renderSideIncidentPanel(category);
            });
        });

        document.getElementById('refreshMap').addEventListener('click', function () {
            this.style.transform = 'rotate(360deg)';
            setTimeout(() => this.style.transform = 'none', 500);

            filterButtons.forEach(b => b.classList.remove('active'));
            document.querySelector('[data-category="semua"]').classList.add('active');

            renderMapMarkers('semua');
            renderSideIncidentPanel('semua');
            map.setView(defaultLocation, 12, { animate: true });
        });
    }


    // ============================================================
    //  FILTER TAB LAPORAN TERPOPULER
    // ============================================================

    function setupPopularFilters() {
        const tabButtons = document.querySelectorAll('.feed-tabs .tab-btn');
        if (tabButtons.length === 0) return;

        tabButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                tabButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                const targetStatus = this.dataset.status;

                document.querySelectorAll('#reportsGridContainer .report-card')
                    .forEach(card => {
                        const match = targetStatus === 'all' || card.dataset.status === targetStatus;
                        card.style.display = match ? '' : 'none';
                    });
            });
        });
    }


    // ============================================================
    //  BOOTSTRAP — jalankan semua fungsi init saat DOM siap
    // ============================================================

    document.addEventListener('DOMContentLoaded', () => {
        initMapEngine();
        renderMapMarkers('semua');
        renderSideIncidentPanel('semua');
        renderPopularReportsGrid();
        setupFilterListeners();
        setupPopularFilters();
    });

    // ============================================================
    //  MODAL CLOSE HANDLER
    // ============================================================

    closeModalBtn.addEventListener('click', () => {
        modalOverlay.classList.remove('open');
    });

    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.classList.remove('open');
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            modalOverlay.classList.remove('open');
        }
    });
    </script>
    <script>
        document.querySelectorAll('.faq-q').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = btn.closest('.faq-item');
                const icon = btn.querySelector('.faq-icon');
                const ans  = item.querySelector('.faq-a');
                const isOpen = ans.classList.contains('show');

                // tutup semua
                document.querySelectorAll('.faq-item').forEach(i => {
                    i.classList.remove('active');
                    i.querySelector('.faq-icon').classList.remove('open');
                    i.querySelector('.faq-a').classList.remove('show');
                });

                // buka yang diklik (kalau belum terbuka)
                if (!isOpen) {
                    item.classList.add('active');
                    icon.classList.add('open');
                    ans.classList.add('show');
                }
            });
        });
    </script>
@endsection