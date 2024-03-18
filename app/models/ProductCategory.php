<?php

namespace App\Models;

class ProductCategory extends Model
{
    protected $table = 'producto_categoria';

    protected $fillable = [
        'id_producto_categoria',
        'id_producto',
        'id_tipo_categoria'
    ];
    
}
