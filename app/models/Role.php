<?php

namespace App\Models;

class Role extends Model{
    protected $table = 'rol';

    protected $fillable = [
        'id_rol',
        'nombre'
    ];
    
}

