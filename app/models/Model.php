<?php

namespace App\Models;

/**
 * Base Model
 * ---
 * The base model provides a space to set atrributes
 * that are common to all models
 */
class Model extends \Leaf\Model
{
    protected $hidden = ['created_at', 'updated_at','contrasenia'];
}
