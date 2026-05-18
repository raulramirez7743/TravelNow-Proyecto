<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PedidoController extends Controller
{
    // ✅ CORREGIDO: Lee desde env('TRAVELNOW_API_URL') — funciona en local y producción
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(env('TRAVELNOW_API_URL', 'http://127.0.0.1:8000/api'), '/') . '/cliente';
    }

    public function checkout()
    {
        if (!session('token')) {
            return redirect('/login')->with('error', 'Debes iniciar sesión para completar la compra.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect('/carrito')->with('error', 'Tu carrito está vacío.');
        }

        $detalles = [];
        foreach ($cart as $item) {
            $det = [
                'tipo_producto' => $item['tipo_producto'],
                'id_producto' => $item['id_producto'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio'],
            ];
            
            if ($item['tipo_producto'] === 'viaje') {
                $det['fecha_inicio'] = $item['fecha_inicio'] ?? null;
                $det['fecha_fin'] = $item['fecha_fin'] ?? null;
                $det['id_vuelo'] = $item['id_vuelo'] ?? null;
                $det['id_habitacion'] = $item['id_habitacion'] ?? null;
            }
            
            $detalles[] = $det;
        }

        try {
            $response = Http::withToken(session('token'))->post("{$this->apiUrl}/pedidos", [
                'detalles' => $detalles
            ]);

            if ($response->successful()) {
                session()->forget('cart');
                $pedido = $response->json()['pedido'];
                return redirect()->route('pedidos.show', $pedido['id_pedido'])->with('success', '¡Pedido creado exitosamente!');
            }

            return redirect('/carrito')->with('error', 'Error al procesar el pedido. ' . $response->body());
        } catch (\Exception $e) {
            return redirect('/carrito')->with('error', 'Error de conexión con el servidor.');
        }
    }

    public function index()
    {
        if (!session('token')) return redirect('/login');

        try {
            $response = Http::withToken(session('token'))->get("{$this->apiUrl}/pedidos");
            if ($response->successful()) {
                return view('pedidos.index', ['pedidos' => $response->json()]);
            }
        } catch (\Exception $e) {
            // Error handling
        }
        return back()->with('error', 'No se pudieron cargar los pedidos.');
    }

    public function show($id)
    {
        if (!session('token')) return redirect('/login');

        try {
            $response = Http::withToken(session('token'))->get("{$this->apiUrl}/pedidos/{$id}");
            if ($response->successful()) {
                return view('pedidos.show', ['pedido' => $response->json()]);
            }
        } catch (\Exception $e) {
            // Error handling
        }
        return redirect()->route('pedidos.index')->with('error', 'Pedido no encontrado.');
    }

    public function cancel($id)
    {
        if (!session('token')) return redirect('/login');

        try {
            $response = Http::withToken(session('token'))->put("{$this->apiUrl}/pedidos/{$id}/cancelar");
            if ($response->successful()) {
                return back()->with('success', 'Pedido cancelado.');
            }
        } catch (\Exception $e) {
            // Error handling
        }
        return back()->with('error', 'Error al cancelar el pedido.');
    }

    public function pay(Request $request, $id)
    {
        if (!session('token')) return redirect('/login');

        $request->validate([
            'codigo_transaccion' => 'required'
        ]);

        try {
            $response = Http::withToken(session('token'))
                ->withHeaders(['Accept' => 'application/json'])
                ->put("{$this->apiUrl}/pedidos/{$id}/pago", [
                    'codigo_transaccion' => $request->codigo_transaccion
                ]);

            if ($response->successful()) {
                return redirect()->route('pedidos.show', $id)
                    ->with('success', '✅ ¡Pago registrado! Tu pedido ha sido confirmado.');
            }

            $errorMsg = $response->json('mensaje') ?? $response->json('message') ?? 'Error HTTP ' . $response->status();
            $errorDetail = $response->json('error') ?? '';
            $linea = $response->json('linea') ? ' (línea ' . $response->json('linea') . ')' : '';

            return back()->with('error', 'Error al procesar el pago: ' . $errorMsg . ($errorDetail ? ' → ' . $errorDetail . $linea : ''));

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', '⚠️ No se puede conectar con el servidor principal (puerto 8000). Asegúrate de que el Core API esté corriendo con: php artisan serve --port=8000');
        } catch (\Exception $e) {
            return back()->with('error', 'Error inesperado: ' . $e->getMessage());
        }
    }
}
