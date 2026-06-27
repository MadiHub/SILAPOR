@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Tambah Dinas')

@section('content')

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.departments.index') }}" style="color:var(--primary-color); text-decoration:none;">Dinas</a>
    <span style="margin:0 6px;">/</span> Tambah Dinas
</div>

<h1 style="margin:0 0 24px;">Tambah Dinas Baru</h1>

<div style="max-width:680px;">
    <form method="POST" action="{{ route('admin.departments.store') }}">
        @csrf

        <div class="card" style="margin-bottom:20px;">
            <h3 style="margin:0 0 18px; font-size:1em;">Informasi Dinas</h3>

            <div style="margin-bottom:16px;">
                <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                    Nama Dinas <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="contoh: Dinas Perhubungan"
                       style="width:100%; padding:10px 12px; border:1px solid {{ $errors->has('name') ? '#ef4444' : 'var(--background-light)' }}; border-radius:6px; font-size:0.9em; box-sizing:border-box;">
                @error('name')
                    <p style="color:#ef4444; font-size:0.8em; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom:16px;">
                <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                    Kode Dinas <span style="color:#ef4444;">*</span>
                </label>
                <div style="position:relative;">
                    <input type="text" name="code" id="code" value="{{ old('code') }}" required
                           placeholder="contoh: DISHUB"
                           maxlength="50"
                           oninput="this.value = this.value.toUpperCase()"
                           style="width:100%; padding:10px 12px; border:1px solid {{ $errors->has('code') ? '#ef4444' : 'var(--background-light)' }}; border-radius:6px; font-size:0.9em; box-sizing:border-box; font-family:monospace; letter-spacing:0.05em;">
                </div>
                <p style="font-size:0.78em; color:#aaa; margin:4px 0 0;">Singkatan unik, akan otomatis diubah ke huruf kapital.</p>
                @error('code')
                    <p style="color:#ef4444; font-size:0.8em; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                    Deskripsi
                </label>
                <textarea name="description" rows="4"
                          placeholder="Tugas pokok dan fungsi dinas ini..."
                          style="width:100%; padding:10px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box; resize:vertical; line-height:1.5;">{{ old('description') }}</textarea>
                @error('description')
                    <p style="color:#ef4444; font-size:0.8em; margin:4px 0 0;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- PREVIEW --}}
        <div class="card" style="margin-bottom:20px; background:var(--background-light);">
            <p style="font-size:0.78em; color:#aaa; text-transform:uppercase; font-weight:600; margin:0 0 10px;">Preview</p>
            <div style="display:flex; align-items:center; gap:12px;">
                <div id="preview-code"
                     style="background:var(--primary-color); color:#fff; padding:5px 14px; border-radius:6px; font-size:0.85em; font-weight:700; letter-spacing:0.08em; min-width:60px; text-align:center;">
                    KODE
                </div>
                <div id="preview-name" style="font-weight:600; color:var(--text-dark);">Nama Dinas</div>
            </div>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;">
            <a href="{{ route('admin.departments.index') }}"
               style="padding:10px 20px; border:1px solid var(--background-light); border-radius:8px; text-decoration:none; color:var(--text-dark); font-size:0.9em;">
                Batal
            </a>
            <button type="submit"
                    style="background:var(--primary-color); color:#fff; border:none; padding:10px 24px; border-radius:8px; cursor:pointer; font-size:0.9em; font-weight:600;">
                <i class="fas fa-save"></i> Simpan Dinas
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
    const nameInput = document.querySelector('input[name="name"]');
    const codeInput = document.getElementById('code');

    function updatePreview() {
        document.getElementById('preview-name').textContent = nameInput.value || 'Nama Dinas';
        document.getElementById('preview-code').textContent = codeInput.value || 'KODE';
    }

    nameInput.addEventListener('input', updatePreview);
    codeInput.addEventListener('input', updatePreview);
    updatePreview();
</script>
@endsection