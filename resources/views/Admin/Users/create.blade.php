@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Tambah Pengguna')

@section('content')

{{-- BREADCRUMB --}}
<div style="font-size:0.85em; color:#999; margin-bottom:16px;">
    <a href="{{ route('admin.users.index') }}" style="color:var(--primary-color); text-decoration:none;">Pengguna</a>
    <span style="margin:0 6px;">/</span> Tambah Pengguna
</div>

<h1 style="margin:0 0 24px;">Tambah Pengguna Baru</h1>

<form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="display:grid; grid-template-columns: 1fr 2fr; gap:24px; align-items:start;">

        {{-- LEFT: AVATAR UPLOAD --}}
        <div class="card" style="text-align:center;">
            <div id="avatar-preview-wrapper" style="margin-bottom:16px;">
                <img id="avatar-preview"
                     src="https://ui-avatars.com/api/?name=New+User&background=133a68&color=fff"
                     style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:3px solid var(--background-light); margin:0 auto 14px; display:block;">
            </div>
            <label for="avatar"
                   style="display:inline-block; background:var(--background-light); color:var(--text-dark); padding:8px 16px; border-radius:6px; cursor:pointer; font-size:0.85em; font-weight:600;">
                <i class="fas fa-upload"></i> Upload Foto
            </label>
            <input type="file" id="avatar" name="avatar" accept="image/*"
                   style="display:none;"
                   onchange="previewAvatar(this)">
            <p style="font-size:0.75em; color:#aaa; margin:8px 0 0;">JPG, PNG, WEBP – Maks 2 MB</p>
            @error('avatar')
                <p style="color:#ef4444; font-size:0.8em; margin-top:6px;">{{ $message }}</p>
            @enderror
        </div>

        {{-- RIGHT: FORM FIELDS --}}
        <div style="display:flex; flex-direction:column; gap:20px;">

            {{-- IDENTITY --}}
            <div class="card">
                <h3 style="margin:0 0 16px; font-size:1em;">Identitas</h3>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">

                    <div>
                        <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                            Nama Lengkap <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="Nama pengguna"
                               style="width:100%; padding:9px 12px; border:1px solid {{ $errors->has('name') ? '#ef4444' : 'var(--background-light)' }}; border-radius:6px; font-size:0.9em; box-sizing:border-box;">
                        @error('name') <p style="color:#ef4444;font-size:0.78em;margin:4px 0 0;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                            Nomor HP
                        </label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx"
                               style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em; box-sizing:border-box;">
                        @error('phone') <p style="color:#ef4444;font-size:0.78em;margin:4px 0 0;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                            Email <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="email@contoh.com"
                               style="width:100%; padding:9px 12px; border:1px solid {{ $errors->has('email') ? '#ef4444' : 'var(--background-light)' }}; border-radius:6px; font-size:0.9em; box-sizing:border-box;">
                        @error('email') <p style="color:#ef4444;font-size:0.78em;margin:4px 0 0;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                            Password <span style="color:#ef4444;">*</span>
                        </label>
                        <div style="position:relative;">
                            <input type="password" name="password" id="password" required
                                   placeholder="Min. 8 karakter"
                                   style="width:100%; padding:9px 38px 9px 12px; border:1px solid {{ $errors->has('password') ? '#ef4444' : 'var(--background-light)' }}; border-radius:6px; font-size:0.9em; box-sizing:border-box;">
                            <button type="button" onclick="togglePassword('password')"
                                    style="position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; color:#aaa; cursor:pointer; font-size:0.85em;">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        @error('password') <p style="color:#ef4444;font-size:0.78em;margin:4px 0 0;">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>

            {{-- ROLE & STATUS --}}
            <div class="card">
                <h3 style="margin:0 0 16px; font-size:1em;">Role & Status</h3>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">

                    <div>
                        <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                            Role <span style="color:#ef4444;">*</span>
                        </label>
                        <select name="role" required
                                style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                            <option value="warga"  {{ old('role','warga')=='warga' ? 'selected':'' }}>Warga</option>
                            <option value="pemda"  {{ old('role')=='pemda'  ? 'selected':'' }}>Pemda</option>
                            <option value="admin"  {{ old('role')=='admin'  ? 'selected':'' }}>Admin</option>
                        </select>
                    </div>

                    <div>
                        <label style="font-size:0.85em; color:#555; display:block; margin-bottom:5px; font-weight:500;">
                            Status <span style="color:#ef4444;">*</span>
                        </label>
                        <select name="status" required
                                style="width:100%; padding:9px 12px; border:1px solid var(--background-light); border-radius:6px; font-size:0.9em;">
                            <option value="active"   {{ old('status','active')=='active'   ? 'selected':'' }}>Aktif</option>
                            <option value="inactive" {{ old('status')=='inactive' ? 'selected':'' }}>Suspended</option>
                            <option value="banned"   {{ old('status')=='banned'   ? 'selected':'' }}>Banned</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- DEPARTMENTS --}}
            <div class="card">
                <h3 style="margin:0 0 6px; font-size:1em;">Dinas (Opsional)</h3>
                <p style="font-size:0.82em; color:#aaa; margin:0 0 14px;">Pilih jika pengguna adalah staf Pemda yang mengelola dinas tertentu.</p>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; max-height:200px; overflow-y:auto; padding:2px;">
                    @foreach($departments as $dept)
                        <label style="display:flex; align-items:center; gap:8px; padding:8px 10px; border:1px solid var(--background-light); border-radius:6px; cursor:pointer; font-size:0.88em; transition:background 0.15s;"
                               onmouseover="this.style.background='var(--background-light)'"
                               onmouseout="this.style.background='#fff'">
                            <input type="checkbox" name="department_ids[]" value="{{ $dept->id }}"
                                   {{ in_array($dept->id, old('department_ids', [])) ? 'checked' : '' }}
                                   style="accent-color:var(--primary-color);">
                            <span>
                                <strong>{{ $dept->code }}</strong> – {{ $dept->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- SUBMIT --}}
            <div style="display:flex; gap:12px; justify-content:flex-end;">
                <a href="{{ route('admin.users.index') }}"
                   style="padding:10px 20px; border:1px solid var(--background-light); border-radius:8px; text-decoration:none; color:var(--text-dark); font-size:0.9em;">
                    Batal
                </a>
                <button type="submit"
                        style="background:var(--primary-color); color:#fff; border:none; padding:10px 24px; border-radius:8px; cursor:pointer; font-size:0.9em; font-weight:600;">
                    <i class="fas fa-save"></i> Simpan Pengguna
                </button>
            </div>

        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('avatar-preview').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }

    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon  = document.getElementById(id + '-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }
</script>
@endsection