<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

    protected $table = 'detalle_pedidos';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_pedido',
        'tipo_producto',
        'id_producto',
        'cantidad',
        'precio_unitario',
        'subtotal',
        'fecha_inicio',
        'fecha_fin',
        'id_vuelo',
        'id_habitacion',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }
}
