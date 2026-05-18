<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UsuarioController extends Controller
{
    // ✅ CORREGIDO: Apuntaba a 8001 (sí mismo). Ahora apunta al Core (8000)
    protected function apiUrl(): string
    {
        return rtrim(env('API_URL', 'http://127.0.0.1:8000/api'), '/') . '/usuarios';
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
            return response()->json(['error' => 'Error al obtener usuario'], 503);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'correo'   => 'required|email|max:100',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:15',
            'rol'      => 'nullable|in:admin,staff',
        ]);

        try {
            $response = Http::post($this->apiUrl(), $request->only([
                'nombre', 'correo', 'password', 'telefono', 'rol',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear usuario'], 503);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'   => 'sometimes|string|max:100',
            'correo'   => 'sometimes|email|max:100',
            'password' => 'sometimes|string|min:6',
            'telefono' => 'nullable|string|max:15',
            'rol'      => 'nullable|in:admin,staff',
        ]);

        try {
            $response = Http::put("{$this->apiUrl()}/{$id}", $request->only([
                'nombre', 'correo', 'password', 'telefono', 'rol',
            ]));
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar usuario'], 503);
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::delete("{$this->apiUrl()}/{$id}");
            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar usuario'], 503);
        }
    }
}
