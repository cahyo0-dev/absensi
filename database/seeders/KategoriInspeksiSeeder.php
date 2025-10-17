<?php
// database/seeders/KategoriInspeksiSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriInspeksi;

class KategoriInspeksiSeeder extends Seeder
{
    public function run()
    {
        $kategories = [
            [
                'nama' => 'Kebersihan Lingkungan Kerja',
                'deskripsi' => 'Pemeriksaan kebersihan area kerja'
            ],
            [
                'nama' => 'Pemeliharaan Toilet',
                'deskripsi' => 'Pemeriksaan kondisi toilet'
            ],
            [
                'nama' => 'Pemeliharaan Halaman/Parkir',
                'deskripsi' => 'Pemeriksaan area halaman dan parkir'
            ]
        ];

        foreach ($kategories as $kategori) {
            KategoriInspeksi::create($kategori);
        }

        $this->command->info('Data kategori inspeksi berhasil ditambahkan!');
    }
}