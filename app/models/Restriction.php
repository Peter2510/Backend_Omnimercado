<?php

namespace App\Models;

class Restriction extends Model{
    protected $table = 'restriccion';
    protected $primaryKey = 'id_restriccion';

    protected $fillable = [
        'id_restriccion',
        'tipo',
        'cantidad'
    ];
    
}

