<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CatalogoController extends Controller
{
    // ✅ CORREGIDO: Lee desde env('TRAVELNOW_API_URL') — funciona en local y producción
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = rtrim(env('TRAVELNOW_API_URL', 'http://127.0.0.1:8000/api'), '/');
    }

    public function index()
    {
        try {
            $destinos = Http::get("{$this->apiUrl}/destinos")->json();
            $hoteles_res = Http::get("{$this->apiUrl}/hoteles")->json();
            $vuelos_res = Http::get("{$this->apiUrl}/vuelos")->json();
            $habitaciones_res = Http::get("{$this->apiUrl}/habitaciones")->json();

            $hoteles = isset($hoteles_res['datos']) ? $hoteles_res['datos'] : (is_array($hoteles_res) ? $hoteles_res : []);
            $vuelos = isset($vuelos_res['datos']) ? $vuelos_res['datos'] : (is_array($vuelos_res) ? $vuelos_res : []);
            $habitaciones = isset($habitaciones_res['datos']) ? $habitaciones_res['datos'] : (is_array($habitaciones_res) ? $habitaciones_res : []);
            $destinos = isset($destinos['datos']) ? $destinos['datos'] : (is_array($destinos) ? $destinos : []);

            $hoteles = $this->assignImages($hoteles, 'hotel');
            $vuelos = $this->assignImages($vuelos, 'vuelo');
            $habitaciones = $this->assignImages($habitaciones, 'habitacion');
            $destinos = $this->assignImages($destinos, 'destino');

        } catch (\Exception $e) {
            $destinos = [];
            $hoteles = [];
            $vuelos = [];
            $habitaciones = [];
        }

        return view('catalogo.index', compact('destinos', 'hoteles', 'vuelos', 'habitaciones'));
    }

    public function showDestino($id)
    {
        try {
            $producto = Http::get("{$this->apiUrl}/destinos/{$id}")->json();
            $producto = $this->assignImages($producto, 'destino');
            
            // Trip builder data
            $hoteles_res = Http::get("{$this->apiUrl}/hoteles")->json();
            $vuelos_res = Http::get("{$this->apiUrl}/vuelos")->json();
            $habitaciones_res = Http::get("{$this->apiUrl}/habitaciones")->json();

            $hoteles_all = isset($hoteles_res['datos']) ? $hoteles_res['datos'] : (is_array($hoteles_res) ? $hoteles_res : []);
            $vuelos_all = isset($vuelos_res['datos']) ? $vuelos_res['datos'] : (is_array($vuelos_res) ? $vuelos_res : []);
            $habitaciones_all = isset($habitaciones_res['datos']) ? $habitaciones_res['datos'] : (is_array($habitaciones_res) ? $habitaciones_res : []);

            // Filter
            $hoteles = collect($hoteles_all)->where('id_destino', $id)->values()->all();
            $vuelos = collect($vuelos_all)->where('id_destino', $id)->values()->all();
            
            $hotel_ids = collect($hoteles)->pluck('id')->toArray();
            $habitaciones = collect($habitaciones_all)->whereIn('id_hotel', $hotel_ids)->values()->all();

            return view('catalogo.show', [
                'producto' => $producto, 
                'tipo' => 'destino',
                'hoteles' => $hoteles,
                'vuelos' => $vuelos,
                'habitaciones' => $habitaciones
            ]);
        } catch (\Exception $e) {
            return redirect()->route('catalogo')->with('error', 'Producto no encontrado');
        }
    }

    public function showHotel($id)
    {
        try {
            $producto = Http::get("{$this->apiUrl}/hoteles/{$id}")->json();
            $producto = $this->assignImages($producto, 'hotel');
            return view('catalogo.show', ['producto' => $producto, 'tipo' => 'hotel']);
        } catch (\Exception $e) {
            return redirect()->route('catalogo')->with('error', 'Producto no encontrado');
        }
    }

    public function showVuelo($id)
    {
        try {
            $producto = Http::get("{$this->apiUrl}/vuelos/{$id}")->json();
            $producto = $this->assignImages($producto, 'vuelo');
            return view('catalogo.show', ['producto' => $producto, 'tipo' => 'vuelo']);
        } catch (\Exception $e) {
            return redirect()->route('catalogo')->with('error', 'Producto no encontrado');
        }
    }

    public function showHabitacion($id)
    {
        try {
            $producto = Http::get("{$this->apiUrl}/habitaciones/{$id}")->json();
            $producto = $this->assignImages($producto, 'habitacion');
            return view('catalogo.show', ['producto' => $producto, 'tipo' => 'habitacion']);
        } catch (\Exception $e) {
            return redirect()->route('catalogo')->with('error', 'Producto no encontrado');
        }
    }

    private function assignImages($data, $tipo)
    {
        if (empty($data)) return $data;

        // Imágenes dinámicas y súper confiables basadas en palabras clave (nunca fallan)
        $images = [
            'cancun' => [
                'https://loremflickr.com/800/600/cancun,beach/all',
                'https://loremflickr.com/800/600/cancun,hotel/all',
                'https://loremflickr.com/800/600/cancun,ocean/all',
                'https://loremflickr.com/800/600/cancun,resort/all'
            ],
            'vancouve' => [
                'https://loremflickr.com/800/600/vancouver,city/all',
                'https://loremflickr.com/800/600/vancouver,nature/all',
                'https://loremflickr.com/800/600/vancouver,bridge/all',
                'https://loremflickr.com/800/600/vancouver,skyline/all'
            ],
            'tokio' => [
                'https://loremflickr.com/800/600/tokyo,city/all',
                'https://loremflickr.com/800/600/tokyo,neon/all',
                'https://loremflickr.com/800/600/tokyo,japan/all',
                'https://loremflickr.com/800/600/tokyo,night/all'
            ],
            'buenos aires' => [
                'https://loremflickr.com/800/600/buenosaires,city/all',
                'https://loremflickr.com/800/600/buenosaires,argentina/all',
                'https://loremflickr.com/800/600/buenosaires,street/all',
                'https://loremflickr.com/800/600/buenosaires,building/all'
            ],
            'madrid' => [
                'https://loremflickr.com/800/600/madrid,city/all',
                'https://loremflickr.com/800/600/madrid,spain/all',
                'https://loremflickr.com/800/600/madrid,plaza/all',
                'https://loremflickr.com/800/600/madrid,street/all'
            ],
            'secrets' => [
                'https://loremflickr.com/800/600/hotel,luxury/all',
                'https://loremflickr.com/800/600/hotel,pool/all',
                'https://loremflickr.com/800/600/hotel,room/all',
                'https://loremflickr.com/800/600/hotel,resort/all'
            ],
            'fairmont' => [
                'https://loremflickr.com/800/600/hotel,architecture/all',
                'https://loremflickr.com/800/600/hotel,lobby/all',
                'https://loremflickr.com/800/600/hotel,bed/all',
                'https://loremflickr.com/800/600/hotel,lounge/all'
            ],
            'shinjuku' => [
                'https://loremflickr.com/800/600/tokyo,hotel/all',
                'https://loremflickr.com/800/600/japan,room/all',
                'https://loremflickr.com/800/600/tokyo,bed/all',
                'https://loremflickr.com/800/600/tokyo,view/all'
            ],
            'alvear' => [
                'https://loremflickr.com/800/600/hotel,classic/all',
                'https://loremflickr.com/800/600/hotel,elegant/all',
                'https://loremflickr.com/800/600/hotel,suite/all',
                'https://loremflickr.com/800/600/hotel,dining/all'
            ],
            'only you' => [
                'https://loremflickr.com/800/600/hotel,boutique/all',
                'https://loremflickr.com/800/600/hotel,modern/all',
                'https://loremflickr.com/800/600/hotel,chic/all',
                'https://loremflickr.com/800/600/hotel,interior/all'
            ],
            'hotel_default' => [
                'https://loremflickr.com/800/600/hotel/all',
                'https://loremflickr.com/800/600/resort/all',
                'https://loremflickr.com/800/600/bedroom/all',
                'https://loremflickr.com/800/600/lobby/all'
            ],
            'vuelo' => [
                'https://loremflickr.com/800/600/airplane/all',
                'https://loremflickr.com/800/600/flight/all',
                'https://loremflickr.com/800/600/airport/all',
                'https://loremflickr.com/800/600/aviation/all'
            ],
            'habitacion' => [
                'https://loremflickr.com/800/600/bedroom/all',
                'https://loremflickr.com/800/600/bed/all',
                'https://loremflickr.com/800/600/hotel,room/all',
                'https://loremflickr.com/800/600/suite/all'
            ]
        ];

        $isList = isset($data[0]) || empty($data);
        $items = $isList ? $data : [$data];

        $items = array_map(function($item) use ($images, $tipo) {
            $key = 'hotel_default';
            if ($tipo === 'vuelo') $key = 'vuelo';
            else if ($tipo === 'habitacion') $key = 'habitacion';
            else {
                $nombre = strtolower($item['nombre'] ?? $item['aerolinea'] ?? '');
                foreach ($images as $k => $imgs) {
                    if (str_contains($nombre, $k)) {
                        $key = $k;
                        break;
                    }
                }
            }

            $sel = $images[$key] ?? $images['hotel_default'];
            $item['imagen_principal'] = $sel[0];
            $item['imagen_1'] = $sel[1];
            $item['imagen_2'] = $sel[2];
            $item['imagen_3'] = $sel[3];
            return $item;
        }, $items);

        return $isList ? $items : $items[0];
    }
}
