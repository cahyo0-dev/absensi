<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pertanyaan;
use App\Models\KategoriInspeksi;

class PertanyaanSeeder extends Seeder
{
    public function run()
    {
        // Kategori 1: Kebersihan Lingkungan Kerja
        $kebersihan = KategoriInspeksi::where('nama', 'Kebersihan Lingkungan Kerja')->first();
        
        $pertanyaanKebersihan = [
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
        
        foreach ($pertanyaanKebersihan as $pertanyaan) {
            Pertanyaan::create([
                'pertanyaan' => $pertanyaan['pertanyaan'],
                'kategori_id' => $kebersihan->id,
                'urutan' => $pertanyaan['urutan']
            ]);
        }

        // Kategori 2: Pemeliharaan Toilet
        $toilet = KategoriInspeksi::where('nama', 'Pemeliharaan Toilet')->first();
        
        $pertanyaanToilet = [
            ['pertanyaan' => 'Toilet bersih dan tidak bau', 'urutan' => 1],
            ['pertanyaan' => 'Lantai toilet kering dan tidak licin', 'urutan' => 2],
            ['pertanyaan' => 'Kloset berfungsi dengan baik (tidak mampet)', 'urutan' => 3],
            ['pertanyaan' => 'Keran air berfungsi normal (tidak bocor)', 'urutan' => 4],
            ['pertanyaan' => 'Tempat sabun dan hand sanitizer terisi', 'urutan' => 5],
            ['pertanyaan' => 'Tissue toilet tersedia dan dalam kondisi baik', 'urutan' => 6],
            ['pertanyaan' => 'Hand dryer atau pengering tangan berfungsi', 'urutan' => 7],
            ['pertanyaan' => 'Ventilasi udara berfungsi dengan baik', 'urutan' => 8],
            ['pertanyaan' => 'Penerangan toilet cukup dan berfungsi', 'urutan' => 9],
            ['pertanyaan' => 'Tempat sampah tersedia dan tidak penuh', 'urutan' => 10],
            ['pertanyaan' => 'Cermin bersih dan tidak pecah', 'urutan' => 11],
            ['pertanyaan' => 'Pintu toilet berfungsi dengan baik', 'urutan' => 12],
            ['pertanyaan' => 'Saluran pembuangan lancar', 'urutan' => 13]
        ];
        
        foreach ($pertanyaanToilet as $pertanyaan) {
            Pertanyaan::create([
                'pertanyaan' => $pertanyaan['pertanyaan'],
                'kategori_id' => $toilet->id,
                'urutan' => $pertanyaan['urutan']
            ]);
        }

        // Kategori 3: Pemeliharaan Halaman/Parkir
        $halaman = KategoriInspeksi::where('nama', 'Pemeliharaan Halaman/Parkir')->first();
        
        $pertanyaanHalaman = [
            ['pertanyaan' => 'Halaman bersih dari sampah dan dedaunan', 'urutan' => 1],
            ['pertanyaan' => 'Rumput dan tanaman terpangkas rapi', 'urutan' => 2],
            ['pertanyaan' => 'Jalan setapak bersih dan tidak berlubang', 'urutan' => 3],
            ['pertanyaan' => 'Lampu penerangan halaman berfungsi semua', 'urutan' => 4],
            ['pertanyaan' => 'Area parkir kendaraan bersih dan rapi', 'urutan' => 5],
            ['pertanyaan' => 'Marka parkir jelas dan tidak pudar', 'urutan' => 6],
            ['pertanyaan' => 'Rambu-rambu parkir terpasang dengan baik', 'urutan' => 7],
            ['pertanyaan' => 'Saluran air halaman tidak tersumbat', 'urutan' => 8],
            ['pertanyaan' => 'Pagar dan pembatas dalam kondisi baik', 'urutan' => 9],
            ['pertanyaan' => 'Tempat duduk taman bersih dan utuh', 'urutan' => 10],
            ['pertanyaan' => 'Area bermain anak bersih dan aman', 'urutan' => 11],
            ['pertanyaan' => 'Tempat sampah outdoor tersedia dan tidak penuh', 'urutan' => 12],
            ['pertanyaan' => 'Sistem keamanan halaman berfungsi', 'urutan' => 13]
        ];
        
        foreach ($pertanyaanHalaman as $pertanyaan) {
            Pertanyaan::create([
                'pertanyaan' => $pertanyaan['pertanyaan'],
                'kategori_id' => $halaman->id,
                'urutan' => $pertanyaan['urutan']
            ]);
        }

        $this->command->info('Data pertanyaan untuk semua kategori berhasil ditambahkan!');
        $this->command->info('- Kebersihan Lingkungan Kerja: ' . count($pertanyaanKebersihan) . ' pertanyaan');
        $this->command->info('- Pemeliharaan Toilet: ' . count($pertanyaanToilet) . ' pertanyaan');
        $this->command->info('- Pemeliharaan Halaman/Parkir: ' . count($pertanyaanHalaman) . ' pertanyaan');
    }
}