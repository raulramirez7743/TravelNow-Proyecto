<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hoteles'; 
    protected $primaryKey = 'id_hotel';
    public $timestamps = false;

    protected $fillable = ['nombre', 'estrellas', 'id_destino'];

    public function destino() {
        return $this->belongsTo(Destino::class, 'id_destino');
    }
}