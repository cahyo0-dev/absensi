<?php

namespace App\Http\Controllers;

use App\Exports\InspeksiExport;
use App\Exports\AllInspeksiExport;
use App\Exports\InspeksiRangeExport;
use App\Exports\InspeksiPresetExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KategoriInspeksi;
use App\Models\Inspeksi;
use App\Models\User;
use App\Models\Pertanyaan;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;

class InspeksiController extends Controller
{
    /**
     * Menampilkan form inspeksi - MODIFIED FOR EDIT
     */
    /**
     * Menampilkan form inspeksi - MODIFIED FOR EDIT
     */
    public function create($id = null)
    {
        $inspeksi = null;
        $jawabanFormatted = [];
        $editMode = false;

        // Jika ada ID, maka mode edit
        if ($id) {
            // PERBAIKAN: Ambil data inspeksi dengan relasi yang lengkap
            $inspeksi = Inspeksi::with([
                'kategori',


                'jawaban.pertanyaan.kategori'
            ])->find($id);

            if ($inspeksi) {
                $editMode = true;

                // PERBAIKAN: Format jawaban untuk form dengan struktur yang benar
                foreach ($inspeksi->jawaban as $jawaban) {
                    if ($jawaban->pertanyaan) {
                        $jawabanFormatted[$jawaban->pertanyaan->kategori_id][$jawaban->pertanyaan->id] = $jawaban->jawaban;
                    }
                }

                Log::info("Edit Mode - Inspeksi ID: " . $inspeksi->id);
                Log::info("Jawaban Formatted: ", $jawabanFormatted);
            }
        }

        // Cek apakah sudah ada inspeksi hari ini (GLOBAL - 1x/hari untuk semua pengawas)
        // Tapi jika mode edit, skip pengecekan ini
        $sudahInspeksiHariIni = false;
        $inspeksiDataHariIni = null;
        $inspektorHariIni = null;

        if (!$editMode) {
            $today = now()->timezone('Asia/Kuala_Lumpur')->toDateString();
            $inspeksiHariIni = Inspeksi::whereDate('tanggal', $today)->first();
            $sudahInspeksiHariIni = $inspeksiHariIni !== null;
            $inspektorHariIni = $inspeksiHariIni ? $inspeksiHariIni->pengawas : null;
        }

        $kategories = KategoriInspeksi::with('pertanyaan')->get();
        $totalPengawas = User::where('role', 'pengawas')->count();

        // Ambil pertanyaan berdasarkan kategori atau semua jika tidak ada kategori
        $pertanyaan = Pertanyaan::with('kategori')->get();

        // Group pertanyaan by kategori untuk display di form
        $pertanyaanGrouped = $pertanyaan->groupBy('kategori_id');

        Log::info("Create/Edit Mode - Edit: " . ($editMode ? 'YES' : 'NO'));
        Log::info("Total Kategories: " . $kategories->count());
        Log::info("Total Pertanyaan: " . $pertanyaan->count());

        return view('pengawas.inspeksi', compact(
            'kategories',
            'sudahInspeksiHariIni',
            'inspeksiDataHariIni',
            'inspektorHariIni',
            'totalPengawas',
            'pertanyaan',
            'pertanyaanGrouped',
            'inspeksi',
            'jawabanFormatted',
            'editMode'
        ));
    }

