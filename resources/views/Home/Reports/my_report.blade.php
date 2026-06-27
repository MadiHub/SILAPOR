@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Laporan Saya')

@section('content')

<style>
/* ============================================
   ACTION BUTTONS DI CARD (edit/delete)
============================================ */
.card-img-wrapper {
    position: relative;
}

.card-action-buttons {
    position: absolute;
    top: 10px;
    left: 10px;
    display: flex;
    gap: 6px;
    z-index: 5;
}

.card-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(17, 28, 46, 0.75);
    backdrop-filter: blur(6px);
    color: #E2E8F0;
    border: 1px solid rgba(255,255,255,0.08);
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.card-action-edit:hover {
    background: rgba(249, 115, 22, 0.85);
    border-color: rgba(249, 115, 22, 0.9);
    color: #fff;
}

.card-action-delete:hover {
    background: rgba(239, 68, 68, 0.85);
    border-color: rgba(239, 68, 68, 0.9);
    color: #fff;
}

/* ============================================
   ACTION BUTTONS DI MODAL DETAIL
============================================ */
.modal-action-row {
    display: flex;
    gap: 10px;
    margin-top: 12px;
}

.btn-modal-edit, .btn-modal-delete {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: all 0.2s ease;
}

.btn-modal-edit {
    background: rgba(249, 115, 22, 0.12);
    color: #f97316;
}
.btn-modal-edit:hover {
    background: rgba(249, 115, 22, 0.22);
}

.btn-modal-delete {
    background: rgba(239, 68, 68, 0.12);
    color: #ef4444;
}
.btn-modal-delete:hover {
    background: rgba(239, 68, 68, 0.22);
}

/* ============================================
   CONFIRM DELETE MODAL (kecil, di atas modal detail)
============================================ */
.confirm-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.65);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 100;
}
.confirm-overlay.open {
    display: flex;
}
.confirm-box {
    background: #111C2E;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 14px;
    padding: 24px;
    max-width: 320px;
    text-align: center;
}
.confirm-box i {
    font-size: 28px;
    color: #ef4444;
    margin-bottom: 10px;
}
.confirm-box h4 {
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 6px;
}
.confirm-box p {
    color: #94a3b8;
    font-size: 13px;
    margin-bottom: 18px;
    line-height: 1.5;
}
.confirm-actions {
    display: flex;
    gap: 10px;
}
.confirm-actions button {
    flex: 1;
    padding: 10px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: none;
}
.confirm-cancel {
    background: rgba(255,255,255,0.06);
    color: #cbd5e1;
}
.confirm-cancel:hover {
    background: rgba(255,255,255,0.1);
}
.confirm-delete-final {
    background: #ef4444;
    color: #fff;
}
.confirm-delete-final:hover {
    background: #dc2626;
}
</style>

<section class="popular-reports-section" id="my-reports-section">
    <div class="section-header row-header">
        <div>
            <span class="sub-title">LAPORAN SAYA</span>
            <h2 class="main-title">Riwayat Laporan yang Anda Buat</h2>
            <p class="section-desc">Pantau status dan progres laporan yang sudah Anda kirimkan.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 text-sm px-4 py-3 rounded-xl" style="background: rgba(34,197,94,0.1); color: #4ade80;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 text-sm px-4 py-3 rounded-xl" style="background: rgba(239,68,68,0.1); color: #f87171;">
            {{ session('error') }}
        </div>
    @endif

    <div class="feed-tabs">
        <button class="tab-btn active" data-status="all">Semua</button>
        <button class="tab-btn" data-status="active">Aktif</button>
        <button class="tab-btn" data-status="process">Diproses</button>
        <button class="tab-btn" data-status="done">Selesai</button>
        <button class="tab-btn" data-status="rejected">Ditolak</button>
    </div>

    <div class="reports-grid" id="myReportsGridContainer">
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

                    {{-- Tombol edit/delete HANYA muncul kalau status masih active --}}
                    @if ($report['status'] === 'active')
                    <div class="card-action-buttons">
                        <a href="{{ route('reports.edit', $report['id']) }}"
                           class="card-action-btn card-action-edit"
                           title="Edit Laporan"
                           onclick="event.stopPropagation()">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button type="button"
                            class="card-action-btn card-action-delete"
                            title="Hapus Laporan"
                            onclick="event.stopPropagation(); openConfirmDelete({{ $report['id'] }})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                    @endif
                </div>

                <div class="card-body">
                    <div class="card-category">{{ $report['category']->name ?? 'Umum' }}</div>
                    <div class="card-title">{{ $report['title'] }}</div>
                    <p class="card-text">{{ $report['description'] }}</p>

                    <div class="card-location">
                        <i class="fa-solid fa-location-dot"></i> {{ $report['address'] ?? 'Lokasi tidak diketahui' }}
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
                Anda belum membuat laporan apapun.
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

