<?php

namespace App\Models;

class Sale extends Model
{
    protected $table = 'venta';
    protected $primaryKey = 'id_venta';

    protected $fillable = [
        'id_venta',
        'id_producto',
        'id_comprador',
        'fecha_venta',
    ];
}
