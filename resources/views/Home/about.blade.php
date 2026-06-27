@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Tentang SiLapor - Sistem Laporan Darurat Bekasi')

@section('content')

{{-- ===== HERO ABOUT ===== --}}
<section class="relative overflow-hidden" style="background: radial-gradient(circle at top right, #112d52 0%, var(--primary-blue) 100%);">
    <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 0); background-size: 24px 24px;"></div>

    <div class="relative max-w-5xl mx-auto px-5 py-20 text-center">
        <span class="inline-flex items-center gap-2 bg-white/10 border border-white/15 text-white/80 text-xs px-4 py-1.5 rounded-full mb-6">
            <span class="w-2 h-2 bg-green-500 rounded-full" style="box-shadow:0 0 8px #27ae60;"></span>
            Tentang Kami
        </span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-5">
            Satu Laporan, <span class="text-[var(--brand-orange)]">Seribu Perubahan</span> untuk Bekasi
        </h1>
        <p class="text-white/65 text-sm md:text-base leading-relaxed max-w-2xl mx-auto">
            SiLapor adalah jembatan digital antara warga dan Pemerintah Kota Bekasi —
            tempat setiap kerusakan jalan, gangguan layanan publik, dan keluhan infrastruktur
            bisa dilaporkan, didukung bersama, dan ditangani secara transparan.
        </p>
    </div>
</section>

{{-- ===== LATAR BELAKANG / WHY ===== --}}
<section class="max-w-6xl mx-auto px-5 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-[var(--brand-orange)] text-xs font-bold tracking-widest uppercase block mb-2">Mengapa SiLapor Ada</span>
            <h2 class="text-2xl md:text-3xl font-bold text-[var(--primary-blue)] mb-4">
                Karena Setiap Warga Berhak Didengar
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                Selama ini, banyak laporan warga — jalan berlubang, lampu jalan mati, saluran air
                tersumbat — hilang begitu saja karena tidak ada saluran yang jelas dan terbuka.
                SiLapor hadir untuk mengubah itu.
            </p>
            <p class="text-gray-600 text-sm leading-relaxed">
                Dengan SiLapor, setiap laporan tercatat, terlihat oleh publik, dan dapat dipantau
                statusnya secara langsung — dari mulai dilaporkan, diproses, hingga selesai
                ditangani oleh dinas terkait di Pemerintah Kota Bekasi.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background:rgba(255,118,27,0.1);">
                    <i class="fa-solid fa-bullhorn text-[var(--brand-orange)]"></i>
                </div>
                <h3 class="font-bold text-[var(--primary-blue)] text-sm mb-1">Transparan</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Status laporan terlihat publik, bukan sekadar masuk email yang tak terjawab.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background:rgba(47,128,237,0.1);">
                    <i class="fa-solid fa-users text-[var(--sys-blue)]"></i>
                </div>
                <h3 class="font-bold text-[var(--primary-blue)] text-sm mb-1">Partisipatif</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Warga bisa memberi dukungan pada laporan lain agar lebih cepat diprioritaskan.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background:rgba(39,174,96,0.1);">
                    <i class="fa-solid fa-bolt text-[var(--sys-green)]"></i>
                </div>
                <h3 class="font-bold text-[var(--primary-blue)] text-sm mb-1">Responsif</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Dinas terkait menerima dan menindaklanjuti laporan langsung dari sistem.</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background:rgba(11,34,64,0.08);">
                    <i class="fa-solid fa-location-dot text-[var(--primary-blue)]"></i>
                </div>
                <h3 class="font-bold text-[var(--primary-blue)] text-sm mb-1">Berbasis Lokasi</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Setiap laporan dipetakan secara real-time agar mudah dipantau wilayahnya.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== CARA KERJA / FLOW ===== --}}
