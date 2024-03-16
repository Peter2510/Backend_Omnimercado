<?php

namespace App\Models;

class Category extends Model
{
    protected $table = 'tipo_categoria_producto';

    // Definición de atributos de la tabla
    protected $fillable = [
        'id_tipo_categoria',
        'nombre'
    ];
}
