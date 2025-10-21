<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'pengawas') {
                return redirect()->route('pengawas.dashboard');
            }
            
            // Untuk pegawai, redirect ke halaman absensi
            return redirect()->route('absensi.index');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nip' => 'required|unique:users',
        'name' => 'required|string|max:255',
        'jabatan' => 'required|string|max:255',
        'unit_kerja' => 'required|string|max:255',
        'provinsi' => 'required|string|max:255',
        'role' => 'required|in:admin,pengawas', // Hanya dua role
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Create user
    $user = User::create([
        'nip' => $request->nip,
        'name' => $request->name,
        'jabatan' => $request->jabatan,
        'unit_kerja' => $request->unit_kerja,
        'provinsi' => $request->provinsi,
        'role' => $request->role,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    // Login user setelah registrasi
    auth()->login($user);

    // Redirect berdasarkan role
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('pengawas.dashboard');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}