<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destino;
use App\Models\Hotel;
use App\Models\Usuario;
use App\Models\Habitacion;
use App\Models\Vuelo;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR 10 DESTINOS
        $destinos = [
            ['nombre' => 'Cancún',         'pais' => 'México'],
            ['nombre' => 'París',           'pais' => 'Francia'],
            ['nombre' => 'Tokio',           'pais' => 'Japón'],
            ['nombre' => 'Roma',            'pais' => 'Italia'],
            ['nombre' => 'Nueva York',      'pais' => 'Estados Unidos'],
            ['nombre' => 'Londres',         'pais' => 'Reino Unido'],
            ['nombre' => 'Dubai',           'pais' => 'Emiratos Árabes Unidos'],
            ['nombre' => 'Sídney',          'pais' => 'Australia'],
            ['nombre' => 'Madrid',          'pais' => 'España'],
            ['nombre' => 'Río de Janeiro',  'pais' => 'Brasil'],
        ];

        foreach ($destinos as $destino) {
            Destino::create($destino);
        }

        // 2. CREAR 10 HOTELES
        $hotelesData = [
            ['nombre' => 'Resort Sol y Arena',  'estrellas' => 5],
            ['nombre' => 'Hilton Plaza',         'estrellas' => 4],
            ['nombre' => 'Holiday Inn Express',  'estrellas' => 3],
            ['nombre' => 'Ritz Palace',          'estrellas' => 5],
            ['nombre' => 'Hostal El Mochilero',  'estrellas' => 2],
            ['nombre' => 'Sheraton Grand',        'estrellas' => 4],
            ['nombre' => 'Four Seasons',          'estrellas' => 5],
            ['nombre' => 'Ibis Styles',           'estrellas' => 3],
            ['nombre' => 'Barceló Maya',          'estrellas' => 4],
            ['nombre' => 'Motel Ruta 66',         'estrellas' => 2],
        ];

        $hoteles = [];
        foreach ($hotelesData as $h) {
            $hoteles[] = Hotel::create($h);
        }

        // 3. CREAR HABITACIONES PARA CADA HOTEL (2 por hotel)
        $tiposHabitacion = [
            ['tipo' => 'Sencilla',  'precio' => 800.00],
            ['tipo' => 'Doble',     'precio' => 1400.00],
            ['tipo' => 'Suite',     'precio' => 2500.00],
        ];

        foreach ($hoteles as $hotel) {
            // 2-3 habitaciones por hotel
            $numHab = rand(2, 3);
            for ($i = 0; $i < $numHab; $i++) {
                $tipo = $tiposHabitacion[$i % count($tiposHabitacion)];
                Habitacion::create([
                    'id_hotel' => $hotel->id,
                    'tipo'     => $tipo['tipo'],
                    'precio'   => $tipo['precio'],
                ]);
            }
        }

        // 4. CREAR 10 USUARIOS
        for ($i = 1; $i <= 10; $i++) {
            Usuario::create([
                'nombre'   => "Viajero $i",
                'correo'   => "viajero$i@correo.com",
                'password' => Hash::make('password123'),
                'telefono' => "555123450$i"
            ]);
        }

        // 5. CREAR VUELOS DE EJEMPLO
        $vuelosData = [
            ['aerolinea' => 'Aeroméxico',  'origen' => 'Ciudad de México', 'destino_vuelo' => 'Cancún',    'precio' => 2500.00,  'fecha_salida' => '2026-06-01', 'asientos' => 150, 'id_destino' => 1],
            ['aerolinea' => 'Air France',  'origen' => 'Ciudad de México', 'destino_vuelo' => 'París',     'precio' => 18000.00, 'fecha_salida' => '2026-06-10', 'asientos' => 200, 'id_destino' => 2],
            ['aerolinea' => 'Japan Air',   'origen' => 'Guadalajara',      'destino_vuelo' => 'Tokio',     'precio' => 22000.00, 'fecha_salida' => '2026-07-01', 'asientos' => 180, 'id_destino' => 3],
            ['aerolinea' => 'Alitalia',    'origen' => 'Monterrey',        'destino_vuelo' => 'Roma',      'precio' => 15000.00, 'fecha_salida' => '2026-07-15', 'asientos' => 160, 'id_destino' => 4],
            ['aerolinea' => 'United Air',  'origen' => 'Ciudad de México', 'destino_vuelo' => 'Nueva York','precio' => 9500.00,  'fecha_salida' => '2026-08-01', 'asientos' => 220, 'id_destino' => 5],
        ];

        foreach ($vuelosData as $vuelo) {
            Vuelo::create($vuelo);
        }
    }
}