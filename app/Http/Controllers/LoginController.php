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
            'email' => 'required',
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
            
            Cache::put($key, $attempts + 1, now()->addMinutes(5));

        
            if ($attempts + 1 == 5) {
                Log::warning('Percobaan login gagal berulang pada email: ' . $email);
            }

            return response()->json([
                'status' => 'error',
                'pesan' => 'Email atau password salah. Percobaan ke-' . ($attempts + 1)
            ], 401);
        }

      
        Cache::forget($key);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'status' => 'berhasil',
            'user' => ['name' => $user->name, 'email' => $user->email],
            'token' => $token
        ]);
    }
}
