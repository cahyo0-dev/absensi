<?php
// app/Http/Controllers/PertanyaanController.php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Tambahkan ini

class PertanyaanController extends Controller
{
    public function getByKategori($kategoriId)
    {
        try {
            // Log untuk debugging
            Log::info('Mengambil pertanyaan untuk kategori: ' . $kategoriId);
            
            $pertanyaans = Pertanyaan::where('kategori_id', $kategoriId)
                ->orderBy('urutan', 'asc')
                ->get(['id', 'pertanyaan', 'kategori_id']);

            Log::info('Pertanyaan ditemukan: ' . $pertanyaans->count());
            
            return response()->json($pertanyaans);
        } catch (\Exception $e) {
            Log::error('Error mengambil pertanyaan: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Gagal memuat pertanyaan',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}