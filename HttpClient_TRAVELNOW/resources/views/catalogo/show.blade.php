@extends('layouts.app')

@section('title', 'Detalle de ' . ucfirst($tipo))

@section('content')
<div class="bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <a href="{{ route('catalogo') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium mb-6 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Catálogo
        </a>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col lg:flex-row">
            <!-- Images Gallery -->
            <div class="w-full lg:w-1/2 p-6 bg-gray-100 flex flex-col gap-4">
                <div class="h-80 md:h-96 w-full rounded-2xl overflow-hidden shadow-sm">
                    <img src="{{ $producto['imagen_principal'] ?? 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&q=80' }}" alt="Principal" class="w-full h-full object-cover">
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="h-24 md:h-32 rounded-xl overflow-hidden shadow-sm">
                        <img src="{{ $producto['imagen_1'] ?? 'https://images.unsplash.com/photo-1503220317375-aaad61436b1b?w=300&q=80' }}" alt="Img 1" class="w-full h-full object-cover">
                    </div>
                    <div class="h-24 md:h-32 rounded-xl overflow-hidden shadow-sm">
                        <img src="{{ $producto['imagen_2'] ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300&q=80' }}" alt="Img 2" class="w-full h-full object-cover">
                    </div>
                    <div class="h-24 md:h-32 rounded-xl overflow-hidden shadow-sm">
                        <img src="{{ $producto['imagen_3'] ?? 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=300&q=80' }}" alt="Img 3" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>

            <!-- Product Details -->
            <div class="w-full lg:w-1/2 p-8 lg:p-12 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 uppercase tracking-wide">
                        {{ ucfirst($tipo) }}
                    </span>
                    @php $stock = $producto['existencia'] ?? $producto['asientos'] ?? null; @endphp
                    @if(isset($stock))
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $stock > 0 ? $stock . ' Disponibles' : 'Agotado' }}
                    </span>
                    @endif
                </div>

                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-4">
                    {{ $producto['nombre'] ?? $producto['tipo'] ?? $producto['aerolinea'] ?? 'Producto' }}
                </h1>
                
                @if(isset($producto['estrellas']))
                <div class="flex text-yellow-400 mb-4">
                    @for($i = 0; $i < $producto['estrellas']; $i++)
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                @endif

                <p class="text-lg text-gray-600 mb-8 flex-grow">
                    {{ $producto['descripcion'] ?? 'Este es un increíble ' . $tipo . ' disponible ahora mismo en Travel Now. ¡No pierdas la oportunidad de reservar!' }}
                </p>

                <div class="border-t border-gray-100 pt-8 mt-auto">
                    @if($tipo === 'destino')
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Precio Total Estimado</p>
                                <p class="text-4xl font-extrabold text-gray-900" id="precio-total">$0.00</p>
                            </div>
                        </div>

                        <!-- Trip Builder -->
                        <div class="space-y-4 mb-6">
                            <div>
                                <label for="vuelo_select" class="block text-sm font-medium text-gray-700 mb-1">Vuelo (Opcional)</label>
                                <select id="vuelo_select" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-blue-500 focus:border-blue-500" onchange="calcularTotal()">
                                    <option value="0" data-precio="0">Sin Vuelo</option>
                                    @if(isset($vuelos))
                                        @foreach($vuelos as $v)
                                            <option value="{{ $v['id'] ?? $v['id_vuelo'] }}" data-precio="{{ $v['precio'] }}" data-nombre="Vuelo {{ $v['aerolinea'] }} a {{ $v['destino_vuelo'] ?? $producto['nombre'] }}">
                                                {{ $v['aerolinea'] }} - {{ $v['origen'] }} a {{ $v['destino_vuelo'] ?? $producto['nombre'] }} (${{ number_format($v['precio'], 2) }}) - ¡Quedan {{ $v['asientos'] ?? 0 }} asientos!
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            
                            <div>
                                <label for="hab_select" class="block text-sm font-medium text-gray-700 mb-1">Habitación (Opcional)</label>
                                <select id="hab_select" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-blue-500 focus:border-blue-500" onchange="calcularTotal()">
                                    <option value="0" data-precio="0">Sin Habitación</option>
                                    @if(isset($habitaciones))
                                        @foreach($habitaciones as $h)
                                            @php
                                                // Encontrar el nombre del hotel
                                                $hotelName = 'Hotel';
                                                if (isset($hoteles)) {
                                                    $htl = collect($hoteles)->firstWhere('id', $h['id_hotel']);
                                                    if ($htl) $hotelName = $htl['nombre'];
                                                }
                                            @endphp
                                            <option value="{{ $h['id'] ?? $h['id_habitacion'] }}" data-precio="{{ $h['precio'] }}" data-nombre="Habitación {{ $h['tipo'] }} en {{ $hotelName }}">
                                                {{ $hotelName }} - {{ $h['tipo'] }} (${{ number_format($h['precio'], 2) }} por noche)
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="flex gap-4">
                                <div class="w-1/2">
                                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                                    <input type="date" id="fecha_inicio" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-blue-500 focus:border-blue-500" onchange="calcularTotal()">
                                </div>
                                <div class="w-1/2">
                                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin</label>
                                    <input type="date" id="fecha_fin" class="w-full border border-gray-300 rounded-xl px-4 py-3 bg-white focus:ring-blue-500 focus:border-blue-500" onchange="calcularTotal()">
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <div class="w-1/3">
                                <label for="cantidad" class="sr-only">Cantidad</label>
                                <select id="cantidad" class="w-full h-14 border border-gray-300 rounded-xl px-4 font-medium text-gray-700 bg-white focus:ring-blue-500 focus:border-blue-500" onchange="calcularTotal()">
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'persona' : 'personas' }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <button type="button" onclick="agregarViajeCompleto()" class="w-2/3 h-14 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-600/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Agregar Viaje al Carrito
                            </button>
                        </div>

                        <!-- Formularios ocultos para agregar al carrito -->
                        <form id="form_add_cart" action="/carrito/add" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="tipo" id="hc_tipo">
                            <input type="hidden" name="id" id="hc_id">
                            <input type="hidden" name="nombre" id="hc_nombre">
                            <input type="hidden" name="precio" id="hc_precio">
                            <input type="hidden" name="cantidad" id="hc_cantidad">
                            <input type="hidden" name="fecha_inicio" id="hc_fecha_inicio">
                            <input type="hidden" name="fecha_fin" id="hc_fecha_fin">
                            <input type="hidden" name="id_vuelo" id="hc_id_vuelo">
                            <input type="hidden" name="id_habitacion" id="hc_id_habitacion">
                            <input type="hidden" name="imagen" value="{{ $producto['imagen_principal'] ?? 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&q=80' }}">
                        </form>

                        <script>
                            // Set default dates
                            const today = new Date();
                            const nextWeek = new Date();
                            nextWeek.setDate(today.getDate() + 5);
                            
                            document.getElementById('fecha_inicio').value = today.toISOString().split('T')[0];
                            document.getElementById('fecha_fin').value = nextWeek.toISOString().split('T')[0];

                            function calcularTotal() {
                                const vueloSel = document.getElementById('vuelo_select');
                                const habSel = document.getElementById('hab_select');
                                const cant = parseInt(document.getElementById('cantidad').value) || 1;
                                
                                const pVuelo = parseFloat(vueloSel.options[vueloSel.selectedIndex].dataset.precio) || 0;
                                const pHab = parseFloat(habSel.options[habSel.selectedIndex].dataset.precio) || 0;
                                
                                // Calculate nights
                                const d1 = new Date(document.getElementById('fecha_inicio').value);
                                const d2 = new Date(document.getElementById('fecha_fin').value);
                                let nights = Math.max(1, Math.ceil((d2 - d1) / (1000 * 60 * 60 * 24)));
                                
                                // Habitacion is per night, vuelo is per person
                                const total = (pVuelo * cant) + (pHab * nights); 
                                
                                document.getElementById('precio-total').innerText = '$' + total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                return total;
                            }
                            
                            async function agregarViajeCompleto() {
                                const vueloSel = document.getElementById('vuelo_select');
                                const habSel = document.getElementById('hab_select');
                                const cant = document.getElementById('cantidad').value;
                                const fi = document.getElementById('fecha_inicio').value;
                                const ff = document.getElementById('fecha_fin').value;
                                
                                const vueloId = vueloSel.value;
                                const habId = habSel.value;
                                
                                if (vueloId == "0" && habId == "0") {
                                    alert("Por favor selecciona un vuelo o una habitación para armar tu viaje.");
                                    return;
                                }

                                if (!fi || !ff || new Date(fi) >= new Date(ff)) {
                                    alert("Por favor selecciona fechas válidas para tu viaje.");
                                    return;
                                }

                                const addForm = document.getElementById('form_add_cart');
                                const total = calcularTotal();
                                
                                // Send as a single 'viaje' object
                                const formData = new FormData(addForm);
                                formData.set('tipo', 'viaje');
                                formData.set('id', "{{ $producto['id'] ?? $producto['id_destino'] }}");
                                formData.set('nombre', "Viaje a {{ $producto['nombre'] }}");
                                formData.set('precio', total);
                                formData.set('cantidad', 1); // El total ya incluye cantidad de personas y noches
                                formData.set('fecha_inicio', fi);
                                formData.set('fecha_fin', ff);
                                formData.set('id_vuelo', vueloId != "0" ? vueloId : '');
                                formData.set('id_habitacion', habId != "0" ? habId : '');
                                
                                try {
                                    await fetch(addForm.action, {
                                        method: 'POST',
                                        body: formData,
                                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                    });
                                    
                                    // Redirigir al carrito
                                    window.location.href = "/carrito";
                                } catch (e) {
                                    alert("Error al agregar al carrito");
                                }
                            }
                            
                            // Init calculation
                            setTimeout(calcularTotal, 100);
                        </script>
                    @else
                        <!-- Standard Single Item Form -->
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Precio Final</p>
                                <p class="text-4xl font-extrabold text-gray-900">${{ number_format($producto['precio'] ?? 0, 2) }}</p>
                            </div>
                        </div>

                        <!-- Add to Cart Form -->
                        <form action="/carrito/add" method="POST" class="flex gap-4">
                            @csrf
                            <input type="hidden" name="tipo" value="{{ $tipo }}">
                            <input type="hidden" name="id" value="{{ $producto['id'] ?? $producto['id_'.$tipo] ?? 0 }}">
                            <input type="hidden" name="nombre" value="{{ $producto['nombre'] ?? $producto['tipo'] ?? $producto['aerolinea'] ?? 'Producto' }}">
                            <input type="hidden" name="precio" value="{{ $producto['precio'] ?? 0 }}">
                            <input type="hidden" name="imagen" value="{{ $producto['imagen_principal'] ?? 'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=800&q=80' }}">
                            
                            <div class="w-1/3">
                                <label for="cantidad" class="sr-only">Cantidad</label>
                                <select id="cantidad" name="cantidad" class="w-full h-14 border border-gray-300 rounded-xl px-4 font-medium text-gray-700 bg-white focus:ring-blue-500 focus:border-blue-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <option value="{{ $i }}">{{ $i }} {{ $i == 1 ? 'persona' : 'personas' }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <button type="submit" class="w-2/3 h-14 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg shadow-blue-600/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Agregar al Carrito
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
