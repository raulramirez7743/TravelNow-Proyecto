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
            'fecha_inicio'   => 'required|date',
            'fecha_fin'      => 'required|date|after:fecha_inicio',
            'id_usuario'     => 'required|integer',
            'id_habitacion'  => 'required|integer',
            'id_vuelo'       => 'nullable|integer',
        ]);

        $reservacion = Reservacion::create($data);

        return response()->json([
            'resultado' => true,
            'datos' => $reservacion,
            'mensaje' => 'Reservación creada',
            'errores' => null
        ], 201);
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

        $data = $request->validate([
            'fecha_inicio'  => 'sometimes|date',
            'fecha_fin'     => 'sometimes|date',
            'id_usuario'    => 'sometimes|integer',
            'id_habitacion' => 'sometimes|integer',
            'id_vuelo'      => 'nullable|integer',
        ]);

        $r->update($data);

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