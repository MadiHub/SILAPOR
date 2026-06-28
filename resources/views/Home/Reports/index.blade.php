@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Semua Laporan')

@section('content')

<style>
.reporter-tag {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #94a3b8;
    margin-top: 4px;
}
.reporter-tag i {
    font-size: 11px;
    color: #64748b;
}
</style>

<section class="popular-reports-section" id="all-reports-section">
    <div class="section-header row-header">
        <div>
            <span class="sub-title">LAPORAN WARGA</span>
            <h2 class="main-title">Semua Laporan yang Masuk</h2>
            <p class="section-desc">Pantau laporan dari seluruh warga dan dukung yang menurutmu perlu ditindaklanjuti.</p>
        </div>
    </div>

    <div class="feed-tabs">
        <button class="tab-btn active" data-status="all">Semua</button>
        <button class="tab-btn" data-status="active">Aktif</button>
        <button class="tab-btn" data-status="process">Diproses</button>
        <button class="tab-btn" data-status="done">Selesai</button>
        <button class="tab-btn" data-status="rejected">Ditolak</button>
    </div>

    <div class="reports-grid" id="reportsGridContainer">
        @forelse ($reports as $report)
            <div class="report-card"
                 data-status="{{ $report['status'] }}"
                 data-report='@json($report)'>

                <div class="card-img-wrapper">
                    <img src="{{ $report['images']->first()?->image_url ? '/storage/' . $report['images']->first()->image_url : 'https://via.placeholder.com/600x400' }}"
                         alt="Gambar Insiden" class="card-img-placeholder">
                    <div class="card-badges">
                        <span class="badge-status-dot status-{{ $report['status'] }}">
                            @switch($report['status'])
                                @case('active') Aktif @break
                                @case('process') Diproses @break
                                @case('done') Selesai @break
                                @case('rejected') Ditolak @break
                                @default {{ $report['status'] }}
                            @endswitch
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="card-category">{{ $report['category']->name ?? 'Umum' }}</div>
                    <div class="card-title">{{ $report['title'] }}</div>
                    <p class="card-text">{{ $report['description'] }}</p>

                    <div class="card-location">
                        <i class="fa-solid fa-location-dot"></i> {{ $report['address'] ?? 'Lokasi tidak diketahui' }}
                    </div>

                    <div class="reporter-tag">
                        <i class="fa-solid fa-user"></i>
                        {{ $report['user']->name ?? 'Warga' }}
                    </div>

                    <div class="card-footer-metrics" data-id="{{ $report['id'] }}">
                        <div class="metrics-left-group">
                            <!--
                                PENTING: Tidak ada onclick di sini.
                                Vote ditangani oleh delegate listener (document.addEventListener)
                                agar tidak double-trigger.
                            -->
                            <button
                                class="metric-item-btn btn-upvote {{ $report['is_voted'] ? 'upvoted' : '' }}"
                                data-id="{{ $report['id'] }}">
                                <i class="fas fa-arrow-up"></i>
                                <span class="vote-count">{{ $report['votes_count'] }}</span>
                            </button>
                        </div>
                        <span class="metric-time">{{ $report['created_at']->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p style="padding:20px; color:#888; text-align:center; font-size:13px;">
                Belum ada laporan yang masuk.
            </p>
        @endforelse
    </div>

    <div class="center-action">
        {{ $reports->links() }}
    </div>
</section>

<div class="modal-overlay" id="reportModal">
    <div class="modal-box">
        <button class="modal-close-btn" id="closeModal">&times;</button>
        <div class="modal-body" id="modalTargetBody">
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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

    function getStatusConfig(status) {
        const config = {
            active:   { label: 'Aktif',    class: 'bg-red-500/10 text-red-400' },
            process:  { label: 'Diproses', class: 'bg-orange-500/10 text-orange-400' },
            done:     { label: 'Selesai',  class: 'bg-green-500/10 text-green-400' },
            rejected: { label: 'Ditolak',  class: 'bg-gray-500/10 text-gray-400' },
        };
        return config[status] ?? { label: 'Unknown', class: 'bg-gray-500/10 text-gray-400' };
    }


    // ============================================================
    //  DATA MAPPING
    // ============================================================

    const datasetInsiden = Array.from(document.querySelectorAll('#reportsGridContainer .report-card'))
        .map(el => {
            const item = JSON.parse(el.dataset.report);
            return {
                id          : item.id,
                kategori    : item.category?.name ?? 'umum',
                icon        : item.category?.icon ?? 'umum',
                judul       : item.title,
                deskripsi   : item.description,
                lokasi      : item.address ?? 'Lokasi tidak diketahui',
                pelapor     : item.user?.name ?? 'Warga',
                status      : item.status,
                votes_count : item.votes_count ?? 0,
                is_voted    : item.is_voted ?? false,
                komentar    : item.comments_count ?? 0,
                updates     : item.updates ?? [],
                koordinat : [
                    parseFloat(item.latitude)  || 0,
                    parseFloat(item.longitude) || 0,
                ],
                gambar      : item.images?.map(img => `/storage/${img.image_url}`) ?? [],
            };
        });


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

                    <!-- Meta info: kategori, lokasi, pelapor -->
                    <div style="margin-top:10px; display:flex; flex-direction:column; gap:6px; font-size:13px; color:#aaa;">
                        <span><i class="${item.icon}"></i> ${escapeHTML(item.kategori.toUpperCase())}</span>
                        <span><i class="fa-solid fa-location-dot"></i> ${escapeHTML(item.lokasi)}</span>
                        <span><i class="fa-solid fa-user"></i> Dilaporkan oleh ${escapeHTML(item.pelapor)}</span>
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
                                                ${update.created_at}
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

                    <!-- Tombol aksi: dukung, maps & tutup -->
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
    //  KLIK CARD (selain tombol vote) → BUKA MODAL
    // ============================================================

    document.querySelectorAll('#reportsGridContainer .report-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (e.target.closest('.btn-upvote')) return;
            const item = JSON.parse(card.dataset.report);
            bukaModalDetailLaporan(item.id);
        });
    });


    // ============================================================
    //  FILTER TAB STATUS
    // ============================================================

    function setupStatusFilters() {
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


    // ============================================================
    //  BOOTSTRAP
    // ============================================================

    document.addEventListener('DOMContentLoaded', () => {
        setupStatusFilters();
    });

</script>
@endsection