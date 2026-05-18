<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = 'reservacions';

    // La tabla reservacions SÍ tiene created_at/updated_at
    public $timestamps = true;

    protected $fillable = [
        'fecha_inicio',
        'fecha_fin',
        'id_usuario',
        'id_cliente',
        'id_habitacion',
        'id_vuelo'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'id_habitacion');
    }

    public function vuelo()
    {
        return $this->belongsTo(Vuelo::class, 'id_vuelo');
    }
}