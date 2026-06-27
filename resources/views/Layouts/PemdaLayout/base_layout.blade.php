
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title')</title>
    <link rel="icon" type="image/ico" href="">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- sweet alert -->
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-light);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            box-shadow: var(--shadow-default);
            z-index: 1000;
        }

        .sidebar-header {
            text-align: center;
            margin-bottom: 40px;
            padding: 0 20px;
        }

        .sidebar-header h2 {
            font-size: 1.8em;
            font-weight: 600;
            color: var(--text-light);
        }

        .sidebar-nav ul {
            list-style: none;
        }

        .sidebar-nav ul li {
            margin-bottom: 10px;
        }

        .sidebar-nav ul li a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease, padding-left 0.3s ease;
            border-left: 5px solid transparent;
        }

        .sidebar-nav ul li a:hover,
        .sidebar-nav ul li a.active {
            background-color: var(--secondary-color);
            border-left-color: var(--text-light);
            padding-left: 30px;
        }

        .sidebar-nav ul li a i {
            margin-right: 15px;
            font-size: 1.2em;
        }

    
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            height: var(--navbar-height);
            background-color: var(--text-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 30px;
            box-shadow: var(--shadow-default);
            z-index: 999; 
        }

        .navbar-search input {
            border: 1px solid #ddd;
            padding: 10px 15px;
            border-radius: var(--border-radius-default);
            font-size: 0.95em;
            width: 250px;
            transition: width 0.3s ease;
        }

        .navbar-search input:focus {
            width: 300px;
            outline: none;
            border-color: var(--primary-color);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
        }

        .admin-profile-pic {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: var(--primary-color); 
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            border: 2px solid var(--secondary-color);
        }

        .admin-profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .admin-profile-info {
            margin-left: 15px;
            font-weight: 500;
            font-size: 1.05em;
        }

        .admin-profile-info span {
            display: block;
            font-size: 0.85em;
            color: #777;
        }

        .content-area {
            flex-grow: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .content-area h1 {
            font-size: 2em;
            margin-bottom: 25px;
            color: var(--primary-color);
        }

        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .card {
            background-color: var(--text-light);
            border-radius: var(--border-radius-default);
            padding: 25px;
            box-shadow: var(--shadow-default);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .card p {
            font-size: 0.95em;
            line-height: 1.6;
        }

        @media (max-width: 992px) {
            .sidebar {
                width: 80px; 
                align-items: center;
            }

            .sidebar-header h2 {
                display: none;
            }

            .sidebar-nav ul li a {
                justify-content: center;
                padding: 15px 0;
            }

            .sidebar-nav ul li a i {
                margin-right: 0;
            }

            .sidebar-nav ul li a span {
                display: none;
            }

            .navbar-search input {
                width: 180px;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                flex-direction: row;
                justify-content: space-around;
                padding: 10px 0;
            }

            .sidebar-header {
                display: none;
            }

            .sidebar-nav ul {
                display: flex;
                flex-grow: 1;
                justify-content: space-around;
            }

            .sidebar-nav ul li {
                margin-bottom: 0;
            }

            .sidebar-nav ul li a {
                padding: 10px;
            }

            .main-content {
                width: 100%;
            }

            .navbar {
                padding: 0 20px;
            }

            .navbar-search {
                display: none; 
            }
            
        }


        .filter-form-container {
            margin-bottom: 2rem; 
            display: flex;
            flex-direction: column; 
            align-items: flex-start; 
            gap: 1rem; 
            padding: 1rem;
            background-color: #ffffff; 
            border-radius: 0.5rem; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); 
        }

        @media (min-width: 640px) {
            .filter-form-container {
                flex-direction: row; 
                align-items: center;
            }
        }

        .form-group {
            flex-grow: 1;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.25rem;
        }

        .form-control-date {
            display: block;
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            padding: 0.625rem 0.75rem;
            color: #4a5568;
            background-color: #f9fafb;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            margin-bottom: 0;
        }

        .form-control-date:focus {
            outline: none;
            border-color: #AA0E0E;
            /* box-shadow: #AA0E0E; */
        }
    </style>
</head>
    <body>
    <aside class="sidebar">

        @php
            $user = auth()->user();
        @endphp


        {{-- HEADER --}}
        <div class="sidebar-header flex flex-col items-center mb-10 px-5">

            <a href="/" class="flex items-center gap-4 group">
                <span class="text-xl font-bold group-hover:text-[var(--color-primary-btn)]">
                    SILAPOR
                </span>
            </a>

            <span class="text-xs text-gray-200 mt-1">
                {{ $user->departments->pluck('name')->join(', ') ?: 'Tidak ada dinas' }}
            </span>

        </div>

        <nav class="sidebar-nav">
            <ul>

                {{-- ================= DASHBOARD ================= --}}
                <li>
                    <a href="{{ route('pemda.dashboard') }}"
                    class="{{ request()->routeIs('pemda.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- ================= REPORTS ================= --}}
                <li>
                    <a href="{{ route('pemda.reports.index') }}"
                    class="{{ request()->routeIs('pemda.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                    </a>
                </li>

                {{-- ================= PROFILE ================= --}}
                <li>
                    <a href="{{ route('pemda.profile') }}"
                    class="{{ request()->routeIs('pemda.profile') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                </li>

            </ul>
        </nav>
    </aside>


    {{-- ================= MAIN CONTENT ================= --}}
    <div class="main-content">

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
                        <p class="text-xs text-gray-500 capitalize">{{ $user->role }} {{ $user->departments->pluck('code')->join(', ') ?: '-' }}</p>
                    </div>

                </div>
            </a>

        </nav>

        <main class="content-area">
            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('scripts')
</body>
</html>