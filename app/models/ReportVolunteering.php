<?php

namespace App\Models;

class ReportVolunteering extends Model
{
    protected $table = 'reporte_voluntariado';
    protected $primaryKey = 'id_reporte_voluntariado';

    protected $fillable = [
        'id_categoria_reporte',
        'id_voluntariado',
    ];
    
}