    /**
     * Menyimpan data inspeksi - FIXED VERSION
     */
    public function store(Request $request)
    {
        Log::info('=== MEMULAI PROSES SIMPAN INSPEKSI ===');
        Log::info('Data request:', $request->all());

        // Jika ada inspeksi_id, maka ini adalah update
        $editMode = !empty($request->inspeksi_id);
        $inspeksiId = $editMode ? $request->inspeksi_id : null;

        // Validasi: cek apakah sudah ada inspeksi hari ini (GLOBAL)
        // Tapi jika mode edit, skip pengecekan ini
        if (!$editMode) {
            $today = now()->timezone('Asia/Kuala_Lumpur')->toDateString();
            $inspeksiHariIni = Inspeksi::whereDate('tanggal', $today)->exists();

            if ($inspeksiHariIni) {
                Log::warning('Inspeksi hari ini sudah ada, tidak bisa simpan');
                return redirect()->back()
                    ->with('error', 'Maaf, inspeksi hari ini sudah dilakukan oleh pengawas lain. Sistem hanya mengizinkan 1 inspeksi per hari.')
                    ->withInput();
            }
        }

        Log::info('Mode: ' . ($editMode ? 'Edit' : 'Create'));

        // Validasi input - DITAMBAHKAN KATEGORI_ID
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'nullable|exists:kategori_inspeksis,id',
            'jawaban' => 'required|array',
            'jawaban.*' => 'required|array',
            'jawaban.*.*' => 'required|in:Ya,Tidak',
            'tanda_tangan' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error('Validasi gagal:', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Log::info('Validasi berhasil, data jawaban:', $request->jawaban);
        Log::info('Kategori ID dari request: ' . ($request->kategori_id ?? 'NULL (Semua Kategori)'));

        try {
            DB::beginTransaction();
            Log::info('Transaction dimulai');

            if ($editMode) {
                // UPDATE MODE
                $inspeksi = Inspeksi::where('pengawas_id', Auth::id())
                    ->findOrFail($inspeksiId);

                $inspeksi->update([
                    'kategori_id' => $request->kategori_id,
                    'tanda_tangan' => $request->tanda_tangan,
                    'updated_at' => now(),
                ]);

                Log::info('Inspeksi updated dengan ID: ' . $inspeksi->id);

                // Hapus jawaban lama
                Jawaban::where('inspeksi_id', $inspeksi->id)->delete();
                Log::info('Jawaban lama dihapus');
            } else {
                // CREATE MODE
                $inspeksi = Inspeksi::create([
                    'pengawas_id' => Auth::id(),
                    'kategori_id' => $request->kategori_id,
                    'tanda_tangan' => $request->tanda_tangan,
                    'tanggal' => now()->timezone('Asia/Kuala_Lumpur')->toDateString(),
                ]);

                Log::info('Inspeksi created dengan ID: ' . $inspeksi->id . ', kategori_id: ' . ($request->kategori_id ?? 'null'));
            }

            // Simpan jawaban dengan logging detail
            $totalJawaban = 0;
            $jawabanDetails = [];

            foreach ($request->jawaban as $kategoriId => $pertanyaanJawaban) {
                foreach ($pertanyaanJawaban as $pertanyaanId => $jawaban) {
                    Log::info("Menyimpan jawaban - Pertanyaan ID: $pertanyaanId, Jawaban: $jawaban");

                    $jawabanRecord = Jawaban::create([
                        'inspeksi_id' => $inspeksi->id,
                        'pertanyaan_id' => $pertanyaanId,
                        'jawaban' => $jawaban
                    ]);

                    $jawabanDetails[] = [
                        'id' => $jawabanRecord->id,
                        'pertanyaan_id' => $pertanyaanId,
                        'jawaban' => $jawaban
                    ];
                    $totalJawaban++;
                }
            }

            Log::info('Jawaban disimpan: ' . $totalJawaban . ' jawaban', $jawabanDetails);
            Log::info('Semua jawaban untuk inspeksi ini:', Jawaban::where('inspeksi_id', $inspeksi->id)->get()->toArray());

            DB::commit();
            Log::info('Transaction committed');

            $message = $editMode ? 'Inspeksi berhasil diperbarui!' : 'Inspeksi berhasil disimpan! Anda adalah pengawas yang melakukan inspeksi hari ini.';

            return redirect()->route('pengawas.laporan')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyimpan inspeksi: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan inspeksi: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan form edit inspeksi
     */
    public function edit($id)
    {
        return $this->create($id);
    }

    /**
     * Update data inspeksi
     */
    public function update(Request $request, $id)
    {
        $request->merge(['inspeksi_id' => $id]);
        return $this->store($request);
    }

    /**
     * Hapus data inspeksi
     */
    public function destroy($id)
    {
        Log::info('=== MEMULAI PROSES HAPUS INSPEKSI ===');
        Log::info('Inspeksi ID yang akan dihapus: ' . $id);

        try {
            $inspeksi = Inspeksi::where('pengawas_id', Auth::id())
                ->findOrFail($id);

            DB::beginTransaction();
            Log::info('Transaction dimulai untuk delete');

            // Hapus semua jawaban terkait
            Jawaban::where('inspeksi_id', $inspeksi->id)->delete();
            Log::info('Jawaban terkait dihapus');

            // Hapus inspeksi
            $inspeksi->delete();
            Log::info('Inspeksi dihapus');

            DB::commit();
            Log::info('Transaction committed untuk delete');

            return response()->json([
                'success' => true,
                'message' => 'Inspeksi berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menghapus inspeksi: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus inspeksi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan laporan inspeksi
     */
    public function laporan()
    {
        Log::info('=== LAPORAN DATA ===');

        // Ambil semua inspeksi dengan relasi (GLOBAL - semua pengawas)
        $inspeksis = Inspeksi::with(['pengawas', 'jawaban', 'kategori'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        Log::info('Jumlah inspeksi ditemukan: ' . $inspeksis->count());

        // Statistik - GLOBAL
        $totalBulanIni = Inspeksi::whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->count();

        $kategoriBerbeda = Inspeksi::distinct('kategori_id')->count('kategori_id');

        $totalHari = Inspeksi::distinct('tanggal')->count('tanggal');

        $rataRataPerHari = $totalHari > 0 ? round($inspeksis->total() / $totalHari, 1) : 0;

        return view('pengawas.laporan', compact(
            'inspeksis',
            'totalBulanIni',
            'kategoriBerbeda',
            'rataRataPerHari'
        ));
    }

    /**
     * Menampilkan detail inspeksi dalam format JSON untuk modal
     */
    public function showDetail($id)
    {
        try {
            Log::info('=== SHOW DETAIL INSPEKSI ===');
            Log::info('ID Inspeksi: ' . $id);

            // Ambil data inspeksi (GLOBAL - tanpa filter pengawas_id)
            $inspeksi = Inspeksi::with([
                'kategori',
                'jawaban.pertanyaan',
                'pengawas'
            ])->find($id);

            if (!$inspeksi) {
                Log::error('Inspeksi tidak ditemukan dengan ID: ' . $id);
                return response()->json([
                    'error' => 'Data inspeksi tidak ditemukan'
                ], 404);
            }

            Log::info('Inspeksi ditemukan: ' . $inspeksi->id);

            return response()->json([
                'id' => $inspeksi->id,
                'created_at' => $inspeksi->created_at ? $inspeksi->created_at->timezone('Asia/Kuala_Lumpur')->toISOString() : null,
                'kategori' => [
                    'id' => $inspeksi->kategori->id ?? null,
                    'nama' => $inspeksi->kategori->nama ?? 'Semua Kategori',
                    'deskripsi' => $inspeksi->kategori->deskripsi ?? ''
                ],
                'jawabans' => $inspeksi->jawaban->map(function ($jawaban) {
                    return [
                        'id' => $jawaban->id,
                        'jawaban' => $jawaban->jawaban,
                        'pertanyaan' => [
                            'id' => $jawaban->pertanyaan->id ?? null,
                            'pertanyaan' => $jawaban->pertanyaan->pertanyaan ?? 'Pertanyaan tidak ditemukan'
                        ]
                    ];
                })->toArray(),
                'tanda_tangan' => $inspeksi->tanda_tangan,
                'lokasi' => $inspeksi->lokasi,
                'keterangan' => $inspeksi->keterangan
            ]);
        } catch (\Exception $e) {
            Log::error('Error dalam showDetail: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Terjadi kesalahan server',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Menampilkan detail inspeksi dalam format HTML
     */
    public function show($id)
    {
        try {
            $inspeksi = Inspeksi::with(['kategori', 'jawaban.pertanyaan', 'pengawas'])
                ->findOrFail($id);

            return view('pengawas.laporan.detail', compact('inspeksi'));
        } catch (\Exception $e) {
            return redirect()->route('pengawas.laporan')
                ->with('error', 'Inspeksi tidak ditemukan');
        }
    }

    /**
     * Export single inspeksi
     */
    public function export($id): BinaryFileResponse
    {
        $inspeksi = Inspeksi::with(['kategori', 'jawaban.pertanyaan', 'pengawas'])
            ->findOrFail($id);

        $filename = 'inspeksi-' . $inspeksi->id . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new InspeksiExport($inspeksi), $filename);
    }

    /**
     * Export all inspeksi to Excel
     */
    public function exportAll(): BinaryFileResponse
    {
        $filename = 'laporan-inspeksi-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new AllInspeksiExport(), $filename);
    }

    /**
     * Export inspeksi by date range
     */
    public function exportRange(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();

        $filename = 'inspeksi-' . $request->start_date . '-hingga-' . $request->end_date . '.xlsx';

        return Excel::download(new InspeksiRangeExport($startDate, $endDate), $filename);
    }

    /**
     * Method untuk export dengan preset waktu
     */
    public function exportPreset($preset)
    {
        try {
            $user = Auth::user();
            $now = Carbon::now();

            // Tentukan rentang waktu berdasarkan preset
            switch ($preset) {
                case 'week':
                    $startDate = $now->copy()->subWeek();
                    $fileName = 'inspeksi_1_minggu_' . $now->format('Y-m-d') . '.xlsx';
                    $presetName = '1 MINGGU TERAKHIR';
                    break;
                case 'month':
                    $startDate = $now->copy()->subMonth();
                    $fileName = 'inspeksi_1_bulan_' . $now->format('Y-m-d') . '.xlsx';
                    $presetName = '1 BULAN TERAKHIR';
                    break;
                case 'year':
                    $startDate = $now->copy()->subYear();
                    $fileName = 'inspeksi_1_tahun_' . $now->format('Y-m-d') . '.xlsx';
                    $presetName = '1 TAHUN TERAKHIR';
                    break;
                default:
                    return redirect()->back()->with('error', 'Preset export tidak valid.');
            }

            $endDate = $now;

            // Ambil data inspeksi berdasarkan rentang tanggal
            $inspeksis = Inspeksi::with(['kategori', 'jawaban', 'pengawas'])
                ->where('pengawas_id', Auth::id()) // Filter by pengawas yang login
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal', 'desc')
                ->get();

            if ($inspeksis->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada data inspeksi dalam rentang waktu ' . $presetName . ' terakhir.');
            }

            // Export menggunakan InspeksiPresetExport
            return Excel::download(new InspeksiPresetExport($inspeksis, $presetName, $startDate, $endDate), $fileName);
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }
}
