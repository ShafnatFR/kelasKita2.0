<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Mentor;
use App\Models\Transaksi; 

class AuthController extends Controller
{
    // =========================================================================
    // BAGIAN LOGIN
    // =========================================================================

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 3. Cek Status User
            if ($user->status !== 'active' && $user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif atau dibanned.']);
            }

            // 4. Redirect sesuai Role
            if ($user->role === 'mentor') {
                return redirect()->route('mentor.dashboard');

            } elseif ($user->role === 'student') {

                $kelas = Transaksi::where('id_user', $user->id_user)
                    ->where('status', 'success')
                    ->first();

                if ($kelas) {
                    return redirect()->route('kelas.belajar', $kelas->id_kelas);
                }

                return redirect()->route('learning.index');

            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard'); 
            }

            return redirect('/'); 
        }

        // 5. Jika Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // =========================================================================
    // BAGIAN REGISTER
    // =========================================================================

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:student,mentor',
        ]);

        DB::beginTransaction();

        try {
            // A. Simpan ke tabel users
            $user = User::create([
                'first_name' => $request->fullname,
                'last_name'  => null,
                'username'   => $request->username,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => $request->role,
                'status'     => 'active',
                'deskripsi'  => null,
                'foto_profil'=> null,
            ]);

            // B. Jika Mentor
            if ($request->role === 'mentor') {
                Mentor::create([
                    'id_user' => $user->id_user,
                    'status'  => 'pending',
                    'keahlian'=> null,
                    'deskripsi_mentor' => null,
                    'bank_name' => null,
                    'rekening_bank' => null,
                    'nama_rekening_mentor' => null
                ]);
            }

            DB::commit();

            // 3. Login Otomatis
            Auth::login($user);

            // 4. Redirect
            if ($user->role === 'mentor') {
                return redirect()->route('mentor.dashboard');
            } else {
                return redirect()->route('learning.index');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mendaftar: ' . $e->getMessage()])->withInput();
        }
    }

    // =========================================================================
    // BAGIAN LOGOUT
    // =========================================================================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
