{{-- resources/views/Pemda/reports/export_pdf.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengaduan Masyarakat</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            padding: 36px 40px; /* <-- margin kertas lebih lega */
        }

        /* ---- HEADER ---- */
        .header {
            display: table;
            width: 100%;
            padding-bottom: 16px;
            border-bottom: 3px solid #0b2240;
            margin-bottom: 24px;
        }
        .header-left  { display: table-cell; vertical-align: bottom; }
        .header-right { display: table-cell; vertical-align: bottom; text-align: right; }

        .header-left h1 {
            font-size: 16px;
            font-weight: 700;
            color: #0b2240;
            letter-spacing: 0.4px;
        }
        .header-left p {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 4px;
        }
        .header-right p      { font-size: 10px; color: #94a3b8; }
        .header-right strong { font-size: 11px; color: #0b2240; }

        /* ---- STAT CARDS ---- */
        .stat-row {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px;
            margin-bottom: 20px;
        }
        .stat-row-inner { display: table-row; }

        .stat-card {
            display: table-cell;
            width: 20%;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .stat-card h3 {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .stat-card p { font-size: 22px; font-weight: 700; }

        /* Warna pakai --sys-* dari variable kamu */
        .c-total   { border-left: 4px solid #0b2240; } .c-total   p { color: #0b2240; }
        .c-active  { border-left: 4px solid #f2994a; } .c-active  p { color: #f2994a; }
        .c-process { border-left: 4px solid #2f80ed; } .c-process p { color: #2f80ed; }
        .c-done    { border-left: 4px solid #27ae60; } .c-done    p { color: #27ae60; }
        .c-reject  { border-left: 4px solid #eb5757; } .c-reject  p { color: #eb5757; }

        /* ---- FILTER BAR ---- */
        .filter-bar {
            font-size: 10px;
            color: #4f4f4f;
            background: #f5f8fc;
            border-left: 3px solid #ff761b; /* brand-orange */
            padding: 8px 14px;
            border-radius: 0 6px 6px 0;
            margin-bottom: 20px;
        }

        /* ---- TABLE CARD ---- */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
        }
        .card-header {
            display: table;
            width: 100%;
            padding: 12px 16px;
            background: #f5f8fc;
            border-bottom: 2px solid #e2e8f0;
        }
        .card-header-left  { display: table-cell; vertical-align: middle; }
        .card-header-right { display: table-cell; vertical-align: middle; text-align: right; }
        .card-header h3    { font-size: 12px; font-weight: 700; color: #0b2240; }
        .card-header span  { font-size: 10px; color: #94a3b8; }

        table.main { width: 100%; border-collapse: collapse; font-size: 10px; }

        table.main thead tr   { background: #0b2240; }
        table.main thead th   {
            padding: 10px 12px;
            color: #fff;
            font-weight: 700;
            text-align: left;
            font-size: 9.5px;
        }
        table.main thead th.c { text-align: center; }

        table.main tbody tr:nth-child(even) { background: #f5f8fc; }
        table.main tbody tr:nth-child(odd)  { background: #ffffff; }

        table.main tbody td {
            padding: 11px 12px;
            border-bottom: 1px solid #e8edf4;
            vertical-align: top;
            color: #334155;
        }
        table.main tbody td.c { text-align: center; }

        .title-text {
            font-weight: 600;
            color: #0b2240;
            margin-bottom: 3px;
            font-size: 10.5px;
        }
        .addr-text { font-size: 9px; color: #94a3b8; }

        /* Badge — warna sys dari variable kamu */
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
        }
        .b-active  { background: #fef0e6; color: #c2690a; }
        .b-process { background: #e8f0fd; color: #1a5fb5; }
        .b-done    { background: #e3f5eb; color: #1a8048; }
        .b-reject  { background: #fdeaea; color: #c0392b; }

        /* Vote pakai brand-orange */
        .vote-num { font-weight: 700; color: #ff761b; font-size: 10px; }

        /* ---- FOOTER ---- */
        .footer {
            display: table;
            width: 100%;
            margin-top: 16px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
        }
        .footer-left  { display: table-cell; font-size: 9px; color: #94a3b8; }
        .footer-right { display: table-cell; font-size: 9px; color: #94a3b8; text-align: right; }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="header" style="width:100%; border-bottom:3px solid #0b2240; padding-bottom:16px; margin-bottom:24px;">
        <tr>
            <td style="vertical-align:bottom;">
                <h1 style="font-size:16px; font-weight:700; color:#0b2240; letter-spacing:0.4px;">
                    LAPORAN PENGADUAN MASYARAKAT
                </h1>
                <p style="font-size:10px; color:#94a3b8; margin-top:4px;">
                    Sistem Pengaduan Masyarakat &middot; {{ config('app.name') }}
                </p>
            </td>
            <td style="vertical-align:bottom; text-align:right;">
                <p style="font-size:10px; color:#94a3b8;">Dicetak pada</p>
                <strong style="font-size:11px; color:#0b2240;">
                    {{ now()->translatedFormat('d F Y, H:i') }} WIB
                </strong>
            </td>
        </tr>
    </table>

    {{-- Stat Cards --}}
    <table class="stat-row">
        <tr class="stat-row-inner">
            <td class="stat-card c-total">
                <h3>Total</h3>
                <p>{{ $stats['total'] }}</p>
            </td>
            <td class="stat-card c-active">
                <h3>Aktif</h3>
                <p>{{ $stats['active'] }}</p>
            </td>
            <td class="stat-card c-process">
                <h3>Diproses</h3>
                <p>{{ $stats['process'] }}</p>
            </td>
            <td class="stat-card c-done">
                <h3>Selesai</h3>
                <p>{{ $stats['done'] }}</p>
            </td>
            <td class="stat-card c-reject">
                <h3>Ditolak</h3>
                <p>{{ $stats['rejected'] }}</p>
            </td>
        </tr>
    </table>

    {{-- Filter info --}}
    @if($filterText)
        <div class="filter-bar">
            <strong>Filter aktif:</strong> {{ $filterText }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card">
        <table class="card-header" style="width:100%;">
            <tr>
                <td class="card-header-left"><h3>Daftar Laporan</h3></td>
                <td class="card-header-right"><span>{{ count($reports) }} laporan ditemukan</span></td>
            </tr>
        </table>

        <table class="main">
            <thead>
                <tr>
                    <th style="width:30px;" class="c">#</th>
                    <th style="width:33%;">Laporan</th>
                    <th style="width:13%;">Pelapor</th>
                    <th style="width:12%;">Kategori</th>
                    <th style="width:11%;" class="c">Status</th>
                    <th style="width:7%;"  class="c">Vote</th>
                    <th style="width:11%;" class="c">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $i => $report)
                    @php
                        $badgeClass = match($report->status) {
                            'active'   => 'b-active',
                            'process'  => 'b-process',
                            'done'     => 'b-done',
                            'rejected' => 'b-reject',
                            default    => '',
                        };
                        $statusLabel = match($report->status) {
                            'active'   => 'Aktif',
                            'process'  => 'Diproses',
                            'done'     => 'Selesai',
                            'rejected' => 'Ditolak',
                            default    => $report->status,
                        };
                    @endphp
                    <tr>
                        <td class="c" style="color:#94a3b8;">{{ $i + 1 }}</td>
                        <td>
                            <div class="title-text">{{ Str::limit($report->title, 55) }}</div>
                            <div class="addr-text">{{ Str::limit($report->address, 50) }}</div>
                        </td>
                        <td>{{ $report->user->name ?? '-' }}</td>
                        <td style="color:#4f4f4f;">{{ $report->category->name ?? '-' }}</td>
                        <td class="c">
                            <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="c">
                            <span class="vote-num">&#8593; {{ $report->votes_count }}</span>
                        </td>
                        <td class="c" style="color:#64748b;">
                            {{ $report->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:30px; color:#94a3b8;">
                            Tidak ada data laporan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <table class="footer" style="width:100%;">
        <tr>
            <td class="footer-left">
                Total {{ count($reports) }} laporan &nbsp;&middot;&nbsp; Sistem Pengaduan Masyarakat
            </td>
            <td class="footer-right">
                Dokumen dicetak otomatis oleh sistem
            </td>
        </tr>
    </table>

</body>
</html>