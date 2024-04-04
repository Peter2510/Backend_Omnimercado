<?php

namespace App\Models;

class StateProduct extends Model
{
    
    protected $table = 'estado_producto';
    protected $primaryKey = 'id_estado_producto';
    protected $fillable = [
        'id_estado_producto',
        'nombre'
    ];
    
}
