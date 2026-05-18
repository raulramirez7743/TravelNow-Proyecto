<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return view('carrito.index', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'tipo' => 'required',
            'nombre' => 'required',
            'precio' => 'required|numeric',
            'imagen' => 'required',
            'cantidad' => 'required|integer|min:1',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date',
            'id_vuelo' => 'nullable|integer',
            'id_habitacion' => 'nullable|integer',
        ]);

        $cart = session()->get('cart', []);

        // Generamos un ID único para el producto en el carrito (por si es diferente tipo pero mismo ID en DB)
        $cartId = $request->tipo . '_' . $request->id;

        if (isset($cart[$cartId])) {
            $cart[$cartId]['cantidad'] += $request->cantidad;
        } else {
            $cart[$cartId] = [
                'id_producto' => $request->id,
                'tipo_producto' => $request->tipo,
                'nombre' => $request->nombre,
                'precio' => $request->precio,
                'imagen' => $request->imagen,
                'cantidad' => $request->cantidad,
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'id_vuelo' => $request->id_vuelo,
                'id_habitacion' => $request->id_habitacion,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('carrito.index')->with('success', 'Producto agregado al carrito exitosamente.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cartId' => 'required',
            'cantidad' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->cartId])) {
            $cart[$request->cartId]['cantidad'] = $request->cantidad;
            session()->put('cart', $cart);
            return redirect()->route('carrito.index')->with('success', 'Cantidad actualizada.');
        }

        return redirect()->route('carrito.index')->with('error', 'Producto no encontrado en el carrito.');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cartId' => 'required',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$request->cartId])) {
            unset($cart[$request->cartId]);
            session()->put('cart', $cart);
            return redirect()->route('carrito.index')->with('success', 'Producto eliminado del carrito.');
        }

        return redirect()->route('carrito.index')->with('error', 'Producto no encontrado en el carrito.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('carrito.index')->with('success', 'Carrito vaciado exitosamente.');
    }
}
