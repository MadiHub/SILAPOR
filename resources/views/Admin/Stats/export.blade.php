@extends('Layouts.AdminLayout.base_layout')

@section('title', 'Export Data')

@section('content')

<div style="margin-bottom:24px;">
    <h1 style="margin:0;">Export Data</h1>
</div>

@include('Admin.Stats._tabs', ['active' => 'export'])

<div style="max-width:760px;">

    <div style="background:#fef3c7; border:1px solid #f59e0b; color:#92400e; padding:12px 16px; border-radius:8px; margin-bottom:24px; font-size:0.88em;">
        <i class="fas fa-info-circle"></i>
        File diunduh dalam format <strong>CSV (UTF-8 BOM)</strong> — langsung bisa dibuka di Excel/Google Sheets.
    </div>

    {{-- EXPORT CARDS GRID --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">

        {{-- LAPORAN --}}
        <div class="card">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                <div style="width:40px; height:40px; border-radius:8px; background:#fee2e2; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-file-alt" style="color:#ef4444; font-size:1.1em;"></i>
                </div>
                <div>
                    <h3 style="margin:0; font-size:1em;">Data Laporan</h3>
                    <p style="font-size:0.78em; color:#aaa; margin:0;">reports + dinas + kategori + status</p>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.stats.export') }}">
                <input type="hidden" name="type" value="reports">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:12px;">
                    <div>
                        <label style="font-size:0.78em; color:#777; display:block; margin-bottom:4px;">Periode</label>
                        <select name="period" style="width:100%; padding:8px 10px; border:1px solid var(--background-light); border-radius:6px; font-size:0.85em;">
                            <option value="7">7 Hari</option>
                            <option value="30" selected>30 Hari</option>
                            <option value="90">90 Hari</option>
                            <option value="180">6 Bulan</option>
                            <option value="365">1 Tahun</option>
                            <option value="3650">Semua</option>
                        </select>
                    </div>
                    <div>
                        <label style="font-size:0.78em; color:#777; display:block; margin-bottom:4px;">Status</label>
                        <select name="status" style="width:100%; padding:8px 10px; border:1px solid var(--background-light); border-radius:6px; font-size:0.85em;">
                            <option value="">Semua</option>
                            <option value="active">Aktif</option>
                            <option value="process">Diproses</option>
                            <option value="done">Selesai</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                </div>
                <p style="font-size:0.75em; color:#aaa; margin:0 0 10px;">
                    Kolom: ID, Judul, Pelapor, Email, Dinas, Kategori, Status, Vote, Alamat, Tanggal
                </p>
                <button type="submit"
                        style="width:100%; background:var(--primary-color); color:#fff; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.88em; font-weight:600;">
                    <i class="fas fa-download"></i> Unduh CSV Laporan
                </button>
            </form>
        </div>

        {{-- PENGGUNA --}}
        <div class="card">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                <div style="width:40px; height:40px; border-radius:8px; background:#ede9fe; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-users" style="color:#8b5cf6; font-size:1.1em;"></i>
                </div>
                <div>
                    <h3 style="margin:0; font-size:1em;">Data Pengguna</h3>
                    <p style="font-size:0.78em; color:#aaa; margin:0;">users + role + status + total laporan</p>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.stats.export') }}">
                <input type="hidden" name="type" value="users">
                <div style="margin-bottom:12px;">
                    <label style="font-size:0.78em; color:#777; display:block; margin-bottom:4px;">Pengguna bergabung dalam</label>
                    <select name="period" style="width:100%; padding:8px 10px; border:1px solid var(--background-light); border-radius:6px; font-size:0.85em;">
                        <option value="30" selected>30 Hari</option>
                        <option value="90">90 Hari</option>
                        <option value="365">1 Tahun</option>
                        <option value="3650">Semua Pengguna</option>
                    </select>
                </div>
                <p style="font-size:0.75em; color:#aaa; margin:0 0 10px;">
                    Kolom: ID, Nama, Email, Telepon, Role, Status, Total Laporan, Login Terakhir, Bergabung
                </p>
                <button type="submit"
                        style="width:100%; background:#8b5cf6; color:#fff; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.88em; font-weight:600;">
                    <i class="fas fa-download"></i> Unduh CSV Pengguna
                </button>
            </form>
        </div>

        {{-- DINAS --}}
        <div class="card">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                <div style="width:40px; height:40px; border-radius:8px; background:#dbeafe; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-building" style="color:#3b82f6; font-size:1.1em;"></i>
                </div>
                <div>
                    <h3 style="margin:0; font-size:1em;">Performa Dinas</h3>
                    <p style="font-size:0.78em; color:#aaa; margin:0;">rekap per dinas + % penyelesaian</p>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.stats.export') }}">
                <input type="hidden" name="type" value="departments">
                <p style="font-size:0.75em; color:#aaa; margin:0 0 14px;">
                    Kolom: ID, Nama, Kode, Total Laporan, Aktif, Diproses, Selesai, Ditolak, % Selesai, Total Staf, Total Kategori
                </p>
                <div style="height:38px;"></div>{{-- spacer supaya tombol sejajar --}}
                <button type="submit"
                        style="width:100%; background:#3b82f6; color:#fff; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.88em; font-weight:600;">
                    <i class="fas fa-download"></i> Unduh CSV Dinas
                </button>
            </form>
        </div>

        {{-- VOTES --}}
        <div class="card">
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                <div style="width:40px; height:40px; border-radius:8px; background:#fef3c7; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-arrow-up" style="color:#f59e0b; font-size:1.1em;"></i>
                </div>
                <div>
                    <h3 style="margin:0; font-size:1em;">Data Vote</h3>
                    <p style="font-size:0.78em; color:#aaa; margin:0;">report_votes + data voter + laporan</p>
                </div>
            </div>
            <form method="GET" action="{{ route('admin.stats.export') }}">
                <input type="hidden" name="type" value="votes">
                <div style="margin-bottom:12px;">
                    <label style="font-size:0.78em; color:#777; display:block; margin-bottom:4px;">Vote dalam</label>
                    <select name="period" style="width:100%; padding:8px 10px; border:1px solid var(--background-light); border-radius:6px; font-size:0.85em;">
                        <option value="30" selected>30 Hari</option>
                        <option value="90">90 Hari</option>
                        <option value="365">1 Tahun</option>
                        <option value="3650">Semua Vote</option>
                    </select>
                </div>
                <p style="font-size:0.75em; color:#aaa; margin:0 0 10px;">
                    Kolom: ID Vote, Nama Voter, Email Voter, ID Laporan, Judul Laporan, Tanggal Vote
                </p>
                <button type="submit"
                        style="width:100%; background:#f59e0b; color:#fff; border:none; padding:9px; border-radius:6px; cursor:pointer; font-size:0.88em; font-weight:600;">
                    <i class="fas fa-download"></i> Unduh CSV Vote
                </button>
            </form>
        </div>

    </div>
</div>

@endsection