{{-- Konfirmasi hapus, dipakai bareng dari card maupun modal --}}
<div class="confirm-overlay" id="confirmDeleteOverlay">
    <div class="confirm-box">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <h4>Hapus Laporan?</h4>
        <p>Laporan dan semua foto yang sudah diunggah akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.</p>
        <form id="deleteForm" method="POST" action="">
            @csrf
            @method('DELETE')
            <div class="confirm-actions">
                <button type="button" class="confirm-cancel" onclick="closeConfirmDelete()">Batal</button>
                <button type="submit" class="confirm-delete-final">Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>

    // --- Dataset diambil langsung dari data-report tiap card (sudah dirender Blade) ---
    const datasetInsiden = Array.from(document.querySelectorAll('#myReportsGridContainer .report-card'))
        .map(el => {
            const item = JSON.parse(el.dataset.report);
            return {
                id: item.id,
                kategori: item.category?.name ?? 'umum',
                icon: item.category?.icon ?? 'umum',
                judul: item.title,
                deskripsi: item.description,
                lokasi: item.address ?? 'Lokasi tidak diketahui',
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

        // tombol edit/delete cuma muncul di modal kalau status masih active
        const actionRow = item.status === 'active' ? `
            <div class="modal-action-row">
                <a href="/reports/${item.id}/edit" class="btn-modal-edit">
                    <i class="fa-solid fa-pen"></i> Edit Laporan
                </a>
                <button type="button" class="btn-modal-delete" onclick="openConfirmDelete(${item.id})">
                    <i class="fa-solid fa-trash"></i> Hapus
                </button>
            </div>
        ` : '';

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

            ${actionRow}
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

    // --- Klik card (selain tombol vote/edit/delete) buka modal ---
    document.querySelectorAll('#myReportsGridContainer .report-card').forEach(card => {
        card.addEventListener('click', (e) => {
            if (e.target.closest('.btn-upvote')) return;
            if (e.target.closest('.card-action-btn')) return;
            const id = parseInt(card.dataset.report ? JSON.parse(card.dataset.report).id : 0);
            bukaModalDetailLaporan(id);
        });
    });

    // --- Konfirmasi Hapus ---
    const confirmOverlay = document.getElementById('confirmDeleteOverlay');
    const deleteForm = document.getElementById('deleteForm');

    function openConfirmDelete(id) {
        deleteForm.action = `/reports/${id}`;
        confirmOverlay.classList.add('open');
    }

    function closeConfirmDelete() {
        confirmOverlay.classList.remove('open');
    }

    confirmOverlay.addEventListener('click', (e) => {
        if (e.target === confirmOverlay) closeConfirmDelete();
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
            document.querySelectorAll('#myReportsGridContainer .report-card').forEach(card => {
                const status = card.dataset.status;
                card.style.display = (targetStatus === 'all' || status === targetStatus) ? '' : 'none';
            });
        });
    });

</script>
@endsection