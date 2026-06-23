<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UrbanAksi-Login</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style type="text/tailwindcss">
        @layer utilities {
            /* Base Body Setup */
            .auth-body {
                @apply bg-gray-50 min-h-screen flex items-center justify-center p-4;
                font-family: 'Inter', sans-serif;
            }

            /* Card Utama */
            .auth-card {
                @apply bg-white p-8 rounded-2xl shadow-sm w-full max-w-[440px] border border-gray-100;
            }

            /* Logo Styling */
            .logo-container {
                @apply flex justify-center mb-4;
            }
            .logo-box {
                @apply bg-[#0b0e14] text-white p-3 rounded-2xl flex items-center justify-center w-12 h-12;
            }

            /* Typography Header */
            .section-header {
                @apply text-center mb-6;
            }
            .auth-title {
                @apply text-2xl font-bold text-gray-900 tracking-tight;
            }
            .auth-subtitle {
                @apply text-gray-400 text-sm mt-1;
            }

            /* Google Button */
            .btn-google {
                @apply w-full flex items-center justify-center gap-3 border border-gray-200 py-3 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-50 transition mb-6;
            }

            /* Divider "atau" */
            .divider {
                @apply flex items-center my-5 text-gray-300 text-xs font-medium;
            }
            .divider-line {
                @apply flex-1 border-t border-gray-200;
            }
            .divider-text {
                @apply px-3 text-gray-400;
            }

            /* Tab Switcher */
            .toggle-tab-container {
                @apply flex bg-gray-50 p-1 rounded-xl border border-gray-100 mb-5;
            }
            .tab-btn {
                @apply flex-1 py-2 rounded-lg font-semibold text-sm transition;
            }
            .tab-btn.active {
                @apply text-white bg-[#0b0e14];
            }
            .tab-btn.inactive {
                @apply text-gray-400 hover:text-gray-600;
            }

            /* Form Inputs */
            .input-label {
                @apply block text-sm font-semibold text-gray-900 mb-1.5;
            }
            .input-wrapper {
                @apply relative;
            }
            .input-icon {
                @apply absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400;
            }
            .form-input {
                @apply w-full pl-11 pr-4 py-3 bg-[#f3f4f6]/60 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-gray-400 text-gray-800;
            }
            .input-note {
                @apply text-[11px] text-gray-400 mt-1 block;
            }

            /* Password Specifics */
            .forgot-password-link {
                @apply text-xs text-gray-400 hover:underline;
            }
            .password-toggle {
                @apply absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 cursor-pointer hover:text-gray-600;
            }

            /* Buttons & Links Footer */
            .btn-primary {
                @apply w-full bg-[#0b0e14] text-white py-3 rounded-xl font-semibold text-sm hover:bg-black/90 transition pt-3.5 pb-3.5;
            }
            .terms-text {
                @apply text-center text-[11px] text-gray-400 mt-4 leading-relaxed;
            }
            .switch-section-text {
                @apply text-center mt-6 text-sm text-gray-500;
            }
            .switch-btn {
                @apply text-gray-900 font-semibold underline hover:text-black;
            }
        }
    </style>
</head>
<body class="auth-body">

    <div class="auth-card">
        
        <div class="logo-container">
            <div class="logo-box">
                <i class="fa-solid fa-sparkles text-xl"></i>
            </div>
        </div>

        <div id="login-section">
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-lg text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)

                            <li>- {{ $error }}</li>

                        @endforeach

                    </ul>
                </div>

            @endif
            <div class="section-header">
                <h2 class="auth-title">Selamat datang</h2>
                <p class="auth-subtitle">Masuk untuk melanjutkan</p>
            </div>

            <a href="{{ route('google.redirect') }}" class="btn-google">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.85c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Lanjutkan dengan Google
            </a>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">atau masuk dengan</span>
                <div class="divider-line"></div>
            </div>

            <div class="toggle-tab-container">
                <button id="tab-email" class="tab-btn active">Email</button>
                <button id="tab-phone" class="tab-btn inactive">No. Telepon</button>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div id="login-input-container">
                    <label class="input-label">Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" placeholder="nama@email.com" class="form-input" name="email">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="input-label mb-0">Password</label>
                        <a href="#" class="forgot-password-link">Lupa password?</a>
                    </div>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" placeholder="••••••••" class="form-input" name="password">
                        <span class="password-toggle">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Masuk</button>
            </form>

            <div class="switch-section-text">
                Belum punya akun? <button id="go-to-register" class="switch-btn">Daftar sekarang</button>
            </div>
        </div>

        <div id="register-section" class="hidden">
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-600 rounded-lg text-sm">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="section-header">
                <h2 class="auth-title">Buat akun baru</h2>
                <p class="auth-subtitle">Daftar dan mulai gratis</p>
            </div>

            <button class="btn-google">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.85z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.85c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Daftar dengan Google
            </button>

            <div class="divider">
                <div class="divider-line"></div>
                <span class="divider-text">atau isi form di bawah</span>
                <div class="divider-line"></div>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
                <div>
                    <label class="input-label">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <i class="fa-regular fa-user"></i>
                        </span>
                        <input type="text" placeholder="Nama lengkap Anda" class="form-input" name="name">
                    </div>
                </div>

                <div>
                    <label class="input-label">Email <span class="text-red-500">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <input type="email" placeholder="nama@email.com" class="form-input" name="email" required>
                    </div>
                </div>

                <div>
                    <label class="input-label">Nomor Telepon <span class="text-red-500">*</span></label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <i class="fa-solid fa-phone"></i>
                        </span>
                        <input type="text" placeholder="+62 812 3456 7890" class="form-input" name="phone" required>
                    </div>
                    <span class="input-note">Email dan nomor telepon wajib diisi</span>
                </div>

                <div>
                    <label class="input-label">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" placeholder="Minimal 8 karakter" class="form-input" name="password">
                        <span class="password-toggle">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div>
                    <label class="input-label">Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" placeholder="Ulangi password" class="form-input" name="password_confirmation">
                        <span class="password-toggle">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Buat Akun</button>
            </form>

            <p class="terms-text">
                Dengan mendaftar, Anda menyetujui <a href="#" class="underline hover:text-gray-600">Syarat & Ketentuan</a> kami.
            </p>

            <div class="switch-section-text">
                Sudah punya akun? <button id="go-to-login" class="switch-btn">Masuk di sini</button>
            </div>
        </div>

    </div>

    <script>
        const loginSection = document.getElementById('login-section');
        const registerSection = document.getElementById('register-section');
        const btnGoToRegister = document.getElementById('go-to-register');
        const btnGoToLogin = document.getElementById('go-to-login');
        
        const tabEmail = document.getElementById('tab-email');
        const tabPhone = document.getElementById('tab-phone');
        const loginInputContainer = document.getElementById('login-input-container');

        // Navigasi halaman (Login <-> Register)
        btnGoToRegister.addEventListener('click', () => {
            loginSection.classList.add('hidden');
            registerSection.classList.remove('hidden');
        });

        btnGoToLogin.addEventListener('click', () => {
            registerSection.classList.add('hidden');
            loginSection.classList.remove('hidden');
        });

        // Logika penukaran Tab Login
        tabEmail.addEventListener('click', () => {
            tabEmail.className = "tab-btn active";
            tabPhone.className = "tab-btn inactive";
            
            loginInputContainer.innerHTML = `
                <label class="input-label">Email</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <i class="fa-regular fa-envelope"></i>
                    </span>
                    <input type="email" placeholder="nama@email.com" class="form-input" name="email">
                </div>
            `;
        });

        tabPhone.addEventListener('click', () => {
            tabPhone.className = "tab-btn active";
            tabEmail.className = "tab-btn inactive";
            
            loginInputContainer.innerHTML = `
                <label class="input-label">Nomor Telepon</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <i class="fa-solid fa-phone"></i>
                    </span>
                    <input type="text" placeholder="+62 812 3456 7890" class="form-input" name="phone">
                </div>
            `;
        });

        // Toggle Show/Hide Password
        document.body.addEventListener('click', function(e) {
            if (e.target.closest('.password-toggle')) {
                const toggleBtn = e.target.closest('.password-toggle');
                const passwordInput = toggleBtn.parentElement.querySelector('input');
                const eyeIcon = toggleBtn.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.className = 'fa-solid fa-eye-slash';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.className = 'fa-regular fa-eye';
                }
            }
        });
    </script>

    <script>
        window.onload = function () {
            const formType = "{{ session('form_type') }}";

            if (formType === 'register') {
                loginSection.classList.add('hidden');
                registerSection.classList.remove('hidden');
            } else {
                loginSection.classList.remove('hidden');
                registerSection.classList.add('hidden');
            }
        };
    </script>
</body>
</html>