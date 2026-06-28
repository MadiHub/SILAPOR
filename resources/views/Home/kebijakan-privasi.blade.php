@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Kebijakan Privasi')

@section('content')

<style>
    /* =============================================
       KEBIJAKAN PRIVASI — menggunakan CSS variable
       root dari base_layout
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
        font-size: 14px;
        color: rgba(255, 255, 255, 0.5);
        position: relative;
    }

    /* ===== PROSE SECTION ===== */
    .static-prose-section {
        background: var(--text-white);
        padding: 72px 5%;
    }

    .prose-container {
        max-width: 780px;
        margin: 0 auto;
    }

    /* Blok intro dengan aksen oranye kiri */
    .prose-intro {
        font-size: 15px;
        color: #4f4f4f;
        line-height: 1.8;
        padding: 20px 24px;
        background: var(--bg-light);
        border-left: 4px solid var(--brand-orange);
        border-radius: 0 8px 8px 0;
        margin-bottom: 40px;
    }

    /* Setiap blok konten */
    .prose-block {
        margin-bottom: 36px;
        padding-bottom: 36px;
        border-bottom: 1px solid #e2e8f0;
    }

    .prose-block:last-of-type {
        border-bottom: none;
    }

    .prose-block h2 {
        font-size: 17px;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    /* Nomor bulat biru */
    .prose-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--accent-blue);
        color: var(--text-white);
        font-size: 13px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .prose-block p {
        font-size: 14px;
        color: #4f4f4f;
        line-height: 1.8;
        margin-bottom: 12px;
    }

    .prose-block ul {
        padding-left: 0;
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 8px;
    }

    .prose-block ul li {
        font-size: 14px;
        color: #64748b;
        line-height: 1.7;
        padding-left: 18px;
        position: relative;
    }

    /* bullet oranye custom */
    .prose-block ul li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 8px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: var(--brand-orange);
    }

    /* ===== CONTACT BAR bawah ===== */
    .prose-contact {
        display: flex;
        align-items: center;
        gap: 18px;
        background: var(--primary-blue);
        border-radius: 12px;
        padding: 22px 26px;
        margin-top: 40px;
    }

    .prose-contact i {
        font-size: 26px;
        color: var(--brand-orange);
        flex-shrink: 0;
    }

    .prose-contact strong {
        font-size: 14px;
        font-weight: 700;
        color: var(--text-white);
        display: block;
        margin-bottom: 4px;
    }

    .prose-contact p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.65);
        margin: 0;
    }

    .prose-contact a {
        color: var(--brand-orange);
        text-decoration: none;
        font-weight: 600;
    }

    .prose-contact a:hover {
        text-decoration: underline;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .static-page-hero { padding: 40px 16px 36px; }
        .static-page-hero h1 { font-size: 24px; }

        .static-prose-section { padding: 48px 16px; }

        .prose-intro { font-size: 14px; padding: 16px 18px; }
        .prose-block h2 { font-size: 15px; }
        .prose-block p,
        .prose-block ul li { font-size: 13px; }

        .prose-contact {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            padding: 20px;
        }

        .prose-contact i { font-size: 22px; }
    }
</style>

{{-- ===== HERO ===== --}}
<div class="static-page-hero">
    <span class="sub-title">DOKUMEN RESMI</span>
    <h1>Kebijakan Privasi</h1>
    <p>Terakhir diperbarui: {{ \Carbon\Carbon::parse('2025-01-01')->translatedFormat('d F Y') }}</p>
</div>

{{-- ===== PROSE SECTION ===== --}}
<section class="static-prose-section">
    <div class="prose-container">

        <div class="prose-intro">
            Platform Pengaduan Masyarakat Bekasi berkomitmen untuk melindungi privasi dan keamanan data pribadi setiap pengguna. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda.
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">1</span> Data yang Kami Kumpulkan</h2>
            <p>Kami mengumpulkan data yang Anda berikan secara langsung saat mendaftar dan menggunakan layanan ini, meliputi:</p>
            <ul>
                <li><strong>Data identitas</strong> - nama lengkap dan alamat email</li>
                <li><strong>Data laporan</strong> - judul, deskripsi, foto, dan koordinat lokasi masalah yang Anda laporkan</li>
                <li><strong>Data aktivitas</strong> - riwayat laporan, vote, dan komentar yang Anda buat di platform</li>
                <li><strong>Data teknis</strong> - alamat IP, jenis browser, dan waktu akses untuk keperluan keamanan sistem</li>
            </ul>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">2</span> Cara Kami Menggunakan Data</h2>
            <p>Data yang dikumpulkan digunakan untuk:</p>
            <ul>
                <li>Memproses dan meneruskan laporan pengaduan ke instansi pemerintah terkait</li>
                <li>Mengirimkan notifikasi pembaruan status laporan melalui email</li>
                <li>Meningkatkan kualitas layanan dan pengalaman pengguna platform</li>
                <li>Mencegah penyalahgunaan dan menjaga keamanan sistem</li>
                <li>Menyusun statistik dan laporan agregat yang tidak mengidentifikasi individu</li>
            </ul>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">3</span> Berbagi Data dengan Pihak Ketiga</h2>
            <p>Kami tidak menjual atau menyewakan data pribadi Anda kepada pihak manapun. Data laporan (judul, deskripsi, foto, dan lokasi) dapat dibagikan kepada:</p>
            <ul>
                <li>Dinas atau instansi pemerintah Kota/Kabupaten Bekasi yang berwenang menangani laporan</li>
                <li>Pengguna lain platform secara terbatas (hanya data laporan yang bersifat publik, bukan identitas pelapor)</li>
            </ul>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">4</span> Keamanan Data</h2>
            <p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang wajar untuk melindungi data Anda, termasuk enkripsi data saat transit (HTTPS) dan penyimpanan password menggunakan algoritma hashing yang aman. Namun, tidak ada sistem yang sepenuhnya bebas risiko.</p>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">5</span> Hak Pengguna</h2>
            <p>Anda memiliki hak untuk:</p>
            <ul>
                <li>Mengakses data pribadi yang kami simpan tentang Anda</li>
                <li>Memperbarui atau mengoreksi data yang tidak akurat melalui halaman Profil</li>
                <li>Meminta penghapusan akun dan data terkait dengan menghubungi admin</li>
            </ul>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">6</span> Perubahan Kebijakan</h2>
            <p>Kami dapat memperbarui kebijakan ini sewaktu-waktu. Perubahan signifikan akan diberitahukan melalui email atau notifikasi di platform. Penggunaan layanan setelah tanggal efektif perubahan dianggap sebagai persetujuan Anda terhadap kebijakan yang diperbarui.</p>
        </div>

        <div class="prose-contact">
            <i class="fa-solid fa-envelope"></i>
            <div>
                <strong>Pertanyaan terkait privasi?</strong>
                <p>Hubungi kami di <a href="mailto:pengaduan@bekasi.go.id">pengaduan@bekasi.go.id</a></p>
            </div>
        </div>

    </div>
</section>

@endsection