<?php
// database/seeders/PertanyaanSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pertanyaan;
use App\Models\KategoriInspeksi;

class PertanyaanSeeder extends Seeder
{
    public function run()
    {
        // Cari atau buat kategori Kebersihan Lingkungan Kerja
        $kebersihan = KategoriInspeksi::firstOrCreate(
            ['nama' => 'Kebersihan Lingkungan Kerja'],
            ['deskripsi' => 'Pemeriksaan kebersihan area kerja']
        );
        
        $pertanyaan = [
            ['pertanyaan' => 'Lantai bersih (Disapu & Dipel)', 'urutan' => 1],
            ['pertanyaan' => 'Dinding bersih', 'urutan' => 2],
            ['pertanyaan' => 'Langit-langit bersih', 'urutan' => 3],
            ['pertanyaan' => 'Meja/kursi bersih dan rapi', 'urutan' => 4],
            ['pertanyaan' => 'Kaca bersih dan tidak berminyak', 'urutan' => 5],
            ['pertanyaan' => 'Lemari/tangga bersih dan rapi/lurus', 'urutan' => 6],
            ['pertanyaan' => 'Ruang & Peralatan di cuci', 'urutan' => 7],
            ['pertanyaan' => 'Tempat sampah tidak penuh', 'urutan' => 8],
            ['pertanyaan' => 'AC berfungsi (dingin)', 'urutan' => 9],
            ['pertanyaan' => 'Rak/rak/lemari arsip bersih/rapi', 'urutan' => 10],
            ['pertanyaan' => 'Papan informasi rapi', 'urutan' => 11],
            ['pertanyaan' => 'Ruang/sisi genset', 'urutan' => 12],
            ['pertanyaan' => 'Check level solar, air radiator, air accu', 'urutan' => 13]
        ];
        
        foreach ($pertanyaan as $pertanyaan) {
            Pertanyaan::firstOrCreate([
                'pertanyaan' => $pertanyaan['pertanyaan'],
                'kategori_id' => $kebersihan->id
            ], [
                'urutan' => $pertanyaan['urutan']
            ]);
        }
        
        $this->command->info('Data pertanyaan untuk Kebersihan Lingkungan Kerja berhasil ditambahkan!');
    }
}