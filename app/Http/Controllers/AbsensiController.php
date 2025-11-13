<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function form()
    {
        return view('absensi.index');
    }

    // Method untuk mengecek absensi berdasarkan NIP
    public function checkAbsensi(Request $request)
    {
        $request->validate([
            'nip' => 'required|string|max:20'
        ]);

        // Cari record absensi hari ini berdasarkan NIP
        $absensiHariIni = Absensi::hariIni($request->nip)->first();

        if ($absensiHariIni) {
            return response()->json([
                'exists' => true,
                'data' => $absensiHariIni,
                'sudah_pulang' => $absensiHariIni->waktu_pulang !== null,
                'message' => $absensiHariIni->waktu_pulang ?
                    'Anda sudah melakukan absensi masuk dan pulang hari ini' :
                    'Data absensi masuk ditemukan'
            ]);
        }

        return response()->json([
            'exists' => false,
            'message' => 'Belum ada absensi hari ini'
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'nip' => 'required|string|max:20',
            'nama' => 'required|string|max:100',
            'jabatan' => 'required|string|max:100',
            'unit_kerja' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'tanda_tangan' => 'required|string',
        ]);

        try {
            // Cari record absensi hari ini berdasarkan NIP
            $absensiHariIni = Absensi::hariIni($request->nip)->first();

            if ($absensiHariIni) {
                // Jika sudah ada record dan belum pulang
                if ($absensiHariIni->waktu_pulang === null) {
                    // Update untuk absensi pulang
                    $absensiHariIni->update([
                        'tanda_tangan_pulang' => $request->tanda_tangan, // GANTI: tanda_tangan -> tanda_tangan_pulang
                        'waktu_pulang' => now(),
                        'updated_at' => now(), // Pastikan updated_at terupdate
                    ]);

                    return redirect()->route('absensi.form')
                        ->with('success', 'Absensi pulang berhasil disimpan!');
                } else {
                    // Jika sudah absen pulang
                    return redirect()->route('absensi.form')
                        ->with('error', 'Anda sudah melakukan absensi masuk dan pulang hari ini!');
                }
            }

            // Jika belum ada record, buat record baru untuk absensi masuk
            $absensi = Absensi::create([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'unit_kerja' => $request->unit_kerja,
                'provinsi' => $request->provinsi,
                'tanda_tangan_masuk' => $request->tanda_tangan, // GANTI: tanda_tangan -> tanda_tangan_masuk
                'waktu_masuk' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('absensi.form')
                ->with('success', 'Absensi masuk berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->route('absensi.form')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function index()
    {
        $absensis = Absensi::orderBy('created_at', 'desc')->paginate(10);
        return view('absensi.list', compact('absensis'));
    }
}
