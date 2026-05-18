@extends('layouts.app')

@section('title', 'Mis Pedidos')

@section('content')
<div class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar Menu -->
        <div class="w-full md:w-1/4">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 text-center border-b border-gray-100">
                    <div class="h-20 w-20 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto text-3xl font-bold mb-3">
                        {{ substr(session('user')['nombre'] ?? 'U', 0, 1) }}
                    </div>
                    <h3 class="font-bold text-gray-900">{{ session('user')['nombre'] ?? 'Usuario' }}</h3>
                </div>
                <nav class="p-2 space-y-1">
                    <a href="{{ route('perfil') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl font-medium transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Información Personal
                    </a>
                    <a href="{{ route('pedidos.index') }}" class="flex items-center px-4 py-3 text-blue-700 bg-blue-50 rounded-xl font-medium transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Mis Pedidos
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:w-3/4">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Historial de Pedidos</h2>

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

            @if(count($pedidos) > 0)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($pedidos as $pedido)
                        <li class="p-6 hover:bg-gray-50 transition-colors flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Pedido #{{ str_pad($pedido['id_pedido'], 5, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-sm text-gray-500 mt-1">Fecha: {{ \Carbon\Carbon::parse($pedido['created_at'])->format('d M Y') }}</p>
                                <div class="mt-2">
                                    @if($pedido['estado'] == 'pendiente')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pendiente de Pago</span>
                                    @elseif($pedido['estado'] == 'pagado')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Pagado</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelado</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right flex flex-col items-end gap-3">
                                <span class="text-xl font-bold text-gray-900">${{ number_format($pedido['total'], 2) }}</span>
                                <a href="{{ route('pedidos.show', $pedido['id_pedido']) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Ver Detalles &rarr;</a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-lg font-medium text-gray-900">No tienes pedidos</h3>
                    <p class="mt-1 text-gray-500">Comienza a explorar nuestros catálogos y haz tu primera reservación.</p>
                    <div class="mt-6">
                        <a href="{{ route('catalogo') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Explorar Catálogo</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
