<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function index()
    {
        return response()->json(Pago::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'monto' => 'required|numeric',
            'metodo_pago' => 'required',
            'id_reservacion' => 'required|exists:reservacions,id'
        ]);

        return response()->json(Pago::create($data), 201);
    }

    public function show($id)
    {
        return response()->json(Pago::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $dato = Pago::findOrFail($id);
        $dato->update($request->all());
        return response()->json($dato);
    }

    public function destroy($id)
    {
        Pago::destroy($id);
        return response()->json(['mensaje' => 'Eliminado']);
    }
}