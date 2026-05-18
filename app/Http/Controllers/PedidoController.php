<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    // --- MÉTODOS PARA ADMIN ---
    public function indexAdmin()
    {
        $pedidos = Pedido::with('cliente', 'detalles')->get();
        return response()->json($pedidos);
    }

    public function showAdmin($id)
    {
        $pedido = Pedido::with('cliente', 'detalles')->findOrFail($id);
        return response()->json($pedido);
    }

    public function cancelAdmin($id)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id);
        if ($pedido->estado == 'cancelado') {
            return response()->json(['mensaje' => 'El pedido ya estaba cancelado'], 400);
        }
        $pedido->estado = 'cancelado';
        $pedido->save();

        // Devolver asientos al inventario
        foreach ($pedido->detalles as $detalle) {
            if (!empty($detalle->id_vuelo)) {
                $vuelo = \App\Models\Vuelo::find($detalle->id_vuelo);
                if ($vuelo) {
                    $vuelo->asientos += $detalle->cantidad;
                    $vuelo->save();
                }
            }
        }

        return response()->json(['mensaje' => 'Pedido cancelado y asientos liberados', 'pedido' => $pedido]);
    }

    public function historialClienteAdmin($clienteId)
    {
        $pedidos = Pedido::with('detalles')->where('id_cliente', $clienteId)->get();
        return response()->json($pedidos);
    }

    // ✅ NUEVO: Admin puede cambiar el estado de un pedido manualmente
    public function updateStatusAdmin(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,pagado,cancelado',
            'codigo_transaccion' => 'nullable|string',
        ]);

        $pedido = Pedido::findOrFail($id);
        $pedido->estado = $request->estado;

        if ($request->filled('codigo_transaccion')) {
            $pedido->codigo_transaccion = $request->codigo_transaccion;
        }

        $pedido->save();

        return response()->json([
            'mensaje' => 'Estado del pedido actualizado a: ' . $request->estado,
            'pedido'  => $pedido->load('detalles', 'cliente'),
        ]);
    }

    // --- MÉTODOS PARA CLIENTE (Requiere Token Sanctum) ---
    public function indexCliente(Request $request)
    {
        $pedidos = Pedido::with('detalles')->where('id_cliente', $request->user()->id_cliente)->get();
        return response()->json($pedidos);
    }

    public function showCliente(Request $request, $id)
    {
        $pedido = Pedido::with('detalles')->where('id_cliente', $request->user()->id_cliente)->findOrFail($id);
        return response()->json($pedido);
    }

    public function storeCliente(Request $request)
    {
        $request->validate([
            'detalles' => 'required|array',
            'detalles.*.tipo_producto' => 'required|string',
            'detalles.*.id_producto' => 'required|integer',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.fecha_inicio' => 'nullable|date',
            'detalles.*.fecha_fin' => 'nullable|date',
            'detalles.*.id_vuelo' => 'nullable|integer',
            'detalles.*.id_habitacion' => 'nullable|integer',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;
            $detallesParaInsertar = [];

            foreach ($request->detalles as $detalle) {
                // Verificar disponibilidad de asientos antes de cobrar
                if (!empty($detalle['id_vuelo'])) {
                    $vuelo = \App\Models\Vuelo::find($detalle['id_vuelo']);
                    if (!$vuelo || $vuelo->asientos < $detalle['cantidad']) {
                        throw new \Exception("No hay suficientes asientos disponibles para el vuelo seleccionado.");
                    }
                }

                $subtotal = $detalle['cantidad'] * $detalle['precio_unitario'];
                $total += $subtotal;
                
                $detallesParaInsertar[] = [
                    'tipo_producto' => $detalle['tipo_producto'],
                    'id_producto'   => $detalle['id_producto'],
                    'cantidad'      => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'subtotal'      => $subtotal,
                    'fecha_inicio'  => $detalle['fecha_inicio'] ?? null,
                    'fecha_fin'     => $detalle['fecha_fin'] ?? null,
                    'id_vuelo'      => $detalle['id_vuelo'] ?? null,
                    'id_habitacion' => $detalle['id_habitacion'] ?? null,
                ];
            }

            $pedido = Pedido::create([
                'id_cliente' => $request->user()->id_cliente,
                'total'      => $total,
                'estado'     => 'pendiente',
            ]);

            foreach ($detallesParaInsertar as $det) {
                $det['id_pedido'] = $pedido->id_pedido;
                DetallePedido::create($det);

                // Descontar asientos del inventario
                if (!empty($det['id_vuelo'])) {
                    $vuelo = \App\Models\Vuelo::find($det['id_vuelo']);
                    if ($vuelo) {
                        $vuelo->asientos -= $det['cantidad'];
                        $vuelo->save();
                    }
                }
            }

            DB::commit();

            return response()->json(['mensaje' => 'Pedido creado', 'pedido' => $pedido->load('detalles')], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Error al crear pedido', 'error' => $e->getMessage()], 500);
        }
    }

    public function cancelCliente(Request $request, $id)
    {
        $pedido = Pedido::with('detalles')->where('id_cliente', $request->user()->id_cliente)->findOrFail($id);
        if ($pedido->estado == 'cancelado') {
            return response()->json(['mensaje' => 'El pedido ya estaba cancelado'], 400);
        }
        $pedido->estado = 'cancelado';
        $pedido->save();
        
        // Devolver asientos al inventario
        foreach ($pedido->detalles as $detalle) {
            if (!empty($detalle->id_vuelo)) {
                $vuelo = \App\Models\Vuelo::find($detalle->id_vuelo);
                if ($vuelo) {
                    $vuelo->asientos += $detalle->cantidad;
                    $vuelo->save();
                }
            }
        }
        
        return response()->json(['mensaje' => 'Pedido cancelado y asientos liberados', 'pedido' => $pedido]);
    }

    // --- MÉTODOS DE PAGO ---
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'codigo_transaccion' => 'required|string'
        ]);

        $pedido = Pedido::with('detalles')->where('id_cliente', $request->user()->id_cliente)->findOrFail($id);
        
        if ($pedido->estado == 'pagado') {
            return response()->json(['mensaje' => 'El pedido ya estaba pagado'], 400);
        }

        DB::beginTransaction();
        try {
            $pedido->estado = 'pagado';
            $pedido->codigo_transaccion = $request->codigo_transaccion;
            $pedido->save();

            // Si es un "viaje", creamos una Reservación
            foreach ($pedido->detalles as $detalle) {
                if ($detalle->tipo_producto === 'viaje') {
                    // Verificar que los IDs referenciados EXISTEN en sus tablas
                    // Si no existen (datos huérfanos), usamos null para no romper la FK
                    $idHabitacion = null;
                    if (!empty($detalle->id_habitacion)) {
                        $habitacionExiste = \App\Models\Habitacion::find($detalle->id_habitacion);
                        $idHabitacion = $habitacionExiste ? $detalle->id_habitacion : null;
                    }

                    $idVuelo = null;
                    if (!empty($detalle->id_vuelo)) {
                        $vueloExiste = \App\Models\Vuelo::find($detalle->id_vuelo);
                        $idVuelo = $vueloExiste ? $detalle->id_vuelo : null;
                    }

                    $reservacion = \App\Models\Reservacion::create([
                        'fecha_inicio'  => $detalle->fecha_inicio ?? now()->toDateString(),
                        'fecha_fin'     => $detalle->fecha_fin    ?? now()->addDays(5)->toDateString(),
                        'id_cliente'    => $pedido->id_cliente,
                        'id_usuario'    => null,
                        'id_habitacion' => $idHabitacion,
                        'id_vuelo'      => $idVuelo,
                    ]);

                    // Crear el Pago asociado a la Reservación (en el panel de admin)
                    \App\Models\Pago::create([
                        'id_reservacion' => $reservacion->id,
                        'monto'          => $detalle->subtotal,
                        'metodo_pago'    => 'Tarjeta de Crédito / Transacción: ' . $request->codigo_transaccion,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['mensaje' => 'Pago actualizado y reservación creada', 'pedido' => $pedido]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error en updatePaymentStatus', [
                'pedido_id'  => $id,
                'mensaje'    => $e->getMessage(),
                'linea'      => $e->getLine(),
                'archivo'    => $e->getFile(),
                'trace'      => $e->getTraceAsString(),
            ]);
            return response()->json([
                'mensaje' => 'Error al procesar pago',
                'error'   => $e->getMessage(),
                'linea'   => $e->getLine(),
            ], 500);
        }
    }
}
