<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;

class AbsensiController extends Controller
{
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
            // Simpan data absensi
            $absensi = Absensi::create([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'unit_kerja' => $request->unit_kerja,
                'provinsi' => $request->provinsi,
                'tanda_tangan' => $request->tanda_tangan,
                'waktu_absen' => now(),
            ]);

            return redirect()->route('absensi.form')
                ->with('success', 'Absensi berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()->route('absensi.form')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}