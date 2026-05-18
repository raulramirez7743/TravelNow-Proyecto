<?php

namespace App\Http\Controllers;

use App\Models\Vuelo;
use Illuminate\Http\Request;

class VueloController extends Controller
{
    public function index()
    {
        return response()->json([
            'resultado' => true,
            'datos' => Vuelo::all(),
            'mensaje' => 'Lista de vuelos',
            'errores' => null
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'aerolinea'     => 'required|string',
            'origen'        => 'required|string',
            'destino_vuelo' => 'nullable|string',
            'fecha_salida'  => 'required|date',
            'precio'        => 'required|numeric|min:0',
            'asientos'      => 'nullable|integer|min:0',
            'imagen'        => 'nullable|string',
            'id_destino'    => 'required|integer|exists:destinos,id',
        ]);

        $vuelo = Vuelo::create($data);

        return response()->json([
            'resultado' => true,
            'datos' => $vuelo,
            'mensaje' => 'Vuelo creado',
            'errores' => null
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'resultado' => true,
            'datos' => Vuelo::findOrFail($id),
            'mensaje' => 'Vuelo encontrado',
            'errores' => null
        ]);
    }

    public function update(Request $request, $id)
    {
        $vuelo = Vuelo::findOrFail($id);

        $data = $request->validate([
            'aerolinea'     => 'sometimes|string',
            'origen'        => 'sometimes|string',
            'destino_vuelo' => 'nullable|string',
            'fecha_salida'  => 'sometimes|date',
            'precio'        => 'sometimes|numeric|min:0',
            'asientos'      => 'nullable|integer|min:0',
            'imagen'        => 'nullable|string',
            'id_destino'    => 'sometimes|integer|exists:destinos,id',
        ]);

        $vuelo->update($data);

        return response()->json([
            'resultado' => true,
            'datos' => $vuelo,
            'mensaje' => 'Vuelo actualizado',
            'errores' => null
        ]);
    }

    public function destroy($id)
    {
        Vuelo::destroy($id);
        return response()->json([
            'resultado' => true,
            'mensaje' => 'Vuelo eliminado',
            'errores' => null
        ]);
    }
}