@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Tambah Kategori')

@section('content')

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.categories.index') }}" style="color:var(--primary-color); text-decoration:none;">Kategori</a>
    <span style="margin:0 6px;">/</span> Tambah Kategori
</div>

<h1 style="margin:0 0 24px;">Tambah Kategori Baru</h1>

<div style="max-width:680px;">
    <form method="POST" action="{{ route('admin.categories.store') }}">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <h3 style="margin:0 0 18px; font-size:1em;">Informasi Kategori</h3>

            {{-- NAMA --}}
            <div style="margin-bottom:16px;">
                <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                    Nama Kategori <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       placeholder="contoh: Jalan Rusak, Sampah, Banjir..."
                       style="width:100%; padding:10px 12px; border:1px solid {{ $errors->has('name') ? '#ef4444' : 'var(--background-light)' }}; border-radius:6px; font-size:0.9em; box-sizing:border-box;">
                @error('name')
                    <p style="color:#ef4444; font-size:0.8em; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>

            {{-- DINAS --}}
            <div style="margin-bottom:16px;">
                <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                    Dinas Penanggungjawab <span style="color:#ef4444;">*</span>
                </label>
                <select name="department_id" id="department_id" required
                        style="width:100%; padding:10px 12px; border:1px solid {{ $errors->has('department_id') ? '#ef4444' : 'var(--background-light)' }}; border-radius:6px; font-size:0.9em;">
                    <option value="">-- Pilih Dinas --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }} ({{ $dept->code }})
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <p style="color:#ef4444; font-size:0.8em; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>

            {{-- DESKRIPSI --}}
            <div>
                <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                    Deskripsi
                </label>
                <textarea name="description" rows="4"
                          placeholder="Jelaskan jenis masalah apa saja yang termasuk dalam kategori ini..."
                          style="width:100%; padding:10px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box; resize:vertical; line-height:1.5;">{{ old('description') }}</textarea>
                @error('description')
                    <p style="color:#ef4444; font-size:0.8em; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- LIVE PREVIEW --}}
        <div class="card" style="margin-bottom:20px; background:var(--background-light);">
            <p style="font-size:0.78em; color:#aaa; text-transform:uppercase; font-weight:600; margin:0 0 12px;">Preview</p>
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="background:#f3f4f6; color:#555; padding:4px 12px; border-radius:20px; font-size:0.82em; font-weight:600;">
                    <i class="fas fa-tag"></i> Kategori
                </span>
                <span id="preview-dept-badge"
                      style="background:var(--primary-color); color:#fff; padding:4px 12px; border-radius:20px; font-size:0.82em; font-weight:600; display:none;">
                </span>
                <span id="preview-name" style="font-weight:600; color:var(--text-dark);">Nama Kategori</span>
            </div>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;">
            <a href="{{ route('admin.categories.index') }}"
               style="padding:10px 20px; border:1px solid var(--background-light); border-radius:8px; text-decoration:none; color:var(--text-dark); font-size:0.9em;">
                Batal
            </a>
            <button type="submit"
                    style="background:var(--primary-color); color:#fff; border:none; padding:10px 24px; border-radius:8px; cursor:pointer; font-size:0.9em; font-weight:600;">
                <i class="fas fa-save"></i> Simpan Kategori
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
<script>
    // dept code lookup from select options
    const deptSelect  = document.getElementById('department_id');
    const nameInput   = document.getElementById('name');
    const previewName = document.getElementById('preview-name');
    const previewBadge = document.getElementById('preview-dept-badge');

    // build map: id → code from option text "(CODE)"
    const deptCodes = {};
    Array.from(deptSelect.options).forEach(opt => {
        if (!opt.value) return;
        const match = opt.text.match(/\(([^)]+)\)$/);
        if (match) deptCodes[opt.value] = match[1];
    });

    function updatePreview() {
        previewName.textContent = nameInput.value || 'Nama Kategori';

        const code = deptCodes[deptSelect.value];
        if (code) {
            previewBadge.textContent = code;
            previewBadge.style.display = 'inline-block';
        } else {
            previewBadge.style.display = 'none';
        }
    }

    nameInput.addEventListener('input', updatePreview);
    deptSelect.addEventListener('change', updatePreview);
    updatePreview();
</script>
@endsection