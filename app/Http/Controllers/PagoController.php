<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        return response()->json([
            'resultado' => true,
            'datos' => Pago::all(),
            'mensaje' => 'Lista de pagos',
            'errores' => null
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'monto'          => 'required|numeric|min:0',
            'metodo_pago'    => 'required|string|max:50',
            'id_reservacion' => 'required|integer|exists:reservacions,id',
        ]);

        $pago = Pago::create($data);

        return response()->json([
            'resultado' => true,
            'datos' => $pago,
            'mensaje' => 'Pago registrado',
            'errores' => null
        ], 201);
    }

    public function show($id)
    {
        return response()->json([
            'resultado' => true,
            'datos' => Pago::findOrFail($id),
            'mensaje' => 'Pago encontrado',
            'errores' => null
        ]);
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $data = $request->validate([
            'monto'          => 'sometimes|numeric|min:0',
            'metodo_pago'    => 'sometimes|string|max:50',
            'id_reservacion' => 'sometimes|integer|exists:reservacions,id',
        ]);

        $pago->update($data);

        return response()->json([
            'resultado' => true,
            'datos' => $pago,
            'mensaje' => 'Pago actualizado',
            'errores' => null
        ]);
    }

    public function destroy($id)
    {
        Pago::destroy($id);
        return response()->json([
            'resultado' => true,
            'mensaje' => 'Pago eliminado',
            'errores' => null
        ]);
    }
}