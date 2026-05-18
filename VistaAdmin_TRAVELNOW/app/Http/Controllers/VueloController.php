<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VueloController extends Controller
{
    // ✅ CORREGIDO: Lee la URL desde .env (API_URL), no hardcodeada
    protected function apiUrl(): string
    {
        return rtrim(config('app.api_url', 'http://127.0.0.1:8000/api'), '/') . '/vuelos';
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
            return response()->json(['error' => 'Error al obtener vuelo'], 503);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'aerolinea'    => 'required|string',
            'origen'       => 'required|string',
            'fecha_salida' => 'required|date',
            'precio'       => 'required|numeric|min:0',
            'id_destino'   => 'required|integer',
        ]);

        try {
            $response = Http::post($this->apiUrl(), $request->only([
                'aerolinea', 'origen', 'destino_vuelo', 'fecha_salida',
                'precio', 'asientos', 'imagen', 'id_destino',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear vuelo'], 503);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'aerolinea'    => 'sometimes|string',
            'origen'       => 'sometimes|string',
            'fecha_salida' => 'sometimes|date',
            'precio'       => 'sometimes|numeric|min:0',
            'id_destino'   => 'sometimes|integer',
        ]);

        try {
            $response = Http::put("{$this->apiUrl()}/{$id}", $request->only([
                'aerolinea', 'origen', 'destino_vuelo', 'fecha_salida',
                'precio', 'asientos', 'imagen', 'id_destino',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar vuelo'], 503);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl()}/{$id}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar vuelo'], 503);
        }
    }
}