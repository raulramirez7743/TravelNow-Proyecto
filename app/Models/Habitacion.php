<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $table = 'habitacions';  // ← nombre correcto de la tabla en MySQL

    // La migración original NO tiene timestamps
    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'precio',
        'id_hotel'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }
}