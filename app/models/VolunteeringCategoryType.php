<?php

namespace App\Models;

class VolunteeringCategoryType extends Model
{
    protected $table = 'tipo_categoria_voluntariado';
    protected $primaryKey = 'id_tipo_categoria';
    
    protected $fillable = [
        'id_tipo_categoria',
        'nombre'
    ];

    public function categories()
    {
        return $this->hasMany(VolunteeringCategory::class, 'id_tipo_categoria');
    }
}
