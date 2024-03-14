<?php

namespace App\Controllers;
use App\Models\User;
use Leaf\FS;

class UserController extends Controller{

    function createUser() {
        
        try {
            $user = new User;
            $user->nombre = app()->request()->get('name');
            $user->correo = app()->request()->get('email');
            $user->genero = 1;
            $user->fecha_nacimiento = app()->request()->get('birthYear');
            $user->contrasenia = password_hash(app()->request()->get('password'), PASSWORD_DEFAULT);
            $user->moneda_local_gastada = 0;
            $user->moneda_local_ganada = 0;
            $user->cantidad_moneda_virtual = _env('INIT_COINS');
            $user->moneda_virtual_ganada = 0;
            $user->moneda_virtual_gastada = 0;
            $user->cantidad_publicaciones_productos = 0;
            $user->cantidad_publicaciones_voluntariados = 0;
            $user->promedio_valoracion = 0;
            $user->activo_publicar = 0;
            $user->activo_plataforma = _env('ACTIVE_USER_DEFAULT');
                                 
            if(app()->request()->files("photo")['size']>0){
                 $image = app()->request()->files("photo");
                 $type = explode("/", $image['type']);
                 $image['name'] = app()->request()->get('email') . "." . $type[1];
                 $user->url_imagen = $image['name'];
                FS::uploadFile($image, "./images/");
            }else{
                 $user->url_imagen = _env('DEFAULT_NAME_PHOTO_USER');
            }            
            $user->save();
            
            return response()->json(['status' => 'success', 'message' => 'Usuario creado exitosamente'], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e], 500);

        }
        
    }

}