<?php
// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Pertanyaan;
use App\Models\Inspeksi;
use App\Http\Controllers\InspeksiController;

Route::get('/pertanyaan/{kategoriId}', function ($kategoriId) {
    $pertanyaans = Pertanyaan::where('kategori_id', $kategoriId)->get();
    return response()->json($pertanyaans);
});

Route::get('/inspeksi/{id}', function ($id) {
    $inspeksi = Inspeksi::with(['kategori', 'jawaban.pertanyaan'])->find($id);
    return response()->json($inspeksi);
});

Route::get('/inspeksi/{id}', function ($id) {
    $inspeksi = Inspeksi::with(['kategori', 'jawaban.pertanyaan'])
                ->where('id', $id)
                ->first();
    
    if (!$inspeksi) {
        return response()->json(['error' => 'Inspeksi tidak ditemukan'], 404);
    }
    
    return response()->json($inspeksi);
});
Route::get('/inspeksi/{id}', [InspeksiController::class, 'apiDetail'])->middleware('auth');