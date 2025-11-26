<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $allUsers = User::all();

        return response()->json([

            'success' => 'Berhasil mengambil data',
            'users'   => $allUsers
        ]);
    }

    public function store(Request $request)
    {
         
        $request->validate([
            'nama'     => 'required',
            'email'    => 'required|unique:users,email',
            'katasandi' => 'required|min:8|string',
            'role'     => 'required'
        ]);

        $user = User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'katasandi' => Hash::make($request->katasandi),
            'role'     => $request->role
        ]);

        // Buat token saat register
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => 'berhasil menambahkan data',
            'user'    => $user,
            'token'   => $token
        ]);
    }
}
