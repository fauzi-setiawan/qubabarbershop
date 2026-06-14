<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar semua user dengan fitur pencarian & filter role (Use Case 010)
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur Filter berdasarkan Role
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Fitur Pencarian Nama/Email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    // Menyimpan user baru (Use Case 010 - Step 5)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:Admin,Project Manager,Software QA',
        ]);

        // Generate inisial otomatis untuk avatar default
        $words = explode(' ', $validated['name']);
        $avatar = mb_strtoupper(implode('', array_map(fn($w) => mb_substr($w, 0, 1), $words)));

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'avatar' => mb_substr($avatar, 0, 2),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil disimpan',
            'data' => $user
        ], 201);
    }

    // Mengubah data user (Use Case 010 - Step 10)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:Admin,Project Manager,Software QA',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Update inisial avatar jika nama pengguna berubah
        $words = explode(' ', $validated['name']);
        $avatar = mb_strtoupper(implode('', array_map(fn($w) => mb_substr($w, 0, 1), $words)));
        $user->avatar = mb_substr($avatar, 0, 2);

        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil diubah',
            'data' => $user
        ]);
    }

    // Menghapus user (Use Case 010 - Step 15)
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil dihapus'
        ]);
    }
}