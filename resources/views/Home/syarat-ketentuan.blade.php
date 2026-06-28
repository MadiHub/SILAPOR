@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Syarat & Ketentuan')

@section('content')

<div class="static-page-hero">
    <span class="sub-title">DOKUMEN RESMI</span>
    <h1>Syarat & Ketentuan</h1>
    <p>Terakhir diperbarui: {{ \Carbon\Carbon::parse('2025-01-01')->translatedFormat('d F Y') }}</p>
</div>

<section class="static-prose-section">
    <div class="prose-container">

        <div class="prose-intro">
            Dengan menggunakan platform ini, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan yang berlaku. Harap baca dokumen ini dengan seksama sebelum menggunakan layanan.
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">1</span> Ketentuan Umum</h2>
            <p>Platform Pengaduan Masyarakat Bekasi adalah layanan digital yang disediakan untuk memfasilitasi warga dalam melaporkan masalah infrastruktur dan kebencanaan kepada instansi pemerintah terkait. Layanan ini bersifat gratis dan ditujukan khusus untuk warga wilayah Kota dan Kabupaten Bekasi.</p>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">2</span> Persyaratan Pengguna</h2>
            <p>Untuk menggunakan layanan ini, Anda harus:</p>
            <ul>
                <li>Berusia minimal 17 tahun atau mendapat izin dari orang tua/wali</li>
                <li>Memiliki alamat email yang valid dan aktif</li>
                <li>Berdomisili atau memiliki kepentingan di wilayah Kota/Kabupaten Bekasi</li>
                <li>Tidak menggunakan layanan untuk tujuan yang melanggar hukum</li>
            </ul>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">3</span> Kewajiban Pengguna</h2>
            <p>Pengguna wajib:</p>
            <ul>
                <li>Memberikan informasi yang akurat, jujur, dan tidak menyesatkan dalam setiap laporan</li>
                <li>Hanya melaporkan masalah yang benar-benar ada dan dapat diverifikasi</li>
                <li>Tidak mengunggah konten yang mengandung SARA, pornografi, atau ujaran kebencian</li>
                <li>Tidak membuat laporan duplikat atau palsu yang dapat menyita sumber daya pemerintah</li>
                <li>Menjaga kerahasiaan kredensial akun dan tidak membagikannya kepada orang lain</li>
            </ul>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">4</span> Konten Laporan</h2>
            <p>Setiap laporan yang dikirimkan menjadi milik platform dan dapat digunakan untuk kepentingan perbaikan layanan publik. Anda memberikan izin kepada platform untuk meneruskan konten laporan (termasuk foto dan deskripsi) kepada instansi pemerintah terkait guna penanganan masalah.</p>
            <p style="margin-top:10px;">Laporan yang mengandung konten tidak pantas, informasi palsu, atau melanggar ketentuan ini dapat dihapus sewaktu-waktu tanpa pemberitahuan sebelumnya.</p>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">5</span> Batasan Tanggung Jawab</h2>
            <p>Platform ini hanya berfungsi sebagai perantara antara warga dan instansi pemerintah. Kami tidak bertanggung jawab atas:</p>
            <ul>
                <li>Keterlambatan atau kegagalan instansi pemerintah dalam menindaklanjuti laporan</li>
                <li>Kerugian yang timbul akibat penggunaan atau ketidakmampuan menggunakan layanan</li>
                <li>Keakuratan informasi yang diberikan oleh pengguna lain di platform</li>
            </ul>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">6</span> Penangguhan Akun</h2>
            <p>Kami berhak menangguhkan atau menghapus akun pengguna yang terbukti melanggar ketentuan ini, membuat laporan palsu, atau menyalahgunakan platform tanpa kewajiban memberikan kompensasi dalam bentuk apapun.</p>
        </div>

        <div class="prose-block">
            <h2><span class="prose-num">7</span> Hukum yang Berlaku</h2>
            <p>Syarat dan ketentuan ini tunduk pada hukum yang berlaku di Republik Indonesia. Setiap perselisihan yang timbul akan diselesaikan melalui jalur musyawarah mufakat, dan jika tidak tercapai kesepakatan, akan diselesaikan melalui pengadilan yang berwenang di Bekasi.</p>
        </div>

        <div class="prose-contact">
            <i class="fa-solid fa-scale-balanced"></i>
            <div>
                <strong>Ada pertanyaan mengenai ketentuan ini?</strong>
                <p>Hubungi kami di <a href="mailto:pengaduan@bekasi.go.id">pengaduan@bekasi.go.id</a></p>
            </div>
        </div>

    </div>
</section>

@endsection