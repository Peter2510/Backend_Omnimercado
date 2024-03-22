<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageBarterProduct extends Model
{
    use HasFactory;
    protected $table = 'producto_trueque_imagen';
    
    protected $fillable = [
        'id_url_imagen',
        'id_producto_trueque',
        'url_imagen '
    ];
    
    function barterProducts() {
        
        return $this->hasMany(BarterProduct::class,'id_producto_trueque','id_producto_trueque');
        
    }
}
