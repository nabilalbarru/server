<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;

Route::get('/user',[UserController::class,'index'])->middleware('auth:sanctum');
Route::post('/register',[UserController::class,'store']);
Route::post('/login',[LoginController::class,'login']);