<?php

namespace App\Http\Controllers;

use App\Models\Inspeksi;
use App\Models\KategoriInspeksi;
use App\Models\Pertanyaan;
use App\Models\Jawaban;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PengawasController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $today = now()->timezone('Asia/Kuala_Lumpur')->toDateString();

        $inspeksiHariIni = Inspeksi::whereDate('tanggal', $today)->first();
        $sudahInspeksiHariIni = $inspeksiHariIni !== null;

        $totalInspeksi = Inspeksi::count();

        $totalBulanIni = Inspeksi::whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->count();

        $totalPengawas = User::where('role', 'pengawas')->count();

        $inspeksiTerbaru = Inspeksi::with(['kategori', 'jawaban', 'pengawas'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('pengawas.dashboard', compact(
            'sudahInspeksiHariIni',
            'inspeksiHariIni',
            'totalInspeksi',
            'totalBulanIni',
            'totalPengawas',
            'inspeksiTerbaru'
        ));
    }

    public function inspeksi()
    {
        $today = now()->timezone('Asia/Kuala_Lumpur')->toDateString();

        $inspeksiHariIni = Inspeksi::whereDate('tanggal', $today)->exists();

        if ($inspeksiHariIni) {
            return redirect()->route('pengawas.dashboard')
                ->with('error', 'Sudah ada inspeksi yang dilakukan hari ini. Hanya boleh 1 inspeksi per hari untuk semua pengawas.');
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

        $today = now()->timezone('Asia/Kuala_Lumpur')->toDateString();
        $inspeksiHariIni = Inspeksi::whereDate('tanggal', $today)->exists();

        if ($inspeksiHariIni) {
            return redirect()->route('pengawas.dashboard')
                ->with('error', 'Sudah ada inspeksi yang dilakukan hari ini. Hanya boleh 1 inspeksi per hari untuk semua pengawas.');
        }

        $inspeksi = Inspeksi::create([
            'pengawas_id' => Auth::id(),
            'kategori_id' => $validated['kategori_id'],
            'tanda_tangan' => $validated['tanda_tangan'],
            'tanggal' => $today,
        ]);

        foreach ($validated['jawaban'] as $pertanyaanId => $jawaban) {
            Jawaban::create([
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
        $inspeksis = Inspeksi::with(['kategori', 'jawaban.pertanyaan', 'pengawas'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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

    // **SETTINGS** untuk pengawas - menggunakan nama yang konsisten
    public function settings()
    {
        $user = Auth::user();
        return view('pengawas.settings', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = Auth::user();

            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->with('error', 'Password saat ini tidak benar.');
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return redirect()->back()->with('success', 'Password berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui password: ' . $e->getMessage());
        }
    }

    // **BANTUAN SEDERHANA** untuk pengawas - FAQ saja
    public function bantuan()
    {
        $user = Auth::user();

        $faqs = [
            [
                'question' => 'Bagaimana cara melakukan inspeksi harian?',
                'answer' => 'Pergi ke menu "Inspeksi" di dashboard, pilih kategori, isi checklist, dan submit. Hanya 1 inspeksi per hari yang diperbolehkan.'
            ],
            [
                'question' => 'Apakah bisa mengedit inspeksi yang sudah disubmit?',
                'answer' => 'Tidak, inspeksi yang sudah disubmit tidak dapat diubah. Pastikan data sudah benar sebelum submit.'
            ],
            [
                'question' => 'Bagaimana cara mengekspor laporan?',
                'answer' => 'Pergi ke menu "Laporan", pilih data yang ingin diekspor, dan klik tombol export. Format yang didukung: Excel.'
            ],
            [
                'question' => 'Apa yang harus dilakukan jika lupa password?',
                'answer' => 'Hubungi administrator sistem untuk reset password.'
            ],
            [
                'question' => 'Kapan inspeksi harian harus dilakukan?',
                'answer' => 'Inspeksi harian dapat dilakukan sekali sehari, kapan saja selama jam operasional.'
            ]
        ];

        return view('pengawas.bantuan', compact('user', 'faqs'));
    }
}
