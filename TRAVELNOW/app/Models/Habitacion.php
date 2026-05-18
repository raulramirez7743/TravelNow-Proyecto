<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $table = 'habitaciones';
    protected $primaryKey = 'id_habitacion';
    public $timestamps = false;

    protected $fillable = ['tipo', 'precio', 'id_hotel'];

    public function hotel() {
        return $this->belongsTo(Hotel::class, 'id_hotel');
    }
}