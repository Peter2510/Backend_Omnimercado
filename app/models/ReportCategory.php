<?php

namespace App\Models;

class ReportCategory extends Model
{
    protected $table = 'categoria_reporte';
    protected $primaryKey = 'id_categoria_reporte';

    // Definición de atributos de la tabla
    protected $fillable = [
        'id_categoria_reporte',
        'nombre'
    ];
}
