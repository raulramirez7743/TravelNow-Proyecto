<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vuelo extends Model
{
    protected $table = 'vuelos';
    protected $primaryKey = 'id_vuelo';
    public $timestamps = false;

    protected $fillable = ['aerolinea', 'origen', 'fecha_salida', 'precio', 'id_destino'];
}