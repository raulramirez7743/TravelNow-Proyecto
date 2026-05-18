<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destino;
use App\Models\Hotel;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. CREAR 10 DESTINOS (Con país)
        $destinos = [
            ['nombre' => 'Cancún', 'pais' => 'México'],
            ['nombre' => 'París', 'pais' => 'Francia'],
            ['nombre' => 'Tokio', 'pais' => 'Japón'],
            ['nombre' => 'Roma', 'pais' => 'Italia'],
            ['nombre' => 'Nueva York', 'pais' => 'Estados Unidos'],
            ['nombre' => 'Londres', 'pais' => 'Reino Unido'],
            ['nombre' => 'Dubai', 'pais' => 'Emiratos Árabes Unidos'],
            ['nombre' => 'Sídney', 'pais' => 'Australia'],
            ['nombre' => 'Madrid', 'pais' => 'España'],
            ['nombre' => 'Río de Janeiro', 'pais' => 'Brasil'],
        ];

        foreach ($destinos as $destino) {
            Destino::create($destino);
        }

        // 2. CREAR 10 HOTELES (Con estrellas)
        $hoteles = [
            ['nombre' => 'Resort Sol y Arena', 'estrellas' => 5],
            ['nombre' => 'Hilton Plaza', 'estrellas' => 4],
            ['nombre' => 'Holiday Inn Express', 'estrellas' => 3],
            ['nombre' => 'Ritz Palace', 'estrellas' => 5],
            ['nombre' => 'Hostal El Mochilero', 'estrellas' => 2],
            ['nombre' => 'Sheraton Grand', 'estrellas' => 4],
            ['nombre' => 'Four Seasons', 'estrellas' => 5],
            ['nombre' => 'Ibis Styles', 'estrellas' => 3],
            ['nombre' => 'Barceló Maya', 'estrellas' => 4],
            ['nombre' => 'Motel Ruta 66', 'estrellas' => 2],
        ];

        foreach ($hoteles as $hotel) {
            Hotel::create($hotel);
        }

        // 3. CREAR 10 USUARIOS
        for ($i = 1; $i <= 10; $i++) {
            Usuario::create([
                'nombre' => "Viajero $i",
                'correo' => "viajero$i@correo.com",
                'password' => Hash::make('password123'),
                'telefono' => "555123450$i"
            ]);
        }
    }
}