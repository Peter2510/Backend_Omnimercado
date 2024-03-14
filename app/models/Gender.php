<?php

namespace App\Models;

class Gender extends Model{
    protected $table = 'genero';

    // Definición de atributos de la tabla
    protected $fillable = [
        'id_genero',
        'nombre'
    ];
    
}

