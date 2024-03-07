<?php

namespace App\Controllers;
use App\Models\Usuario;
use App\Models\Administrativo;

class Admin_UsuarioController extends Controller{

    function validarCorreo(){
    
        $correo = app()->request()->get('correo');
        
        if(is_null($correo)){
            //400 Bad Request
            return response()->json(['status' => 'error' , 'mensaje'=> 'El campo correo es obligatorio'], 400);
        }
        $existeCorreoUsuario = Usuario::where('correo',$correo)->exists();        
        $existeCorreoAdministrativo = Administrativo::where('correo',$correo)->exists();

        if ($existeCorreoUsuario || $existeCorreoAdministrativo){
            //409 Conflict
            return response()->json(['status' => 'error' , 'message'=> 'Correo electronico ya registrado'], 400);
        }else{
            return response()->json(['status' => 'success' , 'message'=> 'Correo electronico disponible'], 200);
        }
    }

}