<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\DestinoController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\VueloController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\AuthController;

// AUTH ADMIN (Mantiene usuarios actuales)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// AUTH CLIENTE (Sanctum)
use App\Http\Controllers\ClienteAuthController;
use App\Http\Controllers\PedidoController;

Route::post('/cliente/register', [ClienteAuthController::class, 'register']);
Route::post('/cliente/login', [ClienteAuthController::class, 'login']);

// RUTAS PROTEGIDAS CLIENTE
Route::middleware('auth:sanctum')->prefix('cliente')->group(function () {
    Route::post('/logout', [ClienteAuthController::class, 'logout']);
    Route::get('/perfil', [ClienteAuthController::class, 'perfil']);
    Route::put('/perfil', [ClienteAuthController::class, 'updatePerfil']);

    // Pedidos Cliente
    Route::get('/pedidos', [PedidoController::class, 'indexCliente']);
    Route::post('/pedidos', [PedidoController::class, 'storeCliente']);
    Route::get('/pedidos/{id}', [PedidoController::class, 'showCliente']);
    Route::put('/pedidos/{id}/cancelar', [PedidoController::class, 'cancelCliente']);
    Route::put('/pedidos/{id}/pago', [PedidoController::class, 'updatePaymentStatus']);
});

// CRUD ADMIN
Route::apiResource('destinos', DestinoController::class);
Route::apiResource('hoteles', HotelController::class);
Route::apiResource('habitaciones', HabitacionController::class);
Route::apiResource('vuelos', VueloController::class);
Route::apiResource('reservaciones', ReservacionController::class);
Route::apiResource('pagos', PagoController::class);
Route::apiResource('usuarios', UsuarioController::class);

use App\Http\Controllers\ClienteController;
Route::get('/clientes', [ClienteController::class, 'index']);

// Pedidos Admin
Route::get('/admin/pedidos', [PedidoController::class, 'indexAdmin']);
Route::get('/admin/pedidos/{id}', [PedidoController::class, 'showAdmin']);
Route::put('/admin/pedidos/{id}/cancelar', [PedidoController::class, 'cancelAdmin']);
Route::put('/admin/pedidos/{id}/estado', [PedidoController::class, 'updateStatusAdmin']); // ✅ NUEVO
Route::get('/admin/clientes/{clienteId}/pedidos', [PedidoController::class, 'historialClienteAdmin']);

// Ruta temporal para Seeding en Producción
Route::get('/seed', function () {
    try {
        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
        return response()->json([
            'status' => 'success',
            'message' => '¡Base de datos limpia, migrada y cargada con éxito!',
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});