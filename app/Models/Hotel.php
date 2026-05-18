<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hotels';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'estrellas',
        'imagen',
        'descripcion',
        'precio_noche',
        'id_destino',
    ];

    public function destino()
    {
        return $this->belongsTo(Destino::class, 'id_destino');
    }

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'id_hotel');
    }
}