<?php

namespace App\Models;

class Product extends Model
{
    protected $table = 'producto';
    protected $fillable = [
        'id_producto',
        'titulo',
        'precio_moneda_virtual',
        'descripcion',
        'id_estado_producto',
        'fecha_publicacion',
        'tipo_condicion',
        'id_publicador'
    ];

    function imageProducts() {
        return $this->hasMany(ImageProduct::class,'id_producto','id_producto');
    }

   
}
