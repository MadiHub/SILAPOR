<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILAPOR - Lupa Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style type="text/tailwindcss">
        @layer utilities {
            .auth-body { @apply bg-gray-50 min-h-screen flex items-center justify-center p-4; font-family: 'Inter', sans-serif; }
            .auth-card { @apply bg-white p-8 rounded-2xl shadow-sm w-full max-w-[440px] border border-gray-100; }
            .logo-container { @apply flex justify-center mb-4; }
            .logo-box { @apply bg-[#0b0e14] text-white p-3 rounded-2xl flex items-center justify-center w-12 h-12; }
            .section-header { @apply text-center mb-6; }
            .auth-title { @apply text-2xl font-bold text-gray-900 tracking-tight; }
            .auth-subtitle { @apply text-gray-400 text-sm mt-1; }
            .input-label { @apply block text-sm font-semibold text-gray-900 mb-1.5; }
            .input-wrapper { @apply relative; }
            .input-icon { @apply absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400; }
            .form-input { @apply w-full pl-11 pr-4 py-3 bg-[#f3f4f6]/60 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-400 text-gray-800; }
            .btn-primary { @apply w-full bg-[#0b0e14] text-white py-3 rounded-xl font-semibold text-sm hover:bg-black/90 transition pt-3.5 pb-3.5; }
            .btn-secondary { @apply w-full border border-gray-200 text-gray-700 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50 transition pt-3.5 pb-3.5; }
        }
    </style>
</head>
<body class="auth-body">
    <div class="auth-card">
        <div class="logo-container">
            <div class="logo-box"><i class="fa-solid fa-sparkles text-xl"></i></div>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-lg text-sm">
                @foreach ($errors->all() as $error)<div>- {{ $error }}</div>@endforeach
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        <div class="section-header">
            <h2 class="auth-title">Lupa Password</h2>
            <p class="auth-subtitle">Masukkan email untuk menerima kode OTP</p>
        </div>

        <form action="{{ route('password.send-otp') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="input-label">Email</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                    <input type="email" name="email" placeholder="nama@email.com"
                           class="form-input" value="{{ old('email') }}" required>
                </div>
            </div>
            <button type="submit" class="btn-primary">Kirim Kode OTP</button>
        </form>

        <div class="text-center mt-6">
            <a href="{{ route('auth') }}" class="text-sm text-gray-500 hover:text-gray-900">
                <i class="fa-solid fa-arrow-left mr-1 text-xs"></i> Kembali ke halaman masuk
            </a>
        </div>
    </div>
</body>
</html>