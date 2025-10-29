<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use App\Models\Inspeksi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;
use App\Exports\InspeksiExport;
use App\Exports\UsersExport;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            $totalUsers = User::count();
            $totalAbsensi = Absensi::count();
            $totalInspeksi = Inspeksi::count();
            
            // Ambil data terbaru dengan eager loading untuk menghindari N+1 query
            $absensiTerbaru = Absensi::latest()->take(5)->get();
            $inspeksiTerbaru = Inspeksi::with(['pengawas', 'kategori'])
                ->latest()
                ->take(5)
                ->get();
            
            // Gabungkan koleksi
            $aktivitasTerbaru = $absensiTerbaru->concat($inspeksiTerbaru)
                ->sortByDesc(function($item) {
                    return $item->created_at;
                })
                ->take(5);

            return view('admin.dashboard', compact(
                'totalUsers', 
                'totalAbsensi', 
                'totalInspeksi', 
                'aktivitasTerbaru'
            ));
            
        } catch (\Exception $e) {
            // Fallback jika ada error
            return view('admin.dashboard', [
                'totalUsers' => 0,
                'totalAbsensi' => 0,
                'totalInspeksi' => 0,
                'aktivitasTerbaru' => collect()
            ])->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    public function users()
    {
        try {
            $users = User::latest()->get();
            return view('admin.users', compact('users'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat data users: ' . $e->getMessage());
        }
    }

    public function laporan(Request $request)
    {
        try {
            $queryAbsensi = Absensi::query();
            $queryInspeksi = Inspeksi::query();
            $queryUsers = User::query();

            // Filter berdasarkan tanggal
            if ($request->has('start_date') && $request->start_date && 
                $request->has('end_date') && $request->end_date) {
                
                $startDate = $request->start_date . ' 00:00:00';
                $endDate = $request->end_date . ' 23:59:59';
                
                $queryAbsensi->whereBetween('created_at', [$startDate, $endDate]);
                $queryInspeksi->whereBetween('created_at', [$startDate, $endDate]);
                $queryUsers->whereBetween('created_at', [$startDate, $endDate]);
            }

            $absensis = $queryAbsensi->latest()->get();
            $inspeksis = $queryInspeksi->with(['pengawas', 'kategori'])->latest()->get();
            $users = $queryUsers->latest()->get();

            return view('admin.laporan', compact('absensis', 'inspeksis', 'users'));
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memuat laporan: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:absensi,inspeksi,users',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date'
            ]);

            $type = $request->type;
            $startDate = $request->start_date;
            $endDate = $request->end_date;

            $filename = $type . '_' . date('Y-m-d') . '.xlsx';

            switch ($type) {
                case 'absensi':
                    return Excel::download(new AbsensiExport($startDate, $endDate), $filename);
                case 'inspeksi':
                    return Excel::download(new InspeksiExport($startDate, $endDate), $filename);
                case 'users':
                    return Excel::download(new UsersExport($startDate, $endDate), $filename);
                default:
                    return redirect()->back()->with('error', 'Tipe export tidak valid.');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan export: ' . $e->getMessage());
        }
    }

    /**
     * Export semua data tanpa filter
     */
    public function exportAll($type)
    {
        try {
            $filename = $type . '_all_' . date('Y-m-d') . '.xlsx';

            switch ($type) {
                case 'absensi':
                    return Excel::download(new AbsensiExport(null, null), $filename);
                case 'inspeksi':
                    return Excel::download(new InspeksiExport(null, null), $filename);
                case 'users':
                    return Excel::download(new UsersExport(null, null), $filename);
                default:
                    return redirect()->back()->with('error', 'Tipe export tidak valid.');
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan export: ' . $e->getMessage());
        }
    }
}