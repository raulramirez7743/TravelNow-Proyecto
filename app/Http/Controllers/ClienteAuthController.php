<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ClienteAuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|string|email|max:100|unique:clientes',
            'password' => 'required|string|min:6',
        ]);

        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'telefono' => $request->telefono ?? null,
            'imagen' => $request->imagen ?? null,
        ]);

        $token = $cliente->createToken('cliente_token')->plainTextToken;

        return response()->json([
            'cliente' => $cliente,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $cliente = Cliente::where('correo', $request->correo)->first();

        if (!$cliente || !Hash::check($request->password, $cliente->password)) {
            throw ValidationException::withMessages([
                'correo' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $token = $cliente->createToken('cliente_token')->plainTextToken;

        return response()->json([
            'cliente' => $cliente,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensaje' => 'Sesión cerrada correctamente.'
        ]);
    }

    public function perfil(Request $request)
    {
        return response()->json($request->user());
    }

    public function updatePerfil(Request $request)
    {
        $cliente = $request->user();

        $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'correo' => 'sometimes|string|email|max:100|unique:clientes,correo,' . $cliente->id_cliente . ',id_cliente',
            'password' => 'sometimes|string|min:6',
            'telefono' => 'sometimes|string|max:15',
            'imagen' => 'sometimes|string',
        ]);

        if ($request->has('nombre')) $cliente->nombre = $request->nombre;
        if ($request->has('correo')) $cliente->correo = $request->correo;
        if ($request->has('telefono')) $cliente->telefono = $request->telefono;
        if ($request->has('imagen')) $cliente->imagen = $request->imagen;
        if ($request->has('password')) $cliente->password = Hash::make($request->password);

        $cliente->save();

        return response()->json([
            'mensaje' => 'Perfil actualizado correctamente.',
            'cliente' => $cliente
        ]);
    }
}
