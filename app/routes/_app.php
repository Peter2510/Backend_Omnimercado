<?php

app()->get('/', function () {
    response()->json(['message' => 'Hola desde xamp']);
});

/** USUARIO-ADMINISTRATIVO */
app()->post('/validar-correo', 'Admin_UsuarioController@validarCorreo');

/** USUARIO */

app()->post('/crear-usuario','UsuarioController@crearUsuario');

/** ADMINISTRATIVO */