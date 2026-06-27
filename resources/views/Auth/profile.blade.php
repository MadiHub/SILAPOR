@extends('Layouts.HomeLayout.base_layout')

@section('title', 'Profile Saya - SiLapor')

@section('content')

@php
    $avatarUrl = $user->avatar;

    if ($avatarUrl && str_contains($avatarUrl, 'googleusercontent.com')) {
        $avatarUrl = preg_replace('/=s\d+-c/', '=s200-c', $avatarUrl);
    } elseif ($avatarUrl) {
        $avatarUrl = asset('storage/' . $avatarUrl);
    } else {
        $avatarUrl = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=133a68&color=fff&size=200&bold=true';
    }
@endphp

<div class="max-w-5xl mx-auto px-5 py-10">

    {{-- ===== BREADCRUMB ===== --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('home.index') }}" class="text-[var(--accent-blue)] hover:underline">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-gray-600">Profile Saya</span>
    </div>

    {{-- ===== HEADER ===== --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[var(--primary-blue)]">Profile Saya</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi akun dan keamanan profil Anda.</p>
    </div>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- ===== KOLOM KIRI: FOTO PROFILE ===== --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 text-center">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-5 text-left">Foto Profile</h3>

                    <div class="relative w-32 h-32 mx-auto mb-5">
                        <img id="avatarPreview"
                             src="{{ $avatarUrl }}"
                             alt="Avatar {{ $user->name }}"
                             class="w-32 h-32 rounded-full object-cover border-4 border-[var(--bg-light)] shadow">
                        <label for="avatarInput"
                               class="absolute bottom-0 right-0 w-10 h-10 bg-[var(--brand-orange)] hover:bg-[var(--brand-orange-hover)]
                                      rounded-full flex items-center justify-center text-white cursor-pointer shadow-lg transition">
                            <i class="fa-solid fa-camera text-sm"></i>
                        </label>
                        <input type="file" id="avatarInput" name="avatar" accept="image/png, image/jpeg, image/webp" class="hidden">
                    </div>

                    <p class="font-bold text-[var(--primary-blue)]">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400 capitalize mt-1">{{ $user->role }}</p>

                    @error('avatar')
                        <p class="text-red-500 text-xs mt-3">{{ $message }}</p>
                    @enderror

                    <p class="text-gray-400 text-xs mt-4">JPG, PNG, atau WEBP. Maks 2MB.</p>
                </div>

                {{-- RINGKASAN AKUN --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mt-6">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4">Ringkasan Akun</h3>
                    <ul class="text-sm divide-y divide-gray-100">
                        <li class="flex justify-between py-3">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fa-solid fa-toggle-on w-4 text-center"></i> Status
                            </span>
                            <span class="font-semibold {{ $user->status === 'active' ? 'text-green-600' : 'text-red-500' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </li>
                        <li class="flex justify-between py-3">
                            <span class="text-gray-500 flex items-center gap-2">
                                <i class="fa-regular fa-clock w-4 text-center"></i> Login Terakhir
                            </span>
                            <span class="font-semibold text-gray-700">
                                {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : '-' }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- ===== KOLOM KANAN: FORM DATA ===== --}}
            <div class="lg:col-span-2 flex flex-col gap-6">

                {{-- INFORMASI AKUN --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-5">Informasi Akun</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700
                                          focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)] focus:border-transparent">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700
                                          focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)] focus:border-transparent">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700
                                          focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)] focus:border-transparent">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- UBAH PASSWORD --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-1">Ubah Password</h3>
                    <p class="text-xs text-gray-400 mb-5">Kosongkan bagian ini jika tidak ingin mengubah password.</p>

                    <div class="flex flex-col gap-5">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" placeholder="••••••••"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700
                                          focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)] focus:border-transparent">
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Password Baru</label>
                                <input type="password" name="password" placeholder="••••••••"
                                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700
                                              focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)] focus:border-transparent">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" placeholder="••••••••"
                                       class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700
                                              focus:outline-none focus:ring-2 focus:ring-[var(--accent-blue)] focus:border-transparent">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('home.index') }}"
                       class="px-6 py-2.5 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-7 py-2.5 bg-[var(--brand-orange)] hover:bg-[var(--brand-orange-hover)] text-white
                                   rounded-lg text-sm font-semibold shadow-md transition flex items-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>

            </div>
        </div>
    </form>
</div>

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