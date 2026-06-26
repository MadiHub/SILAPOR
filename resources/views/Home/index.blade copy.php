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
                    <button class="btn-cta-primary">Buat Laporan Sekarang <i
                            class="fa-solid fa-arrow-right"></i></button>
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
                            $suffix = 'aktif';
                            $hasPill = 'active-pill';
                        } elseif (str_contains($category->icon, 'lightbulb')) {
                            $cardColor = 'yellow-card';
                        } elseif (str_contains($category->icon, 'bridge')) {
                            $cardColor = 'gray-card';
                        } elseif (str_contains($category->icon, 'trash')) {
                            $cardColor = 'green-card';
                        } elseif (str_contains($category->icon, 'house-chimney-crack') || str_contains($category->icon, 'mountain')) {
                            $cardColor = 'red-card';
                            $suffix = 'aktif';
                            $hasPill = 'active-pill';
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
                    <h3>2.847</h3>
                    <p>Total Laporan</p>
                </div>
            </div>
            <div class="stat-box">
                <div class="sb-icon"><i class="fa-regular fa-circle-check"></i></div>
                <div class="sb-text">
                    <h3>2.391</h3>
                    <p>Terselesaikan</p>
                </div>
            </div>
            <div class="stat-box">
                <div class="sb-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="sb-text">
                    <h3>312</h3>
                    <p>Aktif Ditangani</p>
                </div>
            </div>
            <div class="stat-box">
                <div class="sb-icon"><i class="fa-solid fa-users"></i></div>
                <div class="sb-text">
                    <h3>18.640</h3>
                    <p>Warga Terlayani</p>
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
                <button class="btn-filter active" data-category="semua"><i class="fa-solid fa-book-open"></i>
                    Semua</button>
                <button class="btn-filter" data-category="banjir"><i class="fa-solid fa-water"></i> Banjir</button>
                <button class="btn-filter" data-category="jalan"><i class="fa-solid fa-road"></i> Jalan</button>
                <button class="btn-filter" data-category="pju"><i class="fa-regular fa-lightbulb"></i> PJU</button>
                <button class="btn-filter" data-category="sampah"><i class="fa-regular fa-trash-can"></i>
                    Sampah</button>
                <button class="btn-filter" data-category="jembatan"><i class="fa-solid fa-bridge"></i> Jembatan</button>
                <button class="btn-filter" data-category="longsor"><i class="fa-solid fa-hill-rockslide"></i>
                    Longsor</button>
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
            <span class="refresh-indicator"><i class="fa-solid fa-wave-square"></i> Diperbarui setiap 5 menit</span>
        </div>

        <div class="feed-tabs">
            <button class="tab-btn active" data-status="semua">Semua</button>
            <button class="tab-btn" data-status="aktif">Aktif</button>
            <button class="tab-btn" data-status="proses">Diproses</button>
            <button class="tab-btn" data-status="selesai">Selesai</button>
        </div>

        <div class="reports-grid" id="reportsGridContainer">
        </div>

        <div class="center-action">
            <button class="btn-load-more">Lihat Semua Laporan <i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </section>

    <div class="fab-wrapper" id="fabArea">
        <div class="fab-menu-list" id="fabMenuList">
            <div class="fab-menu-item">
                <span class="fab-label">Lapor Darurat</span>
                <button class="fab-sub-btn red-fab"><i class="fa-solid fa-triangle-exclamation"></i></button>
            </div>
            <div class="fab-menu-item">
                <span class="fab-label">Foto Kerusakan</span>
                <button class="fab-sub-btn orange-fab"><i class="fa-solid fa-camera"></i></button>
            </div>
            <div class="fab-menu-item">
                <span class="fab-label">Tandai Lokasi</span>
                <button class="fab-sub-btn blue-fab"><i class="fa-solid fa-location-crosshairs"></i></button>
            </div>
            <div class="fab-menu-item">
                <span class="fab-label">Buat Laporan</span>
                <button class="fab-sub-btn dark-fab"><i class="fa-regular fa-file-lines"></i></button>
            </div>
            <div class="fab-menu-item">
                <span class="fab-label">Hubungi 112</span>
                <button class="fab-sub-btn green-fab"><i class="fa-solid fa-phone"></i></button>
            </div>
        </div>
        <button class="fab-main-trigger" id="fabTrigger">
            <i class="fa-solid fa-plus icon-plus"></i>
        </button>
    </div>

    <div class="modal-overlay" id="reportModal">
        <div class="modal-box">
            <button class="modal-close-btn" id="closeModal">&times;</button>
            <div class="modal-body" id="modalTargetBody">
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
    // --- 1. Mock Data Source - Dataset Utama Aplikasi SiLapor ---
        const datasetInsiden = [
        {
            id: "L-01",
            kategori: "banjir",
            judul: "Banjir Parah Merendam 3 RT di Harapan Indah",
            deskripsi: "Curah hujan tinggi menyebabkan genangan hingga 60 cm. Beberapa akses jalan terganggu dan warga membutuhkan bantuan evakuasi.",
            lokasi: "Harapan Indah, Bekasi",
            koordinat: [-6.1775, 106.9748],
            status: "aktif",
            prioritas: "tinggi",
            mendukung: 428,
            komentar: 67,
            dilihat: "2.841",
            waktu: "2 jam lalu",
            gambar: "https://images.unsplash.com/photo-1547683905-f686c993aae5?auto=format&fit=crop&q=80&w=600"
        },
        {
            id: "L-02",
            kategori: "jalan",
            judul: "Jalan Berlubang Besar di Jl. Ahmad Yani",
            deskripsi: "Lubang jalan cukup besar dan membahayakan pengendara roda dua terutama saat malam hari.",
            lokasi: "Jl. Ahmad Yani, Bekasi Selatan",
            koordinat: [-6.2416, 106.9924],
            status: "proses",
            prioritas: "tinggi",
            mendukung: 312,
            komentar: 43,
            dilihat: "1.956",
            waktu: "1 hari lalu",
            gambar: "https://images.unsplash.com/photo-1515162305285-0293e4767cc2?auto=format&fit=crop&q=80&w=600"
        },
        {
            id: "L-03",
            kategori: "pju",
            judul: "Lampu Jalan Padam Sepanjang Jl. Ir. H. Juanda",
            deskripsi: "Beberapa titik lampu jalan tidak menyala dan menyebabkan kondisi jalan menjadi gelap.",
            lokasi: "Jl. Ir. H. Juanda, Bekasi Timur",
            koordinat: [-6.2478, 107.0141],
            status: "aktif",
            prioritas: "sedang",
            mendukung: 256,
            komentar: 31,
            dilihat: "1.432",
            waktu: "5 hari lalu",
            gambar: "https://images.unsplash.com/photo-1509143142926-f9f85a219f1f?auto=format&fit=crop&q=80&w=600"
        },
        {
            id: "L-04",
            kategori: "jembatan",
            judul: "Jembatan Kali Bekasi Mengalami Keretakan",
            deskripsi: "Ditemukan retakan pada struktur jembatan yang memerlukan pemeriksaan lebih lanjut.",
            lokasi: "Kali Bekasi, Bekasi Barat",
            koordinat: [-6.2298, 106.9782],
            status: "proses",
            prioritas: "tinggi",
            mendukung: 198,
            komentar: 29,
            dilihat: "1.104",
            waktu: "3 hari lalu",
            gambar: "https://images.unsplash.com/photo-1545558014-8687977e99a5?auto=format&fit=crop&q=80&w=600"
        },
        {
            id: "L-05",
            kategori: "longsor",
            judul: "Longsor Ringan di Area Tebing Jalan Chairil Anwar",
            deskripsi: "Material tanah menutupi sebagian badan jalan sehingga mengganggu lalu lintas.",
            lokasi: "Jl. Chairil Anwar, Bekasi Timur",
            koordinat: [-6.2587, 107.0195],
            status: "aktif",
            prioritas: "tinggi",
            mendukung: 187,
            komentar: 22,
            dilihat: "891",
            waktu: "8 jam lalu",
            gambar: "https://images.unsplash.com/photo-1578328819058-b69f3a3b0f6b?auto=format&fit=crop&q=80&w=600"
        },
        {
            id: "L-06",
            kategori: "sampah",
            judul: "Sampah Menumpuk di Pasar Baru Bekasi",
            deskripsi: "Sampah belum terangkut selama beberapa hari sehingga mengganggu aktivitas masyarakat.",
            lokasi: "Pasar Baru Bekasi",
            koordinat: [-6.2369, 106.9997],
            status: "selesai",
            prioritas: "sedang",
            mendukung: 143,
            komentar: 18,
            dilihat: "783",
            waktu: "2 hari lalu",
            gambar: "https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&q=80&w=600"
        }
    ];

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
            if (status === 'aktif') return '#eb5757';   // Merah
            if (status === 'proses') return '#f2994a';  // Orange
            return '#27ae60';                           // Hijau
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
                        <h4 style="margin:0 0 4px 0; color:#0b2240; font-size:13px;">${item.judul}</h4>
                        <p style="margin:0 0 6px 0; font-size:11px; color:#666;">${item.lokasi}</p>
                        <button onclick="bukaModalDetailLaporan('${item.id}')" style="background:#133a68; color:white; border:none; padding:4px 8px; border-radius:4px; font-size:10px; cursor:pointer; width:100%;">Lihat Detail</button>
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
                const div = document.createElement('div');
                div.className = 'incident-item';
                div.setAttribute('data-id', item.id);
                div.innerHTML = `
                    <div class="incident-item-title">${item.judul}</div>
                    <div class="incident-item-meta"><i class="fa-solid fa-location-dot"></i> ${item.lokasi}</div>
                    <div class="incident-status-row">
                        <span class="status-badge ${item.status}">${item.status.toUpperCase()}</span>
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

            datasetInsiden.forEach((item, index) => {
                const card = document.createElement('div');
                card.className = 'report-card';
                card.innerHTML = `
                    <div class="card-img-wrapper">
                        <img src="${item.gambar}" alt="Gambar Insiden" class="card-img-placeholder">
                        <div class="card-badges">
                            <span class="badge-status-dot ${item.status}">${item.status.charAt(0).toUpperCase() + item.status.slice(1)}</span>
                            <span class="badge-priority ${item.prioritas}">Prioritas ${item.prioritas}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-category">${item.kategori}</div>
                        <div class="card-title">${item.judul}</div>
                        <p class="card-text">${item.deskripsi}</p>
                        <div class="card-location"><i class="fa-solid fa-location-dot"></i> ${item.lokasi}</div>
                        <div class="card-footer-metrics">
                            <div class="metrics-left-group">
                                <button class="metric-item-btn btn-upvote" data-id="${item.id}">
                                    <i class="fa-regular fa-thumbs-up"></i> <span class="vote-count">${item.mendukung}</span>
                                </button>
                                <span class="metric-item-btn"><i class="fa-regular fa-comment"></i> ${item.komentar}</span>
                                <span class="metric-item-btn"><i class="fa-regular fa-eye"></i> ${item.dilihat}</span>
                            </div>
                            <span class="metric-time">${item.waktu}</span>
                        </div>
                    </div>
                `;

                // Penanganan klik card utama untuk buka detail modal
                card.addEventListener('click', (e) => {
                    // Cegah trigger jika menekan tombol upvote
                    if (e.target.closest('.btn-upvote')) return;
                    bukaModalDetailLaporan(item.id);
                });

                container.appendChild(card);
            });
        }

        // --- 5. Logika Interaksi Upvote Button ---
        function setupUpvoteHandlers() {
            document.addEventListener('click', function(e) {
                const upvoteBtn = e.target.closest('.btn-upvote');
                if (upvoteBtn) {
                    e.stopPropagation(); // Mencegah bubbling klik ke card
                    const id = upvoteBtn.getAttribute('data-id');
                    const dataObj = datasetInsiden.find(item => item.id === id);
                    
                    if (upvoteBtn.classList.contains('upvoted')) {
                        upvoteBtn.classList.remove('upvoted');
                        dataObj.mendukung--;
                        upvoteBtn.querySelector('i').className = 'fa-regular fa-thumbs-up';
                    } else {
                        upvoteBtn.classList.add('upvoted');
                        dataObj.mendukung++;
                        upvoteBtn.querySelector('i').className = 'fa-solid fa-thumbs-up';
                    }
                    upvoteBtn.querySelector('.vote-count').textContent = dataObj.mendukung;
                }
            });
        }

        // --- 6. Logika Modal Box System Open & Close ---
        const modalOverlay = document.getElementById('reportModal');
        const closeModalBtn = document.getElementById('closeModal');
        const modalTargetBody = document.getElementById('modalTargetBody');

        function bukaModalDetailLaporan(id) {
            const item = datasetInsiden.find(obj => obj.id === id);
            if (!item) return;

            modalTargetBody.innerHTML = `
                <img src="${item.gambar}" class="modal-img" alt="Detail">
                <h3 class="modal-title">${item.judul}</h3>
                <div class="modal-meta-row">
                    <span><i class="fa-solid fa-folder"></i> Kategori: ${item.kategori.toUpperCase()}</span>
                    <span><i class="fa-solid fa-location-dot"></i> ${item.lokasi}</span>
                    <span><i class="fa-solid fa-clock"></i> ${item.waktu}</span>
                </div>
                <div style="margin-bottom: 15px;">
                    <span class="status-badge ${item.status}" style="display:inline-block; margin-right:8px;">STATUS: ${item.status.toUpperCase()}</span>
                    <span class="badge-priority ${item.prioritas}">PRIORITAS ${item.prioritas.toUpperCase()}</span>
                </div>
                <p class="modal-desc">${item.deskripsi}</p>
                <div style="margin-top:25px; display:flex; gap:10px;">
                    <button class="btn-lapor" style="flex:1;" onclick="alert('Terima kasih, dukungan Anda berhasil ditambahkan!')"><i class="fa-solid fa-thumbs-up"></i> Dukung Laporan Ini</button>
                    <button class="btn-load-more" style="margin-top:0;" onclick="document.getElementById('reportModal').classList.remove('open')">Tutup</button>
                </div>
            `;
            modalOverlay.classList.add('open');
        }

        closeModalBtn.addEventListener('click', () => modalOverlay.classList.remove('open'));
        modalOverlay.addEventListener('click', (e) => {
            if (e.target === modalOverlay) modalOverlay.classList.remove('open');
        });

        // --- 7. Floating Action Button (FAB) Menu Animation System ---
        const fabTrigger = document.getElementById('fabTrigger');
        const fabArea = document.getElementById('fabArea');

        fabTrigger.addEventListener('click', (e) => {
            e.stopPropagation();
            fabArea.classList.toggle('open');
        });

        // Tutup menu FAB jika klik di luar area tombol
        document.addEventListener('click', () => {
            fabArea.classList.remove('open');
        });

        // --- 8. Setup Map Filter Event Listeners ---
        function setupFilterListeners() {
            const filterButtons = document.querySelectorAll('.btn-filter');
            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const category = this.getAttribute('data-category');
                    renderMapMarkers(category);
                    renderSideIncidentPanel(category);
                });
            });

            // Refresh Map Button Click Action Trigger
            document.getElementById('refreshMap').addEventListener('click', function() {
                this.style.transform = 'rotate(360deg)';
                setTimeout(() => this.style.transform = 'none', 500);
                
                // Reset filter ke Semua
                document.querySelectorAll('.btn-filter').forEach(b => b.classList.remove('active'));
                document.querySelector('[data-category="semua"]').classList.add('active');
                
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
                        
                        let targetStatus = this.textContent.trim().toLowerCase();
                        if (targetStatus === 'diproses') targetStatus = 'proses';
                        const reportCards = document.querySelectorAll('#reportsGridContainer .report-card');
                        reportCards.forEach(card => {
                            const statusBadge = card.querySelector('.badge-status-dot');
                            if (!statusBadge) return;
                            let cardStatus = statusBadge.textContent.trim().toLowerCase();
                            if (targetStatus === 'semua' || cardStatus === targetStatus) {
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
            setupUpvoteHandlers();
            setupFilterListeners();
            setupPopularFilters();
            setupFloatingActionButton();
        });
    </script>
@endsection