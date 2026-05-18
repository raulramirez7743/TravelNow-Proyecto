@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8">
        
        <!-- Sidebar Menu -->
        <div class="w-full md:w-1/4">
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 text-center border-b border-gray-100">
                    <div class="h-20 w-20 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto text-3xl font-bold mb-3">
                        {{ substr($user['nombre'] ?? 'U', 0, 1) }}
                    </div>
                    <h3 class="font-bold text-gray-900">{{ $user['nombre'] ?? 'Usuario' }}</h3>
                    <p class="text-sm text-gray-500">{{ $user['correo'] ?? '' }}</p>
                </div>
                <nav class="p-2 space-y-1">
                    <a href="{{ route('perfil') }}" class="flex items-center px-4 py-3 text-blue-700 bg-blue-50 rounded-xl font-medium transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Información Personal
                    </a>
                    <a href="{{ route('pedidos.index') }}" class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl font-medium transition-colors">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Mis Pedidos
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full md:w-3/4">
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Actualizar Perfil</h2>

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

                <form action="{{ route('perfil.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <input id="nombre" name="nombre" type="text" value="{{ $user['nombre'] ?? '' }}" required class="mt-1 block w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50">
                        </div>
                        <div>
                            <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                            <input id="correo" name="correo" type="email" value="{{ $user['correo'] ?? '' }}" required class="mt-1 block w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50">
                        </div>
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                            <input id="telefono" name="telefono" type="text" value="{{ $user['telefono'] ?? '' }}" class="mt-1 block w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Nueva Contraseña (Opcional)</label>
                            <input id="password" name="password" type="password" minlength="6" class="mt-1 block w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50" placeholder="••••••••">
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 shadow-md shadow-blue-600/30 transition-all">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
