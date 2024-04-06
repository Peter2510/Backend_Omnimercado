<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImageVolunteering extends Model
{
    use HasFactory;
    protected $table = 'voluntariado_imagen';
    
    protected $fillable = [
        'id_url_imagen',
        'id_voluntariado',
        'url_imagen'
    ];
    
    function volunteering() {
        
        return $this->hasMany(Volunteering::class,'id_voluntariado','id_voluntariado');
        
    }
}
