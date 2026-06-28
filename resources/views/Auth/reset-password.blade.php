<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILAPOR - Reset Password</title>
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
            .form-input { @apply w-full pl-11 pr-11 py-3 bg-[#f3f4f6]/60 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-400 text-gray-800; }
            .password-toggle { @apply absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 cursor-pointer hover:text-gray-600; }
            .btn-primary { @apply w-full bg-[#0b0e14] text-white py-3 rounded-xl font-semibold text-sm hover:bg-black/90 transition pt-3.5 pb-3.5; }
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

        <div class="section-header">
            <h2 class="auth-title">Password Baru</h2>
            <p class="auth-subtitle">Buat password baru yang kuat</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="input-label">Password Baru</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" placeholder="Minimal 8 karakter" class="form-input">
                    <span class="password-toggle"><i class="fa-regular fa-eye"></i></span>
                </div>
            </div>
            <div>
                <label class="input-label">Konfirmasi Password</label>
                <div class="input-wrapper">
                    <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="form-input">
                    <span class="password-toggle"><i class="fa-regular fa-eye"></i></span>
                </div>
            </div>
            <button type="submit" class="btn-primary">Simpan Password Baru</button>
        </form>
    </div>

    <script>
        document.body.addEventListener('click', function(e) {
            if (e.target.closest('.password-toggle')) {
                const btn = e.target.closest('.password-toggle');
                const input = btn.parentElement.querySelector('input');
                const icon = btn.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'fa-solid fa-eye-slash';
                } else {
                    input.type = 'password';
                    icon.className = 'fa-regular fa-eye';
                }
            }
        });
    </script>
</body>
</html>