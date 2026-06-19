<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SiLapor - Sistem Laporan Darurat Bekasi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style type="text/tailwindcss">
        @layer base {
        body {
            font-family: 'Inter', sans-serif;
        }
        }
        /* --- Core Configurations & Variables Setup --- */
        :root {
            --primary-blue: #0b2240;
            --accent-blue: #133a68;
            --brand-orange: #ff761b;
            --brand-orange-hover: #e05e0b;
            --text-white: #ffffff;
            --bg-light: #f5f8fc;
            --border-color: rgba(255, 255, 255, 0.1);
            
            /* System Colors Constants */
            --sys-orange: #f2994a;
            --sys-blue: #2f80ed;
            --sys-yellow: #f2c94c;
            --sys-gray: #4f4f4f;
            --sys-green: #27ae60;
            --sys-red: #eb5757;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-light);
            color: #333333;
            overflow-x: hidden;
        }

        /* --- Emergency Hotline Header Bar --- */
        .emergency-bar {
            background-color: #061529;
            padding: 8px 5%;
            font-size: 12px;
            color: #cbd5e1;
        }

        .eb-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .eb-tags {
            display: flex;
            gap: 10px;
        }

        .eb-tag {
            color: var(--text-white);
            text-decoration: none;
            padding: 3px 12px;
            border-radius: 20px;
            font-weight: 600;
            transition: opacity 0.2s;
        }

        .eb-tag:hover {
            opacity: 0.85;
        }

        .eb-tag.polisi { background-color: #2f80ed; }
        .eb-tag.pemadam { background-color: #eb5757; }
        .eb-tag.ambulans { background-color: #27ae60; }
        .eb-tag.darurat { background-color: #e05e0b; }

        /* --- Navbar Navigation System Layout --- */
        .navbar-wrapper {
            background-color: var(--primary-blue);
            position: sticky;
            top: 0;
            z-index: 999;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5%;
            max-width: 1400px;
            margin: 0 auto;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            background-color: rgba(255,255,255,0.1);
            color: var(--text-white);
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .brand-text h1 {
            color: var(--text-white);
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .brand-text p {
            color: rgba(255,255,255,0.6);
            font-size: 11px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 25px;
        }

        .nav-links a {
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 4px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--text-white);
        }

        /* Dropdown Menu Box Component */
        .dropdown {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            background-color: var(--text-white);
            list-style: none;
            min-width: 180px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            padding: 8px 0;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .dropdown:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-menu a {
            color: #333333 !important;
            padding: 10px 20px;
            display: block;
        }

        .dropdown-menu a:hover {
            background-color: #f0f4f8;
            color: var(--brand-orange) !important;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-notif {
            background: none;
            border: none;
            color: var(--text-white);
            font-size: 20px;
            cursor: pointer;
            position: relative;
            padding: 5px;
            transition: transform 0.2s;
        }

        .btn-notif:hover {
            transform: scale(1.1);
        }

        .btn-notif .badge {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 8px;
            height: 8px;
            background-color: var(--brand-orange);
            border-radius: 50%;
        }

        .btn-lapor {
            background-color: var(--brand-orange);
            color: var(--text-white);
            border: none;
            padding: 10px 22px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            box-shadow: 0 4px 14px rgba(255, 118, 27, 0.3);
            transition: all 0.3s ease;
        }

        .btn-lapor:hover {
            background-color: var(--brand-orange-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 118, 27, 0.4);
        }

        .btn-lapor:active {
            transform: translateY(0);
        }

        /* --- 2. Hero Presentation Section Architecture --- */
        .hero-section {
            background: radial-gradient(circle at top right, #112d52 0%, var(--primary-blue) 100%);
            padding: 60px 5% 140px 5%;
            position: relative;
        }

        /* Background Cross Pattern Overlay */
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(rgba(255,255,255,0.05) 1px, transparent 0);
            background-size: 24px 24px;
            pointer-events: none;
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 50px;
            align-items: center;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            color: rgba(255,255,255,0.8);
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 12px;
            margin-bottom: 25px;
        }

        .status-pill .indicator {
            width: 8px;
            height: 8px;
            background-color: #27ae60;
            border-radius: 50%;
            box-shadow: 0 0 8px #27ae60;
        }

        .hero-title {
            color: var(--text-white);
            font-size: 42px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero-title .highlight {
            color: var(--brand-orange);
        }

        .hero-desc {
            color: rgba(255, 255, 255, 0.65);
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .hero-cta-group {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-cta-primary {
            background: linear-gradient(90deg, #ff761b, #ff944d);
            color: var(--text-white);
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(255, 118, 27, 0.4);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 118, 27, 0.5);
        }

        .btn-cta-secondary {
            border: 1px solid rgba(255,255,255,0.25);
            background-color: rgba(255,255,255,0.05);
            color: var(--text-white);
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-cta-secondary:hover {
            background-color: rgba(255,255,255,0.1);
            border-color: rgba(255,255,255,0.5);
        }

        /* Right Side Category Cards Grid Pattern */
        .hero-grid-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .stat-card {
            border-radius: 12px;
            padding: 24px;
            color: var(--text-white);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .stat-card .card-icon {
            font-size: 26px;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .stat-card h3 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 6px;
            opacity: 0.9;
        }

        .stat-card .count {
            font-size: 24px;
            font-weight: 700;
        }

        .stat-card .count span {
            font-size: 12px;
            font-weight: 400;
            opacity: 0.7;
            display: inline-block;
            margin-left: 4px;
        }

        /* Categorized Background Configurations */
        .orange-card { background: linear-gradient(135deg, #e67e22, #d35400); }
        .blue-card { background: linear-gradient(135deg, #3498db, #2980b9); }
        .yellow-card { background: linear-gradient(135deg, #f1c40f, #f39c12); }
        .gray-card { background: linear-gradient(135deg, #7f8c8d, #95a5a6); }
        .green-card { background: linear-gradient(135deg, #2ecc71, #27ae60); }
        .red-card { background: linear-gradient(135deg, #e74c3c, #c0392b); }

        /* Ribbon tag indicator 'Aktif' */
        .active-pill::after {
            content: 'AKTIF';
            position: absolute;
            top: 12px;
            right: 12px;
            background-color: rgba(255,255,255,0.2);
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }

        /* Floating Metrics Bar Row Layout */
        .summary-stats-bar {
            max-width: 1400px;
            margin: 0 auto;
            background-color: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            
            border-radius: 16px;
            padding: 25px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            z-index: 10;
            position: absolute;
            bottom: 10px; 
            left: 5%;
            right: 5%;
        }

        .stat-box {
            display: flex;
            align-items: center;
            gap: 16px;
            justify-content: center;
        }

        .stat-box:not(:last-child) {
            border-right: 1px solid rgba(255,255,255,0.15);
        }

        .sb-icon {
            width: 46px;
            height: 46px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--text-white);
            font-size: 18px;
        }

        .sb-text h3 {
            color: var(--text-white);
            font-size: 20px;
            font-weight: 700;
        }

        .sb-text p {
            color: rgba(255,255,255,0.6);
            font-size: 12px;
            margin-top: 2px;
        }

        /* --- 3. Map Section Layout System --- */
        .map-section-wrapper {
            max-width: 1400px;
            margin: 140px auto 60px auto;
            padding: 0 5%;
        }

        .section-header {
            margin-bottom: 25px;
        }

        .section-header.row-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 20px;
        }

        .sub-title {
            color: var(--brand-orange);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 6px;
        }

        .main-title {
            font-size: 28px;
            color: var(--primary-blue);
            font-weight: 700;
        }

        .section-desc {
            color: #666666;
            font-size: 14px;
            margin-top: 5px;
        }

        .refresh-indicator {
            font-size: 12px;
            color: #888888;
            background-color: #eef2f7;
            padding: 6px 14px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Filters Navigation Styling UI */
        .map-filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-filter {
            background-color: var(--text-white);
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .btn-filter:hover {
            background-color: #f7fafc;
            border-color: #cbd5e0;
        }

        .btn-filter.active {
            background-color: var(--accent-blue);
            color: var(--text-white);
            border-color: var(--accent-blue);
        }

        .status-legend {
            display: flex;
            align-items: center;
            gap: 15px;
            background-color: var(--text-white);
            padding: 6px 16px;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            font-size: 13px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-item .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
        }

        .dot.red { background-color: var(--sys-red); }
        .dot.orange { background-color: var(--sys-orange); }
        .dot.green { background-color: var(--sys-green); }

        .btn-refresh {
            background: none;
            border: none;
            color: #718096;
            cursor: pointer;
            font-size: 14px;
            padding: 2px;
            margin-left: 5px;
            transition: transform 0.3s;
        }

        .btn-refresh:hover {
            transform: rotate(180deg);
            color: #2d3748;
        }

        /* Map Content Splitting Grid Panels */
        .map-content-container {
            display: grid;
            grid-template-columns: 1.4fr 0.6fr;
            background-color: var(--text-white);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        .map-canvas {
            height: 520px;
            width: 100%;
            z-index: 1;
        }

        /* Incident Panel UI Right Sidebar Component */
        .incident-panel {
            display: flex;
            flex-direction: column;
            height: 520px;
            background-color: #ffffff;
            border-left: 1px solid #e2e8f0;
        }

        .panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .panel-header h3 {
            font-size: 15px;
            color: #1a202c;
            font-weight: 700;
        }

        .panel-header h3 span {
            color: #718096;
            font-weight: 400;
            font-size: 13px;
        }

        .incident-list {
            overflow-y: auto;
            flex: 1;
        }

        .incident-item {
            padding: 16px 20px;
            border-bottom: 1px solid #f7fafc;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .incident-item:hover, .incident-item.selected {
            background-color: #f0f6ff;
        }

        .incident-item-title {
            font-size: 14px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 4px;
        }

        .incident-item-meta {
            font-size: 12px;
            color: #718096;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .incident-status-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-badge {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 4px;
        }

        .status-badge.aktif { background-color: #ffebe6; color: var(--sys-red); }
        .status-badge.proses { background-color: #fff0db; color: var(--sys-orange); }
        .status-badge.selesai { background-color: #e6f7ed; color: var(--sys-green); }

        .incident-time {
            font-size: 11px;
            color: #a0aec0;
        }

        /* --- 4. Popular Reports Grid Feed Layout Section --- */
        .popular-reports-section {
            max-width: 1400px;
            margin: 60px auto;
            padding: 0 5%;
        }

        .feed-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 500;
            color: #718096;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .tab-btn:hover {
            color: var(--primary-blue);
            background-color: #eef2f7;
        }

        .tab-btn.active {
            background-color: var(--accent-blue);
            color: var(--text-white);
        }

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .report-card {
            background-color: var(--text-white);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            border: 1px solid #e2e8f0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.08);
        }

        .card-img-wrapper {
            position: relative;
            height: 180px;
            background-color: #e2e8f0;
        }

        .card-img-placeholder {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-badges {
            position: absolute;
            top: 12px;
            left: 12px;
            right: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .badge-status-dot {
            background-color: rgba(0, 0, 0, 0.6);
            color: var(--text-white);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(4px);
        }

        .badge-status-dot::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .badge-status-dot.aktif::before { background-color: var(--sys-red); }
        .badge-status-dot.proses::before { background-color: var(--sys-orange); }
        .badge-status-dot.selesai::before { background-color: var(--sys-green); }

        .badge-priority {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-priority.tinggi { background-color: #ffebe6; color: var(--sys-red); }
        .badge-priority.sedang { background-color: #fff0db; color: var(--sys-orange); }

        .card-body {
            padding: 20px;
        }

        .card-category {
            font-size: 12px;
            color: #a0aec0;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .card-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a202c;
            line-height: 1.4;
            margin-bottom: 8px;
            height: 42px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-text {
            font-size: 13px;
            color: #4a5568;
            line-height: 1.5;
            margin-bottom: 15px;
            height: 58px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-location {
            font-size: 12px;
            color: #718096;
            margin-bottom: 16px;
        }

        .card-footer-metrics {
            border-top: 1px solid #f0f4f8;
            padding-top: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .metrics-left-group {
            display: flex;
            gap: 12px;
        }

        .metric-item-btn {
            background: none;
            border: none;
            font-size: 12px;
            color: #718096;
            display: flex;
            align-items: center;
            gap: 4px;
            cursor: pointer;
            transition: color 0.2s;
        }

        .metric-item-btn:hover {
            color: var(--brand-orange);
        }

        .metric-item-btn.upvoted {
            color: var(--brand-orange);
            font-weight: 600;
        }

        .metric-time {
            font-size: 11px;
            color: #a0aec0;
        }

        .center-action {
            text-align: center;
            margin-top: 40px;
        }

        .btn-load-more {
            background-color: var(--text-white);
            border: 1px solid var(--accent-blue);
            color: var(--accent-blue);
            padding: 12px 30px;
            font-weight: 600;
            font-size: 14px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-load-more:hover {
            background-color: var(--accent-blue);
            color: var(--text-white);
        }

        /* --- 5. Interactive Floating Action Button (FAB) Area --- */
        .fab-wrapper {
            position: fixed;
            bottom: 35px;
            right: 35px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            /* PERBAIKAN 1: Ubah flex-end ke center agar pusat vertikal semua tombol (utama & sub-menu) sejajar lurus */
            align-items: center; 
        }

        .fab-main-trigger {
            width: 60px;
            height: 60px;
            background: radial-gradient(circle, var(--brand-orange) 0%, #e05e0b 100%);
            border-radius: 50%;
            border: none;
            outline: none;
            color: var(--text-white);
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 5px 20px rgba(224, 94, 11, 0.4);
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s;
        }

        .fab-main-trigger:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(224, 94, 11, 0.5);
        }

        .fab-main-trigger .icon-plus {
            transition: transform 0.3s ease;
        }

        /* Animasi Putar Saat FAB Aktif Terbuka */
        .fab-wrapper.open .fab-main-trigger .icon-plus {
            transform: rotate(135deg);
        }

        .fab-menu-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-bottom: 18px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: none;
            /* PERBAIKAN 2: Mengunci perataan menu list di tengah */
            align-items: center; 
        }

        .fab-wrapper.open .fab-menu-list {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        .fab-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            /* PERBAIKAN 3: Gunakan width 100% dan justify-content agar posisi sub-button tetap konsisten */
            justify-content: flex-end; 
            position: relative;
        }

        .fab-label {
            background-color: var(--text-white);
            color: #333333;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            white-space: nowrap;
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.2s ease;
            /* PERBAIKAN 4: Ubah ke posisi absolute agar lebar teks label tidak menggeser atau menekan lingkaran sub-button */
            position: absolute;
            right: 56px; /* 44px (lebar sub-button) + 12px (gap) */
        }

        .fab-menu-item:hover .fab-label {
            opacity: 1;
            transform: translateX(0);
        }

        .fab-sub-btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: none;
            color: var(--text-white);
            font-size: 16px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            /* PERBAIKAN 5: Pastikan icon di dalam sub-button benar-benar simetris di tengah */
            align-items: center; 
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: transform 0.2s;
            padding: 0; /* Menghilangkan padding bawaan browser yang bisa menggeser icon */
        }

        .fab-sub-btn:hover {
            transform: scale(1.1);
        }

        .fab-sub-btn.red-fab { background-color: var(--sys-red); }
        .fab-sub-btn.orange-fab { background-color: var(--sys-orange); }
        .fab-sub-btn.blue-fab { background-color: var(--sys-blue); }
        .fab-sub-btn.dark-fab { background-color: var(--primary-blue); }
        .fab-sub-btn.green-fab { background-color: var(--sys-green); }

        /* --- 6. Comprehensive Responsive Footer System --- */
        .footer-distributed {
            background-color: var(--primary-blue);
            padding: 60px 5% 30px 5%;
            border-top: 1px solid var(--border-color);
        }

        .footer-top-section {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.3fr repeat(3, 0.9fr);
            gap: 40px;
            padding-bottom: 40px;
            border-bottom: 1px solid var(--border-color);
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .footer-brand .fb-icon {
            background-color: rgba(255,255,255,0.08);
            color: var(--brand-orange);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 18px;
            border: 1px solid rgba(255,255,255,0.15);
        }

        .footer-brand h2 {
            color: var(--text-white);
            font-size: 20px;
            font-weight: 700;
        }

        .footer-brand p {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
        }

        .footer-about-text {
            color: rgba(255,255,255,0.65);
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .footer-contact-details {
            color: rgba(255,255,255,0.6);
            font-size: 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 20px;
        }

        .footer-contact-details i {
            width: 16px;
            color: var(--brand-orange);
        }

        .footer-social-medias {
            display: flex;
            gap: 10px;
        }

        .footer-social-medias a {
            width: 34px;
            height: 34px;
            background-color: rgba(255,255,255,0.06);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }

        .footer-social-medias a:hover {
            background-color: var(--brand-orange);
            color: var(--text-white);
            transform: translateY(-2px);
        }

        .footer-links-column h3 {
            color: var(--text-white);
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
        }

        .footer-links-column ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-links-column ul a {
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s;
        }

        .footer-links-column ul a:hover {
            color: var(--brand-orange);
        }

        .footer-bottom-copyright {
            max-width: 1400px;
            margin: 25px auto 0 auto;
            display: flex;
            justify-content: space-between;
            color: rgba(255,255,255,0.45);
            font-size: 12px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .footer-bottom-copyright .heart-icon {
            color: #eb5757;
        }

        /* --- 7. Modal Pop-up System Architecture --- */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(6, 21, 41, 0.6);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }

        .modal-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .modal-box {
            background-color: var(--text-white);
            width: 90%;
            max-width: 600px;
            border-radius: 16px;
            padding: 30px;
            position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            transform: translateY(-30px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.open .modal-box {
            transform: translateY(0);
        }

        .modal-close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #a0aec0;
        }

        .modal-close-btn:hover {
            color: #333333;
        }

        .modal-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 10px;
        }

        .modal-meta-row {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #718096;
            margin-bottom: 15px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .modal-desc {
            font-size: 14px;
            line-height: 1.6;
            color: #4a5568;
        }

        /* --- Media Queries Responsive Breakpoints System --- */
        @media (max-width: 1024px) {
            .hero-container { grid-template-columns: 1fr; gap: 40px; }
            .summary-stats-bar { grid-template-columns: repeat(2, 1fr); bottom: -120px; gap: 20px; }
            .map-section-wrapper { margin-top: 200px; }
            .map-content-container { grid-template-columns: 1fr; }
            .map-canvas, .incident-panel { height: 400px; }
            .incident-panel { border-left: none; border-top: 1px solid #e2e8f0; }
            .reports-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-top-section { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 768px) {
            .nav-links, .nav-actions .header-btn { display: none; }
            .hero-title { font-size: 32px; }
            .reports-grid { grid-template-columns: 1fr; }
            .summary-stats-bar { grid-template-columns: 1fr; bottom: -200px; }
            .map-section-wrapper { margin-top: 280px; }
            .footer-top-section { grid-template-columns: 1fr; }
            .footer-bottom-copyright { flex-direction: column; text-align: center; }
        }
    </style>
</head>
<body>
  
    <div class="emergency-bar">
        <div class="eb-container">
            <span><i class="fa-solid fa-phone-volume"></i> Nomor Darurat Jakarta:</span>
            <div class="eb-tags">
                <a href="tel:110" class="eb-tag polisi">Polisi &mdash; 110</a>
                <a href="tel:113" class="eb-tag pemadam">Pemadam &mdash; 113</a>
                <a href="tel:118" class="eb-tag ambulans">Ambulans &mdash; 118</a>
                <a href="tel:112" class="eb-tag darurat">Darurat &mdash; 112</a>
            </div>
        </div>
    </div>

    <header class="navbar-wrapper">
        <nav class="navbar">
            <div class="nav-brand">
                <div class="brand-icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="brand-text">
                    <h1>SiLapor</h1>
                    <p>Sistem Laporan Darurat Jakarta</p>
                </div>
            </div>
            <ul class="nav-links">
                <li><a href="#" class="active">Beranda</a></li>
                <li class="dropdown">
                    <a href="#">Layanan <i class="fa-solid fa-chevron-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="#map-section">Lapor Kerusakan</a></li>
                        <li><a href="#map-section">Status Bencana</a></li>
                        <li><a href="#map-section">Peta Insiden</a></li>
                    </ul>
                </li>
                <li><a href="#laporan-terpopuler">Laporan</a></li>
                <li><a href="#">Statistik</a></li>
                <li><a href="#">Tentang</a></li>
            </ul>
            <div class="nav-actions">
                <button class="btn-notif"><i class="fa-regular fa-bell"></i><span class="badge"></span></button>
                <button class="btn-lapor header-btn">Buat Laporan</button>
            </div>
        </nav>
    </header>

  <main>
    @yield('content')
  </main>

   <footer class="footer-distributed">
        <div class="footer-top-section">
            <div class="footer-left-branding">
                <div class="footer-brand">
                    <div class="fb-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="fb-text">
                        <h2>SiLapor</h2>
                        <p>Sistem Laporan Darurat Kota</p>
                    </div>
                </div>
                <p class="footer-about-text">
                    Platform resmi Pemerintah Kota Jakarta untuk pelaporan dan pemantauan kerusakan infrastruktur,
                    bencana alam ringan, dan gangguan layanan publik secara real-time.
                </p>
                <div class="footer-contact-details">
                    <p><i class="fa-solid fa-location-arrow"></i> Jl. Medan Merdeka Selatan No.8-9, Jakarta Pusat, DKI
                        Jakarta 10110</p>
                    <p><i class="fa-solid fa-phone"></i> (021) 1500177</p>
                    <p><i class="fa-solid fa-envelope"></i> silapor@jakarta.go.id</p>
                </div>
                <div class="footer-social-medias">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            <div class="footer-links-column">
                <h3>LAYANAN</h3>
                <ul>
                    <li><a href="#">Lapor Kerusakan Jalan</a></li>
                    <li><a href="#">Status Bencana Alam</a></li>
                    <li><a href="#">Pantau Banjir</a></li>
                    <li><a href="#">Laporan Saya</a></li>
                    <li><a href="#">Statistik Kota</a></li>
                </ul>
            </div>

            <div class="footer-links-column">
                <h3>INSTANSI TERKAIT</h3>
                <ul>
                    <li><a href="#">Dinas PU Kota Jakarta</a></li>
                    <li><a href="#">BPBD Kota Jakarta</a></li>
                    <li><a href="#">Dinas Kebersihan</a></li>
                    <li><a href="#">PLN Distribusi Jabar</a></li>
                    <li><a href="#">PDAM Tirtawening</a></li>
                </ul>
            </div>

            <div class="footer-links-column">
                <h3>BANTUAN</h3>
                <ul>
                    <li><a href="#">Cara Membuat Laporan</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Hubungi Kami</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom-copyright">
            <p>&copy; 2026 SiLapor &mdash; Pemerintah Kota Jakarta. Hak Cipta Dilindungi.</p>
            <p>Dibuat dengan <span class="heart-icon">&hearts;</span> untuk warga Jakarta</p>
        </div>
    </footer>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.1/dist/sweetalert2.all.min.js"></script>

</body>
</html>
