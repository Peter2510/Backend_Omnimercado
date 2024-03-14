<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Gender;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Leaf\FS;

class Admin_UserController extends Controller
{


    function getGenders()
    {

        try {

            $genders = Gender::all();
            return response()->json(['status' => 'success', 'genders' => $genders], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al obtener los generos'], 500);
        }
    }

    function validateEmail()
    {

        try {
            $email = app()->request()->get('email');

            if (is_null($email)) {
                return response()->json(['status' => 'error', 'message' => 'El campo correo es obligatorio'], 400);
            }
            $existsUserEmail = User::where('correo', $email)->exists();
            $existsAdminEmail = Admin::where('correo', $email)->exists();

            if ($existsUserEmail || $existsAdminEmail) {
                //409 Conflict
                return response()->json(['status' => 'error', 'message' => 'Correo electronico ya registrado'], 400);
            } else {
                return response()->json(['status' => 'success', 'message' => 'Correo electronico disponible'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al validar el correo'], 500);
        }
    }

    function profile_image($url_image){

        $pathImage = 'images/' . $url_image;

        if (file_exists($pathImage)) {
            $imageData = file_get_contents($pathImage);

            $image_data_json = [
                'type' => FS::extension($pathImage),
                'image' => base64_encode($imageData)
            ];

            return $image_data_json;
        } else {

            $pathDefaultImage = 'images/' . _env('DEFAULT_NAME_PHOTO_USER')."."._env('DEFAULT_TYPE_PHOTO_USER');
            $imageData = file_get_contents($pathDefaultImage);
            $image_data_json = [
                'type' => FS::extension($pathDefaultImage),
                'image' => base64_encode($imageData)
            ];

            return $image_data_json;
        }
    }
    function login()
    {
        try {

            $email = app()->request()->get('email');
            $password =  app()->request()->get('password');

            if (is_null($email) || is_null($password)) {
                return response()->json(['status' => 'error', 'message' => 'El campo correo y contraseña son obligatorios'], 400);
            }

            $user = User::select('id_usuario', 'nombre', 'contrasenia', 'cantidad_moneda_virtual', 'url_imagen', 'activo_plataforma')
                ->where('correo', $email)->first();

            if ($user) {
                if (password_verify($password, $user->contrasenia)) {
                    $user->makeHidden(['contrasenia']);
                    $profile_image = $this->profile_image($user->url_imagen);
                    return response()->json(['status' => 'success',  'user' => $user,'profile_image'=>$profile_image], 200);
                }
            } else {
                $admin = Admin::select('id_administrativo', 'contrasenia', 'nombre', 'rol', 'url_imagen', 'activo')
                    ->where('correo', $email)->first();

                if ($admin) {
                    if (password_verify($password, $admin->contrasenia)) {
                        $admin->makeHidden(['contrasenia']);
                        $profile_image = $this->profile_image($admin->url_imagen);
                        return response()->json(['status' => 'success',  'admin' => $admin,'profile_image'=>$profile_image], 200);
                    }
                }
            }

            return response()->json(['status' => 'error', 'message' => 'Credenciales incorrectas'], 401);
        } catch (\Exception $e) {
            echo $e;
            return response()->json(['status' => 'error', 'message' => 'Error al validar login'], 500);
        }
    }

    function auth()
    {

        try {

            $email = app()->request()->get('email');
            $password =  app()->request()->get('password');

            if (is_null($email) || is_null($password)) {
                return response()->json(['status' => 'error', 'message' => 'El campo correo y contraseña son obligatorios'], 400);
            }

            $user = User::select('id_usuario', 'nombre', 'contrasenia', 'cantidad_moneda_virtual', 'url_imagen', 'activo_plataforma')
                ->where('correo', $email)->first();

            if ($user) {
                if (password_verify($password, $user->contrasenia)) {
                    $user->makeHidden(['contrasenia']);
                    $now = strtotime("now");
                    $key = _env('KEY_JWT');
                    $payload = [
                        'exp' => $now + 3600,
                        'data' => $user
                    ];
                    $jwt = JWT::encode($payload, $key, 'HS256');
                    return response()->json(['status' => 'success',  'user' => $jwt], 200);
                }
            } else {
                $admin = Admin::select('id_administrativo', 'contrasenia', 'nombre', 'rol', 'url_imagen', 'activo')
                    ->where('correo', $email)->first();

                if ($admin) {
                    if (password_verify($password, $admin->contrasenia)) {
                        $admin->makeHidden(['contrasenia']);
                        $now = strtotime("now");
                        $key = _env('KEY_JWT');
                        $payload = [
                            'exp' => $now + 3600,
                            'data' => $admin
                        ];
                        $jwt = JWT::encode($payload, $key, 'HS256');
                        return response()->json(['status' => 'success',  'admin' => $jwt], 200);
                    }
                }
            }

            return response()->json(['status' => 'error', 'message' => 'Credenciales incorrectas'], 401);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al validar login'], 500);
        }
    }

}


