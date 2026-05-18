<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * ✅ NUEVO: Controlador para que el Admin gestione los pedidos de los clientes.
 * Consume las rutas /api/admin/pedidos del Core (puerto 8000).
 */
class PedidoController extends Controller
{
    protected function apiUrl(): string
    {
        return rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/') . '/admin/pedidos';
    }

    /**
     * Listar todos los pedidos de todos los clientes.
     * GET /pedidos
     */
    public function index()
    {
        try {
            $response = Http::get($this->apiUrl());
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo conectar con el servidor'], 503);
        }
    }

    /**
     * Ver detalle de un pedido específico con sus items.
     * GET /pedidos/{id}
     */
    public function show($id)
    {
        try {
            $response = Http::get("{$this->apiUrl()}/{$id}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener pedido'], 503);
        }
    }

    /**
     * Cambiar estado de un pedido (pendiente → pagado → cancelado).
     * PUT /pedidos/{id}/estado
     */
    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado'              => 'required|in:pendiente,pagado,cancelado',
            'codigo_transaccion'  => 'nullable|string',
        ]);

        try {
            $baseUrl = rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/');
            $response = Http::put("{$baseUrl}/admin/pedidos/{$id}/estado", $request->only([
                'estado', 'codigo_transaccion',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar estado del pedido'], 503);
        }
    }

    /**
     * Cancelar un pedido.
     * PUT /pedidos/{id}/cancelar
     */
    public function cancelar($id)
    {
        try {
            $baseUrl = rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/');
            $response = Http::put("{$baseUrl}/admin/pedidos/{$id}/cancelar");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cancelar pedido'], 503);
        }
    }

    /**
     * Ver historial de pedidos de un cliente específico.
     * GET /pedidos/cliente/{clienteId}
     */
    public function historialCliente($clienteId)
    {
        try {
            $baseUrl = rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/');
            $response = Http::get("{$baseUrl}/admin/clientes/{$clienteId}/pedidos");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener historial'], 503);
        }
    }
}
