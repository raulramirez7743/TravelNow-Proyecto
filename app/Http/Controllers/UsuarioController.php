<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            'nombre'   => 'required|string|max:100',
            'correo'   => 'required|email|max:100|unique:usuarios,correo',
            'password' => 'required|string|min:6',
            'telefono' => 'nullable|string|max:15',
            'rol'      => 'nullable|in:admin,staff',
        ]);

        // ✅ FIX: Hashear el password antes de guardar
        $data['password'] = Hash::make($data['password']);

        $usuario = Usuario::create($data);

        return response()->json([
            'resultado' => true,
            'datos' => $usuario,
            'mensaje' => 'Usuario creado',
            'errores' => null
        ], 201);
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

        $data = $request->validate([
            'nombre'   => 'sometimes|string|max:100',
            'correo'   => 'sometimes|email|max:100',
            'password' => 'sometimes|string|min:6',
            'telefono' => 'nullable|string|max:15',
            'rol'      => 'nullable|in:admin,staff',
        ]);

        // ✅ FIX: Hashear password si se envía en la actualización
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $usuario->update($data);

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