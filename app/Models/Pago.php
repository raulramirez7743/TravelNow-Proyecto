<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    public $timestamps = false;

    protected $fillable = [
        'monto',
        'metodo_pago',
        'id_reservacion'
    ];

    public function reservacion()
    {
        return $this->belongsTo(Reservacion::class, 'id_reservacion');
    }
}