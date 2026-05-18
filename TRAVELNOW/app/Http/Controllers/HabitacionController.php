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
            'tipo' => 'required',
            'precio' => 'required|numeric',
            'id_hotel' => 'required|numeric'
        ]);

        $habitacion = Habitacion::create($data);

        return response()->json([
            'resultado' => true,
            'datos' => $habitacion,
            'mensaje' => 'Habitación creada',
            'errores' => null
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'resultado' => false,
            'mensaje' => $e->getMessage()
        ], 500);
    }
}
}