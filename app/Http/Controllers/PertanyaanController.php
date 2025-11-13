<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\KategoriInspeksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

    public function edit($kategoriId)
    {
        try {
            $kategori = KategoriInspeksi::findOrFail($kategoriId);
            $pertanyaans = Pertanyaan::where('kategori_id', $kategoriId)
                ->orderBy('urutan', 'asc')
                ->get();

            return view('pertanyaan.edit', compact('kategori', 'pertanyaans'));
        } catch (\Exception $e) {
            Log::error('Error loading edit page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Kategori tidak ditemukan');
        }
    }

    public function store(Request $request, $kategoriId)
    {
        try {
            $request->validate([
                'pertanyaan' => 'required|string|max:500'
            ]);

            // Get the highest urutan value for this kategori
            $maxUrutan = Pertanyaan::where('kategori_id', $kategoriId)->max('urutan');
            $newUrutan = $maxUrutan ? $maxUrutan + 1 : 1;

            $pertanyaan = Pertanyaan::create([
                'pertanyaan' => $request->pertanyaan,
                'kategori_id' => $kategoriId,
                'urutan' => $newUrutan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil ditambahkan',
                'data' => $pertanyaan
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing question: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pertanyaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'pertanyaan' => 'required|string|max:500'
            ]);

            $pertanyaan = Pertanyaan::findOrFail($id);
            $pertanyaan->update([
                'pertanyaan' => $request->pertanyaan
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil diperbarui',
                'data' => $pertanyaan
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating question: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui pertanyaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $pertanyaan = Pertanyaan::findOrFail($id);
                $kategoriId = $pertanyaan->kategori_id;
                $urutan = $pertanyaan->urutan;

                // Hapus pertanyaan
                $pertanyaan->delete();

                // Update urutan untuk pertanyaan yang tersisa
                Pertanyaan::where('kategori_id', $kategoriId)
                    ->where('urutan', '>', $urutan)
                    ->decrement('urutan');
            });

            return response()->json([
                'success' => true,
                'message' => 'Pertanyaan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting question: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pertanyaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function reorder(Request $request, $kategoriId)
    {
        try {
            $request->validate([
                'pertanyaans' => 'required|array'
            ]);

            DB::transaction(function () use ($request, $kategoriId) {
                foreach ($request->pertanyaans as $index => $pertanyaanId) {
                    Pertanyaan::where('id', $pertanyaanId)
                        ->where('kategori_id', $kategoriId)
                        ->update(['urutan' => $index + 1]);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Urutan pertanyaan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Log::error('Error reordering questions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan: ' . $e->getMessage()
            ], 500);
        }
    }
}