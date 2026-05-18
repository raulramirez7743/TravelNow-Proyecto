@extends('layouts.app')

@section('title', 'Carrito de Compras')

@section('content')
<div class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-extrabold text-gray-900 mb-8 flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Tu Carrito de Viaje
        </h1>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(count($cart) > 0)
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8">
            <div class="p-6 sm:p-10">
                <div class="flow-root">
                    <ul role="list" class="-my-6 divide-y divide-gray-200">
                        @foreach($cart as $id => $item)
                        <li class="py-6 flex flex-col sm:flex-row">
                            <div class="flex-shrink-0 w-full sm:w-32 h-32 overflow-hidden rounded-xl">
                                <img src="{{ $item['imagen'] }}" alt="{{ $item['nombre'] }}" class="w-full h-full object-center object-cover">
                            </div>

                            <div class="mt-4 sm:mt-0 sm:ml-6 flex-1 flex flex-col">
                                <div>
                                    <div class="flex justify-between text-base font-medium text-gray-900">
                                        <h3>{{ $item['nombre'] }}</h3>
                                        <p class="ml-4">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500 capitalize">{{ $item['tipo_producto'] }}</p>
                                    
                                    @if(isset($item['fecha_inicio']) && isset($item['fecha_fin']))
                                    <div class="mt-2 text-sm text-gray-500 bg-blue-50 p-3 rounded-lg border border-blue-100">
                                        <p><strong>Fechas:</strong> {{ \Carbon\Carbon::parse($item['fecha_inicio'])->format('d M Y') }} al {{ \Carbon\Carbon::parse($item['fecha_fin'])->format('d M Y') }}</p>
                                        @if($item['id_vuelo'])
                                            <p class="mt-1">✈️ Incluye Vuelo</p>
                                        @endif
                                        @if($item['id_habitacion'])
                                            <p class="mt-1">🛏️ Incluye Habitación</p>
                                        @endif
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 flex items-end justify-between text-sm mt-4">
                                    <form action="{{ route('carrito.update') }}" method="POST" class="flex items-center">
                                        @csrf
                                        <input type="hidden" name="cartId" value="{{ $id }}">
                                        <label for="cantidad-{{ $id }}" class="mr-3 text-gray-500">Cant.</label>
                                        <input type="number" id="cantidad-{{ $id }}" name="cantidad" value="{{ $item['cantidad'] }}" min="1" class="w-16 border border-gray-300 rounded-md px-2 py-1 text-center focus:ring-blue-500 focus:border-blue-500">
                                        <button type="submit" class="ml-2 text-blue-600 hover:text-blue-500 font-medium">Actualizar</button>
                                    </form>

                                    <form action="{{ route('carrito.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="cartId" value="{{ $id }}">
                                        <button type="submit" class="font-medium text-red-600 hover:text-red-500 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
            <div class="bg-gray-50 p-6 sm:p-10 border-t border-gray-200">
                <div class="flex justify-between text-xl font-bold text-gray-900 mb-6">
                    <p>Subtotal</p>
                    <p>${{ number_format($total, 2) }}</p>
                </div>
                <p class="mt-0.5 text-sm text-gray-500 mb-6">Los impuestos y gastos de envío se calculan en el checkout.</p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <form action="{{ route('carrito.clear') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-600 font-medium transition-colors">Vaciar Carrito</button>
                    </form>

                    <div class="flex gap-4">
                        <a href="{{ route('catalogo') }}" class="flex items-center justify-center px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Seguir Viendo
                        </a>
                        <!-- Checkout logic later -->
                        @if(session('token'))
                        <form action="/pedidos/checkout" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center justify-center px-8 py-3 border border-transparent rounded-xl shadow-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                Proceder al Pago
                            </button>
                        </form>
                        @else
                        <a href="/login?redirect=carrito" class="flex items-center justify-center px-8 py-3 border border-transparent rounded-xl shadow-md text-base font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                            Inicia Sesión para Comprar
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-24 bg-white rounded-3xl shadow-sm">
            <svg class="mx-auto h-24 w-24 text-gray-300 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Tu carrito está vacío</h2>
            <p class="text-gray-500 mb-8">Parece que aún no has agregado ninguna experiencia a tu carrito.</p>
            <a href="{{ route('catalogo') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-600/30 transition-all">
                Explorar Destinos
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
