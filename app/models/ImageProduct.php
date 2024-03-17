<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageProduct extends Model
{
    use HasFactory;
    protected $table = 'producto_imagen';
    
    protected $fillable = [
        'id_url_imagen',
        'id_producto',
        'url_imagen '
    ];
    
    function products() {
        
        return $this->hasMany(Product::class,'id_producto');
        
    }
}
