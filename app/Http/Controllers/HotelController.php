<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        return response()->json([
            'resultado' => true,
            'datos' => Hotel::all(),
            'mensaje' => 'Lista de hoteles',
            'errores' => null
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'       => 'required|string|max:100',
            'estrellas'    => 'required|integer|min:1|max:5',
            'id_destino'   => 'required|integer|exists:destinos,id',
            'imagen'       => 'nullable|string',
            'descripcion'  => 'nullable|string',
            'precio_noche' => 'nullable|numeric|min:0',
        ]);

        $hotel = Hotel::create($data);

        return response()->json([
            'resultado' => true,
            'datos' => $hotel,
            'mensaje' => 'Hotel creado',
            'errores' => null
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'resultado' => true,
            'datos' => Hotel::findOrFail($id),
            'mensaje' => 'Hotel encontrado',
            'errores' => null
        ]);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::findOrFail($id);

        $data = $request->validate([
            'nombre'       => 'sometimes|string|max:100',
            'estrellas'    => 'sometimes|integer|min:1|max:5',
            'id_destino'   => 'sometimes|integer|exists:destinos,id',
            'imagen'       => 'nullable|string',
            'descripcion'  => 'nullable|string',
            'precio_noche' => 'nullable|numeric|min:0',
        ]);

        $hotel->update($data);

        return response()->json([
            'resultado' => true,
            'datos' => $hotel,
            'mensaje' => 'Hotel actualizado',
            'errores' => null
        ]);
    }

    public function destroy($id)
    {
        Hotel::destroy($id);

        return response()->json([
            'resultado' => true,
            'mensaje' => 'Hotel eliminado',
            'errores' => null
        ]);
    }
}