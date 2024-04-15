<?php

namespace App\Models;

class VoluntaryRegistration extends Model
{
    protected $table = 'registro_voluntariado';
    protected $primaryKey = 'id_registro_voluntariado';

    protected $fillable = [
        'id_registro_voluntariado',
        'id_voluntariado',
        'id_colaborador',
        'voluntario_asistio'
    ];

}
