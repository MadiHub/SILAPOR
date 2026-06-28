@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Edit Kategori – ' . $category->name)

@section('content')

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.categories.index') }}" style="color:var(--primary-color); text-decoration:none;">Kategori</a>
    <span style="margin:0 6px;">/</span>
    <a href="{{ route('admin.categories.show', $category->id) }}" style="color:var(--primary-color); text-decoration:none;">{{ $category->name }}</a>
    <span style="margin:0 6px;">/</span> Edit
</div>

<h1 style="margin:0 0 24px;">Edit Kategori</h1>

<div style="max-width:680px;">
    <form method="POST" action="{{ route('admin.categories.update', $category->id) }}">
        @csrf @method('PUT')

        <div class="card" style="margin-bottom:20px;">
            <h3 style="margin:0 0 18px; font-size:1em;">Informasi Kategori</h3>

            {{-- NAMA --}}
            <div style="margin-bottom:16px;">
                <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                    Nama Kategori <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
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
                        <option value="{{ $dept->id }}"
                            {{ old('department_id', $category->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }} ({{ $dept->code }})
                        </option>
                    @endforeach
                </select>
                @if($category->department_id !== (int) old('department_id', $category->department_id))
                    <p style="font-size:0.78em; color:#f59e0b; margin:4px 0 0;">
                        <i class="fas fa-exclamation-triangle"></i>
                        Mengubah dinas di sini sama dengan melakukan remap — laporan terkait akan ikut berpindah dinas.
                    </p>
                @else
                    <p style="font-size:0.78em; color:#aaa; margin:4px 0 0;">
                        Untuk memindahkan ke dinas lain, gunakan fitur <a href="{{ route('admin.categories.show', $category->id) }}" style="color:var(--primary-color);">Pindah Dinas</a> di halaman detail.
                    </p>
                @endif
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
                          style="width:100%; padding:10px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box; resize:vertical; line-height:1.5;">{{ old('description', $category->description) }}</textarea>
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
                      style="background:var(--primary-color); color:#fff; padding:4px 12px; border-radius:20px; font-size:0.82em; font-weight:600;">
                    {{ $category->department->code ?? '' }}
                </span>
                <span id="preview-name" style="font-weight:600; color:var(--text-dark);">{{ $category->name }}</span>
            </div>
        </div>

        {{-- META --}}
        <div class="card" style="margin-bottom:20px; font-size:0.85em; color:#777;">
            <p style="margin:0 0 4px;"><i class="fas fa-hashtag" style="width:14px;"></i> ID: #{{ $category->id }}</p>
            <p style="margin:0 0 4px;"><i class="fas fa-file-alt" style="width:14px;"></i> Total laporan: <strong>{{ $category->reports_count }}</strong></p>
        </div>

        <div style="display:flex; gap:12px; justify-content:flex-end;">
            <a href="{{ route('admin.categories.show', $category->id) }}"
               style="padding:10px 20px; border:1px solid var(--background-light); border-radius:8px; text-decoration:none; color:var(--text-dark); font-size:0.9em;">
                Batal
            </a>
            <button type="submit"
                    style="background:var(--primary-color); color:#fff; border:none; padding:10px 24px; border-radius:8px; cursor:pointer; font-size:0.9em; font-weight:600;">
                <i class="fas fa-save"></i> Perbarui Kategori
            </button>
        </div>

    </form>
</div>

@endsection

@section('scripts')
<script>
    const deptSelect   = document.getElementById('department_id');
    const nameInput    = document.getElementById('name');
    const previewName  = document.getElementById('preview-name');
    const previewBadge = document.getElementById('preview-dept-badge');

    const deptCodes = {};
    Array.from(deptSelect.options).forEach(opt => {
        if (!opt.value) return;
        const match = opt.text.match(/\(([^)]+)\)$/);
        if (match) deptCodes[opt.value] = match[1];
    });

    function updatePreview() {
        previewName.textContent = nameInput.value || 'Nama Kategori';
        const code = deptCodes[deptSelect.value];
        previewBadge.textContent = code || '—';
    }

    nameInput.addEventListener('input', updatePreview);
    deptSelect.addEventListener('change', updatePreview);
</script>
@endsection