<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return response()->json([
            'resultado' => true,
            'datos' => Usuario::all(),
            'mensaje' => 'Lista de usuarios',
            'errores' => null
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required',
            'correo' => 'required',
            'telefono' => 'required'
        ]);

        $usuario = Usuario::create($data);

        return response()->json([
            'resultado' => true,
            'datos' => $usuario,
            'mensaje' => 'Usuario creado',
            'errores' => null
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'resultado' => true,
            'datos' => Usuario::findOrFail($id),
            'mensaje' => 'Usuario encontrado',
            'errores' => null
        ]);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $usuario->update($request->all());

        return response()->json([
            'resultado' => true,
            'datos' => $usuario,
            'mensaje' => 'Usuario actualizado',
            'errores' => null
        ]);
    }

    public function destroy($id)
    {
        Usuario::destroy($id);

        return response()->json([
            'resultado' => true,
            'mensaje' => 'Usuario eliminado'
        ]);
    }
}