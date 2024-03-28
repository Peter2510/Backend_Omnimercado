<?php

namespace App\Models;

class Gender extends Model{
    
    protected $table = 'genero';
    protected $primaryKey = 'id_genero';

    protected $fillable = [
        'id_genero',
        'nombre'
    ];
    
}

