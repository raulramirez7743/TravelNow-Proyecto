<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    protected $table = 'reservaciones';
    protected $primaryKey = 'id_reservacion';
    public $timestamps = false;

    protected $fillable = ['fecha_inicio', 'fecha_fin', 'id_usuario', 'id_habitacion', 'id_vuelo'];
}