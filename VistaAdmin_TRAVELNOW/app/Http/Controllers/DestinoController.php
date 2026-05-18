<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DestinoController extends Controller
{
    // ✅ CORREGIDO: Lee la URL desde .env (API_URL), consistente con los demás controllers
    protected function apiUrl(): string
    {
        return rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/') . '/destinos';
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
            return response()->json(['error' => 'Error al obtener destino'], 503);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100',
            'pais'        => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $response = Http::post($this->apiUrl(), $request->only(['nombre', 'pais', 'descripcion']));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear destino'], 503);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'      => 'sometimes|string|max:100',
            'pais'        => 'sometimes|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        try {
            $response = Http::put("{$this->apiUrl()}/{$id}", $request->only(['nombre', 'pais', 'descripcion']));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar destino'], 503);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl()}/{$id}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar destino'], 503);
        }
    }
}
