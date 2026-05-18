<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destino extends Model
{
    protected $fillable = [
        'nombre',
        'pais',
        'descripcion'
    ];

    public $timestamps = false;
}