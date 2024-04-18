<?php

namespace App\Models;

class ReportBarter extends Model
{
    protected $table = 'reporte_producto_trueque';
    protected $primaryKey = 'id_reporte_producto_trueque';

    protected $fillable = [
        'id_categoria_reporte',
        'id_producto_trueque',
    ];
    
}
