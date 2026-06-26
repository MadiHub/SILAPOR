@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Beranda')

@section('content')
<section class="hero-section">
        <div class="hero-container">
            <div class="hero-content">
                <div class="status-pill"><span class="indicator"></span> Sistem Aktif &mdash; Bekasi</div>
                <h2 class="hero-title">Laporkan Masalah<br><span class="highlight">Infrastruktur & Bencana</span> Kota
                </h2>
                <p class="hero-desc">Platform digital terintegrasi untuk warga melaporkan kerusakan jalan, jembatan,
                    saluran air, banjir, dan bencana ringan langsung ke instansi terkait secara real-time.</p>
                <div class="hero-cta-group">
                    <a href="{{ route('reports.index') }}" class="btn-cta-primary">Buat Laporan Sekarang <i
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
            <button class="btn-load-more">Lihat Semua Laporan <i class="fa-solid fa-chevron-right"></i></button>
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
        console.log(reportsFromBackend);
    </script>

    <script>

        function mapStatusToUI(status) {
            if (status === 'active') return 'aktif';     
            if (status === 'process') return 'proses';
            if (status === 'done') return 'selesai';
            if (status === 'rejected') return 'ditolak';
        }

        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();

            const diff = Math.floor((now - date) / 1000); // detik

            const menit = Math.floor(diff / 60);
            const jam = Math.floor(diff / 3600);
            const hari = Math.floor(diff / 86400);

            if (diff < 60) return 'Baru saja';
            if (menit < 60) return `${menit} menit lalu`;
            if (jam < 24) return `${jam} jam lalu`;
            if (hari < 7) return `${hari} hari lalu`;

            return date.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        // --- 1. Mock Data Source - Dataset Utama Aplikasi SiLapor ---
        const datasetInsiden = reportsFromBackend.map(item => ({
            id: item.id,
            kategori: item.category?.name ?? 'umum',
            icon: item.category?.icon ?? 'umum',
            judul: item.title,
            deskripsi: item.description,
            lokasi: item.address ?? 'Lokasi tidak diketahui',
            koordinat: [
                parseFloat(item.latitude) || 0,
                parseFloat(item.longitude) || 0
            ],
            status: item.status,
            votes_count: item.votes_count ?? 0,
            is_voted: item.is_voted ?? false,
            komentar: item.comments_count ?? 0,
            updates: item.updates ?? [],
            dilihat: 0,
            waktu: formatTimeAgo(item.created_at),
            gambar: item.images?.map(img => `/storage/${img.image_url}`) ?? [],
            thumbnail: item.images?.[0]?.image_url
                ? `/storage/${item.images[0].image_url}`
                : 'https://via.placeholder.com/600x400'
        }));
        console.log(datasetInsiden)

        // status list insedent
        function getStatusConfig(status) {
            switch (status) {
                case 'active':
                    return {
                        label: 'Aktif',
                        class: 'bg-red-500/10 text-red-400'
                    };
                case 'process':
                    return {
                        label: 'Diproses',
                        class: 'bg-orange-500/10 text-orange-400'
                    };
                case 'done':
                    return {
                        label: 'Selesai',
                        class: 'bg-green-500/10 text-green-400'
                    };
                case 'rejected':
                    return {
                        label: 'Ditolak',
                        class: 'bg-gray-500/10 text-gray-400'
                    };
                default:
                    return {
                        label: 'Unknown',
                        class: 'bg-gray-500/10 text-gray-400'
                    };
            }
        }

        // --- 2. Inisialisasi Peta Leaflet & Layer OpenStreetMap ---
        let map;
        let markerGroup;
        const defaultLocation = [-6.2349, 106.9896]; // Alun-Alun Kota Bekasi

        function initMapEngine() {
            map = L.map('map-container', {
                center: defaultLocation,
                zoom: 12,
                scrollWheelZoom: false
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            markerGroup = L.layerGroup().addTo(map);
        }

        // Fungsi Menentukan Warna Pin Marker Peta Sesuai Status Laporan
        function getMarkerColorByStatus(status) {
            if (status === 'active') return '#eb5757';   // Merah (Aktif)
            if (status === 'process') return '#f2994a';   // Orange
            if (status === 'done') return '#27ae60';      // Hijau
            return '#999'; // rejected / default
        }

        // Render Marker di Peta Berdasarkan Filter Kategori
        function renderMapMarkers(kategoriFilter = 'semua') {
            markerGroup.clearLayers();

            const filteredData = datasetInsiden.filter(item => 
                kategoriFilter === 'semua' || item.kategori === kategoriFilter
            );

            filteredData.forEach(item => {
                const color = getMarkerColorByStatus(item.status);
                
                // Custom Svg Vector Marker Pin Leaflet
                const customIcon = L.divIcon({
                    html: `<div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.4);"></div>`,
                    className: 'custom-map-pin',
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                const marker = L.marker(item.koordinat, { icon: customIcon }).addTo(markerGroup);
                
                // Popup saat marker di klik
              marker.bindPopup(`
                    <div style="font-family:'Segoe UI', sans-serif; padding:2px;">
                        <h4 style="margin:0 0 4px 0; color:#0b2240; font-size:13px;">
                            ${item.judul}
                        </h4>
                        <p style="margin:0 0 6px 0; font-size:11px; color:#666;">
                            ${item.lokasi}
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

        // --- 3. Render List Panel Samping Peta Konten ---
        function renderSideIncidentPanel(kategoriFilter = 'semua') {
            const container = document.getElementById('incidentListContainer');
            const countTarget = document.getElementById('incident-count');
            container.innerHTML = '';

            const filteredData = datasetInsiden.filter(item => 
                kategoriFilter === 'semua' || item.kategori === kategoriFilter
            );

            countTarget.textContent = `(${filteredData.length} titik)`;

            if (filteredData.length === 0) {
                container.innerHTML = `<p style="padding:20px; color:#888; text-align:center; font-size:13px;">Tidak ada laporan kategori ini.</p>`;
                return;
            }

            filteredData.forEach(item => {
                const status = getStatusConfig(item.status);
                const div = document.createElement('div');
                div.className = 'incident-item';
                div.setAttribute('data-id', item.id);
                div.innerHTML = `
                    <div class="incident-item-title">${item.judul}</div>
                    <div class="incident-item-meta"><i class="fa-solid fa-location-dot"></i> ${item.lokasi}</div>
                    <div class="incident-status-row">
                        <span class="status-badge px-2 py-1 text-xs rounded-full ${status.class}">
                             ${status.label.toUpperCase()}
                        </span>    
                        <span class="incident-time">${item.waktu}</span>
                    </div>
                `;

                // Klik List Panel Samping memicu geser peta dan buka popup marker
                div.addEventListener('click', () => {
                    document.querySelectorAll('.incident-item').forEach(el => el.classList.remove('selected'));
                    div.classList.add('selected');
                    map.setView(item.koordinat, 14, { animate: true, duration: 0.6 });
                    
                    // Loop internal mencari koordinat marker terdekat untuk dibuka
                    markerGroup.eachLayer(layer => {
                        if (layer.getLatLng().lat === item.koordinat[0] && layer.getLatLng().lng === item.koordinat[1]) {
                            layer.openPopup();
                        }
                    });
                });

                container.appendChild(div);
            });
        }

        // --- 4. Render Grid Laporan Terpopuler Feed Layout ---
        function renderPopularReportsGrid() {
            const container = document.getElementById('reportsGridContainer');
            container.innerHTML = '';

            function mapStatusToUI(status) {
                if (status === 'active') return 'Aktif';
                if (status === 'process') return 'Diproses';
                if (status === 'done') return 'Selesai';
                if (status === 'rejected') return 'Ditolak';
                return status;
            }

            datasetInsiden.forEach((item, index) => {
                const card = document.createElement('div');

                // 🔥 simpan status asli dari DB
                card.className = 'report-card';
                card.dataset.status = item.status;

                card.innerHTML = `
                    <div class="card-img-wrapper">
                        <img src="${item.thumbnail}" alt="Gambar Insiden" class="card-img-placeholder">
                        <div class="card-badges">
                            <span class="badge-status-dot status-${item.status}">
                                ${mapStatusToUI(item.status)}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="card-category">${item.kategori}</div>
                        <div class="card-title">${item.judul}</div>
                        <p class="card-text">${item.deskripsi}</p>

                        <div class="card-location">
                            <i class="fa-solid fa-location-dot"></i> ${item.lokasi}
                        </div>

                        <div class="card-footer-metrics" data-id="${item.id}">
                            <div class="metrics-left-group">
                                <button class="metric-item-btn btn-upvote ${item.is_voted ? 'upvoted' : ''}" 
                                    onclick="voteReport(${item.id}, this)">

                                    <i class="fa-solid fa-thumbs-up"></i> 
                                    <span class="vote-count">${item.votes_count}</span>
                                </button>
                            </div>

                            <span class="metric-time">${item.waktu}</span>
                        </div>
                    </div>
                `;

                // 🔥 klik card → buka modal
                card.addEventListener('click', (e) => {
                    if (e.target.closest('.btn-upvote')) return;
                    bukaModalDetailLaporan(item.id);
                });

                container.appendChild(card);
            });
        }

        // --- 6. Logika Modal Box System Open & Close ---
        const modalOverlay = document.getElementById('reportModal');
        const closeModalBtn = document.getElementById('closeModal');
        const modalTargetBody = document.getElementById('modalTargetBody');

        let swiperInstance;

        function bukaModalDetailLaporan(id) {
            const item = datasetInsiden.find(obj => obj.id === id);
            if (!item) return;

            const images = item.gambar.length
                ? item.gambar
                : ['https://via.placeholder.com/600x400'];

            const status = getStatusConfig(item.status);

            modalTargetBody.innerHTML = `
                <!-- SLIDER -->
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

                <!-- TITLE + STATUS -->
                <div style="display:flex; justify-content:space-between; align-items:start; gap:10px;">
                    <h3 class="modal-title" style="margin:0;">
                        ${item.judul}
                    </h3>

                    <span class="status-badge px-3 py-1 text-xs rounded-full whitespace-nowrap ${status.class}">
                        ${status.label}
                    </span>
                </div>

                <!-- META -->
                <div style="margin-top:10px; display:flex; flex-direction:column; gap:6px; font-size:13px; color:#aaa;">
                    <span><i class="${item.icon}"></i> ${item.kategori.toUpperCase()}</span>
                    <span><i class="fa-solid fa-location-dot"></i> ${item.lokasi}</span>
                    <span><i class="fa-solid fa-clock"></i> ${item.waktu}</span>
                </div>

                <!-- DESKRIPSI -->
                <p style="margin-top:15px; line-height:1.6;">
                    ${item.deskripsi}
                </p>

                <!-- 🔥 PROGRESS TIMELINE -->
                ${item.updates && item.updates.length > 0 ? `
                    <div style="margin-top:20px;">
                        <h4 style="font-size:14px; margin-bottom:10px;">Riwayat Progress</h4>

                        <div style="position:relative; padding-left:18px;">
                            <!-- garis -->
                            <div style="position:absolute; left:6px; top:0; bottom:0; width:2px; background:#eee;"></div>

                            ${item.updates.slice(0,3).map(update => `
                                <div style="position:relative; margin-bottom:15px;">
                                    
                                    <!-- titik -->
                                    <div style="position:absolute; left:-12px; top:4px; width:8px; height:8px;
                                        background:#3b82f6; border-radius:50%;">
                                    </div>

                                    <!-- card -->
                                    <div style="background:#f9fafb; padding:10px; border-radius:6px;">
                                        <div style="font-size:11px; color:#999; margin-bottom:3px;">
                                            ${formatTimeAgo(update.created_at)}
                                        </div>
                                        <div style="font-size:13px; color:#444;">
                                            ${update.note}
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

                <!-- ACTION BUTTON -->
                <div style="margin-top:25px; display:flex; gap:10px;" data-id="${item.id}">
                    <button class="btn-lapor ${item.is_voted ? '' : 'not-voted'}" 
                        onclick="voteReport(${item.id}, this)">

                        <i class="fa-solid fa-thumbs-up"></i> 
                        Dukung 
                        (<span class="vote-count">${item.votes_count}</span>)
                    </button>

                    <button class="btn-load-more" 
                        style="margin-top:0;"
                        onclick="document.getElementById('reportModal').classList.remove('open')">
                        Tutup
                    </button>
                </div>
            `;

            modalOverlay.classList.add('open');

            // 🔥 FIX BUG SWIPER DOUBLE
            if (swiperInstance) {
                swiperInstance.destroy(true, true);
            }

            swiperInstance = new Swiper('.mySwiper', {
                loop: true,
                spaceBetween: 10,

                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },

                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
            });
        }
        
       function updateVoteUI(id, data) {
            document.querySelectorAll(`[data-id="${id}"]`).forEach(wrapper => {

                const btn = wrapper.querySelector('.btn-upvote, .btn-lapor');
                if (!btn) return;

                // update angka
                const countEl = btn.querySelector('.vote-count');
                if (countEl) countEl.textContent = data.votes;

                // 🔥 CARD
                if (btn.classList.contains('btn-upvote')) {
                    btn.classList.toggle('upvoted', data.status === 'voted');
                }

                // 🔥 MODAL
                if (btn.classList.contains('btn-lapor')) {
                    btn.classList.toggle('not-voted', data.status !== 'voted');
                }
            });
        }

        function refreshUI(id) {
            const item = datasetInsiden.find(x => x.id === id);
            if (!item) return;

            // 🔥 UPDATE CARD
            document.querySelectorAll(`.card-footer-metrics[data-id="${id}"]`)
                .forEach(el => {
                    const btn = el.querySelector('.btn-upvote');
                    if (!btn) return;

                    btn.classList.toggle('upvoted', item.is_voted);
                    btn.querySelector('.vote-count').textContent = item.votes_count;
                });

            // 🔥 UPDATE MODAL (kalau lagi dibuka)
            const modalBtn = document.querySelector(`.btn-lapor[onclick*="${id}"]`);
            if (modalBtn) {
                modalBtn.classList.toggle('not-voted', !item.is_voted);
                modalBtn.querySelector('.vote-count').textContent = item.votes_count;
            }
        }

    //     async function voteReport(id, btn) {
    //     try {
    //         const res = await fetch(`/reports/${id}/vote`, {
    //             method: 'POST',
    //             headers: {
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    //                 'Content-Type': 'application/json'
    //             }
    //         });

    //         const data = await res.json();

    //         // 🔥 UPDATE DATASET (INI SOURCE OF TRUTH)
    //         const item = datasetInsiden.find(x => x.id === id);
    //         if (item) {
    //             item.votes_count = data.votes;
    //             item.is_voted = data.status === 'voted';
    //         }

    //         // 🔥 UPDATE CARD + MODAL SEKALIGUS
    //         refreshUI(id);

    //     } catch (err) {
    //         console.error(err);
    //     }
    // }

      async function voteReport(id, btn) {
            try {
                const res = await fetch(`/reports/${id}/vote`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json' // 🔥 INI PENTING BANGET
                    }
                });

                // 🔥 sekarang ini bakal ke-trigger
                if (res.status === 401) {
                    const data = await res.json();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: data.message || 'Harus login dulu!',
                        confirmButtonText: 'Login'
                    }).then(() => {
                        window.location.href = '/auth';
                    });

                    return;
                }

                const data = await res.json();

                const item = datasetInsiden.find(x => x.id === id);
                if (item) {
                    item.votes_count = data.votes;
                    item.is_voted = data.status === 'voted';
                }

                refreshUI(id);

            } catch (err) {
                console.error('ERROR:', err);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan, coba lagi!'
                });
            }
        }
    
        // --- 7. Floating Action Button (FAB) Menu Animation System ---
        // const fabTrigger = document.getElementById('fabTrigger');
        // const fabArea = document.getElementById('fabArea');

        // fabTrigger.addEventListener('click', (e) => {
        //     e.stopPropagation();
        //     fabArea.classList.toggle('open');
        // });

        // // Tutup menu FAB jika klik di luar area tombol
        // document.addEventListener('click', () => {
        //     fabArea.classList.remove('open');
        // });

        // --- 8. Setup Map Filter Event Listeners ---
        function setupFilterListeners() {
            const filterButtons = document.querySelectorAll('.btn-filter');

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function () {

                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const category = this.dataset.category; // 🔥 lebih clean dari getAttribute

                    renderMapMarkers(category);
                    renderSideIncidentPanel(category);
                });
            });

            document.getElementById('refreshMap').addEventListener('click', function () {

                this.style.transform = 'rotate(360deg)';
                setTimeout(() => this.style.transform = 'none', 500);

                document.querySelectorAll('.btn-filter')
                    .forEach(b => b.classList.remove('active'));

                document.querySelector('[data-category="semua"]')
                    .classList.add('active');

                renderMapMarkers('semua');
                renderSideIncidentPanel('semua');

                map.setView(defaultLocation, 12, { animate: true });
            });
        }

        // --- 9. Setup Popular Filter Event Listeners ---
        function setupPopularFilters() {
            // Menangkap class tombol tab pembungkus asli Anda
            const reportFilterButtons = document.querySelectorAll('.feed-tabs .tab-btn');
            
            if (reportFilterButtons.length > 0) {
                reportFilterButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        reportFilterButtons.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                        
                        let targetStatus = this.dataset.status;
                        // if (targetStatus === 'diproses') targetStatus = 'proses';
                        const reportCards = document.querySelectorAll('#reportsGridContainer .report-card');
                        reportCards.forEach(card => {
                            const status = card.dataset.status; 

                            if (targetStatus === 'all' || status === targetStatus) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    });
                });
            }
        }

        // --- 10. Core Engine Bootstrap Orchestrator ---
        document.addEventListener("DOMContentLoaded", () => {
            initMapEngine();
            renderMapMarkers('semua');
            renderSideIncidentPanel('semua');
            renderPopularReportsGrid();
            setupFilterListeners();
            setupPopularFilters();
            // setupFloatingActionButton();
        });
    </script>
@endsection