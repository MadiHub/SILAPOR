@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Cara Membuat Laporan')

@section('content')

<style>
    /* =============================================
       STATIC PAGE — menggunakan CSS variable root
       dari base_layout (--primary-blue, dll.)
    ============================================= */

    /* ===== HERO ===== */
    .static-page-hero {
        background: radial-gradient(circle at top right, #112d52 0%, var(--primary-blue) 100%);
        padding: 64px 60px 56px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    /* dot pattern sama kayak hero utama */
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

    /* ===== STEPS SECTION ===== */
    .how-section {
        background: var(--bg-light);
        padding: 72px 5%;
    }

    .steps-container {
        max-width: 1000px;
        margin: 0 auto 48px;
        position: relative;
    }

    /* Garis konektor horizontal antar step */
    .steps-connector {
        position: absolute;
        top: 52px;
        left: 10%;
        right: 10%;
        height: 2px;
        background: #e2e8f0;
        z-index: 0;
    }

    .steps-connector-fill {
        width: 75%;
        height: 100%;
        background: linear-gradient(90deg, var(--brand-orange), var(--sys-orange));
        border-radius: 2px;
    }

    .steps-wrapper {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        position: relative;
        z-index: 1;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .step-num {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #94a3b8;
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #e2e8f0;
        transition: all 0.3s;
    }

    .step-num.active {
        background: linear-gradient(135deg, var(--brand-orange), var(--brand-orange-hover));
        color: var(--text-white);
        box-shadow: 0 0 0 3px rgba(255, 118, 27, 0.2);
    }

    .step-badge {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 1px;
        color: var(--brand-orange);
        background: rgba(255, 118, 27, 0.08);
        border: 1px solid rgba(255, 118, 27, 0.2);
        padding: 2px 8px;
        border-radius: 20px;
        margin-bottom: 16px;
    }

    .step-icon-wrap {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        background: var(--text-white);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: var(--accent-blue);
        margin-bottom: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        transition: all 0.3s;
    }

    .step-item:hover .step-icon-wrap {
        background: var(--accent-blue);
        color: var(--text-white);
        border-color: var(--accent-blue);
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(19, 58, 104, 0.2);
    }

    .step-item h3 {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 8px;
    }

    .step-item p {
        font-size: 12.5px;
        color: #64748b;
        line-height: 1.65;
    }

    /* CTA bawah steps */
    .how-cta {
        text-align: center;
    }

    .how-cta .btn-cta-primary {
        display: inline-flex;
        text-decoration: none;
    }

    /* ===== DETAIL CARDS SECTION ===== */
    .static-detail-section {
        background: var(--text-white);
        padding: 72px 5%;
    }

    .static-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        max-width: 960px;
        margin: 0 auto;
    }

    /* Section header seragam */
    .static-section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .static-section-header .sub-title {
        color: var(--brand-orange);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 8px;
    }

    .static-section-header .main-title {
        font-size: 26px;
        color: var(--primary-blue);
        font-weight: 700;
    }

    .static-section-header .section-desc {
        font-size: 14px;
        color: #64748b;
        margin-top: 6px;
    }

    .detail-card {
        background: var(--bg-light);
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 28px;
        position: relative;
        transition: box-shadow 0.3s, transform 0.3s;
    }

    .detail-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(11, 34, 64, 0.08);
    }

    /* Garis aksen oranye di kiri atas */
    .detail-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 48px;
        background: var(--brand-orange);
        border-radius: 14px 0 0 0;
    }

    .detail-num {
        font-size: 48px;
        font-weight: 800;
        color: #e2e8f0;
        line-height: 1;
        margin-bottom: 8px;
    }

    .detail-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 10px;
    }

    .detail-card p {
        font-size: 13.5px;
        color: #4f4f4f;
        line-height: 1.75;
        margin-bottom: 14px;
    }

    .detail-card ul {
        padding-left: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
        list-style: none;
    }

    .detail-card ul li {
        font-size: 13px;
        color: #64748b;
        line-height: 1.6;
        padding-left: 18px;
        position: relative;
    }

    /* bullet oranye custom */
    .detail-card ul li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 7px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--brand-orange);
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .steps-wrapper { grid-template-columns: repeat(2, 1fr); }
        .steps-connector { display: none; }
    }

    @media (max-width: 768px) {
        .static-page-hero { padding: 40px 16px 36px; }
        .static-page-hero h1 { font-size: 24px; }
        .static-page-hero p { font-size: 13px; }

        .how-section { padding: 48px 16px; }
        .steps-wrapper { grid-template-columns: repeat(2, 1fr); gap: 24px; }
        .steps-connector { display: none; }

        .static-detail-section { padding: 48px 16px; }
        .static-detail-grid { grid-template-columns: 1fr; }

        .static-section-header .main-title { font-size: 22px; }
        .detail-card { padding: 22px 20px; }
        .detail-num { font-size: 36px; }
    }

    @media (max-width: 480px) {
        .steps-wrapper { grid-template-columns: 1fr 1fr; gap: 16px; }
        .step-icon-wrap { width: 52px; height: 52px; font-size: 20px; }
        .step-item h3 { font-size: 13px; }
        .step-item p { font-size: 12px; }
    }
