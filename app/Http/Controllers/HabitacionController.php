<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    public function index()
    {
        return response()->json([
            'resultado' => true,
            'datos' => Habitacion::all(),
            'mensaje' => 'Lista de habitaciones',
            'errores' => null
        ]);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'tipo'     => 'required|string|max:50',
                'precio'   => 'required|numeric|min:0',
                'id_hotel' => 'required|integer|exists:hotels,id',
            ]);

            $habitacion = Habitacion::create($data);

            return response()->json([
                'resultado' => true,
                'datos' => $habitacion,
                'mensaje' => 'Habitación creada',
                'errores' => null
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'resultado' => false,
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        return response()->json([
            'resultado' => true,
            'datos' => Habitacion::findOrFail($id),
            'mensaje' => 'Habitación encontrada',
            'errores' => null
        ]);
    }

    public function update(Request $request, $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        $data = $request->validate([
            'tipo'     => 'sometimes|string|max:50',
            'precio'   => 'sometimes|numeric|min:0',
            'id_hotel' => 'sometimes|integer|exists:hotels,id',
        ]);

        $habitacion->update($data);

        return response()->json([
            'resultado' => true,
            'datos' => $habitacion,
            'mensaje' => 'Habitación actualizada',
            'errores' => null
        ]);
    }

    public function destroy($id)
    {
        Habitacion::destroy($id);
        return response()->json([
            'resultado' => true,
            'mensaje' => 'Habitación eliminada',
            'errores' => null
        ]);
    }
}