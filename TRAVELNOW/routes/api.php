<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DestinoController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\VueloController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// CRUD
Route::apiResource('destinos', DestinoController::class);
Route::apiResource('hoteles', HotelController::class);
Route::apiResource('habitaciones', HabitacionController::class);
Route::apiResource('vuelos', VueloController::class);
Route::apiResource('reservaciones', ReservacionController::class);
Route::apiResource('pagos', PagoController::class);
Route::apiResource('usuarios', UsuarioController::class);