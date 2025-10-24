<?php

namespace App\Http\Controllers;

use App\Exports\InspeksiExport;
use App\Exports\AllInspeksiExport;
use App\Exports\InspeksiRangeExport;
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
 * Menampilkan dashboard pengawas
 */
    public function dashboard()
    {
        Log::info('=== DASHBOARD DATA ===');
        
        // Data dengan logging untuk debugging - update query untuk menggunakan tanggal
        $inspeksiHariIni = Inspeksi::whereDate('tanggal', today())->first();
        $totalInspeksi = Inspeksi::count();
        $inspeksiBulanIni = Inspeksi::whereMonth('tanggal', now()->month)->count();
        $totalPengawas = \App\Models\User::where('role', 'pengawas')->count();
        
        // Tambahkan variabel inspektorHariIni
        $inspektorHariIni = null;
        if ($inspeksiHariIni) {
            $inspektorHariIni = $inspeksiHariIni->pengawas;
        }
        
        Log::info('Dashboard Data:', [
            'inspeksi_hari_ini' => $inspeksiHariIni ? $inspeksiHariIni->id : 'Tidak ada',
            'total_inspeksi' => $totalInspeksi,
            'inspeksi_bulan_ini' => $inspeksiBulanIni,
            'total_pengawas' => $totalPengawas,
            'inspektor_hari_ini' => $inspektorHariIni ? $inspektorHariIni->name : 'Tidak ada'
        ]);

        return view('pengawas.dashboard', compact(
            'inspeksiHariIni',
            'totalInspeksi',
            'inspeksiBulanIni',
            'totalPengawas',
            'inspektorHariIni'
        ));
    }

    /**
     * Menampilkan form inspeksi
     */
    public function create()
    {
        // Cek apakah sudah ada inspeksi hari ini (GLOBAL - 1x/hari untuk semua pengawas)
        $sudahInspeksiHariIni = Inspeksi::sudahInspeksiHariIni();
        $inspeksiDataHariIni = null;
        $inspektorHariIni = null;

        if ($sudahInspeksiHariIni) {
            $inspeksiDataHariIni = Inspeksi::getInspeksiHariIni();
            $inspektorHariIni = $inspeksiDataHariIni->pengawas ?? null;
        }

        $kategories = KategoriInspeksi::all();
        $totalPengawas = User::totalPengawas();

        return view('pengawas.inspeksi', compact(
            'kategories', 
            'sudahInspeksiHariIni',
            'inspeksiDataHariIni',
            'inspektorHariIni',
            'totalPengawas'
        ));
    }

    /**
     * Menyimpan data inspeksi
     */
    public function store(Request $request)
    {
        Log::info('=== MEMULAI PROSES SIMPAN INSPEKSI ===');
        Log::info('Data request:', $request->all());
        
        // Validasi: cek apakah sudah ada inspeksi hari ini (GLOBAL)
        if (Inspeksi::sudahInspeksiHariIni()) {
            Log::warning('Inspeksi hari ini sudah ada, tidak bisa simpan');
            return redirect()->back()
                ->with('error', 'Maaf, inspeksi hari ini sudah dilakukan oleh pengawas lain. Sistem hanya mengizinkan 1 inspeksi per hari.')
                ->withInput();
        }

        Log::info('Belum ada inspeksi hari ini, lanjut validasi');

        // Validasi input
        $validator = Validator::make($request->all(), [
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

        try {
            DB::beginTransaction();
            Log::info('Transaction dimulai');

            // Create inspeksi - SESUAIKAN DENGAN STRUKTUR TABEL
            $inspeksi = Inspeksi::create([
                'pengawas_id' => Auth::id(),
                'kategori_id' => 1, // Ubah dari kategori_inspeksi_id menjadi kategori_id
                'tanda_tangan' => $request->tanda_tangan,
                'tanggal' => now(), // Tambahkan field tanggal
                // Hapus field yang tidak ada di tabel: keterangan, status
            ]);

            Log::info('Inspeksi created dengan ID: ' . $inspeksi->id);

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

            return redirect()->route('pengawas.laporan')
                ->with('success', 'Inspeksi berhasil disimpan! Anda adalah pengawas yang melakukan inspeksi hari ini.');

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
     * Menampilkan laporan inspeksi
     */
    public function laporan()
    {
        Log::info('=== LAPORAN DATA ===');
        
        // Ambil semua inspeksi dengan relasi
        $inspeksis = Inspeksi::with(['pengawas', 'jawaban'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        Log::info('Jumlah inspeksi ditemukan: ' . $inspeksis->count());

        // Statistik - update query untuk menggunakan tanggal
        $totalBulanIni = Inspeksi::whereMonth('tanggal', now()->month)->count();
        $kategoriBerbeda = Inspeksi::distinct('kategori_id')->count('kategori_id');
        
        $hariDalamBulan = now()->daysInMonth;
        $rataRataPerHari = $hariDalamBulan > 0 ? round($totalBulanIni / $hariDalamBulan, 1) : 0;

        return view('pengawas.laporan', compact(
            'inspeksis',
            'totalBulanIni',
            'kategoriBerbeda',
            'rataRataPerHari'
        ));
    }

    /**
     * Menampilkan detail inspeksi
     */
    public function show($id)
    {
        try {
            $inspeksi = Inspeksi::with([
                'kategori',
                'jawaban.pertanyaan', 
                'pengawas'
            ])
            ->where('pengawas_id', Auth::id())
            ->findOrFail($id);

            // Jika request dari JavaScript fetch, kembalikan JSON
            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'id' => $inspeksi->id,
                    'created_at' => $inspeksi->created_at->timezone('Asia/Kuala_Lumpur')->toISOString(),
                    'kategori' => [
                        'id' => $inspeksi->kategori->id ?? null,
                        'nama' => $inspeksi->kategori->nama ?? 'Semua Kategori',
                        'deskripsi' => $inspeksi->kategori->deskripsi ?? ''
                    ],
                    'jawabans' => $inspeksi->jawaban->map(function($jawaban) {
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
            }

            // Jika request biasa, kembalikan view
            return view('pengawas.detail_inspeksi', compact('inspeksi'));

        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->is('api/*')) {
                return response()->json([
                    'error' => 'Inspeksi tidak ditemukan atau akses ditolak',
                    'message' => $e->getMessage()
                ], 404);
            }
            
            return redirect()->route('pengawas.laporan')
                ->with('error', 'Inspeksi tidak ditemukan');
        }
    }
    // API untuk mendapatkan detail inspeksi
    public function apiDetail($id)
    {
        try {
            $inspeksi = Inspeksi::with([
                'kategori',
                'jawaban.pertanyaan',
                'pengawas'
            ])
            ->where('pengawas_id', Auth::id())
            ->findOrFail($id);

            return response()->json([
                'id' => $inspeksi->id,
                'created_at' => $inspeksi->created_at,
                'kategori' => [
                    'nama' => $inspeksi->kategori->nama ?? 'Semua Kategori',
                    'deskripsi' => $inspeksi->kategori->deskripsi ?? null
                ],
                'jawabans' => $inspeksi->jawaban->map(function($jawaban) {
                    return [
                        'id' => $jawaban->id,
                        'jawaban' => $jawaban->jawaban,
                        'pertanyaan' => [
                            'pertanyaan' => $jawaban->pertanyaan->pertanyaan ?? 'Pertanyaan tidak ditemukan'
                        ]
                    ];
                }),
                'tanda_tangan' => $inspeksi->tanda_tangan,
                'keterangan' => $inspeksi->keterangan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Inspeksi tidak ditemukan atau akses ditolak'
            ], 404);
        }
    }

    public function export($id): BinaryFileResponse
    {
        $inspeksi = Inspeksi::with(['kategori', 'jawaban.pertanyaan', 'pengawas'])
            ->where('pengawas_id', Auth::id())
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



    // Tambahkan method ini di controller
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

    // Method untuk export dengan preset waktu (opsional)
    public function exportPreset($preset)
    {
        $endDate = Carbon::now();
        
        switch ($preset) {
            case 'week':
                $startDate = Carbon::now()->subWeek();
                $filename = 'inspeksi-1-minggu-terakhir.xlsx';
                break;
            case 'month':
                $startDate = Carbon::now()->subMonth();
                $filename = 'inspeksi-1-bulan-terakhir.xlsx';
                break;
            case 'year':
                $startDate = Carbon::now()->subYear();
                $filename = 'inspeksi-1-tahun-terakhir.xlsx';
                break;
            case 'all':
                $startDate = Carbon::create(2000, 1, 1); // Tanggal sangat awal
                $filename = 'inspeksi-semua-data.xlsx';
                break;
            default:
                return redirect()->back()->with('error', 'Preset tidak valid');
        }

        return Excel::download(new InspeksiRangeExport($startDate, $endDate), $filename);
    }

}
