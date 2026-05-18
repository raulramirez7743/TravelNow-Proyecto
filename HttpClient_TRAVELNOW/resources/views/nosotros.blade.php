@extends('layouts.app')

@section('title', 'Nosotros')

@section('content')
<div class="bg-white py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center">
            <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Nuestra Historia</h2>
            <p class="mt-1 text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">
                Travel Now
            </p>
            <p class="max-w-xl mt-5 mx-auto text-xl text-gray-500">
                Somos una agencia apasionada por conectar a las personas con el mundo, creando experiencias inolvidables en cada viaje.
            </p>
        </div>

        <div class="mt-16 bg-gray-50 rounded-3xl overflow-hidden flex flex-col lg:flex-row shadow-xl">
            <div class="w-full lg:w-1/2 h-64 lg:h-auto">
                <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Viajeros">
            </div>
            <div class="w-full lg:w-1/2 p-8 lg:p-16 flex flex-col justify-center">
                <h3 class="text-3xl font-bold text-gray-900 mb-6">Nuestra Misión</h3>
                <p class="text-lg text-gray-600 mb-6">
                    En Travel Now creemos que viajar no debe ser un lujo inalcanzable, sino una experiencia transformadora accesible para todos. Por eso, trabajamos día a día para negociar las mejores tarifas y ofrecer paquetes excepcionales.
                </p>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-gray-700 font-medium">Más de 10 años de experiencia</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-gray-700 font-medium">Alianzas con más de 500 hoteles globales</span>
                    </div>
                    <div class="flex items-center">
                        <svg class="h-6 w-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-gray-700 font-medium">Soporte al viajero 24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
