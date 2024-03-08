<?php

app()->get('/', function () {
    response()->json(['message' => 'API Omnimercado']);
});

/** USUARIO-ADMINISTRATIVO */
app()->post('/validar-correo', 'Admin_UserController@validateEmail');
app()->post('/validar-credenciales-login','Admin_UserController@login');
//app()->post('/jwt','Admin_UserController@auth');


/** USUARIO */

app()->post('/crear-usuario','UserController@createUser');

/** ADMINISTRATIVO */