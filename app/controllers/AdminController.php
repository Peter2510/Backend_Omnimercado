<?php

namespace App\Controllers;
use App\Models\Admin;

class AdminController extends Controller{
    
    function createAdmin() {
        
        try {
            $admin = new Admin;
            $admin->nombre = app()->request()->get('name');
            $admin->correo = app()->request()->get('email');
            $admin->contrasenia = password_hash(app()->request()->get('password'), PASSWORD_DEFAULT);
            $admin->url_imagen = 'admin.png';
            $admin->rol = app()->request()->get('role');
            $admin->activo =app()->request()->get('active');

            $admin->save();
        
            return response()->json(['status' => 'success', 'message' => 'Administrador creado exitosamente'], 200);

        } catch (\Exception $e) {
            
            return response()->json(['status' => 'error', 'message' => 'Error al crear el administrador'], 500);

        }
        
    }


}