</style>

{{-- ===== HERO ===== --}}
<div class="static-page-hero">
    <span class="sub-title">PANDUAN PENGGUNAAN</span>
    <h1>Cara Membuat Laporan</h1>
    <p>Ikuti 4 langkah mudah untuk melaporkan masalah infrastruktur di sekitar Anda.</p>
</div>

{{-- ===== STEPS ===== --}}
<section class="how-section">

    <div class="steps-container">
        <div class="steps-connector">
            <div class="steps-connector-fill"></div>
        </div>

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
                <p>Cek lokasi Anda dan pilih kategori masalah yang sesuai seperti jalan, drainase, atau bencana.</p>
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
                <p>Kirim laporan dan pantau perkembangannya secara real-time. Notifikasi dikirim setiap ada pembaruan.</p>
            </div>

        </div>
    </div>

    <div class="how-cta">
        <a href="{{ route('reports.create') }}" class="btn-cta-primary">
            Buat Laporan Sekarang <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

</section>

{{-- ===== DETAIL CARDS ===== --}}
<section class="static-detail-section">

    <div class="static-section-header">
        <span class="sub-title">DETAIL LANGKAH</span>
        <div class="main-title">Penjelasan Lengkap Setiap Tahap</div>
        <p class="section-desc">Panduan terperinci agar proses pelaporan berjalan lancar dan cepat ditangani.</p>
    </div>

    <div class="static-detail-grid">

        <div class="detail-card">
            <div class="detail-num">01</div>
            <h3>Buat Akun / Masuk</h3>
            <p>Kunjungi halaman <strong>Daftar</strong> dan isi formulir dengan nama lengkap, email aktif, dan password. Setelah itu cek email untuk verifikasi. Jika sudah punya akun, langsung masuk melalui halaman <strong>Login</strong>.</p>
            <ul>
                <li>Gunakan email yang aktif dan dapat diakses</li>
                <li>Password minimal 8 karakter</li>
                <li>Verifikasi email wajib sebelum membuat laporan</li>
            </ul>
        </div>

        <div class="detail-card">
            <div class="detail-num">02</div>
            <h3>Pilih Kategori & Cek Lokasi</h3>
            <p>Pada formulir laporan, pilih kategori yang paling sesuai dengan masalah yang Anda temukan. Tandai lokasi menggunakan peta interaktif atau izinkan sistem mendeteksi lokasi otomatis.</p>
            <ul>
                <li>Kategori: Jalan, Jembatan, Drainase, Banjir, Penerangan, dan lainnya</li>
                <li>Lokasi bisa ditandai manual atau otomatis via GPS</li>
                <li>Pastikan lokasi sudah tepat sebelum melanjutkan</li>
            </ul>
        </div>

        <div class="detail-card">
            <div class="detail-num">03</div>
            <h3>Tambah Foto & Deskripsi</h3>
            <p>Unggah minimal 1 foto yang menunjukkan kondisi masalah dengan jelas. Tulis deskripsi singkat namun informatif agar petugas dapat memahami situasi lapangan dengan cepat.</p>
            <ul>
                <li>Format foto: JPG, PNG, WEBP — maks. 5MB per foto</li>
                <li>Foto dari beberapa sudut mempercepat verifikasi</li>
                <li>Deskripsi disarankan minimal 20 kata</li>
            </ul>
        </div>

        <div class="detail-card">
            <div class="detail-num">04</div>
            <h3>Kirim & Pantau Status</h3>
            <p>Setelah semua data terisi, klik <strong>Kirim Laporan</strong>. Laporan akan diverifikasi admin sebelum diteruskan ke instansi terkait. Pantau status kapan saja di halaman <strong>Laporan Saya</strong>.</p>
            <ul>
                <li>Status laporan: Aktif → Diproses → Selesai</li>
                <li>Notifikasi perubahan status dikirim via email</li>
                <li>Laporan selesai dapat diberi ulasan</li>
            </ul>
        </div>

    </div>

</section>

@endsection