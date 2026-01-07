<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

        $users = $query->latest()->paginate(10); // Use pagination for API

        return response()->json($users);
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
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'foto_profil' => 'avatars/default.jpg',
        ]);

        if ($request->role === 'mentor') {
            Mentor::create([
                'id_user' => $user->id_user,
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'message' => 'User berhasil ditambahkan',
            'data' => $user
        ], 201);
    }

    public function show($id)
    {
        $user = User::with('adminNote')->findOrFail($id);
        return response()->json($user);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,banned',
        ]);

        $user = User::findOrFail($id);
        $user->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Status user berhasil diperbarui',
            'data' => $user
        ]);
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

        return response()->json([
            'message' => 'Catatan admin berhasil disimpan'
        ]);
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->status !== 'inactive') {
            return response()->json([
                'message' => 'Hanya user dengan status inactive yang bisa diaktifkan.'
            ], 400);
        }

        $user->update(['status' => 'active']);

        return response()->json([
            'message' => 'User berhasil diaktifkan',
            'data' => $user
        ]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }
}
