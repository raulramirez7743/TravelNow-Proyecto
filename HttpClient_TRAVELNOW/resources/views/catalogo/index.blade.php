@extends('layouts.app')

@section('title', 'Catálogo de Productos')

@section('content')
<div class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-4xl font-extrabold text-gray-900 text-center mb-12">Catálogo de Viajes</h1>
        
        @if(session('error'))
            <div class="mb-8 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Destinos -->
        @if(count($destinos) > 0)
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Destinos Populares
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($destinos as $destino)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all overflow-hidden flex flex-col">
                    <img src="{{ $destino['imagen_principal'] ?? 'https://images.unsplash.com/photo-1503220317375-aaad61436b1b?w=500&q=80' }}" alt="{{ $destino['nombre'] ?? 'Destino' }}" class="w-full h-48 object-cover">
                    <div class="p-5 flex flex-col flex-grow">
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $destino['nombre'] ?? 'Destino sin nombre' }}</h3>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $destino['descripcion'] ?? 'Explora este maravilloso destino.' }}</p>
                        <div class="mt-auto flex items-center justify-between">
                            <span class="text-xl font-bold text-blue-600">${{ number_format($destino['precio'] ?? 0, 2) }}</span>
                            <a href="{{ route('catalogo.destino', $destino['id'] ?? $destino['id_destino'] ?? 0) }}" class="px-4 py-2 bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-800 rounded-lg text-sm font-medium transition-colors">Ver Detalles</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Hoteles -->
        @if(count($hoteles) > 0)
        <div class="mb-16">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                Hoteles de Lujo
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($hoteles as $hotel)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all overflow-hidden flex flex-col">
                    <img src="{{ $hotel['imagen_principal'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=500&q=80' }}" alt="{{ $hotel['nombre'] ?? 'Hotel' }}" class="w-full h-48 object-cover">
                    <div class="p-5 flex flex-col flex-grow">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900">{{ $hotel['nombre'] ?? 'Hotel sin nombre' }}</h3>
                            <div class="flex text-yellow-400">
                                @for($i = 0; $i < ($hotel['estrellas'] ?? 5); $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $hotel['descripcion'] ?? 'Hospedaje de primera clase.' }}</p>
                        <div class="mt-auto flex items-center justify-between">
                            <span class="text-xl font-bold text-blue-600">${{ number_format($hotel['precio'] ?? 0, 2) }}</span>
                            <a href="{{ route('catalogo.hotel', $hotel['id'] ?? $hotel['id_hotel'] ?? 0) }}" class="px-4 py-2 bg-gray-100 hover:bg-blue-600 hover:text-white text-gray-800 rounded-lg text-sm font-medium transition-colors">Ver Detalles</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Vuelos y Habitaciones (Similar) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Vuelos -->
            @if(count($vuelos) > 0)
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    Vuelos Destacados
                </h2>
                <div class="space-y-4">
                    @foreach(array_slice($vuelos, 0, 4) as $vuelo)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-between items-center hover:shadow-md transition-shadow">
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $vuelo['aerolinea'] ?? 'Aerolínea' }} - {{ $vuelo['codigo'] ?? 'V001' }}</h4>
                            <p class="text-sm text-gray-500">Origen a Destino</p>
                        </div>
                        <div class="text-right flex flex-col items-end">
                            <span class="font-bold text-blue-600 block">${{ number_format($vuelo['precio'] ?? 0, 2) }}</span>
                            <a href="{{ route('catalogo.vuelo', $vuelo['id'] ?? $vuelo['id_vuelo'] ?? 0) }}" class="text-xs text-blue-500 hover:text-blue-700 font-medium mt-1">Ver detalles &rarr;</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Habitaciones -->
            @if(count($habitaciones) > 0)
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Habitaciones
                </h2>
                <div class="space-y-4">
                    @foreach(array_slice($habitaciones, 0, 4) as $hab)
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex justify-between items-center hover:shadow-md transition-shadow">
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $hab['tipo'] ?? 'Estándar' }}</h4>
                            <p class="text-sm text-gray-500">Hotel ID: {{ $hab['id_hotel'] ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right flex flex-col items-end">
                            <span class="font-bold text-blue-600 block">${{ number_format($hab['precio'] ?? 0, 2) }}</span>
                            <a href="{{ route('catalogo.habitacion', $hab['id'] ?? $hab['id_habitacion'] ?? 0) }}" class="text-xs text-blue-500 hover:text-blue-700 font-medium mt-1">Ver detalles &rarr;</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        @if(count($destinos) == 0 && count($hoteles) == 0 && count($vuelos) == 0 && count($habitaciones) == 0)
        <div class="text-center py-20">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay productos disponibles</h3>
            <p class="mt-2 text-sm text-gray-500">La API no devolvió datos. Asegúrate de que el backend esté corriendo en el puerto 8000.</p>
        </div>
        @endif

    </div>
</div>
@endsection
