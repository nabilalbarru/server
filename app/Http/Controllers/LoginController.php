<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache; 
use Illuminate\Support\Facades\Log;   

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $email = $request->email;
        $key = 'login_attempts_' . $email;
        $attempts = Cache::get($key, 0);

        
        if ($attempts >= 5) {
            return response()->json([
                'status' => 'error',
                'pesan' => 'Akun terkunci sementara. Coba lagi dalam 15 menit.'
            ], 429); 
        }

        $user = User::where('email', $email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            
            Cache::put($key, $attempts + 1, now()->addMinutes(15));

        
            if ($attempts + 1 >= 5) {
                Log::warning('Percobaan login gagal berulang pada email: ' . $email);
            }

            return response()->json([
                'status' => 'error',
                'pesan' => 'Email atau password salah. Sisa percobaan: ' . (5 - ($attempts + 1))
            ], 401);
        }

      
        Cache::forget($key);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'berhasil',
            'user' => $user, 
            'token' => $token
        ]);
    }

    /**
     * fungsi logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'berhasil',
            'pesan' => 'Logout berhasil'
        ]);
    }
}
