<?php

namespace App\Controllers;
use App\Models\User;
use Leaf\FS;
use App\Controllers\Admin_UserController;

class UserController extends Controller{

    function createUser() {
        
        try {
            $user = new User;
            $user->nombre = app()->request()->get('name');
            $user->correo = app()->request()->get('email');
            $user->genero = app()->request()->get('gender');
            $user->fecha_nacimiento = app()->request()->get('birthYear');
            $user->contrasenia = password_hash(app()->request()->get('password'), PASSWORD_DEFAULT);
            $user->moneda_local_gastada = 0;
            $user->moneda_local_ganada = 0;
            $user->cantidad_moneda_virtual = _env('INIT_COINS');
            $user->moneda_virtual_ganada = 0;
            $user->moneda_virtual_gastada = 0;
            $user->promedio_valoracion = 0;
            $user->activo_publicar = 0;
            $user->activo_plataforma = _env('ACTIVE_USER_DEFAULT');
            
            $file = app()->request()->files("photo");
            
            if($file!=null){
                 $image = app()->request()->files("photo");
                 $type = explode("/", $image['type']);
                 $image['name'] = app()->request()->get('email') . "." . $type[1];
                 $user->url_imagen = $image['name'];
                 FS::uploadFile($image, _env('STORAGE_USER_IMAGES'));
            }else{
                 $user->url_imagen = _env('DEFAULT_NAME_PHOTO_USER')."."._env('DEFAULT_TYPE_PHOTO_USER');
            }            
            $user->save();
            
            return response()->json(['status' => 'success', 'message' => 'Usuario creado exitosamente'], 200);

        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al crear el usuario'], 500);

        }
        
    }

    function userProfile($user_id) {

        try {
            
            $user = $user = User::select('usuario.nombre', 'correo', 'fecha_nacimiento', 'cantidad_moneda_virtual', 'genero.nombre as genero')
            ->leftJoin('genero', 'usuario.genero', '=', 'genero.id_genero')
            ->where('id_usuario', $user_id)
            ->first();

            return response()->json(['status' => 'success', 'user' => $user], 200);


        } catch (\Exception $e) {
            echo($e);
            return response()->json(['status' => 'error', 'message' => 'Error al obtener el usuario'], 500);
        }
        
    }

}