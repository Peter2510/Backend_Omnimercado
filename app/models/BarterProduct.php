<?php

namespace App\Models;

class BarterProduct extends Model
{
    protected $table = 'producto_trueque';
    protected $fillable = [
        'id_producto_trueque',
        'titulo',
        'equivalente_moneda_local',
        'equivalente_moneda_virtual',
        'descripcion_producto',
        'id_estado',
        'fecha_publicacion',
        'id_condicion',
        'descripcion_solicitud',
        'id_publicador'
    ];
    
    function imageBarterProducts() {
        return $this->hasMany(ImageBarterProduct::class,'id_producto_trueque','id_producto_trueque');
    }

}
