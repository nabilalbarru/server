<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\KendaraanController;

Route::get('/user',[UserController::class,'index'])->middleware('auth:sanctum');
Route::post('/register',[UserController::class,'store']);
Route::post('/login',[LoginController::class,'login']);

// Route untuk kelola kendaraan
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/kendaraan', [KendaraanController::class, 'index']);
    Route::post('/kendaraan', [KendaraanController::class, 'store']);
    Route::get('/kendaraan/{id}', [KendaraanController::class, 'show']);
    Route::put('/kendaraan/{id}', [KendaraanController::class, 'update']);
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy']);
});
