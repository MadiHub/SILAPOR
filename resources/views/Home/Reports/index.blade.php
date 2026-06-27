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

    <div class="reports-grid" id="allReportsGridContainer">
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
                            <button class="metric-item-btn btn-upvote {{ $report['is_voted'] ? 'upvoted' : '' }}"
                                onclick="event.stopPropagation(); voteReport({{ $report['id'] }}, this)">
                                <i class="fa-solid fa-thumbs-up"></i>
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

    // --- Dataset diambil langsung dari data-report tiap card (sudah dirender Blade) ---
    const datasetInsiden = Array.from(document.querySelectorAll('#allReportsGridContainer .report-card'))
        .map(el => {
            const item = JSON.parse(el.dataset.report);
            return {
                id: item.id,
                kategori: item.category?.name ?? 'umum',
                icon: item.category?.icon ?? 'umum',
                judul: item.title,
                deskripsi: item.description,
                lokasi: item.address ?? 'Lokasi tidak diketahui',
                pelapor: item.user?.name ?? 'Warga',
                status: item.status,
                votes_count: item.votes_count ?? 0,
                is_voted: item.is_voted ?? false,
                komentar: item.comments_count ?? 0,
                gambar: item.images?.map(img => `/storage/${img.image_url}`) ?? [],
            };
        });

    function getStatusConfig(status) {
        switch (status) {
            case 'active':
                return { label: 'Aktif', class: 'bg-red-500/10 text-red-400' };
            case 'process':
                return { label: 'Diproses', class: 'bg-orange-500/10 text-orange-400' };
            case 'done':
                return { label: 'Selesai', class: 'bg-green-500/10 text-green-400' };
            case 'rejected':
                return { label: 'Ditolak', class: 'bg-gray-500/10 text-gray-400' };
            default:
                return { label: 'Unknown', class: 'bg-gray-500/10 text-gray-400' };
        }
    }

    // --- Modal Box System Open & Close ---
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

            <div style="display:flex; justify-content:space-between; align-items:start; gap:10px;">
                <h3 class="modal-title" style="margin:0;">
                    ${item.judul}
                </h3>

                <span class="status-badge px-3 py-1 text-xs rounded-full whitespace-nowrap ${status.class}">
                    ${status.label}
                </span>
            </div>

            <div class="modal-meta-row" style="margin-top:10px; display:flex; flex-direction:column; gap:6px; font-size:13px; color:#aaa;">
                <span><i class="${item.icon}"></i> ${item.kategori.toUpperCase()}</span>
                <span><i class="fa-solid fa-location-dot"></i> ${item.lokasi}</span>
                <span><i class="fa-solid fa-user"></i> Dilaporkan oleh ${item.pelapor}</span>
            </div>

            <p class="modal-desc" style="margin-top:15px; line-height:1.6;">
                ${item.deskripsi}
            </p>

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

    closeModalBtn.addEventListener('click', () => {
        modalOverlay.classList.remove('open');
    });

    modalOverlay.addEventListener('click', (e) => {
        if (e.target === modalOverlay) {
            modalOverlay.classList.remove('open');
        }
    });

    // --- Klik card (selain tombol vote) buka modal ---
    document.querySelectorAll('#allReportsGridContainer .report-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (e.target.closest('.btn-upvote')) return;
            const id = parseInt(card.dataset.report ? JSON.parse(card.dataset.report).id : 0);
            bukaModalDetailLaporan(id);
        });
    });

    // --- Vote System ---
    function refreshUI(id) {
        const item = datasetInsiden.find(x => x.id === id);
        if (!item) return;

        document.querySelectorAll(`.card-footer-metrics[data-id="${id}"]`)
            .forEach(el => {
                const btn = el.querySelector('.btn-upvote');
                if (!btn) return;
                btn.classList.toggle('upvoted', item.is_voted);
                btn.querySelector('.vote-count').textContent = item.votes_count;
            });

        const modalBtn = document.querySelector(`.btn-lapor[onclick*="${id}"]`);
        if (modalBtn) {
            modalBtn.classList.toggle('not-voted', !item.is_voted);
            modalBtn.querySelector('.vote-count').textContent = item.votes_count;
        }
    }

    async function voteReport(id, btn) {
        try {
            const res = await fetch(`/reports/${id}/vote`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();

            const item = datasetInsiden.find(x => x.id === id);
            if (item) {
                item.votes_count = data.votes;
                item.is_voted = data.status === 'voted';
            }

            refreshUI(id);

        } catch (err) {
            console.error(err);
        }
    }

    // --- Filter Tab ---
    document.querySelectorAll('.feed-tabs .tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.feed-tabs .tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const targetStatus = this.dataset.status;
            document.querySelectorAll('#allReportsGridContainer .report-card').forEach(card => {
                const status = card.dataset.status;
                card.style.display = (targetStatus === 'all' || status === targetStatus) ? '' : 'none';
            });
        });
    });

</script>
@endsection