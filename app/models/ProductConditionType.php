<?php

namespace App\Models;

class ProductConditionType extends Model
{
    protected $table = 'tipo_condicion';
    
    protected $hidden = ['created_at', 'updated_at'];
    
    protected $fillable = [
        'id_tipo_condicion',
        'nombre'
    ];
}
