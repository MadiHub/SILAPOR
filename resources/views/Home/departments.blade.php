@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Semua Instansi Terkait')

@section('content')

<style>
    /* =============================================
       INSTANSI PAGE — menggunakan CSS variable root
       dari base_layout
    ============================================= */

    /* ===== HERO ===== */
    .static-page-hero {
        background: radial-gradient(circle at top right, #112d52 0%, var(--primary-blue) 100%);
        padding: 64px 60px 56px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .static-page-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 0);
        background-size: 24px 24px;
        pointer-events: none;
    }

    .static-page-hero .sub-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 2px;
        color: var(--brand-orange);
        text-transform: uppercase;
        display: block;
        margin-bottom: 12px;
        position: relative;
    }

    .static-page-hero h1 {
        font-size: 32px;
        font-weight: 800;
        color: var(--text-white);
        margin-bottom: 12px;
        position: relative;
    }

    .static-page-hero p {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.65);
        position: relative;
    }

    /* ===== STATS BAR ===== */
    .instansi-stats-bar {
        background: var(--accent-blue);
        padding: 18px 5%;
        display: flex;
        justify-content: center;
        gap: 48px;
        flex-wrap: wrap;
    }

    .isb-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-white);
    }

    .isb-item i {
        font-size: 20px;
        color: var(--brand-orange);
    }

    .isb-item strong {
        font-size: 20px;
        font-weight: 700;
        display: block;
        line-height: 1;
    }

    .isb-item span {
        font-size: 11px;
        color: rgba(255,255,255,0.6);
    }

    /* ===== MAIN SECTION ===== */
    .instansi-section {
        background: var(--bg-light);
        padding: 56px 5% 72px;
    }

    /* ===== SEARCH & FILTER BAR ===== */
    .instansi-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 32px;
        flex-wrap: wrap;
    }

    .instansi-toolbar-left h2 {
        font-size: 22px;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 4px;
    }

    .instansi-toolbar-left p {
        font-size: 13px;
        color: #718096;
    }

    .instansi-search-form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .instansi-search-wrap {
        position: relative;
    }

    .instansi-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
        pointer-events: none;
    }

    .instansi-search-input {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 16px 10px 38px;
        font-size: 13px;
        color: #2d3748;
        background: var(--text-white);
        width: 260px;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .instansi-search-input:focus {
        border-color: var(--accent-blue);
        box-shadow: 0 0 0 3px rgba(19, 58, 104, 0.08);
    }

    .instansi-search-btn {
        background: var(--accent-blue);
        color: var(--text-white);
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, transform 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .instansi-search-btn:hover {
        background: var(--primary-blue);
        transform: translateY(-1px);
    }

    /* ===== GRID CARDS ===== */
    .instansi-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .instansi-card {
        background: var(--text-white);
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 28px 24px;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .instansi-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 32px rgba(11, 34, 64, 0.1);
        border-color: var(--accent-blue);
    }

    /* Aksen garis oranye kiri atas */
    .instansi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 56px;
        background: var(--brand-orange);
        border-radius: 14px 0 0 0;
    }

    /* Watermark code di background */
    .instansi-card::after {
        content: attr(data-code);
        position: absolute;
        bottom: -8px;
        right: 12px;
        font-size: 52px;
        font-weight: 800;
        color: var(--bg-light);
        line-height: 1;
        pointer-events: none;
        letter-spacing: -1px;
    }

    .instansi-card-header {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .instansi-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--accent-blue), var(--primary-blue));
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-white);
        font-size: 20px;
        flex-shrink: 0;
    }

    .instansi-card-title {
        flex: 1;
        min-width: 0;
    }

    .instansi-code-badge {
        display: inline-block;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: var(--brand-orange);
        background: rgba(255, 118, 27, 0.08);
        border: 1px solid rgba(255, 118, 27, 0.2);
        padding: 2px 8px;
        border-radius: 4px;
        margin-bottom: 6px;
    }

    .instansi-card-title h3 {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-blue);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .instansi-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.65;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .instansi-desc.empty {
        font-style: italic;
        color: #a0aec0;
    }

    /* Footer metrics */
    .instansi-card-footer {
        display: flex;
        gap: 16px;
        padding-top: 14px;
        border-top: 1px solid #f0f4f8;
        position: relative;
        z-index: 1;
    }

    .instansi-metric {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #718096;
    }

    .instansi-metric i {
        font-size: 13px;
        color: var(--accent-blue);
    }

    .instansi-metric strong {
        font-weight: 700;
        color: var(--primary-blue);
    }

    /* ===== EMPTY STATE ===== */
    .instansi-empty {
        text-align: center;
        padding: 64px 24px;
        background: var(--text-white);
        border-radius: 16px;
        border: 1px solid #e2e8f0;
    }

    .instansi-empty i {
        font-size: 48px;
        color: #cbd5e0;
        margin-bottom: 16px;
        display: block;
    }

    .instansi-empty h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 8px;
    }

    .instansi-empty p {
        font-size: 14px;
        color: #718096;
        margin-bottom: 20px;
    }

    .instansi-empty a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--accent-blue);
        color: var(--text-white);
        padding: 10px 22px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
    }

    .instansi-empty a:hover {
        background: var(--primary-blue);
    }

    /* ===== PAGINATION ===== */
    .instansi-pagination {
        display: flex;
        justify-content: center;
    }

    .instansi-pagination .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .instansi-pagination .page-item .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: var(--text-white);
        color: #4a5568;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
    }

    .instansi-pagination .page-item.active .page-link {
        background: var(--accent-blue);
        border-color: var(--accent-blue);
        color: var(--text-white);
    }

    .instansi-pagination .page-item .page-link:hover {
        background: var(--bg-light);
        border-color: var(--accent-blue);
        color: var(--accent-blue);
    }

    .instansi-pagination .page-item.disabled .page-link {
        opacity: 0.4;
        pointer-events: none;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .instansi-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 768px) {
        .static-page-hero { padding: 40px 16px 36px; }
        .static-page-hero h1 { font-size: 24px; }

        .instansi-stats-bar { gap: 24px; padding: 16px; }

        .instansi-section { padding: 40px 16px 56px; }

        .instansi-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .instansi-search-form { width: 100%; }
        .instansi-search-wrap { flex: 1; }
        .instansi-search-input { width: 100%; }

        .instansi-grid { grid-template-columns: 1fr; gap: 14px; }

        .instansi-card::after { font-size: 40px; }
    }
