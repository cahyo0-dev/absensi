<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PertanyaanController extends Controller
{
    public function getByKategori($kategoriId)
    {
        try {
            Log::info('Mengambil pertanyaan untuk kategori: ' . $kategoriId);
            
            $pertanyaan = Pertanyaan::where('kategori_id', $kategoriId)
                ->orderBy('urutan', 'asc')
                ->get(['id', 'pertanyaan', 'kategori_id']);

            Log::info('Pertanyaan ditemukan: ' . $pertanyaan->count());
            
            return response()->json($pertanyaan);
        } catch (\Exception $e) {
            Log::error('Error mengambil pertanyaan: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Gagal memuat pertanyaan',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}