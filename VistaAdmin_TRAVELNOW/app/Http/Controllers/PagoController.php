<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PagoController extends Controller
{
    // ✅ CORREGIDO: Apuntaba a 8001 (sí mismo). Ahora apunta al Core (8000)
    protected function apiUrl(): string
    {
        return rtrim(config('app.api_url', 'http://127.0.0.1:8000/api'), '/') . '/pagos';
    }

    public function index()
    {
        try {
            $response = Http::get($this->apiUrl());
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo conectar con el servidor'], 503);
        }
    }

    public function show($id)
    {
        try {
            $response = Http::get("{$this->apiUrl()}/{$id}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener pago'], 503);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'monto'          => 'required|numeric|min:0',
            'metodo_pago'    => 'required|string|max:50',
            'id_reservacion' => 'required|integer',
        ]);

        try {
            $response = Http::post($this->apiUrl(), $request->only([
                'monto', 'metodo_pago', 'id_reservacion',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al registrar pago'], 503);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'monto'          => 'sometimes|numeric|min:0',
            'metodo_pago'    => 'sometimes|string|max:50',
            'id_reservacion' => 'sometimes|integer',
        ]);

        try {
            $response = Http::put("{$this->apiUrl()}/{$id}", $request->only([
                'monto', 'metodo_pago', 'id_reservacion',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar pago'], 503);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl()}/{$id}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar pago'], 503);
        }
    }
}
