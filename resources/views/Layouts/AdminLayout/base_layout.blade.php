<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — SILAPOR Admin</title>
    <link rel="icon" type="image/ico" href="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #133a68; 
            --secondary-color: #133a68 ;
            --background-light: #F0F2F5;
            --text-dark: #333;
            --text-light: #fff;
            --sidebar-width: 250px;
            --navbar-height: 70px;
            --border-radius-default: 8px;
            --shadow-default: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-default);
            z-index: 1000;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 0 20px;
        }

        .sidebar-nav ul { list-style: none; }

        .sidebar-nav ul li { margin-bottom: 4px; }

        .sidebar-nav ul li a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9em;
            transition: background-color 0.2s ease, padding-left 0.2s ease, color 0.2s ease;
            border-left: 4px solid transparent;
        }

        .sidebar-nav ul li a:hover,
        .sidebar-nav ul li a.active {
            background-color: rgba(0,0,0,0.2);
            border-left-color: var(--text-light);
            padding-left: 30px;
            color: var(--text-light);
        }

        .sidebar-nav ul li a i {
            margin-right: 12px;
            font-size: 1em;
            width: 18px;
            text-align: center;
        }

        /* Grup label di sidebar */
        .sidebar-group-label {
            padding: 16px 25px 6px;
            font-size: 0.7em;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
        }

        /* ===== MAIN ===== */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .navbar {
            height: var(--navbar-height);
            background-color: var(--text-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            box-shadow: var(--shadow-default);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-search input {
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: var(--border-radius-default);
            font-size: 0.9em;
            width: 250px;
            transition: width 0.3s ease, border-color 0.2s ease;
        }

        .navbar-search input:focus {
            width: 300px;
            outline: none;
            border-color: var(--primary-color);
        }

        .content-area {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .content-area h1 {
            font-size: 1.8em;
            margin-bottom: 25px;
            color: var(--primary-color);
        }

        /* ===== CARDS ===== */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .card {
            background-color: var(--text-light);
            border-radius: var(--border-radius-default);
            padding: 25px;
            box-shadow: var(--shadow-default);
            transition: transform 0.25s ease;
        }

        .card:hover { transform: translateY(-4px); }

        .card h3 {
            margin-bottom: 12px;
            color: var(--primary-color);
        }

        .card p { font-size: 0.95em; line-height: 1.6; }

        /* ===== FILTER FORM ===== */
        .filter-form-container {
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
        }

        @media (min-width: 640px) {
            .filter-form-container { flex-direction: row; align-items: center; }
        }

        .form-group { flex-grow: 1; }
        .form-group label { display: block; font-size: 0.875rem; font-weight: 600; color: #2d3748; margin-bottom: 0.25rem; }

        .form-control-date {
            display: block;
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
            padding: 0.625rem 0.75rem;
            color: #4a5568;
            background-color: #f9fafb;
            transition: border-color 0.15s ease-in-out;
        }

        .form-control-date:focus { outline: none; border-color: var(--primary-color); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 992px) {
            .sidebar { width: 70px; }
            .sidebar-header h2,
            .sidebar-header p,
            .sidebar-nav ul li a span,
            .sidebar-group-label { display: none; }
            .sidebar-nav ul li a { justify-content: center; padding: 14px 0; border-left: none; border-bottom: 3px solid transparent; }
            .sidebar-nav ul li a:hover,
            .sidebar-nav ul li a.active { padding-left: 0; border-bottom-color: var(--text-light); }
            .sidebar-nav ul li a i { margin-right: 0; }
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; flex-direction: row; padding: 8px 0; position: relative; }
            .sidebar-header { display: none; }
            .sidebar-nav ul { display: flex; flex-grow: 1; justify-content: space-around; }
            .sidebar-nav ul li { margin-bottom: 0; }
            .sidebar-nav ul li a { padding: 10px 8px; }
            .navbar-search { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>

    @php $user = auth()->user(); @endphp

    {{-- ===== SIDEBAR ===== --}}
    <aside class="sidebar">

        {{-- Header --}}
        <div class="sidebar-header flex flex-col items-center mb-6 px-5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 group">
                <span class="text-xl font-bold tracking-wide group-hover:opacity-80">SILAPOR</span>
            </a>
            <span class="text-xs mt-1 px-2 py-0.5 rounded-full bg-white/20 font-semibold tracking-widest uppercase">
                Admin
            </span>
        </div>

        <nav class="sidebar-nav flex-1">
            <ul>

                {{-- OVERVIEW --}}
                <li class="sidebar-group-label">Overview</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- MASTER DATA --}}
                <li class="sidebar-group-label">Master Data</li>

                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Pengguna</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.departments.index') }}"
                       class="{{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i>
                        <span>Dinas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.categories.index') }}"
                       class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Kategori</span>
                    </a>
                </li>

                {{-- LAPORAN --}}
                <li class="sidebar-group-label">Laporan</li>

                <li>
                    <a href="{{ route('admin.reports.index') }}"
                       class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Semua Laporan</span>
                    </a>
                </li>

                {{-- ANALITIK --}}
                <li class="sidebar-group-label">Analitik</li>

                <li>
                    <a href="{{ route('admin.stats.overview') }}"
                       class="{{ request()->routeIs('admin.stats.overview') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Statistik</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.stats.departments') }}"
                       class="{{ request()->routeIs('admin.stats.departments') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i>
                        <span>Performa Dinas</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.stats.top-votes') }}"
                       class="{{ request()->routeIs('admin.stats.top-votes') ? 'active' : '' }}">
                        <i class="fas fa-fire"></i>
                        <span>Top Votes</span>
                    </a>
                </li>

                {{-- SISTEM --}}
                <li class="sidebar-group-label">Sistem</li>

                <li>
                    <a href="{{ route('admin.audit-logs.index') }}"
                       class="{{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}">
                        <i class="fas fa-history"></i>
                        <span>Audit Log</span>
                    </a>
                </li>

            </ul>
        </nav>

        {{-- Logout di bawah sidebar --}}
        <div class="px-4 pb-4 mt-auto">
            <form action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-2 rounded-md text-white/80 hover:bg-black/20 hover:text-white transition text-sm font-medium">
                    <i class="fas fa-sign-out-alt w-4 text-center"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="main-content">

        {{-- NAVBAR --}}
         <nav class="navbar">

            <div class="navbar-search">
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="text-red-600 hover:bg-red-100 px-3 py-2 rounded-md">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>

            @php
                $avatarUrl = $user->avatar
                ? asset('storage/' . $user->avatar)
                : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=133a68&color=fff';
            @endphp

            <a href="{{ route('pemda.profile') }}">
                <div class="flex items-center gap-3 p-2 bg-white shadow rounded-md">

                    <div class="w-8 h-8 rounded-full overflow-hidden">
                        <img src="{{ $avatarUrl }}" class="w-full h-full object-cover">
                    </div>

                    <div>
                        <p class="text-sm font-semibold">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $user->role }}</p>
                    </div>

                </div>
            </a>

        </nav>

        {{-- CONTENT --}}
        <main class="content-area">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium"
                     style="background:#10b9811A; color:#10b981; border:1px solid #10b98133;">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium"
                     style="background:#ef44441A; color:#ef4444; border:1px solid #ef444433;">
                    <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')

</body>
</html>