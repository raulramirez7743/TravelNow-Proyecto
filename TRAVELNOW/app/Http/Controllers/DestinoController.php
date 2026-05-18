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
        return response()->json(Destino::create($request->all()), 201);
    }

    public function show($id)
    {
        return response()->json(Destino::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $dato = Destino::findOrFail($id);
        $dato->update($request->all());
        return response()->json($dato);
    }

    public function destroy($id)
    {
        Destino::destroy($id);
        return response()->json(['mensaje' => 'Eliminado']);
    }
}