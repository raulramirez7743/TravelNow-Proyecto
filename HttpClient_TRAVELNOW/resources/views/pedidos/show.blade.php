@extends('layouts.app')

@section('title', 'Detalle de Pedido')

@section('content')
<div class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('pedidos.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Mis Pedidos
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-3xl overflow-hidden">
            <!-- Encabezado del Pedido -->
            <div class="bg-gray-900 px-8 py-6 flex flex-col sm:flex-row justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">Pedido #{{ str_pad($pedido['id_pedido'], 5, '0', STR_PAD_LEFT) }}</h2>
                    <p class="text-gray-400 mt-1">Realizado el {{ \Carbon\Carbon::parse($pedido['created_at'])->format('d M Y, h:i A') }}</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    @if($pedido['estado'] == 'pendiente')
                        <span class="px-4 py-2 rounded-full text-sm font-bold bg-yellow-500 text-yellow-900">PENDIENTE DE PAGO</span>
                    @elseif($pedido['estado'] == 'pagado')
                        <span class="px-4 py-2 rounded-full text-sm font-bold bg-green-500 text-green-900">PAGADO</span>
                    @else
                        <span class="px-4 py-2 rounded-full text-sm font-bold bg-red-500 text-red-900">CANCELADO</span>
                    @endif
                </div>
            </div>

            <!-- Detalles de Productos -->
            <div class="p-8">
                <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2">Artículos Reservados</h3>
                <ul class="divide-y divide-gray-100">
                    @foreach($pedido['detalles'] as $detalle)
                    <li class="py-4 flex justify-between items-center">
                        <div>
                            <p class="font-bold text-gray-900 capitalize">{{ $detalle['tipo_producto'] }} (ID: {{ $detalle['id_producto'] }})</p>
                            <p class="text-sm text-gray-500">Cantidad: {{ $detalle['cantidad'] }} x ${{ number_format($detalle['precio_unitario'], 2) }}</p>
                        </div>
                        <p class="font-bold text-gray-800">${{ number_format($detalle['subtotal'], 2) }}</p>
                    </li>
                    @endforeach
                </ul>

                <div class="mt-8 border-t pt-6 flex justify-between items-center">
                    <p class="text-xl font-medium text-gray-500">Total</p>
                    <p class="text-3xl font-extrabold text-blue-600">${{ number_format($pedido['total'], 2) }}</p>
                </div>
            </div>

            <!-- Acciones -->
            <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                @if($pedido['estado'] == 'pendiente')
                    <form action="{{ route('pedidos.cancel', $pedido['id_pedido']) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('¿Estás seguro de que quieres cancelar este pedido?')" class="text-red-600 hover:text-red-800 font-medium transition-colors">
                            Cancelar Pedido
                        </button>
                    </form>
                    
                    <!-- Simulación Pasarela de Pago PayPal -->
                    <form action="{{ route('pedidos.pay', $pedido['id_pedido']) }}" method="POST" class="w-full sm:w-auto">
                        @csrf
                        <!-- Simulamos que recibimos un código de transacción de la API de PayPal -->
                        <input type="hidden" name="codigo_transaccion" value="PAY-{{ strtoupper(uniqid()) }}">
                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center px-8 py-3 border border-transparent rounded-xl shadow-md text-base font-bold text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Pagar con PayPal (Test)
                        </button>
                    </form>
                @elseif($pedido['estado'] == 'pagado')
                    <div class="w-full text-center sm:text-left">
                        <p class="text-sm text-gray-500">Código de Transacción:</p>
                        <p class="font-mono text-gray-900 font-medium">{{ $pedido['codigo_transaccion'] }}</p>
                    </div>
                    <button disabled class="px-8 py-3 bg-gray-300 text-gray-500 font-bold rounded-xl cursor-not-allowed">
                        Completado
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
