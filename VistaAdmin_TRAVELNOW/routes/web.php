<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DestinoController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\VueloController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\PedidoController; // ✅ NUEVO

/*
 * VistaAdmin_TRAVELNOW — Panel de Administración
 * Corre en http://localhost:8001
 * Consume la API del Core en http://localhost:8000/api (vía env API_URL)
 */

Route::get('/', function () {
    return view('welcome');
})->name('admin.home');

// ──────────────────────────────────────────
//  CRUD de Inventario (consumen el Core)
// ──────────────────────────────────────────
Route::resource('destinos', DestinoController::class);
Route::resource('hoteles', HotelController::class);
Route::resource('habitaciones', HabitacionController::class);
Route::resource('vuelos', VueloController::class);
Route::resource('usuarios', UsuarioController::class);
Route::resource('reservaciones', ReservacionController::class);
Route::resource('pagos', PagoController::class);

// ──────────────────────────────────────────
//  Gestión de Pedidos de Clientes ✅ NUEVO
// ──────────────────────────────────────────
Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
Route::get('/pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
Route::put('/pedidos/{id}/estado', [PedidoController::class, 'updateEstado'])->name('pedidos.estado');
Route::put('/pedidos/{id}/cancelar', [PedidoController::class, 'cancelar'])->name('pedidos.cancelar');
Route::get('/pedidos/cliente/{clienteId}', [PedidoController::class, 'historialCliente'])->name('pedidos.historial');
