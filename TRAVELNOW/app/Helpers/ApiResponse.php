<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success($data = null, $message = "OK")
    {
        return response()->json([
            "resultado" => true,
            "datos" => $data,
            "mensaje" => $message,
            "errores" => null
        ]);
    }

    public static function error($message = "Error", $errors = null)
    {
        return response()->json([
            "resultado" => false,
            "datos" => null,
            "mensaje" => $message,
            "errores" => $errors
        ], 400);
    }
}