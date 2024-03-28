<?php

namespace App\Controllers;
use App\Models\Admin;
use App\Models\Role;

class AdminController extends Controller{
    
    function getRoles(){
        
        try {
            $roles = Role::all();          
        
            return response()->json(['status' => 'success', 'roles' => $roles], 200);

        } catch (\Exception $e) {
            
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los roles'], 500);

        }

    }
    
    
    function createAdmin() {
        
        try {
            $admin = new Admin;
            $admin->nombre = app()->request()->get('name');
            $admin->correo = app()->request()->get('email');
            $admin->contrasenia = password_hash(app()->request()->get('password'), PASSWORD_DEFAULT);
            $admin->rol = app()->request()->get('rol');
            $admin->activo = _env('ACTIVE_ADMIN_DEFAULT');;
            $admin->genero = app()->request()->get('gender');
            $admin->url_imagen = _env('DEFAULT_NAME_PHOTO_ADMIN')."."._env('DEFAULT_TYPE_PHOTO_ADMIN');
            
            $admin->save();
            
            return response()->json(['status' => 'success', 'message' => 'Usuario adminstrativo creado'], 200);

        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al usuario administrativo'], 500);

        }
        
    }

    function adminProfile($user_id) {

        try {
            
            $admin = Admin::select('administrativo.nombre', 'correo', 'genero.nombre as genero')
            ->leftJoin('genero', 'administrativo.genero', '=', 'genero.id_genero')
            ->where('id_administrativo', $user_id)
            ->first();

            return response()->json(['status' => 'success', 'admin' => $admin], 200);


        } catch (\Exception $e) {
            echo($e);
            return response()->json(['status' => 'error', 'message' => 'Error al obtener el usuario'], 500);
        }
        
    }


}