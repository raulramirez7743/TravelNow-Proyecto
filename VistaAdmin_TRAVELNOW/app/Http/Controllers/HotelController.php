<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HotelController extends Controller
{
    // ✅ CORREGIDO: Lee la URL desde .env (API_URL), no hardcodeada
    protected function apiUrl(): string
    {
        return rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/') . '/hoteles';
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
            return response()->json(['error' => 'Error al obtener hotel'], 503);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:100',
            'estrellas'    => 'required|integer|min:1|max:5',
            'id_destino'   => 'required|integer',
            'imagen'       => 'nullable|string',
            'descripcion'  => 'nullable|string',
            'precio_noche' => 'nullable|numeric|min:0',
        ]);

        try {
            $response = Http::post($this->apiUrl(), $request->only([
                'nombre', 'estrellas', 'id_destino', 'imagen', 'descripcion', 'precio_noche',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear hotel'], 503);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'       => 'sometimes|string|max:100',
            'estrellas'    => 'sometimes|integer|min:1|max:5',
            'id_destino'   => 'sometimes|integer',
            'imagen'       => 'nullable|string',
            'descripcion'  => 'nullable|string',
            'precio_noche' => 'nullable|numeric|min:0',
        ]);

        try {
            $response = Http::put("{$this->apiUrl()}/{$id}", $request->only([
                'nombre', 'estrellas', 'id_destino', 'imagen', 'descripcion', 'precio_noche',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar hotel'], 503);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl()}/{$id}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar hotel'], 503);
        }
    }
}