@extends('Layouts.PemdaLayout.base_layout')

@section('title', 'Profile')

@section('content')

{{-- ===== BREADCRUMB ===== --}}
<div style="display:flex; align-items:center; gap:8px; font-size:0.88em; color:#999; margin-bottom:20px;">
    <a href="{{ route('pemda.dashboard') }}" style="color:var(--primary-color); text-decoration:none;">Dashboard</a>
    <i class="fas fa-chevron-right" style="font-size:0.7em;"></i>
    <span style="color:var(--text-dark);">Profile</span>
</div>

{{-- ===== HEADER ===== --}}
<div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:25px;">
    <div>
        <h1 style="margin:0 0 6px 0; font-size:1.5em;">Profile Saya</h1>
        <div style="font-size:0.85em; color:#999;">
            <i class="fas fa-id-badge" style="margin-right:4px;"></i>
            {{ ucfirst($user->role) }}
        </div>
    </div>
</div>

@if(session('success'))
    <div style="background:#ecfdf5; border-left:4px solid #10b981; padding:12px 16px; border-radius:8px; color:#065f46; margin-bottom:20px; font-size:0.9em;">
        <i class="fas fa-check-circle" style="margin-right:6px;"></i> {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background:#fef2f2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:8px; color:#991b1b; margin-bottom:20px; font-size:0.9em;">
        <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i>
        <strong>Terjadi kesalahan:</strong>
        <ul style="margin:6px 0 0 20px; padding:0;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('pemda.profile.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:25px; align-items:start;">

        {{-- ===== KOLOM KIRI ===== --}}
        <div style="display:flex; flex-direction:column; gap:25px;">

            {{-- INFORMASI AKUN --}}
            <div class="card">
                <h3 style="margin-top:0;">Informasi Akun</h3>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px;">
                    <div>
                        <label style="font-size:0.78em; color:#999; text-transform:uppercase; margin-bottom:3px; display:block;">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                      font-size:0.9em; box-sizing:border-box; font-family:inherit; color:var(--text-dark);">
                    </div>
                    <div>
                        <label style="font-size:0.78em; color:#999; text-transform:uppercase; margin-bottom:3px; display:block;">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               style="width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                      font-size:0.9em; box-sizing:border-box; font-family:inherit; color:var(--text-dark);">
                    </div>
                    <div style="grid-column: 1/-1;">
                        <label style="font-size:0.78em; color:#999; text-transform:uppercase; margin-bottom:3px; display:block;">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                               placeholder="08xxxxxxxxxx"
                               style="width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                      font-size:0.9em; box-sizing:border-box; font-family:inherit; color:var(--text-dark);">
                    </div>
                </div>
            </div>

            {{-- UBAH PASSWORD --}}
            <div class="card">
                <h3 style="margin-top:0;">Ubah Password</h3>
                <p style="color:#999; font-size:0.85em; margin-top:-6px; margin-bottom:16px;">
                    Kosongkan bagian ini jika tidak ingin mengubah password.
                </p>

                <div style="display:grid; grid-template-columns: 1fr; gap:14px;">
                    <div>
                        <label style="font-size:0.78em; color:#999; text-transform:uppercase; margin-bottom:3px; display:block;">Password Saat Ini</label>
                        <input type="password" name="current_password" placeholder="••••••••"
                               style="width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                      font-size:0.9em; box-sizing:border-box; font-family:inherit; color:var(--text-dark);">
                    </div>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px;">
                        <div>
                            <label style="font-size:0.78em; color:#999; text-transform:uppercase; margin-bottom:3px; display:block;">Password Baru</label>
                            <input type="password" name="password" placeholder="••••••••"
                                   style="width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                          font-size:0.9em; box-sizing:border-box; font-family:inherit; color:var(--text-dark);">
                        </div>
                        <div>
                            <label style="font-size:0.78em; color:#999; text-transform:uppercase; margin-bottom:3px; display:block;">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="••••••••"
                                   style="width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                          font-size:0.9em; box-sizing:border-box; font-family:inherit; color:var(--text-dark);">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <a href="{{ route('pemda.dashboard') }}"
                   style="padding:10px 20px; background:#f1f5f9; color:#555; border-radius:8px;
                          font-size:0.9em; text-decoration:none;">
                    Batal
                </a>
                <button type="submit"
                        style="padding:10px 24px; background:var(--primary-color); color:#fff;
                               border:none; border-radius:8px; font-size:0.9em; cursor:pointer; font-weight:600;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>

        </div>

        {{-- ===== KOLOM KANAN ===== --}}
        <div style="display:flex; flex-direction:column; gap:25px;">

            {{-- FOTO PROFILE --}}
            <div class="card" style="text-align:center;">
                <h3 style="margin-top:0; text-align:left;">Foto Profile</h3>

                <img id="avatarPreview"
                     src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                     style="width:140px; height:140px; border-radius:50%; object-fit:cover; margin:10px auto 16px;
                            display:block; border:3px solid var(--background-light);">

                <label for="avatarInput"
                       style="display:inline-block; padding:8px 18px; background:var(--background-light); color:var(--text-dark);
                              border-radius:8px; font-size:0.85em; cursor:pointer;">
                    <i class="fas fa-camera" style="margin-right:5px;"></i> Ganti Foto
                </label>
                <input type="file" id="avatarInput" name="avatar" accept="image/png, image/jpeg, image/webp" style="display:none;">

                @error('avatar')
                    <div style="color:#ef4444; font-size:0.8em; margin-top:8px;">{{ $message }}</div>
                @enderror

                <p style="color:#aaa; font-size:0.75em; margin-top:10px; margin-bottom:0;">JPG, PNG, atau WEBP. Maks 2MB.</p>
            </div>

            {{-- INFO DEPARTEMEN (READ-ONLY) --}}
            <div class="card">
                <h3 style="margin-top:0;">Departemen</h3>
                <p style="color:#aaa; font-size:0.78em; margin-top:-8px; margin-bottom:14px;">
                    <i class="fas fa-lock" style="margin-right:4px;"></i>Informasi ini hanya dapat diubah oleh admin.
                </p>

                @if($user->departments->isEmpty())
                    <p style="color:#aaa; font-size:0.9em;">Belum tergabung dalam departemen.</p>
                @else
                    <ul style="list-style:none; padding:0; margin:0; font-size:0.9em;">
                        @foreach($user->departments as $department)
                            <li style="display:flex; align-items:center; gap:10px; padding:10px 0;
                                       border-bottom:1px solid var(--background-light);">
                                <div style="width:34px; height:34px; border-radius:8px; background:rgba(59,130,246,0.12);
                                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="fas fa-building" style="color:var(--primary-color); font-size:0.9em;"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600; color:var(--text-dark);">{{ $department->name }}</div>
                                    <div style="font-size:0.78em; color:#999;">{{ $department->code }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- RINGKASAN AKUN --}}
            <div class="card">
                <h3 style="margin-top:0;">Ringkasan</h3>
                <ul style="list-style:none; padding:0; margin:0; font-size:0.9em;">
                    <li style="display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid var(--background-light);">
                        <span style="color:#777;"><i class="fas fa-toggle-on" style="width:16px; margin-right:6px;"></i>Status</span>
                        <span style="font-weight:600; color:{{ $user->status === 'active' ? '#10b981' : '#ef4444' }};">
                            {{ ucfirst($user->status) }}
                        </span>
                    </li>
                    <li style="display:flex; justify-content:space-between; padding:10px 0;">
                        <span style="color:#777;"><i class="fas fa-clock" style="width:16px; margin-right:6px;"></i>Login Terakhir</span>
                        <span style="font-weight:600;">
                            {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : '-' }}
                        </span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</form>

<script>
    document.getElementById('avatarInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('avatarPreview').src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
</script>

@endsection