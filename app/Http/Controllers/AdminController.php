<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absensi;
use App\Models\Inspeksi;
use App\Models\KategoriInspeksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;
use App\Exports\AllInspeksiExport;
use App\Exports\InspeksiExport;
use App\Exports\UsersExport;

class AdminController extends Controller
{
    // app/Http/Controllers/AdminController.php
    public function dashboard()
    {
        $user = Auth::user();

        // Statistik
        $totalUsers = User::count();
        $totalAbsensi = Absensi::count();
        $totalInspeksi = Inspeksi::count();

        // Statistik absensi hari ini
        $absensiMasukHariIni = Absensi::whereDate('waktu_masuk', today())->count();
        $absensiPulangHariIni = Absensi::whereDate('waktu_pulang', today())->count();

        // Data absensi hari ini untuk tabel
        $absensiHariIni = Absensi::whereDate('created_at', today())
            ->orderBy('waktu_masuk', 'desc')
            ->get();

        // Aktivitas terbaru (gabungan absensi dan inspeksi)
        $absensiTerbaru = Absensi::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $inspeksiTerbaru = Inspeksi::with(['pengawas', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Gabungkan aktivitas
        $aktivitasTerbaru = $absensiTerbaru->concat($inspeksiTerbaru)
            ->sortByDesc('created_at')
            ->take(5);

        return view('admin.dashboard', compact(
            'user',
            'totalUsers',
            'totalAbsensi',
            'totalInspeksi',
            'absensiMasukHariIni',
            'absensiPulangHariIni',
            'absensiHariIni',
            'aktivitasTerbaru'
        ));
    }

    public function users()
    {
        try {
            $user = Auth::user();
            $users = User::latest()->get();
            return view('admin.users', compact('user', 'users'));
        } catch (\Exception $e) {
            return view('admin.users', [
                'user' => Auth::user(),
                'users' => new Collection()
            ])->with('error', 'Gagal memuat data users: ' . $e->getMessage());
        }
    }

    public function createUser()
    {
        try {
            $user = Auth::user();
            return view('admin.users.create', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Gagal memuat form: ' . $e->getMessage());
        }
    }

    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'nip' => 'required|unique:users',
                'name' => 'required',
                'jabatan' => 'required',
                'unit_kerja' => 'required',
                'provinsi' => 'required',
                'role' => 'required|in:admin,pengawas',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);

            User::create([
                'nip' => $request->nip,
                'name' => $request->name,
                'jabatan' => $request->jabatan,
                'unit_kerja' => $request->unit_kerja,
                'provinsi' => $request->provinsi,
                'role' => $request->role,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('admin.users')->with('success', 'User berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal membuat user: ' . $e->getMessage());
        }
    }

    public function showUser($id)
    {
        try {
            $user = Auth::user();
            $userDetail = User::findOrFail($id);

            // Hitung total inspeksi jika user adalah pengawas
            $inspeksiCount = 0;
            if ($userDetail->role === 'pengawas') {
                $inspeksiCount = Inspeksi::where('pengawas_id', $id)->count();
            }

            return view('admin.users.show', compact('user', 'userDetail', 'inspeksiCount'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'User tidak ditemukan: ' . $e->getMessage());
        }
    }

    public function editUser($id)
    {
        try {
            $user = Auth::user();
            $userEdit = User::findOrFail($id);
            return view('admin.users.edit', compact('user', 'userEdit'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'User tidak ditemukan: ' . $e->getMessage());
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $request->validate([
                'nip' => 'required|unique:users,nip,' . $id,
                'name' => 'required',
                'jabatan' => 'required',
                'unit_kerja' => 'required',
                'provinsi' => 'required',
                'role' => 'required|in:admin,pengawas',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|min:6|confirmed',
            ]);

            $data = [
                'nip' => $request->nip,
                'name' => $request->name,
                'jabatan' => $request->jabatan,
                'unit_kerja' => $request->unit_kerja,
                'provinsi' => $request->provinsi,
                'role' => $request->role,
                'email' => $request->email,
            ];

            if ($request->password) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('admin.users')->with('success', 'User berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal mengupdate user: ' . $e->getMessage());
        }
    }

    public function destroyUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $currentUser = Auth::user();

            // Cegah menghapus diri sendiri
            if ($user->id === $currentUser->id) {
                return redirect()->route('admin.users')->with('error', 'Tidak dapat menghapus akun sendiri');
            }

            $user->delete();

            return redirect()->route('admin.users')->with('success', 'User berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }

    // app/Http/Controllers/AdminController.php
    public function systemInfo()
    {
        $user = Auth::user();

        // Statistik sistem
        $stats = [
            'total_users' => User::count(),
            'total_pengawas' => User::where('role', 'pengawas')->count(),
            'total_inspeksi' => Inspeksi::count(),
            'inspeksi_bulan_ini' => Inspeksi::whereMonth('created_at', now()->month)->count(),
            'kategori_aktif' => KategoriInspeksi::has('pertanyaan')->count(),
        ];

        // Info server
        $serverInfo = [
            'laravel_version' => app()->version(),
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'N/A',
            'database_connection' => config('database.default'),
            'timezone' => config('app.timezone'),
            'environment' => config('app.env'),
        ];

        $absensiTerbaru = Absensi::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $inspeksiTerbaru = Inspeksi::with(['pengawas', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $aktivitasTerbaru = $absensiTerbaru->concat($inspeksiTerbaru)
            ->sortByDesc('created_at')
            ->take(5);

        return view('admin.system-info', compact(
            'user',
            'stats',
            'serverInfo',
            'aktivitasTerbaru'
        ));
    }

    // **BANTUAN SEDERHANA** untuk admin - FAQ saja
    public function bantuan()
    {
        $user = Auth::user();

        $faqs = [
            [
                'question' => 'Bagaimana mengelola user?',
                'answer' => 'Pergi ke menu "Users" untuk menambah, edit, atau hapus user. Pastikan NIP dan email unik untuk setiap user.'
            ],
            [
                'question' => 'Cara mengekspor data lengkap?',
                'answer' => 'Gunakan menu "Export" untuk mengekspor data absensi, inspeksi, atau users. Pilih rentang tanggal jika diperlukan.'
            ],
            [
                'question' => 'Bagaimana reset password user?',
                'answer' => 'Edit user tersebut dan masukkan password baru di form edit. Biarkan kosong jika tidak ingin mengganti password.'
            ],
            [
                'question' => 'Apa perbedaan role admin dan pengawas?',
                'answer' => 'Admin memiliki akses penuh ke semua fitur termasuk manajemen user. Pengawas hanya bisa melakukan inspeksi dan melihat laporan.'
            ],
            [
                'question' => 'Bagaimana melihat statistik sistem?',
                'answer' => 'Dashboard menampilkan total users, absensi, inspeksi, dan aktivitas terbaru secara real-time.'
            ]
        ];

        return view('admin.bantuan', compact('user', 'faqs'));
    }

    public function laporan(Request $request)
    {
        // INISIALISASI DENGAN COLLECTION KOSONG SEBELUM APAPUN
        $absensis = new Collection();
        $inspeksis = new Collection();
        $users = new Collection();
        $user = Auth::user();

        try {
            // Query Absensi dengan try-catch terpisah
            try {
                $queryAbsensi = Absensi::query();
                if (
                    $request->has('start_date') && $request->start_date &&
                    $request->has('end_date') && $request->end_date
                ) {
                    $startDate = $request->start_date . ' 00:00:00';
                    $endDate = $request->end_date . ' 23:59:59';
                    $queryAbsensi->whereBetween('waktu_masuk', [$startDate, $endDate]);
                }
                $absensis = $queryAbsensi->orderBy('waktu_masuk', 'desc')->get();
            } catch (\Exception $e) {
                $absensis = new Collection();
            }

            // Query Users dengan try-catch terpisah
            try {
                $queryUsers = User::query();
                if (
                    $request->has('start_date') && $request->start_date &&
                    $request->has('end_date') && $request->end_date
                ) {
                    $startDate = $request->start_date . ' 00:00:00';
                    $endDate = $request->end_date . ' 23:59:59';
                    $queryUsers->whereBetween('created_at', [$startDate, $endDate]);
                }
                $users = $queryUsers->latest()->get();
            } catch (\Exception $e) {
                $users = new Collection();
            }

            // Query Inspeksi dengan try-catch terpisah
            try {
                $queryInspeksi = Inspeksi::query();
                if (
                    $request->has('start_date') && $request->start_date &&
                    $request->has('end_date') && $request->end_date
                ) {
                    $startDate = $request->start_date . ' 00:00:00';
                    $endDate = $request->end_date . ' 23:59:59';
                    $queryInspeksi->whereBetween('created_at', [$startDate, $endDate]);
                }

                // SANGAT SEDERHANA - hanya ambil data dasar
                $inspeksis = $queryInspeksi->latest()->get();
            } catch (\Exception $e) {
                $inspeksis = new Collection();
            }

            return view('admin.laporan', compact(
                'user',
                'absensis',
                'inspeksis',
                'users'
            ));
        } catch (\Exception $e) {
            // Tetap gunakan collection kosong yang sudah diinisialisasi
        }

        // FINAL VALIDATION - PASTIKAN SEMUA ADALAH COLLECTION
        if (!$absensis instanceof Collection) $absensis = new Collection();
        if (!$inspeksis instanceof Collection) $inspeksis = new Collection();
        if (!$users instanceof Collection) $users = new Collection();

        return view('admin.laporan', compact(
            'user',
            'absensis',
            'inspeksis',
            'users'
        ));
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

            $filename = $type . '_' . date('Y-m-d_H-i-s') . '.xlsx';

            switch ($type) {
                case 'absensi':
                    // Gunakan AbsensiExport dengan parameter tanggal
                    return Excel::download(new AbsensiExport($startDate, $endDate), $filename);

                case 'inspeksi':
                    // Gunakan AllInspeksiExport untuk semua data inspeksi
                    return Excel::download(new AllInspeksiExport($startDate, $endDate), $filename);

                case 'users':
                    $query = User::query();
                    if ($startDate && $endDate) {
                        $start = $startDate . ' 00:00:00';
                        $end = $endDate . ' 23:59:59';
                        $query->whereBetween('created_at', [$start, $end]);
                    }
                    $data = $query->get();

                    if ($data->isEmpty()) {
                        return redirect()->back()->with('error', 'Tidak ada data users untuk diekspor.');
                    }

                    return Excel::download(new UsersExport($data), $filename);

                default:
                    return redirect()->back()->with('error', 'Tipe export tidak valid.');
            }
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal melakukan export: ' . $e->getMessage());
        }
    }

    public function exportAll($type)
    {
        try {
            $filename = $type . '_all_' . date('Y-m-d') . '.xlsx';

            switch ($type) {
                case 'absensi':
                    return Excel::download(new AbsensiExport(), $filename);
                case 'inspeksi':
                    // Gunakan AllInspeksiExport tanpa parameter untuk semua data
                    return Excel::download(new AllInspeksiExport(), $filename);
                case 'users':
                    return Excel::download(new UsersExport(User::all()), $filename);
                default:
                    return redirect()->back()->with('error', 'Tipe export tidak valid.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal melakukan export: ' . $e->getMessage());
        }
    }
}
