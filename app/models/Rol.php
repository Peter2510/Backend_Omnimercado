<?php

namespace App\Models;

class Rol extends Model{
    protected $table = 'rol';

    // Definición de atributos de la tabla
    protected $fillable = [
        'id_rol',
        'nombre'
    ];
    
}

