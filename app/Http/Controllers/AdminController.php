<?php
// app/Http/Controllers/AdminController.php
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
        $totalUsers = User::count();
        $totalAbsensi = Absensi::count();
        $totalInspeksi = Inspeksi::count();
        
        $aktivitasTerbaru = collect();
        
        // Ambil 5 absensi terbaru
        $absensiTerbaru = Absensi::latest()->take(5)->get();
        $aktivitasTerbaru = $aktivitasTerbaru->merge($absensiTerbaru);
        
        // Ambil 5 inspeksi terbaru
        $inspeksiTerbaru = Inspeksi::with('pengawas')->latest()->take(5)->get();
        $aktivitasTerbaru = $aktivitasTerbaru->merge($inspeksiTerbaru);
        
        // Urutkan berdasarkan created_at
        $aktivitasTerbaru = $aktivitasTerbaru->sortByDesc('created_at')->take(5);

        return view('admin.dashboard', compact('totalUsers', 'totalAbsensi', 'totalInspeksi', 'aktivitasTerbaru'));
    }

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function laporan(Request $request)
    {
        $queryAbsensi = Absensi::query();
        $queryInspeksi = Inspeksi::query();
        $queryUsers = User::query();

        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            
            $queryAbsensi->whereBetween('created_at', [$startDate, $endDate]);
            $queryInspeksi->whereBetween('created_at', [$startDate, $endDate]);
            $queryUsers->whereBetween('created_at', [$startDate, $endDate]);
        }

        $absensis = $queryAbsensi->get();
        $inspeksis = $queryInspeksi->with(['pengawas', 'kategori'])->get();
        $users = $queryUsers->get();

        return view('admin.laporan', compact('absensis', 'inspeksis', 'users'));
    }

    public function export(Request $request)
    {
        $type = $request->type;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        switch ($type) {
            case 'absensi':
                return Excel::download(new AbsensiExport($startDate, $endDate), 'absensi.xlsx');
            case 'inspeksi':
                return Excel::download(new InspeksiExport($startDate, $endDate), 'inspeksi.xlsx');
            case 'users':
                return Excel::download(new UsersExport($startDate, $endDate), 'users.xlsx');
            default:
                return redirect()->back()->with('error', 'Tipe export tidak valid.');
        }
    }
}