</style>

{{-- ===== HERO ===== --}}
<div class="static-page-hero">
    <span class="sub-title">PEMERINTAH KOTA BEKASI</span>
    <h1>Instansi Terkait</h1>
    <p>Daftar lengkap dinas dan instansi pemerintah yang menangani laporan warga Bekasi.</p>
</div>

{{-- ===== STATS BAR ===== --}}
<div class="instansi-stats-bar">
    <div class="isb-item">
        <i class="fa-solid fa-building-columns"></i>
        <div>
            <strong>{{ $totalDepartments }}</strong>
            <span>Total Instansi</span>
        </div>
    </div>
    <div class="isb-item">
        <i class="fa-solid fa-clipboard-list"></i>
        <div>
            <strong>{{ number_format($totalReports) }}</strong>
            <span>Total Laporan Masuk</span>
        </div>
    </div>
    <div class="isb-item">
        <i class="fa-solid fa-circle-check"></i>
        <div>
            <strong>Aktif</strong>
            <span>Status Layanan</span>
        </div>
    </div>
</div>

{{-- ===== MAIN SECTION ===== --}}
<section class="instansi-section">

    {{-- Toolbar: judul + search --}}
    <div class="instansi-toolbar">
        <div class="instansi-toolbar-left">
            <h2>Semua Instansi</h2>
            <p>
                @if($search)
                    Menampilkan hasil pencarian untuk "<strong>{{ $search }}</strong>"
                    — {{ $departments->total() }} instansi ditemukan
                @else
                    Menampilkan {{ $departments->total() }} instansi
                @endif
            </p>
        </div>

        <form method="GET" action="{{ route('department.index') }}" class="instansi-search-form">
            <div class="instansi-search-wrap">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input
                    type="text"
                    name="search"
                    class="instansi-search-input"
                    placeholder="Cari instansi..."
                    value="{{ $search }}"
                >
            </div>
            <button type="submit" class="instansi-search-btn">
                <i class="fa-solid fa-search"></i> Cari
            </button>
            @if($search)
                <a href="{{ route('department.index') }}"
                   style="padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;
                          background: #fff; color: #718096; font-size: 13px; text-decoration: none;
                          display: flex; align-items: center; gap: 6px; transition: all 0.2s;"
                   title="Reset pencarian">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- Cards Grid --}}
    @if($departments->count() > 0)
        <div class="instansi-grid">
            @foreach($departments as $dept)
                @php
                    $icons = [
                        'jalan'       => 'fa-road',
                        'pemadam'     => 'fa-fire-extinguisher',
                        'banjir'      => 'fa-water',
                        'kesehatan'   => 'fa-kit-medical',
                        'pendidikan'  => 'fa-graduation-cap',
                        'lingkungan'  => 'fa-leaf',
                        'sosial'      => 'fa-people-group',
                        'perhubungan' => 'fa-traffic-light',
                        'default'     => 'fa-building-columns',
                    ];
                    $iconKey = 'default';
                    foreach ($icons as $keyword => $icon) {
                        if (str_contains(strtolower($dept->name . ' ' . $dept->code), $keyword)) {
                            $iconKey = $keyword;
                            break;
                        }
                    }
                    $iconClass = $icons[$iconKey];
                @endphp

                <div class="instansi-card" data-code="{{ strtoupper(substr($dept->code, 0, 4)) }}">
                    <div class="instansi-card-header">
                        <div class="instansi-icon">
                            <i class="fa-solid {{ $iconClass }}"></i>
                        </div>
                        <div class="instansi-card-title">
                            <span class="instansi-code-badge">{{ strtoupper($dept->code) }}</span>
                            <h3>{{ $dept->name }}</h3>
                        </div>
                    </div>

                    <p class="instansi-desc {{ empty($dept->description) ? 'empty' : '' }}">
                        {{ $dept->description ?? 'Belum ada deskripsi untuk instansi ini.' }}
                    </p>

                    <div class="instansi-card-footer">
                        <div class="instansi-metric">
                            <i class="fa-solid fa-clipboard-list"></i>
                            <span><strong>{{ $dept->reports_count }}</strong> Laporan</span>
                        </div>
                        <div class="instansi-metric">
                            <i class="fa-solid fa-tags"></i>
                            <span><strong>{{ $dept->categories_count }}</strong> Kategori</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($departments->hasPages())
            <div class="instansi-pagination">
                {{ $departments->links() }}
            </div>
        @endif

    @else
        {{-- Empty state --}}
        <div class="instansi-empty">
            <i class="fa-solid fa-building-columns"></i>
            <h3>Instansi Tidak Ditemukan</h3>
            <p>
                @if($search)
                    Tidak ada instansi yang cocok dengan pencarian "{{ $search }}".
                @else
                    Belum ada data instansi yang tersedia.
                @endif
            </p>
            @if($search)
                <a href="{{ route('department.index') }}">
                    <i class="fa-solid fa-arrow-left"></i> Lihat Semua Instansi
                </a>
            @endif
        </div>
    @endif

</section>

@endsection