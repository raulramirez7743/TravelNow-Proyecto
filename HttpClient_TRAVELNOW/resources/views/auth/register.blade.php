@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-xl">
        <div>
            <h2 class="mt-4 text-center text-3xl font-extrabold text-gray-900">
                Únete a Travel Now
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 transition-colors">Inicia sesión</a>
            </p>
        </div>

        @if(session('error'))
            <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                    <input id="nombre" name="nombre" type="text" value="{{ old('nombre') }}" required class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1" placeholder="Juan Pérez">
                    @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input id="correo" name="correo" type="email" value="{{ old('correo') }}" required class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1" placeholder="ejemplo@correo.com">
                    @error('correo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input id="password" name="password" type="password" required minlength="6" class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1" placeholder="••••••••">
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @else <p class="text-xs text-gray-500 mt-1">Mínimo 6 caracteres.</p> @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="6" class="appearance-none relative block w-full px-4 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-xl focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm mt-1" placeholder="••••••••">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg shadow-blue-600/30 transition-all">
                    Crear Cuenta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
