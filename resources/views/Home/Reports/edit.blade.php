@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Edit Laporan')

@section('content')

<style type="text/tailwindcss">
body {
    background: radial-gradient(circle at top right, #111e36, #070c16);
    color: #E2E8F0;
}

.glass-card {
    background: rgba(17, 28, 46, 0.6);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.category-card:hover {
    border-color: rgba(249, 115, 22, 0.4);
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -10px rgba(249, 115, 22, 0.25);
}
.category-card:active {
    transform: translateY(-1px);
}

.custom-input {
    transition: all 0.2s ease;
    border: 1px solid rgba(255, 255, 255, 0.05) !important;
}
.custom-input:focus {
    box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15) !important;
    border-color: #f97316 !important;
}

@keyframes popIn {
    0% { opacity: 0; transform: scale(0.9); }
    100% { opacity: 1; transform: scale(1); }
}
.animate-pop-in {
    animation: popIn 0.25s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
}

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
.category-card.active {
    border-color: rgba(249, 115, 22, 0.8);
    background: rgba(249, 115, 22, 0.08);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -10px rgba(249, 115, 22, 0.3);
}

/* badge foto lama yang ditandai untuk dihapus */
.marked-delete {
    opacity: 0.35;
    filter: grayscale(0.6);
}
.marked-delete .undo-overlay {
    display: flex !important;
}
</style>

<!-- LOADING -->
<div id="loadingOverlay"
     class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50">
    <div class="bg-[#111C2E] px-6 py-5 rounded-xl text-center">
        <div class="loader mx-auto mb-3"></div>
        <p id="loadingText">Menyimpan...</p>
    </div>
</div>

<main class="max-w-4xl mx-auto px-4 mt-10 mb-10">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-white">Edit Laporan</h1>
        <a href="{{ route('reports.me') }}" class="text-sm text-gray-400 hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-4 bg-red-500/10 text-red-400 text-sm px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="editForm" class="space-y-6" action="{{ route('reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- KATEGORI -->
        <div class="flex items-center space-x-2 border-b border-gray-800 pb-2 mb-4">
            <span class="text-xs font-mono text-orange-500 font-bold">01</span>
            <h3 class="text-md font-bold text-gray-300">Kategori Masalah</h3>
        </div>
        <div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach ($problem_categories as $category)
                <label class="glass-card category-card rounded-xl p-4 flex flex-col items-center justify-center cursor-pointer relative group {{ $report->category_id === $category->id ? 'active' : '' }}">
                    <input type="radio" name="category_id" value="{{ $category->id }}" class="hidden"
                        {{ $report->category_id === $category->id ? 'checked' : '' }} required>
                    <div class="text-center">
                        <i class="{{ $category->icon }}"></i>
                        <div class="text-xs mt-2">{{ $category->name }}</div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- LOKASI (READONLY - tidak bisa diubah saat edit) -->
        <div>
            <div class="flex items-center space-x-2 border-b border-gray-800 pb-2 mb-4">
                <span class="text-xs font-mono text-orange-500 font-bold">02</span>
                <h3 class="text-md font-bold text-gray-300">Lokasi Kejadian</h3>
            </div>
            <div class="mb-2 text-sm px-3 py-2 rounded-xl bg-blue-500/10 text-blue-400">
                <i class="fa-solid fa-lock mr-1"></i> Lokasi tidak dapat diubah setelah laporan dibuat
            </div>
            <div class="relative">
                <i class="fa-solid fa-location-dot absolute left-4 top-3.5 text-gray-500"></i>
                <input type="text" value="{{ $report->address }}" disabled
                    class="w-full glass-card rounded-xl pl-10 pr-4 py-3 text-sm text-gray-400 bg-[#0c1726]/50 border-0 cursor-not-allowed">
            </div>
        </div>

        <!-- DETAIL -->
        <div>
            <div class="flex items-center space-x-2 border-b border-gray-800 pb-2 mb-4">
                <span class="text-xs font-mono text-orange-500 font-bold">03</span>
                <h3 class="text-md font-bold text-gray-300">Detail Laporan</h3>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Judul Masalah <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" maxlength="255" value="{{ $report->title }}"
                        class="w-full glass-card custom-input rounded-xl px-4 py-3 text-sm focus:outline-none text-white bg-[#111C2E]/50 border-0"
                        required>
                    <div class="text-right text-xs text-gray-500 mt-1" id="titleCount">0/255</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1">Deskripsi Masalah <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" maxlength="1000" rows="4"
                        class="w-full glass-card custom-input rounded-xl px-4 py-3 text-sm focus:outline-none text-white resize-none bg-[#111C2E]/50 border-0"
                        required>{{ $report->description }}</textarea>
                    <div class="text-right text-xs text-gray-500 mt-1" id="descriptionCount">0/1000</div>
                </div>
            </div>
        </div>

        <!-- FOTO -->
        <div>
            <div class="flex items-center space-x-2 border-b border-gray-800 pb-2 mb-2">
                <span class="text-xs font-mono text-orange-500 font-bold">04</span>
                <h3 class="text-md font-bold text-gray-300">Foto Masalah</h3>
            </div>
            <p class="text-xs text-gray-400 mb-4">Foto lama bisa dihapus dengan klik tombol X. Total foto maksimal 10.</p>

            <!-- FOTO LAMA -->
            <div id="existingGrid" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4 mb-4">
                @foreach ($report->images as $img)
                <div class="relative existing-photo" data-img-id="{{ $img->id }}">
                    <img src="/storage/{{ $img->image_url }}" class="w-full h-24 object-cover rounded-xl">
                    <input type="hidden" name="existing_images[]" value="{{ $img->id }}" class="existing-input">
                    <button type="button"
                        class="delete-existing absolute top-1 right-1 bg-red-500 px-2 text-white rounded text-xs">
                        X
                    </button>
                    <div class="undo-overlay absolute inset-0 hidden items-center justify-center bg-black/60 rounded-xl">
                        <button type="button" class="undo-existing text-xs text-white underline">Batal hapus</button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- DROPZONE FOTO BARU -->
            <div id="dropzone"
                class="border-2 border-dashed border-gray-800 hover:border-gray-500 rounded-xl p-8 text-center bg-[#111C2E]/30 cursor-pointer transition-all duration-300 group">
                <input type="file" id="fileInput" name="images[]" multiple accept="image/*" class="hidden">
                <div class="flex flex-col items-center justify-center pointer-events-none">
                    <div class="bg-[#1C2C42]/60 p-3 rounded-xl mb-3 shadow-inner group-hover:scale-110 group-hover:bg-[#253954]/80 transition-all duration-300">
                        <i class="fa-solid fa-cloud-arrow-up text-xl text-gray-300 group-hover:text-orange-500 transition-colors"></i>
                    </div>
                    <p class="text-sm font-semibold text-white">Klik atau seret foto baru ke sini</p>
                    <p class="text-xs text-gray-500 mt-1">Format JPG, PNG, WEBP · Maks. 10 MB per foto</p>
                </div>
            </div>

            <div id="previewGrid" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 gap-4 mt-4"></div>
        </div>

        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 pt-4">
            <a href="{{ route('reports.me') }}"
                class="w-full sm:w-1/3 border border-gray-800 text-gray-400 font-medium py-3 rounded-xl hover:bg-gray-800/60 hover:text-white active:scale-95 transition-all duration-200 text-sm text-center cursor-pointer">
                Batal
            </a>
            <button type="submit" id="submitBtn"
                class="w-full sm:w-2/3 bg-orange-500 text-white font-semibold py-3 rounded-xl hover:bg-orange-600 active:scale-[0.98] transition-all duration-200 shadow-lg shadow-orange-500/10 hover:shadow-orange-600/20 text-sm flex items-center justify-center space-x-2 cursor-pointer group">
                <i class="fa-solid fa-floppy-disk group-hover:scale-110 transition-transform"></i>
                <span>Simpan Perubahan</span>
            </button>
        </div>
    </form>
</main>

<script>

/* =========================
   STATE
========================= */
let selectedFiles = [];
let existingCount = {{ $report->images->count() }};

/* =========================
   ELEMENT
========================= */
const form = document.getElementById('editForm');
const fileInput = document.getElementById('fileInput');
const dropzone = document.getElementById('dropzone');
const previewGrid = document.getElementById('previewGrid');
const categoryCards = document.querySelectorAll('.category-card');
const titleInput = document.getElementById('title');
const descInput = document.getElementById('description');

/* =========================
   COUNTER
========================= */
function updateCounter(input, el, max) {
    el.textContent = `${input.value.length}/${max}`;
}
updateCounter(titleInput, document.getElementById('titleCount'), 255);
updateCounter(descInput, document.getElementById('descriptionCount'), 1000);
titleInput.addEventListener('input', () => updateCounter(titleInput, document.getElementById('titleCount'), 255));
descInput.addEventListener('input', () => updateCounter(descInput, document.getElementById('descriptionCount'), 1000));

/* =========================
   KATEGORI
========================= */
categoryCards.forEach(card => {
    const radio = card.querySelector('input[type="radio"]');
    card.addEventListener('click', () => {
        categoryCards.forEach(c => c.classList.remove('active'));
        card.classList.add('active');
        radio.checked = true;
    });
});

/* =========================
   HAPUS FOTO LAMA (toggle, bukan langsung hilang)
   Supaya user bisa "batal hapus" sebelum submit
========================= */
document.getElementById('existingGrid').addEventListener('click', (e) => {

    const deleteBtn = e.target.closest('.delete-existing');
    const undoBtn = e.target.closest('.undo-existing');

    if (deleteBtn) {
        const wrapper = deleteBtn.closest('.existing-photo');
        wrapper.classList.add('marked-delete');
        wrapper.querySelector('.existing-input').disabled = true; // jangan dikirim = dianggap dihapus
        existingCount--;
        return;
    }

    if (undoBtn) {
        const wrapper = undoBtn.closest('.existing-photo');
        wrapper.classList.remove('marked-delete');
        wrapper.querySelector('.existing-input').disabled = false;
        existingCount++;
        return;
    }
});

/* =========================
   UPLOAD FOTO BARU
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

    if (existingCount + selectedFiles.length + arr.length > 10) {
        alert("Total foto (lama + baru) maksimal 10");
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

function renderPreview() {
    previewGrid.innerHTML = '';

    selectedFiles.forEach((file, i) => {
        const reader = new FileReader();

        reader.onload = e => {
            const div = document.createElement('div');
            div.className = "relative animate-pop-in";
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded-xl">
                <span class="absolute bottom-1 left-1 bg-orange-500 text-white text-[10px] px-1.5 py-0.5 rounded">Baru</span>
                <button type="button"
                    data-index="${i}"
                    class="delete-new absolute top-1 right-1 bg-red-500 px-2 text-white rounded text-xs">
                    X
                </button>
            `;
            previewGrid.appendChild(div);
        };

        reader.readAsDataURL(file);
    });
}

previewGrid.addEventListener('click', e => {
    const btn = e.target.closest('.delete-new');
    if (!btn) return;

    const i = btn.dataset.index;
    selectedFiles.splice(i, 1);

    renderPreview();
    syncInput();
});

function syncInput() {
    const dt = new DataTransfer();
    selectedFiles.forEach(f => dt.items.add(f));
    fileInput.files = dt.files;
}

/* =========================
   LOADING SAAT SUBMIT
========================= */
form.addEventListener('submit', () => {
    document.getElementById('loadingOverlay').classList.remove('hidden');
    document.getElementById('loadingOverlay').classList.add('flex');
});

</script>

@endsection