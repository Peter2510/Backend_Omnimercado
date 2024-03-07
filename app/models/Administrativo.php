<?php

namespace App\Models;

class Administrativo extends Model{
    protected $table = 'administrativo';

    // Definición de atributos de la tabla
    protected $fillable = [
        'id_administrativo',
        'nombre',
        'correo',         
        'contrasenia',
        'rol',
        'url_imagen'
    ];
    
}

