<?php

namespace App\Http\Controllers;

use App\Models\Reservacion;
use Illuminate\Http\Request;

class ReservacionController extends Controller
{
    public function index()
    {
        return response()->json([
            'resultado' => true,
            'datos' => Reservacion::all(),
            'mensaje' => 'Lista de reservaciones',
            'errores' => null
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'fecha_inicio' => 'required',
            'fecha_fin' => 'required',
            'id_usuario' => 'required|numeric',
            'id_habitacion' => 'required|numeric',
            'id_vuelo' => 'required|numeric'
        ]);

        return response()->json([
            'resultado' => true,
            'datos' => Reservacion::create($data),
            'mensaje' => 'Reservación creada',
            'errores' => null
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'resultado' => true,
            'datos' => Reservacion::findOrFail($id),
            'mensaje' => 'Detalle reservación',
            'errores' => null
        ]);
    }

    public function update(Request $request, $id)
    {
        $r = Reservacion::findOrFail($id);
        $r->update($request->all());

        return response()->json([
            'resultado' => true,
            'datos' => $r,
            'mensaje' => 'Actualizada',
            'errores' => null
        ]);
    }

    public function destroy($id)
    {
        Reservacion::destroy($id);

        return response()->json([
            'resultado' => true,
            'mensaje' => 'Eliminada'
        ]);
    }
}