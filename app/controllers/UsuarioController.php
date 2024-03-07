<?php

namespace App\Controllers;
use App\Models\Usuario;

class UsuarioController extends Controller{

    function crearUsuario() {
        
        try {
            $usuario = new Usuario;
            $usuario->nombre = app()->request()->get('name');
            $usuario->correo = app()->request()->get('email');
            $usuario->fecha_nacimiento = app()->request()->get('birthYear');
            $usuario->contrasenia = password_hash(app()->request()->get('password'), PASSWORD_DEFAULT);
            $usuario->moneda_local_gastada = 0;
            $usuario->moneda_local_ganada = 0;
            $usuario->cantidad_moneda_virtual = 5;
            $usuario->moneda_virtual_ganada = 0;
            $usuario->moneda_virtual_gastada = 0;
            $usuario->cantidad_publicaciones_productos = 0;
            $usuario->cantidad_publicaciones_voluntariados = 0;
            $usuario->promedio_valoracion = 0;
            $usuario->activo = 0;
            $usuario->url_imagen = 'usuario.png';

            $usuario->save();
        
            return response()->json(['status' => 'success', 'message' => 'Usuario creado exitosamente'], 200);

        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al crear el usuario'], 500);
        }
        
    }

}