<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Now - @yield('title', 'La mejor agencia de viajes')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Header & Nav -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 flex items-center gap-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Travel Now
                    </a>
                </div>
                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Inicio</a>
                    <a href="{{ route('catalogo') }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Catálogo</a>
                    <a href="{{ route('nosotros') }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Nosotros</a>
                    <a href="{{ route('contacto') }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Contacto</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <!-- Cart Icon -->
                    <a href="/carrito" class="relative text-gray-600 hover:text-blue-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full h-5 w-5 flex items-center justify-center text-xs font-bold">{{ count(session('cart')) }}</span>
                        @endif
                    </a>
                    <!-- User Menu -->
                    @if(session('token'))
                        <a href="/perfil" class="text-gray-600 hover:text-blue-600 font-medium">Mi Perfil</a>
                        <a href="/logout" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">Salir</a>
                    @else
                        <a href="/login" class="text-blue-600 hover:text-blue-800 font-medium">Ingresar</a>
                        <a href="/registro" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">Regístrate</a>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Travel Now
                </h3>
                <p class="text-gray-400">Tu pasaporte a experiencias inolvidables. Descubre el mundo con nosotros, al mejor precio y con el mejor servicio.</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Enlaces Rápidos</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-blue-400 transition-colors">Inicio</a></li>
                    <li><a href="{{ route('catalogo') }}" class="hover:text-blue-400 transition-colors">Catálogo</a></li>
                    <li><a href="{{ route('nosotros') }}" class="hover:text-blue-400 transition-colors">Nosotros</a></li>
                    <li><a href="{{ route('contacto') }}" class="hover:text-blue-400 transition-colors">Contacto</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Contacto</h4>
                <ul class="space-y-2 text-gray-400">
                    <li>📍 Calle Viajes 123, Ciudad Turística</li>
                    <li>📞 +52 555 123 4567</li>
                    <li>✉️ info@travelnow.com</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} Travel Now. Todos los derechos reservados.
        </div>
    </footer>

</body>
</html>
