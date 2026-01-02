<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter Role
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Get filter options
        $roles = User::select('role')->distinct()->pluck('role');
        $statuses = User::select('status')->distinct()->pluck('status');

        $users = $query->latest()->get();

        return view('admin.pages.kelola-user.index', compact('users', 'roles', 'statuses'));
    }

    public function create()
    {
        return view('admin.pages.kelola-user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'username' => 'required|string|max:20|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,mentor,student',
            'status' => 'required|in:active,inactive,banned',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'foto_profil' => 'avatars/default.jpg', // Default avatar
        ]);

        // Jika role mentor, buat juga record di tabel mentors
        if ($request->role === 'mentor') {
            \App\Models\Mentor::create([
                'id_user' => $user->id_user,
                'status' => 'pending', // Default status mentor baru
            ]);
        }

        return redirect()->route('admin.kelola.user')->with('success', 'User berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.pages.kelola-user.show', compact('user'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,banned',
        ]);

        $user = User::findOrFail($id);
        $user->update(['status' => $request->status]);

        return back()->with('success', 'Status user berhasil diperbarui.');
    }

    public function updateCatatan(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'nullable|string',
        ]);

        $user = User::findOrFail($id);
        
        if ($user->adminNote) {
            $user->adminNote->update(['content' => $request->catatan_admin]);
        } else {
            $user->adminNote()->create(['content' => $request->catatan_admin]);
        }

        return back()->with('success', 'Catatan admin berhasil disimpan.');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        
        // Hanya bisa mengaktifkan jika statusnya inactive
        if ($user->status !== 'inactive') {
            return back()->with('error', 'Hanya user dengan status inactive yang bisa diaktifkan.');
        }

        $user->update(['status' => 'active']);

        return back()->with('success', 'User berhasil diaktifkan.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus secara permanen.');
    }
}
