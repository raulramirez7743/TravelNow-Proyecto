<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use Illuminate\Http\Request;

class DestinoController extends Controller
{
    public function index()
    {
        return response()->json(Destino::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:100',
            'pais'        => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $destino = Destino::create($data);
        return response()->json($destino, 201);
    }

    public function show($id)
    {
        return response()->json(Destino::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nombre'      => 'sometimes|string|max:100',
            'pais'        => 'sometimes|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $destino = Destino::findOrFail($id);
        $destino->update($data);
        return response()->json($destino);
    }

    public function destroy($id)
    {
        Destino::destroy($id);
        return response()->json(['mensaje' => 'Eliminado']);
    }
}