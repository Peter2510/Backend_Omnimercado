<?php

namespace App\Models;

class Volunteering extends Model
{
    protected $table = 'voluntariado';
    protected $primaryKey = 'id_voluntariado';

    protected $fillable = [
        'id_voluntariado',
        'codigo_pago',
        'titulo',
        'retribucion_moneda_virtual',
        'descripcion',
        'lugar',
        'fecha',
        'hora',
        'maximo_voluntariados',
        'minimo_edad',
        'maximo_edad',
        'id_estado',
        'id_publicador',
        'fecha_publicacion',
        'descripcion_retribucion'
    ];



    function imageVolunteering() {
        return $this->hasMany(ImageVolunteering::class,'id_voluntariado','id_voluntariado');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'id_publicador', 'id_usuario');
    }

}
