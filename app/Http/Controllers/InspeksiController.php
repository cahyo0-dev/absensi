<?php

namespace App\Http\Controllers;

use App\Exports\InspeksiExport;
use App\Exports\AllInspeksiExport;
use App\Exports\InspeksiRangeExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KategoriInspeksi;
use App\Models\Inspeksi;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Carbon\Carbon;

class InspeksiController extends Controller
{
    /**
     * Menampilkan dashboard pengawas
     */
    public function dashboard()
    {
        $inspeksiHariIni = Inspeksi::where('pengawas_id', Auth::id())
            ->whereDate('created_at', today())
            ->count();

        $totalInspeksi = Inspeksi::where('pengawas_id', Auth::id())
            ->count();

        return view('pengawas.dashboard', compact('inspeksiHariIni', 'totalInspeksi'));
    }

    /**
     * Menampilkan form inspeksi
     */
    public function create()
    {
        $kategories = KategoriInspeksi::all();
        $inspeksiHariIni = Inspeksi::where('pengawas_id', Auth::id())
            ->whereDate('created_at', today())
            ->exists();

        return view('pengawas.inspeksi', compact('kategories', 'inspeksiHariIni'));
    }

    /**
     * Menyimpan data inspeksi
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanda_tangan' => 'required|string',
                'jawaban' => 'required|array',
                'jawaban.*' => 'required|array',
                'jawaban.*.*' => 'required|in:Ya,Tidak'
            ]);

            // Check if already did inspection today
            $inspeksiHariIni = Inspeksi::where('pengawas_id', Auth::id())
                ->whereDate('created_at', today())
                ->exists();

            if ($inspeksiHariIni) {
                return redirect()->back()
                    ->with('error', 'Anda sudah melakukan inspeksi hari ini. Hanya boleh 1 inspeksi per hari.')
                    ->withInput();
            }

            // Create inspection record
            $inspeksi = Inspeksi::create([
                'pengawas_id' => Auth::id(),
                'tanda_tangan' => $validated['tanda_tangan'],
                'tanggal' => now()->toDateString(),
            ]);

            // Save answers for all categories
            foreach ($validated['jawaban'] as $kategoriId => $jawabanKategori) {
                foreach ($jawabanKategori as $pertanyaanId => $jawaban) {
                    Jawaban::create([
                        'inspeksi_id' => $inspeksi->id,
                        'pertanyaan_id' => $pertanyaanId,
                        'jawaban' => $jawaban,
                    ]);
                }
            }

            return redirect()->route('pengawas.laporan')
                ->with('success', 'Inspeksi untuk semua kategori berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan laporan inspeksi
     */
    public function laporan()
    {
        $inspeksis = Inspeksi::with([
            'jawaban', 
            'jawaban.pertanyaan',
            'kategori',
            'pengawas'
        ])
        ->where('pengawas_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        $inspeksis->getCollection()->transform(function ($inspeksi) {
            $inspeksi->created_at = $inspeksi->created_at->timezone('Asia/Kuala_Lumpur');
            return $inspeksi;
        });

        return view('pengawas.laporan', compact('inspeksis'));
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
                'lokasi' => $inspeksi->lokasi,
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
