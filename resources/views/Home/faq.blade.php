@extends('Layouts.HomeLayout.base_layout')

@section('title', 'FAQ - Pertanyaan Umum')

@section('content')

<style>
    /* =============================================
       FAQ PAGE — menggunakan CSS variable root
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

    /* ===== FAQ SECTION ===== */
    .faq-section {
        background: var(--bg-light);
        padding: 72px 5%;
    }

    .faq-grid {
        max-width: 960px;
        margin: 0 auto 48px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .faq-item {
        background: var(--text-white);
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px 22px;
        cursor: pointer;
        transition: box-shadow 0.25s, border-color 0.25s;
        align-self: start;
    }

    .faq-item:hover {
        box-shadow: 0 6px 20px rgba(11, 34, 64, 0.07);
    }

    .faq-item.active {
        border-color: var(--accent-blue);
        box-shadow: 0 6px 20px rgba(19, 58, 104, 0.1);
    }

    .faq-q {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .faq-q span {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-blue);
        line-height: 1.5;
        flex: 1;
    }

    .faq-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: var(--bg-light);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .faq-icon i {
        font-size: 11px;
        color: var(--accent-blue);
        transition: transform 0.3s ease;
    }

    .faq-icon.open {
        background: var(--accent-blue);
        border-color: var(--accent-blue);
    }

    .faq-icon.open i {
        color: var(--text-white);
        transform: rotate(45deg);
    }

    .faq-divider {
        height: 1px;
        background: #e2e8f0;
        margin: 14px 0;
        display: none;
    }

    .faq-item.active .faq-divider {
        display: block;
    }

    .faq-a {
        font-size: 13.5px;
        color: #4f4f4f;
        line-height: 1.75;
        display: none;
    }

    .faq-a.show {
        display: block;
    }

    /* ===== CONTACT BAR ===== */
    .faq-contact-bar {
        max-width: 960px;
        margin: 0 auto;
        background: var(--primary-blue);
        border-radius: 14px;
        padding: 28px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .faq-contact-bar h3 {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-white);
        margin-bottom: 6px;
    }

    .faq-contact-bar p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
        margin: 0;
    }

    .faq-contact-bar .btn-cta-secondary {
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .static-page-hero { padding: 40px 16px 36px; }
        .static-page-hero h1 { font-size: 24px; }
        .static-page-hero p { font-size: 13px; }

        .faq-section { padding: 48px 16px; }
        .faq-grid { grid-template-columns: 1fr; }

        .faq-contact-bar {
            flex-direction: column;
            align-items: flex-start;
            padding: 22px 20px;
            gap: 16px;
        }

        .faq-contact-bar .btn-cta-secondary {
            width: 100%;
            justify-content: center;
        }
    }
</style>

{{-- ===== HERO ===== --}}
<div class="static-page-hero">
    <span class="sub-title">PERTANYAAN UMUM</span>
    <h1>Ada yang Ingin Ditanyakan?</h1>
    <p>Temukan jawaban atas pertanyaan yang paling sering diajukan warga.</p>
</div>

{{-- ===== FAQ SECTION ===== --}}
<section class="faq-section">

    @php
    $faqs = [
        [
            'q' => 'Apakah perlu mendaftar untuk membuat laporan?',
            'a' => 'Ya, pendaftaran diperlukan agar laporan dapat ditindaklanjuti dan Anda dapat memantau status laporan secara real-time. Proses daftar hanya membutuhkan email dan password.',
        ],
        [
            'q' => 'Berapa lama laporan akan ditindaklanjuti?',
            'a' => 'Waktu respons bervariasi tergantung kategori. Laporan darurat seperti bencana ditargetkan respons dalam 1×24 jam, sedangkan masalah infrastruktur umum dalam 3–7 hari kerja.',
        ],
        [
            'q' => 'Apakah saya bisa melaporkan masalah di luar Bekasi?',
            'a' => 'Saat ini platform ini hanya melayani wilayah Kota dan Kabupaten Bekasi. Laporan di luar wilayah tersebut tidak dapat diproses oleh instansi terkait.',
        ],
        [
            'q' => 'Bagaimana cara mendukung laporan warga lain?',
            'a' => 'Anda dapat memberikan vote pada laporan yang dianggap penting. Semakin banyak vote, laporan akan mendapat prioritas lebih tinggi untuk ditangani oleh instansi terkait.',
        ],
        [
            'q' => 'Foto apa saja yang perlu dilampirkan?',
            'a' => 'Lampirkan foto yang jelas menunjukkan kondisi masalah dari beberapa sudut. Format yang didukung: JPG, PNG, dan WEBP dengan ukuran maksimal 5MB per foto. Minimal 1 foto wajib dilampirkan.',
        ],
        [
            'q' => 'Apakah identitas pelapor dijaga kerahasiaannya?',
            'a' => 'Ya. Identitas pelapor hanya diketahui oleh admin dan instansi terkait. Data pribadi Anda tidak akan ditampilkan secara publik di halaman laporan manapun.',
        ],
        [
            'q' => 'Bagaimana jika laporan saya ditolak?',
            'a' => 'Laporan dapat ditolak jika tidak sesuai kategori, lokasi di luar wilayah layanan, atau foto tidak jelas. Anda akan mendapat notifikasi disertai alasan penolakan dan dapat mengajukan laporan baru.',
        ],
        [
            'q' => 'Apakah ada batas jumlah laporan yang bisa dibuat?',
            'a' => 'Tidak ada batasan jumlah laporan. Namun pastikan setiap laporan benar-benar valid dan belum pernah dilaporkan sebelumnya oleh warga lain untuk menghindari duplikasi.',
        ],
        [
            'q' => 'Bisakah saya mengedit laporan yang sudah dikirim?',
            'a' => 'Laporan yang sudah dikirim tidak dapat diedit langsung. Namun jika ada informasi yang perlu dikoreksi, Anda dapat menghubungi admin melalui fitur komentar di halaman detail laporan.',
        ],
        [
            'q' => 'Bagaimana cara menghapus laporan?',
            'a' => 'Laporan yang sudah diproses tidak dapat dihapus oleh pengguna. Untuk laporan yang masih berstatus Aktif, Anda dapat menghubungi admin jika ada kesalahan yang perlu ditindaklanjuti.',
        ],
    ];
    @endphp

    <div class="faq-grid">
        @foreach($faqs as $i => $item)
            <div class="faq-item {{ $i === 0 ? 'active' : '' }}">
                <div class="faq-q">
                    <span>{{ $item['q'] }}</span>
                    <div class="faq-icon {{ $i === 0 ? 'open' : '' }}">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                </div>
                <div class="faq-divider"></div>
                <div class="faq-a {{ $i === 0 ? 'show' : '' }}">
                    {{ $item['a'] }}
                </div>
            </div>
        @endforeach
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

<script>
    // Accordion FAQ toggle
    document.querySelectorAll('.faq-item').forEach(item => {
        item.querySelector('.faq-q').addEventListener('click', () => {
            const isActive = item.classList.contains('active');

            // Tutup semua
            document.querySelectorAll('.faq-item').forEach(el => {
                el.classList.remove('active');
                el.querySelector('.faq-a').classList.remove('show');
                el.querySelector('.faq-icon').classList.remove('open');
            });

            // Buka yang diklik (toggle)
            if (!isActive) {
                item.classList.add('active');
                item.querySelector('.faq-a').classList.add('show');
                item.querySelector('.faq-icon').classList.add('open');
            }
        });
    });
</script>

@endsection