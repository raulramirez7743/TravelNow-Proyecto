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

        // Imágenes premium fijas de Unsplash (100% profesionales y correspondientes al destino)
        $images = [
            'cancun' => [
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1544735716-392fe2489ffa?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1506929562872-bb421503ef21?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&auto=format&fit=crop'
            ],
            'paris' => [
                'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1499856871958-5b9627545d1a?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1509060464153-44667396260f?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1473442240418-452f03b7ae40?w=800&auto=format&fit=crop'
            ],
            'tokio' => [
                'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1490761902188-5803d2983733?w=800&auto=format&fit=crop'
            ],
            'tokyo' => [
                'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1503899036084-c55cdd92da26?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1493976040374-85c8e12f0c0e?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1490761902188-5803d2983733?w=800&auto=format&fit=crop'
            ],
            'roma' => [
                'https://images.unsplash.com/photo-1552832230-c0197dd311b5?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1515542622106-78bda8ba0e5b?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1529154036614-a60975f5c760?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1531572753322-ad063cecc140?w=800&auto=format&fit=crop'
            ],
            'rome' => [
                'https://images.unsplash.com/photo-1552832230-c0197dd311b5?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1515542622106-78bda8ba0e5b?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1529154036614-a60975f5c760?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1531572753322-ad063cecc140?w=800&auto=format&fit=crop'
            ],
            'nueva york' => [
                'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1485738422979-f5c462d49f74?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1492666673288-3c4b4576ad9a?w=800&auto=format&fit=crop'
            ],
            'new york' => [
                'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1485738422979-f5c462d49f74?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1492666673288-3c4b4576ad9a?w=800&auto=format&fit=crop'
            ],
            'londres' => [
                'https://images.unsplash.com/photo-1513635269975-59663e0ca1ad?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1529655683826-aba9b3e21f83?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1505761671935-60b3a7427bad?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1512470876302-972faa2aa9a4?w=800&auto=format&fit=crop'
            ],
            'london' => [
                'https://images.unsplash.com/photo-1513635269975-59663e0ca1ad?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1529655683826-aba9b3e21f83?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1505761671935-60b3a7427bad?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1512470876302-972faa2aa9a4?w=800&auto=format&fit=crop'
            ],
            'dubai' => [
                'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1546412414-8035e1776c9a?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1582672060674-bc2bd808a8b5?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1528702748617-c64d49f918af?w=800&auto=format&fit=crop'
            ],
            'sidney' => [
                'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1528072164453-f4e8ef0d475a?w=800&auto=format&fit=crop'
            ],
            'sydney' => [
                'https://images.unsplash.com/photo-1506973035872-a4ec16b8e8d9?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1524413840807-0c3cb6fa808d?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1528072164453-f4e8ef0d475a?w=800&auto=format&fit=crop'
            ],
            'madrid' => [
                'https://images.unsplash.com/photo-1539650116574-8efeb43e2750?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1543783207-ec64e4d95325?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1490642914619-7955a3fd483c?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1512423377749-e587d55fdb6d?w=800&auto=format&fit=crop'
            ],
            'rio de janeiro' => [
                'https://images.unsplash.com/photo-1483729558449-99ef09a8c325?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1594916689886-508b7ad2f416?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1518638150341-db700a8a650d?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1579607831086-63eeb11ad383?w=800&auto=format&fit=crop'
            ],
            // HOTELES CATEGORÍAS (Resorts de playa)
            'resort' => [
                'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=800&auto=format&fit=crop'
            ],
            'sol y arena' => [
                'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=800&auto=format&fit=crop'
            ],
            'barcelo' => [
                'https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1540541338287-41700207dee6?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1584132967334-10e028bd69f7?w=800&auto=format&fit=crop'
            ],
            // HOTELES CATEGORÍAS (Lujo citadino)
            'hilton' => [
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&auto=format&fit=crop'
            ],
            'palace' => [
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&auto=format&fit=crop'
            ],
            'four seasons' => [
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&auto=format&fit=crop'
            ],
            'ritz' => [
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&auto=format&fit=crop'
            ],
            'sheraton' => [
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1564507592333-c60657eea523?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&auto=format&fit=crop'
            ],
            // HOTELES CATEGORÍAS (Standard / Hostal / Motel)
            'holiday inn' => [
                'https://images.unsplash.com/photo-1506059612708-99d6c258160e?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1517840901100-8179e982acb7?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1549294413-26f195afcbce?w=800&auto=format&fit=crop'
            ],
            'ibis' => [
                'https://images.unsplash.com/photo-1506059612708-99d6c258160e?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1517840901100-8179e982acb7?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1549294413-26f195afcbce?w=800&auto=format&fit=crop'
            ],
            'hostal' => [
                'https://images.unsplash.com/photo-1506059612708-99d6c258160e?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1517840901100-8179e982acb7?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1549294413-26f195afcbce?w=800&auto=format&fit=crop'
            ],
            'mochilero' => [
                'https://images.unsplash.com/photo-1506059612708-99d6c258160e?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1517840901100-8179e982acb7?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1549294413-26f195afcbce?w=800&auto=format&fit=crop'
            ],
            'motel' => [
                'https://images.unsplash.com/photo-1506059612708-99d6c258160e?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1517840901100-8179e982acb7?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1549294413-26f195afcbce?w=800&auto=format&fit=crop'
            ],
            'hotel_default' => [
                'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1520250497591-112f2f40a3f4?w=800&auto=format&fit=crop'
            ],
            'vuelo' => [
                'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1483450388369-9ed95738483c?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1464037862646-647f185c0b44?w=800&auto=format&fit=crop'
            ],
            'habitacion' => [
                'https://images.unsplash.com/photo-1618773928121-c32242e63f39?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1591088398332-8a7791972843?w=800&auto=format&fit=crop'
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
                // Sanitizar acentos para la comparación de palabras clave
                $nombreSinAcentos = str_replace(
                    ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ'],
                    ['a', 'e', 'i', 'o', 'u', 'u', 'n'],
                    $nombre
                );

                foreach ($images as $k => $imgs) {
                    if (str_contains($nombreSinAcentos, $k)) {
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
