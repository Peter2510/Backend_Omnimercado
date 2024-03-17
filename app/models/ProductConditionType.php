<?php

namespace App\Models;

class ProductConditionType extends Model
{
    protected $table = 'tipo_condicion';
    
    protected $fillable = [
        'id_tipo_condicion',
        'nombre'
    ];
}
