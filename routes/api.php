<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\MitraLingkunganController;

Route::get('/user',[UserController::class,'index'])->middleware('auth:sanctum');
Route::post('/register',[UserController::class,'store']);
Route::post('/login',[LoginController::class,'login']);
Route::post('/tracking', [TrackingController::class, 'store'])->middleware('auth:sanctum');
Route::get('/tracking/riwayat', [TrackingController::class, 'riwayat'])->middleware('auth:sanctum'); 

// Route untuk kelola kendaraan
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/kendaraan', [KendaraanController::class, 'index']);
    Route::post('/kendaraan', [KendaraanController::class, 'store']);
    Route::get('/kendaraan/{id}', [KendaraanController::class, 'show']);
    Route::put('/kendaraan/{id}', [KendaraanController::class, 'update']);
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy']);
    Route::get('/total_users', function () {
        $total_users = \App\Models\User::where('role', 'user')->count();
        return response()->json([
            'total_users' => $total_users,
        ]);
    });
    Route::get('/total_kendaraan', function () {
    $total = \App\Models\Kendaraan::count();
    return response()->json(['total_kendaraan' => $total]);

});
    Route::get('/total_mitra', function () {
    $total = \App\Models\MitraLingkungan::count();
    return response()->json(['total_mitra' => $total]);
 });
    // Route::get('/mitra', [MitraController::class, 'index']);
    // Route::post('/mitra', [MitraController::class, 'store']);
    // Route::get('/mitra/{id}', [MitraController::class, 'show']);
    // Route::put('/mitra/{id}', [MitraController::class, 'update']);
    // Route::delete('/mitra/{id}', [MitraController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/mitra', [MitraLingkunganController::class, 'index']);
    Route::post('/mitra', [MitraLingkunganController::class, 'store']);
    Route::get('/mitra/{id}', [MitraLingkunganController::class, 'show']);
    Route::put('/mitra/{id}', [MitraLingkunganController::class, 'update']);
    Route::delete('/mitra/{id}', [MitraLingkunganController::class, 'destroy']);

});


