<?php

namespace App\Models;

class BarterProductCategory extends Model
{
    protected $table = 'producto_trueque_categoria';

    protected $fillable = [
        'id_producto_trueque_categoria',
        'id_producto_trueque',
        'id_tipo_categoria'
    ];
    
}