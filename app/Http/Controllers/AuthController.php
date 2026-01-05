<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Wajib untuk transaksi 2 tabel
use App\Models\User;
use App\Models\Mentor;

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

            // 3. Cek Status User (Sesuai Enum di tabel users: active, inactive, banned)
            if ($user->status !== 'active' && $user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif atau dibanned.']);
            }

            // 4. Redirect sesuai Role (Sesuai Enum di tabel users: admin, mentor, student)
            if ($user->role === 'mentor') {
                return redirect()->route('mentor.dashboard');
            } elseif ($user->role === 'student') {
                return redirect()->route('student.dashboard');
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
            'fullname' => 'required|string|max:255', // Di form 'name'-nya fullname
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:student,mentor', // Pilihan role
        ]);

        // 2. Gunakan Database Transaction
        // Agar jika gagal simpan Mentor, data User juga batal disimpan (bersih)
        DB::beginTransaction();

        try {
            // A. Simpan ke Tabel 'users'
            // Mapping: Input 'fullname' masuk ke kolom 'first_name'
            $user = User::create([
                'first_name' => $request->fullname, 
                'last_name'  => null, // Nullable sesuai migrasi Anda
                'username'   => $request->username,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
                'role'       => $request->role, 
                'status'     => 'active', // Default aktif agar bisa login
                'deskripsi'  => null,
                'foto_profil'=> null,
            ]);

            // B. Simpan Data Tambahan ke Tabel 'mentors' (Jika role = mentor)
            if ($request->role === 'mentor') {
                Mentor::create([
                    'id_user' => $user->id_user, // FK ke users
                    'status'  => 'pending',      // Default pending sesuai migrasi Anda
                    'keahlian'=> null,
                    'deskripsi_mentor' => null,
                    'bank_name' => null,
                    'rekening_bank' => null,
                    'nama_rekening_mentor' => null
                ]);
            }
            
            // C. Jika Role Student
            // Karena Anda bilang modul student dikerjakan teman, 
            // kita biarkan dia terdaftar di tabel 'users' saja sudah cukup untuk login.
            /* elseif ($request->role === 'student') {
                 // Student::create(['id_user' => $user->id_user]);
            } */

            DB::commit(); // Simpan Data Permanen

            // 3. Login Otomatis
            Auth::login($user);

            // 4. Redirect
            if ($user->role === 'mentor') {
                return redirect()->route('mentor.dashboard');
            } else {
                return redirect()->route('student.dashboard'); 
            }

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua jika ada error
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