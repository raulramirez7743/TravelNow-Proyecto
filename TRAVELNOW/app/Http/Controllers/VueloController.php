<?php

namespace App\Http\Controllers;

use App\Models\Vuelo;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class VueloController extends Controller
{
    public function index()
    {
        return ApiResponse::success(Vuelo::all(), "Lista de vuelos");
    }

    public function store(Request $request)
    {
        $request->validate([
            "aerolinea" => "required",
            "origen" => "required",
            "fecha_salida" => "required|date",
            "precio" => "required",
            "id_destino" => "required|exists:destinos,id"
        ]);

        $vuelo = Vuelo::create($request->all());

        return ApiResponse::success($vuelo, "Vuelo creado");
    }

    public function show($id)
    {
        $vuelo = Vuelo::findOrFail($id);
        return ApiResponse::success($vuelo, "Vuelo encontrado");
    }

    public function update(Request $request, $id)
    {
        $vuelo = Vuelo::findOrFail($id);
        $vuelo->update($request->all());

        return ApiResponse::success($vuelo, "Vuelo actualizado");
    }

    public function destroy($id)
    {
        Vuelo::destroy($id);
        return ApiResponse::success(null, "Vuelo eliminado");
    }
}