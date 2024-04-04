<?php

namespace App\Models;

class Product extends Model
{
    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    protected $fillable = [
        'id_producto',
        'titulo',
        'precio_moneda_virtual',
        'descripcion',
        'id_estado_producto',
        'fecha_publicacion',
        'tipo_condicion',
        'id_publicador'
    ];

    function imageProducts() {
        return $this->hasMany(ImageProduct::class,'id_producto','id_producto');
    }

    public function StateProduct()
    {
        return $this->belongsTo(StateProduct::class, 'id_estado_producto', 'id_estado_producto');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'id_publicador', 'id_usuario');
    }

    public function ProductCategory()
    {
        return $this->hasMany(ProductCategory::class, 'id_producto', 'id_producto');
    }
   
}
