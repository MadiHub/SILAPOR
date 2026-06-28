<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SILAPOR - Verifikasi OTP</title>
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

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        <div class="section-header">
            <h2 class="auth-title">Verifikasi OTP</h2>
            <p class="auth-subtitle">
                Kode telah dikirim ke<br>
                <strong class="text-gray-700">{{ session('reset_email') }}</strong>
            </p>
        </div>

        <form action="{{ route('password.check-otp') }}" method="POST" class="space-y-5">
            @csrf
            {{-- Input OTP 6 kotak --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-3 text-center">Masukkan 6 Digit Kode OTP</label>
                <div class="flex gap-2 justify-center" id="otp-boxes">
                    @for ($i = 0; $i < 6; $i++)
                        <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                               class="w-11 h-12 text-center text-lg font-bold bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-gray-500 text-gray-800 otp-digit">
                    @endfor
                </div>
                <input type="hidden" name="otp" id="otp-hidden">
            </div>

            <button type="submit" class="btn-primary" id="btn-verify">Verifikasi</button>
        </form>

        {{-- Resend OTP --}}
        <div class="text-center mt-5 text-sm text-gray-500">
            Tidak menerima kode?
            <form action="{{ route('password.send-otp') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="email" value="{{ session('reset_email') }}">
                <button type="submit" class="text-gray-900 font-semibold underline hover:text-black">Kirim ulang</button>
            </form>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('password.request') }}" class="text-sm text-gray-400 hover:text-gray-700">
                <i class="fa-solid fa-arrow-left mr-1 text-xs"></i> Ganti email
            </a>
        </div>
    </div>

    <script>
        const digits = document.querySelectorAll('.otp-digit');
        const hidden = document.getElementById('otp-hidden');

        digits.forEach((input, idx) => {
            input.addEventListener('input', e => {
                // Hanya angka
                input.value = input.value.replace(/\D/, '');
                if (input.value && idx < 5) digits[idx + 1].focus();
                syncHidden();
            });
            input.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !input.value && idx > 0) {
                    digits[idx - 1].focus();
                }
            });
            // Paste support
            input.addEventListener('paste', e => {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g,'').slice(0,6);
                pasted.split('').forEach((ch, i) => { if (digits[i]) digits[i].value = ch; });
                if (digits[Math.min(pasted.length, 5)]) digits[Math.min(pasted.length, 5)].focus();
                syncHidden();
            });
        });

        function syncHidden() {
            hidden.value = Array.from(digits).map(d => d.value).join('');
        }
    </script>
</body>
</html>