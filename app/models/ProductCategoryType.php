<?php

namespace App\Models;

class ProductCategoryType extends Model
{
    protected $table = 'tipo_categoria_producto';
    protected $primaryKey = 'id_tipo_categoria';
    
    protected $fillable = [
        'id_tipo_categoria',
        'nombre'
    ];

    public function categories()
    {
        return $this->hasMany(ProductCategory::class, 'id_tipo_categoria');
    }
}
