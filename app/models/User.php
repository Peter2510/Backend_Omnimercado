<?php

namespace App\Models;

class User extends Model{
    protected $table = 'usuario';

    // Definición de atributos de la tabla
    protected $fillable = [
        'id_usuario',
        'nombre',                         
        'correo',
        'fecha_nacimiento',
        'contrasenia',
        'moneda_local_gastada',
        'moneda_local_ganada',
        'cantidad_moneda_virtual',
        'moneda_virtual_ganada',
        'moneda_virtual_gastada',
        'cantidad_publicaciones_productos',
        'cantidad_publicaciones_voluntariados',
        'promedio_valoracion',
        'activo_publicar',
        'activo_plataforma', 
        'url_imagen',
        'genero'
    ];
    
}

