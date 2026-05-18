<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/nosotros', function () {
    return view('nosotros');
})->name('nosotros');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

// Catálogo (Consumiendo API)
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo');
Route::get('/catalogo/destino/{id}', [CatalogoController::class, 'showDestino'])->name('catalogo.destino');
Route::get('/catalogo/hotel/{id}', [CatalogoController::class, 'showHotel'])->name('catalogo.hotel');
Route::get('/catalogo/vuelo/{id}', [CatalogoController::class, 'showVuelo'])->name('catalogo.vuelo');
Route::get('/catalogo/habitacion/{id}', [CatalogoController::class, 'showHabitacion'])->name('catalogo.habitacion');

// Carrito de Compras (Sesión)
use App\Http\Controllers\CartController;
Route::get('/carrito', [CartController::class, 'index'])->name('carrito.index');
Route::post('/carrito/add', [CartController::class, 'add'])->name('carrito.add');
Route::post('/carrito/update', [CartController::class, 'update'])->name('carrito.update');
Route::post('/carrito/remove', [CartController::class, 'remove'])->name('carrito.remove');
Route::post('/carrito/clear', [CartController::class, 'clear'])->name('carrito.clear');

// Autenticación
use App\Http\Controllers\AuthController;
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/perfil', [AuthController::class, 'perfil'])->name('perfil');
Route::post('/perfil/update', [AuthController::class, 'updatePerfil'])->name('perfil.update');

// Pedidos y Checkout
use App\Http\Controllers\PedidoController;
Route::post('/pedidos/checkout', [PedidoController::class, 'checkout'])->name('pedidos.checkout');
Route::get('/mis-pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
Route::get('/mis-pedidos/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
Route::post('/mis-pedidos/{id}/cancelar', [PedidoController::class, 'cancel'])->name('pedidos.cancel');
Route::post('/mis-pedidos/{id}/pagar', [PedidoController::class, 'pay'])->name('pedidos.pay');
