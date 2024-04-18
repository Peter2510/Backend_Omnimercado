<?php

namespace App\Models;

class ReportProduct extends Model
{
    protected $table = 'reporte_producto';
    protected $primaryKey = 'id_reporte_producto';

    protected $fillable = [
        'id_categoria_reporte',
        'id_producto',
    ];
    
}
