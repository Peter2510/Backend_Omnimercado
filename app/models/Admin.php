<?php

namespace App\Models;

class Admin extends Model{
    protected $table = 'administrativo';

    // Definición de atributos de la tabla
    protected $fillable = [
        'id_administrativo',
        'nombre',
        'correo',         
        'contrasenia',
        'rol',
        'activo',
        'url_imagen',
        'genero'
    ];
    
}

