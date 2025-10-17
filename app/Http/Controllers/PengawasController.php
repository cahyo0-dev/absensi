<?php

namespace App\Http\Controllers;

use App\Models\Inspeksi;
use App\Models\KategoriInspeksi;
use App\Models\Pertanyaan;
use App\Models\JawabanInspeksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengawasController extends Controller
{
    public function dashboard()
    {
        // PERBAIKI: Gunakan Carbon untuk date comparison
        $inspeksiHariIni = Inspeksi::where('pengawas_id', Auth::id())
            ->whereDate('tanggal', today())
            ->count();

        $totalInspeksi = Inspeksi::where('pengawas_id', Auth::id())->count();

        return view('pengawas.dashboard', compact('inspeksiHariIni', 'totalInspeksi'));
    }

    public function inspeksi()
    {
        // PERBAIKI: Gunakan kolom 'tanggal' bukan 'created_at'
        $inspeksiHariIni = Inspeksi::where('pengawas_id', Auth::id())
            ->whereDate('tanggal', today())
            ->exists();

        if ($inspeksiHariIni) {
            return redirect()->route('pengawas.dashboard')
                ->with('error', 'Anda sudah melakukan inspeksi hari ini.');
        }

        $kategories = KategoriInspeksi::with('pertanyaans')->get();
        return view('pengawas.inspeksi', compact('kategories'));
    }

    public function storeInspeksi(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_inspeksis,id',
            'jawaban' => 'required|array',
            'tanda_tangan' => 'required|string',
        ]);

        // Buat inspeksi
        $inspeksi = Inspeksi::create([
            'pengawas_id' => Auth::id(),
            'kategori_id' => $validated['kategori_id'],
            'tanda_tangan' => $validated['tanda_tangan'],
            'tanggal' => now()->toDateString(),
        ]);

        // Simpan jawaban
        foreach ($validated['jawaban'] as $pertanyaanId => $jawaban) {
            JawabanInspeksi::create([
                'inspeksi_id' => $inspeksi->id,
                'pertanyaan_id' => $pertanyaanId,
                'jawaban' => $jawaban,
            ]);
        }

        return redirect()->route('pengawas.dashboard')
            ->with('success', 'Inspeksi berhasil disimpan.');
    }

    public function laporan()
    {
        $inspeksis = Inspeksi::with(['kategori', 'jawabans.pertanyaan'])
            ->where('pengawas_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pengawas.laporan', compact('inspeksis'));
    }
}