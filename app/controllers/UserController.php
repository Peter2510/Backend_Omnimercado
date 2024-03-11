<?php

namespace App\Controllers;
use App\Models\User;

class UserController extends Controller{

    function createUser() {
        
        try {
            $user = new User;
            $user->nombre = app()->request()->get('name');
            $user->correo = app()->request()->get('email');
            $user->fecha_nacimiento = app()->request()->get('birthYear');
            $user->contrasenia = password_hash(app()->request()->get('password'), PASSWORD_DEFAULT);
            $user->moneda_local_gastada = 0;
            $user->moneda_local_ganada = 0;
            $user->cantidad_moneda_virtual = 5;
            $user->moneda_virtual_ganada = 0;
            $user->moneda_virtual_gastada = 0;
            $user->cantidad_publicaciones_productos = 0;
            $user->cantidad_publicaciones_voluntariados = 0;
            $user->promedio_valoracion = 0;
            $user->activo_publicar = 0;
            $user->activo_plataforma = 1;
            $user->url_imagen = 'usuario.png';

            $user->save();
        
            return response()->json(['status' => 'success', 'message' => 'Usuario creado exitosamente'], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al crear el usuario'], 500);

        }
        
    }

}