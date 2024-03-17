<?php

namespace App\Models;

class ProductCategoryType extends Model
{
    protected $table = 'tipo_categoria_producto';
    
    protected $fillable = [
        'id_tipo_categoria',
        'nombre'
    ];
}
