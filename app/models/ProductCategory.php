<?php

namespace App\Models;

class ProductCategory extends Model
{
    protected $table = 'producto_categoria';
    protected $primaryKey = 'id_producto_categoria';

    protected $fillable = [
        'id_producto_categoria',
        'id_producto',
        'id_tipo_categoria'
    ];

    public function categoryType()
    {
        return $this->belongsTo(ProductCategoryType::class, 'id_tipo_categoria');
    }
    
}
