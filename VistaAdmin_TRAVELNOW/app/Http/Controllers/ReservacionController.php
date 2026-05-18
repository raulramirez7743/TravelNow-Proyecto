<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

// ✅ BUG CORREGIDO: El archivo tenía la clase UsuarioController en lugar de ReservacionController
class ReservacionController extends Controller
{
    // ✅ CORREGIDO: Lee la URL desde .env (API_URL)
    protected function apiUrl(): string
    {
        return rtrim(config('app.api_url', 'http://127.0.0.1:8000/api'), '/') . '/reservaciones';
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
            return response()->json(['error' => 'Error al obtener reservación'], 503);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha_inicio'   => 'required|date',
            'fecha_fin'      => 'required|date|after:fecha_inicio',
            'id_usuario'     => 'required|integer',
            'id_habitacion'  => 'required|integer',
            'id_vuelo'       => 'nullable|integer',
        ]);

        try {
            $response = Http::post($this->apiUrl(), $request->only([
                'fecha_inicio', 'fecha_fin', 'id_usuario', 'id_habitacion', 'id_vuelo',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear reservación'], 503);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fecha_inicio'  => 'sometimes|date',
            'fecha_fin'     => 'sometimes|date',
            'id_usuario'    => 'sometimes|integer',
            'id_habitacion' => 'sometimes|integer',
            'id_vuelo'      => 'nullable|integer',
        ]);

        try {
            $response = Http::put("{$this->apiUrl()}/{$id}", $request->only([
                'fecha_inicio', 'fecha_fin', 'id_usuario', 'id_habitacion', 'id_vuelo',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar reservación'], 503);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl()}/{$id}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar reservación'], 503);
        }
    }
}