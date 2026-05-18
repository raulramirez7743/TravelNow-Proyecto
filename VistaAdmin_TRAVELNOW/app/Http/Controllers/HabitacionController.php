<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HabitacionController extends Controller
{
    // ✅ CORREGIDO: Lee la URL desde .env (API_URL)
    protected function apiUrl(): string
    {
        return rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/') . '/habitaciones';
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
            return response()->json(['error' => 'Error al obtener habitación'], 503);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo'     => 'required|string|max:50',
            'precio'   => 'required|numeric|min:0',
            'id_hotel' => 'required|integer',
        ]);

        try {
            $response = Http::post($this->apiUrl(), $request->only(['tipo', 'precio', 'id_hotel']));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear habitación'], 503);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo'     => 'sometimes|string|max:50',
            'precio'   => 'sometimes|numeric|min:0',
            'id_hotel' => 'sometimes|integer',
        ]);

        try {
            $response = Http::put("{$this->apiUrl()}/{$id}", $request->only(['tipo', 'precio', 'id_hotel']));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar habitación'], 503);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl()}/{$id}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar habitación'], 503);
        }
    }
}