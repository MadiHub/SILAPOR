@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Buat Laporan')

@section('content')

<style type="text/tailwindcss">
body {
    background: radial-gradient(circle at top right, #111e36, #070c16);
    color: #E2E8F0;
}

/* Efek Glassmorphic untuk komponen */
.glass-card {
    background: rgba(17, 28, 46, 0.6);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Efek Hover Premium untuk Card Kategori */
.category-card:hover {
    border-color: rgba(249, 115, 22, 0.4);
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -10px rgba(249, 115, 22, 0.25);
}
.category-card:active {
    transform: translateY(-1px);
}

/* Transisi Halus untuk Elemen Input Form */
.custom-input {
    transition: all 0.2s ease;
    border: 1px solid rgba(255, 255, 255, 0.05) !important;
}
.custom-input:focus {
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15) !important;
    border-color: #f97316 !important;
}

/* Animasi Muncul Halus (Pop-In) untuk Preview Foto */
@keyframes popIn {
    0% { opacity: 0; transform: scale(0.9); }
    100% { opacity: 1; transform: scale(1); }
}
.animate-pop-in {
    animation: popIn 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

@keyframes bellSwing {
    0%, 100% { transform: rotate(0); }
    20% { transform: rotate(15deg); }
    40% { transform: rotate(-10deg); }
    60% { transform: rotate(5deg); }
    80% { transform: rotate(-5deg); }
}
.group:hover .animate-swing {
    animation: bellSwing 0.6s ease-in-out;
}
/* loader */
.loader {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(255,255,255,0.15);
    border-top: 3px solid #f97316;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.category-card {
    transition: all 0.2s ease;
    border: 1px solid rgba(255,255,255,0.05);
}

/* state aktif */
.category-card.active {
    border-color: rgba(249, 115, 22, 0.8);
    background: rgba(249, 115, 22, 0.08);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -10px rgba(249, 115, 22, 0.3);
}
</style>

<!-- LOADING -->
<div id="loadingOverlay"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-[#111C2E] px-6 py-5 rounded-xl text-center">
        <div class="loader mx-auto mb-3"></div>
        <p id="loadingText">Loading...</p>
    </div>
</div>

<main class="max-w-4xl mx-auto px-4 mt-10 mb-10">

<form id="reportForm" class="space-y-6" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="latitude" name="latitude">
    <input type="hidden" id="longitude" name="longitude">

    <!-- KATEGORI -->
    <div class="flex items-center space-x-2 border-b border-gray-800 pb-2 mb-4">
        <span class="text-xs font-mono text-orange-500 font-bold">01</span>
        <h3 class="text-md font-bold text-gray-300">Kategori Masalah</h3>
    </div>
    <div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach ($problem_categories as $category)
            <label class="glass-card category-card rounded-xl p-4 flex flex-col items-center justify-center cursor-pointer relative group">
                <input type="radio" name="category_id" value="{{ $category->id }}" class="hidden" required>
                <div class="text-center">
                    <i class="{{ $category->icon }}"></i>
                    <div class="text-xs mt-2">{{ $category->name }}</div>
                </div>
            </label>
            @endforeach
        </div>
    </div>

    <div>
        <div class="flex items-center space-x-2 border-b border-gray-800 pb-2 mb-4">
            <span class="text-xs font-mono text-orange-500 font-bold">02</span>
            <h3 class="text-md font-bold text-gray-300">Cek Lokasi</h3>
        </div>
        <!-- STATUS -->
        <div id="statusLokasi"
            class="mb-4 text-sm px-3 py-2 rounded-xl bg-yellow-500/10 text-yellow-400">
            📍 Lokasi belum dicek
        </div>
        <!-- CEK LOKASI -->
        <button type="button"
            onclick="cekLokasi()"
            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-3 rounded-xl flex items-center justify-center gap-2 transition duration-200">
            <i class="fa-solid fa-location-crosshairs"></i>
            Cek Lokasi Saya
        </button>
    </div>

    <div>
        <div class="flex items-center space-x-2 border-b border-gray-800 pb-2 mb-4">
            <span class="text-xs font-mono text-orange-500 font-bold">03</span>
            <h3 class="text-md font-bold text-gray-300">Detail Laporan</h3>
        </div>
        <!-- DETAIL -->
        <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Lokasi Kejadian <span
                            class="text-red-500">*</span></label>
                    <div class="relative">
                        <i class="fa-solid fa-location-dot absolute left-4 top-3.5 text-gray-500"></i>
                        <input id="lokasiText" name="address" type="text" placeholder="RT 03/RW 07, Kel. Sukamaju, Kec. Cilandak"
                            class="w-full glass-card custom-input rounded-xl pl-10 pr-4 py-3 text-sm focus:outline-none text-white bg-[#111C2E]/50 border-0">
                    </div>
                    <div class="text-right text-xs text-gray-500 mt-1 transition-colors" id="lokasiCount">0/100
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Judul Masalah <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" maxlength="120"
                        placeholder="Contoh: Jalan berlubang di Jl. Merdeka No. 12"
                        class="w-full glass-card custom-input rounded-xl px-4 py-3 text-sm focus:outline-none text-white bg-[#111C2E]/50 border-0"
                        required>
                    <div class="text-right text-xs text-gray-500 mt-1 transition-colors" id="titleCount">0/120</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Deskripsi Masalah <span
                            class="text-red-500">*</span></label>
                    <textarea id="description" name="description" maxlength="1000" rows="4"
                        placeholder="Jelaskan masalah secara rinci — kapan terjadi, dampak yang ditimbulkan, kondisi saat ini, dan informasi penting lainnya..."
                        class="w-full glass-card custom-input rounded-xl px-4 py-3 text-sm focus:outline-none text-white resize-none bg-[#111C2E]/50 border-0"
                        required></textarea>
                    <div class="text-right text-xs text-gray-500 mt-1 transition-colors" id="descriptionCount">0/1000
                </div>
            </div>
        </div>
        
        <div>
            <div>
                <div class="flex items-center space-x-2 border-b border-gray-800 pb-2 mb-2">
                    <span class="text-xs font-mono text-orange-500 font-bold">04</span>
                    <h3 class="text-md font-bold text-gray-300">Foto Masalah</h3>
                </div>
                <p class="text-xs text-gray-400 mb-4">Tambahkan foto sebagai bukti pendukung laporan (maks. 10 foto -
                    JPG / PNG / WEBP)</p>

                <div id="dropzone"
                    class="border-2 border-dashed border-gray-800 hover:border-gray-500 rounded-xl p-8 text-center bg-[#111C2E]/30 cursor-pointer transition-all duration-300 group">
                    <input type="file" id="fileInput" name="images[]" multiple accept="image/*" class="hidden">
                    <div class="flex flex-col items-center justify-center pointer-events-none">
                        <div
                            class="bg-[#1C2C42]/60 p-3 rounded-xl mb-3 shadow-inner group-hover:scale-110 group-hover:bg-[#253954]/80 transition-all duration-300">
                            <i
                                class="fa-solid fa-cloud-arrow-up text-xl text-gray-300 group-hover:text-orange-500 transition-colors"></i>
                        </div>
                        <p class="text-sm font-semibold text-white">Klik atau seret foto ke sini</p>
                        <p class="text-xs text-gray-500 mt-1">Format JPG, PNG, WEBP · Maks. 10 MB per foto</p>
                    </div>
                </div>

                <div id="previewGrid" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4 mt-4"></div>
            </div>

            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 pt-4">
                <button type="reset" id="btnReset"
                    class="w-full sm:w-1/3 border border-gray-800 text-gray-400 font-medium py-3 rounded-xl hover:bg-gray-800/60 hover:text-white active:scale-95 transition-all duration-200 text-sm cursor-pointer">
                    Reset Form
                </button>
                <button type="submit" id="submitBtn" 
                    class="w-full sm:w-2/3 bg-orange-500 text-white font-semibold py-3 rounded-xl hover:bg-orange-600 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-orange-500/10 hover:shadow-orange-600/20 text-sm flex items-center justify-center space-x-2 cursor-pointer group">
                    <i
                        class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    <span>Kirim Laporan Sekarang</span>
                </button>
        </div>

</form>
</main>

<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>

<script>

/* =========================
   STATE
========================= */
let lokasiValid = false;
let lokasiPolygon = null;
let polygonReady = false;
let gpsLock = false;
let selectedFiles = [];

/* =========================
   ELEMENT
========================= */
const form = document.getElementById('reportForm');
const submitBtn = document.getElementById('submitBtn');
const fileInput = document.getElementById('fileInput');
const dropzone = document.getElementById('dropzone');
const previewGrid = document.getElementById('previewGrid');
const categoryCards = document.querySelectorAll('.category-card');

categoryCards.forEach(card => {
    const radio = card.querySelector('input[type="radio"]');

    card.addEventListener('click', () => {
        // reset semua
        categoryCards.forEach(c => c.classList.remove('active'));

        // set active ke yang diklik
        card.classList.add('active');

        // tetap sync ke radio
        radio.checked = true;
    });
});

function toggleForm(disabled = true) {
    const elements = form.querySelectorAll('input, textarea, button');

    elements.forEach(el => {
        // skip tombol cek lokasi
        if (el.type === 'button' && el.innerText.includes('Cek Lokasi')) return;

        // skip reset kalau mau tetap aktif
        if (el.id === 'btnReset') return;

        el.disabled = disabled;

        if (disabled) {
            el.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            el.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    });

    // 🔥 HANDLE DROPZONE (UPLOAD FOTO)
    if (disabled) {
        dropzone.classList.add('opacity-50', 'cursor-not-allowed');
        dropzone.classList.remove('cursor-pointer');
    } else {
        dropzone.classList.remove('opacity-50', 'cursor-not-allowed');
        dropzone.classList.add('cursor-pointer');
    }
}

/* =========================
   STATUS UI
========================= */
function setStatus(msg, color) {
    const el = document.getElementById('statusLokasi');

    el.innerHTML = msg;

    el.className =
        "mb-4 text-sm px-3 py-2 rounded-xl " +
        (color === "green"
            ? "bg-green-500/10 text-green-400"
            : color === "red"
            ? "bg-red-500/10 text-red-400"
            : "bg-yellow-500/10 text-yellow-400");
}

/* =========================
   FORM LOCK
========================= */
function lockForm() {
    lokasiValid = false;
    toggleForm(true);
}

function unlockForm() {
    lokasiValid = true;
    toggleForm(false);
}

/* =========================
   LOADING
========================= */
function showLoading(text="Loading...") {
    document.getElementById('loadingText').innerText = text;
    document.getElementById('loadingOverlay').classList.remove('hidden');
    document.getElementById('loadingOverlay').classList.add('flex');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}

/* =========================
   REVERSE GEOCODING
========================= */
async function getAlamat(lat, lng) {
    try {
        const res = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`
        );

        const data = await res.json();

        return data.display_name || "Alamat tidak ditemukan";
    } catch (err) {
        return "Alamat tidak tersedia";
    }
}

/* =========================
   POLYGON
========================= */
async function loadPolygon() {
    try {
        showLoading("Memuat wilayah...");
        const res = await fetch('/api/polygon/get');
        const data = await res.json();

        lokasiPolygon = turf.polygon(data.coordinates);
        polygonReady = true;

    } catch (err) {
        setTimeout(loadPolygon, 3000);
    } finally {
        hideLoading();
    }
}

/* =========================
   CEK LOKASI
========================= */
function cekLokasi() {

    if (!polygonReady) {
        alert("Polygon belum siap");
        return;
    }

    if (gpsLock) return;
    gpsLock = true;

    setStatus("⏳ Mengecek lokasi...", "yellow");
    showLoading("Mengambil GPS...");

    navigator.geolocation.getCurrentPosition(
        async (pos) => {

            try {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                const point = turf.point([lng, lat]);
                const isInside = turf.booleanPointInPolygon(point, lokasiPolygon);

                if (isInside) {

                    setStatus("📍 Mengambil alamat...", "yellow");

                    // 🔥 ambil alamat
                    const alamat = await getAlamat(lat, lng);

                    document.getElementById('lokasiText').value = alamat;

                    setStatus("✔ Lokasi & alamat valid", "green");
                    unlockForm();

                } else {
                    setStatus("❌ Di luar area", "red");
                    lockForm();
                }

            } finally {
                gpsLock = false;
                hideLoading();
            }
        },

        (err) => {
            gpsLock = false;
            hideLoading();
            setStatus("❌ GPS error", "red");
        }
    );
}

/* =========================
   UPLOAD MULTI IMAGE
========================= */
dropzone.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', e => handleFiles(e.target.files));

dropzone.addEventListener('dragover', e => e.preventDefault());

dropzone.addEventListener('drop', e => {
    e.preventDefault();
    handleFiles(e.dataTransfer.files);
});

function handleFiles(files) {

    const arr = Array.from(files);

    if (selectedFiles.length + arr.length > 10) {
        alert("Maks 10 foto");
        return;
    }

    arr.forEach(f => {
        if (f.type.startsWith('image/')) {
            selectedFiles.push(f);
        }
    });

    renderPreview();
    syncInput();
}

/* PREVIEW */
function renderPreview() {

    previewGrid.innerHTML = '';

    selectedFiles.forEach((file, i) => {

        const reader = new FileReader();

        reader.onload = e => {
            const div = document.createElement('div');
            div.className = "relative";

            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-xl">
                <button type="button"
                    data-index="${i}"
                    class="delete absolute top-1 right-1 bg-red-500 px-2 text-white rounded">
                    X
                </button>
            `;

            previewGrid.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
}

/* DELETE */
previewGrid.addEventListener('click', e => {

    if (!e.target.classList.contains('delete')) return;

    const i = e.target.dataset.index;

    selectedFiles.splice(i, 1);

    renderPreview();
    syncInput();
});

/* SYNC INPUT */
function syncInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}

/* =========================
   SUBMIT
========================= */
// form.addEventListener('submit', e => {

//     e.preventDefault();

//     if (!lokasiValid) {
//         alert("Cek lokasi dulu!");
//         return;
//     }

//     const formData = new FormData(form);

//     selectedFiles.forEach(f => formData.append('images[]', f));

//     console.log("READY:", formData);

//     alert("Siap kirim 🚀");
// });

/* =========================
   INIT
========================= */
document.addEventListener('DOMContentLoaded', () => {
    loadPolygon();
    lockForm();
});

</script>

@endsection