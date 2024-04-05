<?php

namespace App\Models;

class VolunteeringCategory extends Model
{
    protected $table = 'voluntariado_categoria';
    protected $primaryKey = 'id_voluntariado_categoria';

    protected $fillable = [
        'id_voluntariado_categoria',
        'id_voluntariado',
        'id_tipo_categoria'
    ];

    public function categoryType()
    {
        return $this->belongsTo(ProductCategoryType::class, 'id_tipo_categoria');
    }
}
