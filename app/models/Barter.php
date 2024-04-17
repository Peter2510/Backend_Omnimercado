<?php

namespace App\Models;

class Barter extends Model
{
    protected $table = 'trueque';
    protected $primaryKey = 'id_trueque';

    // Definición de atributos de la tabla
    protected $fillable = [
        'id_trueque',
        'id_producto_trueque',
        'id_comprador',
        'fecha_trueque',
    ];
}
