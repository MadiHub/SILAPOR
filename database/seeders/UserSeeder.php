<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Insert Department 
        $departments = [
            ['name' => 'Dinas Pendidikan Kota Bekasi', 'code' => 'DISDIK'],
            ['name' => 'Dinas Kesehatan Kota Bekasi', 'code' => 'DINKES'],
            ['name' => 'Dinas Pemadam Kebakaran dan Penyelamatan Kota Bekasi', 'code' => 'DAMKAR'],
            ['name' => 'Satuan Polisi Pamong Praja Kota Bekasi', 'code' => 'SATPOLPP'],
            ['name' => 'Dinas Sosial Kota Bekasi', 'code' => 'DINSOS'],
            ['name' => 'Dinas Tenaga Kerja Kota Bekasi', 'code' => 'DISNAKER'],
            ['name' => 'Dinas Ketahanan Pangan, Pertanian dan Perikanan Kota Bekasi', 'code' => 'DKP3'],
            ['name' => 'Dinas Lingkungan Hidup Kota Bekasi', 'code' => 'DLH'],
            ['name' => 'Dinas Kependudukan dan Pencatatan Sipil Kota Bekasi', 'code' => 'DUKCAPIL'],
            ['name' => 'Dinas Perhubungan Kota Bekasi', 'code' => 'DISHUB'],
            ['name' => 'Dinas Komunikasi, Informatika, Statistik dan Persandian Kota Bekasi', 'code' => 'DISKOMINFOSTANDI'],
            ['name' => 'Dinas Penanaman Modal dan Pelayanan Terpadu Satu Pintu Kota Bekasi', 'code' => 'DPMPTSP'],
            ['name' => 'Dinas Kepemudaan dan Olahraga Kota Bekasi', 'code' => 'DISPORA'],
            ['name' => 'Dinas Pariwisata dan Kebudayaan Kota Bekasi', 'code' => 'DISPARBUD'],
            ['name' => 'Dinas Perdagangan dan Perindustrian Kota Bekasi', 'code' => 'DISDAGPERIN'],
            ['name' => 'Dinas Koperasi, Usaha Kecil dan Menengah Kota Bekasi', 'code' => 'DISKOPUKM'],
            ['name' => 'Dinas Bina Marga dan Sumber Daya Air Kota Bekasi', 'code' => 'DBMSDA'],
            ['name' => 'Dinas Tata Ruang Kota Bekasi', 'code' => 'DISTARU'],
            ['name' => 'Dinas Pemberdayaan Perempuan dan Perlindungan Anak Kota Bekasi', 'code' => 'DP3A'],
            ['name' => 'Dinas Pengendalian Penduduk dan Keluarga Berencana Kota Bekasi', 'code' => 'DPPKB'],
            ['name' => 'Dinas Arsip dan Perpustakaan Kota Bekasi', 'code' => 'DISARPUS'],
            ['name' => 'Dinas Perumahan, Kawasan Permukiman dan Pertanahan Kota Bekasi', 'code' => 'DISPERKIMTAN'],
        ];

        $departmentIds = [];

        foreach ($departments as $department) {
            $departmentIds[$department['code']] = DB::table('departments')->insertGetId([
                'name' => $department['name'],
                'code' => $department['code'],
                'description' => $department['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $categories = [
            [
                'name' => 'Kerusakan Jalan',
                'icon' => 'fa-solid fa-road',
                'department_code' => 'DBMSDA',
                'description' => 'Jalan berlubang, rusak, atau tidak layak pakai'
            ],
            [
                'name' => 'Banjir & Genangan',
                'icon' => 'fa-solid fa-cloud-showers-heavy',
                'department_code' => 'DLH',
                'description' => 'Banjir, genangan, atau saluran air tersumbat'
            ],
            [
                'name' => 'PJU Mati',
                'icon' => 'fa-solid fa-lightbulb',
                'department_code' => 'DISHUB',
                'description' => 'Lampu penerangan jalan umum tidak menyala'
            ],
            [
                'name' => 'Jembatan Rusak',
                'icon' => 'fa-solid fa-bridge',
                'department_code' => 'DBMSDA',
                'description' => 'Kerusakan pada jembatan atau struktur penyeberangan'
            ],
            [
                'name' => 'Sampah Menumpuk',
                'icon' => 'fa-solid fa-trash',
                'department_code' => 'DLH',
                'description' => 'Penumpukan sampah di area publik'
            ],
            [
                'name' => 'Longsor Ringan',
                'icon' => 'fa-solid fa-mountain',
                'department_code' => 'DLH',
                'description' => 'Longsor skala kecil di wilayah tertentu'
            ],
        ];

        foreach ($categories as $category) {
            $departmentId = isset($departmentIds[$category['department_code']]) 
                ? $departmentIds[$category['department_code']] 
                : null;

            DB::table('problem_categories')->insert([
                'department_id' => $departmentId, 
                'name'          => $category['name'],
                'icon'          => $category['icon'],
                'description'   => $category['description'],
            ]);
        }

        // 🔹 ADMIN
        DB::table('users')->insert([
            'name' => 'Megi',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🔹 WARGA
        DB::table('users')->insert([
            'name' => 'Galuh',
            'email' => 'warga@mail.com',
            'password' => Hash::make('warga123'),
            'role' => 'warga',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🔹 PEMDA
        $pemdaId = DB::table('users')->insertGetId([
            'name' => 'Madi',
            'email' => 'pemda@mail.com',
            'password' => Hash::make('pemda123'),
            'role' => 'pemda',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🔹 Relasi PEMDA ke Department
        DB::table('user_departments')->insert([
            'user_id' => $pemdaId,
            'department_id' => $departmentIds['DLH'],
        ]);
    }
}