<section class="py-16" style="background-color: var(--bg-light);">
    <div class="max-w-6xl mx-auto px-5">
        <div class="text-center mb-14">
            <span class="text-[var(--brand-orange)] text-xs font-bold tracking-widest uppercase block mb-2">Cara Kerja</span>
            <h2 class="text-2xl md:text-3xl font-bold text-[var(--primary-blue)]">Dari Laporan Sampai Solusi</h2>
            <p class="text-gray-500 text-sm mt-3 max-w-xl mx-auto">Empat langkah sederhana yang menghubungkan suara warga dengan tindakan nyata Pemerintah Kota Bekasi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative">
                <div class="w-12 h-12 rounded-xl bg-[var(--sys-red)] text-white flex items-center justify-center font-bold mb-5">1</div>
                <h3 class="font-bold text-[var(--primary-blue)] text-sm mb-2">Warga Melapor</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Warga membuat laporan lengkap dengan foto, lokasi, dan kategori masalah langsung dari aplikasi.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative">
                <div class="w-12 h-12 rounded-xl bg-[var(--sys-orange)] text-white flex items-center justify-center font-bold mb-5">2</div>
                <h3 class="font-bold text-[var(--primary-blue)] text-sm mb-2">Warga Lain Mendukung</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Laporan muncul di peta dan feed publik — warga lain bisa memberi vote dukungan agar makin diprioritaskan.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative">
                <div class="w-12 h-12 rounded-xl bg-[var(--sys-blue)] text-white flex items-center justify-center font-bold mb-5">3</div>
                <h3 class="font-bold text-[var(--primary-blue)] text-sm mb-2">Pemda Memproses</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Dinas terkait di Pemkot Bekasi menerima laporan, mengubah status, dan mencatat progres penanganan.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 relative">
                <div class="w-12 h-12 rounded-xl bg-[var(--sys-green)] text-white flex items-center justify-center font-bold mb-5">4</div>
                <h3 class="font-bold text-[var(--primary-blue)] text-sm mb-2">Laporan Selesai</h3>
                <p class="text-gray-500 text-xs leading-relaxed">Status berubah menjadi selesai, warga mendapat kepastian, dan riwayatnya tetap tercatat secara transparan.</p>
            </div>

        </div>
    </div>
</section>

{{-- ===== STATUS LEGEND INFO ===== --}}
<section class="max-w-6xl mx-auto px-5 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div class="order-2 lg:order-1">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-5">Status Laporan</h3>
                <ul class="flex flex-col gap-4 text-sm">
                    <li class="flex items-center gap-3">
                        <span class="status-badge aktif px-3 py-1 rounded text-xs font-bold">AKTIF</span>
                        <span class="text-gray-600">Laporan baru masuk, menunggu ditindaklanjuti dinas terkait.</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="status-badge proses px-3 py-1 rounded text-xs font-bold">DIPROSES</span>
                        <span class="text-gray-600">Sedang ditangani, progres dicatat secara berkala oleh Pemda.</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="status-badge selesai px-3 py-1 rounded text-xs font-bold">SELESAI</span>
                        <span class="text-gray-600">Masalah telah ditangani dan laporan ditutup.</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="order-1 lg:order-2">
            <span class="text-[var(--brand-orange)] text-xs font-bold tracking-widest uppercase block mb-2">Pantau Bersama</span>
            <h2 class="text-2xl md:text-3xl font-bold text-[var(--primary-blue)] mb-4">
                Tidak Ada Laporan yang Hilang Begitu Saja
            </h2>
            <p class="text-gray-600 text-sm leading-relaxed mb-4">
                Setiap laporan yang masuk ke SiLapor punya status yang jelas dan dapat dipantau
                siapa saja. Tidak ada lagi keluhan yang dikirim lalu hilang tanpa kabar.
            </p>
            <p class="text-gray-600 text-sm leading-relaxed">
                Warga dapat melihat riwayat progres penanganan, sementara Pemda dapat
                memprioritaskan laporan berdasarkan jumlah dukungan dan urgensi di lapangan.
            </p>
        </div>
    </div>
</section>

{{-- ===== CTA PENUTUP ===== --}}
<section class="px-5 pb-20">
    <div class="max-w-5xl mx-auto rounded-3xl px-10 py-14 text-center relative overflow-hidden"
         style="background: linear-gradient(135deg, var(--accent-blue), var(--primary-blue));">
        <div class="absolute inset-0 pointer-events-none" style="background-image: radial-gradient(rgba(255,255,255,0.06) 1px, transparent 0); background-size: 24px 24px;"></div>
        <div class="relative">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">Suaramu Penting untuk Bekasi</h2>
            <p class="text-white/65 text-sm mb-8 max-w-lg mx-auto">
                Lihat kerusakan jalan, fasilitas umum rusak, atau gangguan layanan? Laporkan sekarang dan jadi bagian dari perubahan.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('reports.index') }}"
                   class="inline-flex items-center gap-2 px-7 py-3 rounded-lg font-semibold text-sm text-white shadow-lg transition hover:-translate-y-0.5"
                   style="background: linear-gradient(90deg, #ff761b, #ff944d); box-shadow: 0 4px 15px rgba(255, 118, 27, 0.4);">
                    <i class="fa-regular fa-file-lines"></i> Buat Laporan Sekarang
                </a>
                <a href="{{ route('home.index') }}#map-section"
                   class="inline-flex items-center gap-2 px-7 py-3 rounded-lg font-medium text-sm text-white border border-white/25 bg-white/5 hover:bg-white/10 transition">
                    <i class="fa-solid fa-map-location-dot"></i> Lihat Peta Laporan
                </a>
            </div>
        </div>
    </div>
</section>

@endsection