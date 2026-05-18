<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vuelo extends Model
{
    protected $table = 'vuelos';
    public $timestamps = false;

    protected $fillable = [
        'aerolinea',
        'origen',
        'destino_vuelo',
        'fecha_salida',
        'precio',
        'asientos',
        'imagen',
        'id_destino'
    ];

    public function destino()
    {
        return $this->belongsTo(Destino::class, 'id_destino');
    }
}