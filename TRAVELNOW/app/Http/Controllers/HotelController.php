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
            'nombre' => 'required',
            'estrellas' => 'required|numeric',
            'id_destino' => 'required|numeric'
        ]);

        $hotel = Hotel::create($data);

        return response()->json([
            'resultado' => true,
            'datos' => $hotel,
            'mensaje' => 'Hotel creado',
            'errores' => null
        ]);
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
        $hotel->update($request->all());

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