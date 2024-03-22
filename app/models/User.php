<?php

namespace App\Models;

class User extends Model{
    protected $table = 'usuario';

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
        'promedio_valoracion',
        'activo_publicar',
        'activo_plataforma', 
        'url_imagen',
        'genero'
    ];
    
}

