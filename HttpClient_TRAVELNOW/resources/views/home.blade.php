@extends('layouts.app')

@section('title', 'Inicio')

@section('content')
<div class="relative bg-blue-900 overflow-hidden">
    <!-- Hero Image -->
    <div class="absolute inset-0">
        <img class="w-full h-full object-cover opacity-40 mix-blend-multiply" src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80" alt="Viaje">
    </div>
    
    <div class="relative max-w-7xl mx-auto py-24 px-4 sm:py-32 sm:px-6 lg:px-8 flex flex-col items-center text-center">
        <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl lg:text-6xl drop-shadow-md">
            Explora el mundo <span class="text-blue-400">hoy</span>
        </h1>
        <p class="mt-6 max-w-2xl text-xl text-gray-200">
            Descubre destinos increíbles, hoteles de lujo y vuelos a los mejores precios. Tu próxima aventura comienza aquí.
        </p>
        <div class="mt-10 flex gap-4">
            <a href="{{ route('catalogo') }}" class="bg-blue-600 border border-transparent rounded-lg py-3 px-8 text-base font-medium text-white hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/30">
                Ver Catálogo
            </a>
            <a href="{{ route('nosotros') }}" class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg py-3 px-8 text-base font-medium text-white hover:bg-white/20 transition-colors">
                Conócenos
            </a>
        </div>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-base font-semibold text-blue-600 tracking-wide uppercase">Servicios Premium</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Todo lo que necesitas para tu viaje
            </p>
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="pt-6">
                    <div class="flow-root bg-gray-50 rounded-2xl px-6 pb-8 shadow-sm hover:shadow-md transition-shadow">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-blue-600 rounded-xl shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Destinos Exóticos</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Explora lugares impresionantes alrededor de todo el mundo con guías especializados.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <div class="flow-root bg-gray-50 rounded-2xl px-6 pb-8 shadow-sm hover:shadow-md transition-shadow">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-blue-600 rounded-xl shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Hoteles de Lujo</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Descansa en los mejores alojamientos con servicios de clase mundial y vistas increíbles.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <div class="flow-root bg-gray-50 rounded-2xl px-6 pb-8 shadow-sm hover:shadow-md transition-shadow">
                        <div class="-mt-6">
                            <div>
                                <span class="inline-flex items-center justify-center p-3 bg-blue-600 rounded-xl shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </span>
                            </div>
                            <h3 class="mt-8 text-lg font-medium text-gray-900 tracking-tight">Vuelos Cómodos</h3>
                            <p class="mt-5 text-base text-gray-500">
                                Reserva tus boletos de avión con las mejores aerolíneas y en los horarios más convenientes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
