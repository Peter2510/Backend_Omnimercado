<?php

app()->get('/', function () {
    response()->json(['message' => 'API Omnimercado']);
});

/** USER-ADMIN */
app()->get('/obtener-generos','Admin_UserController@getGenders');
app()->post('/validar-correo', 'Admin_UserController@validateEmail');
app()->post('/validar-credenciales-login','Admin_UserController@login');
//app()->post('/upload','Admin_UserController@uploadImage');
//app()->post('/jwt','Admin_UserController@auth');
app()->get('/ver-imagen','Admin_UserController@response_image');


/** USER */
app()->post('/crear-usuario','UserController@createUser');

/** ADMIN */
app()->get('/obtener-roles-administrativo','AdminController@getRoles');
app()->post('/crear-admin','AdminController@createAdmin');



/** PRODUCTS */
app()->get('/obtener-condicion-productos','ProductController@getAllProductConditionType');
app()->get('/obtener-categorias-productos','ProductController@getAllProductCategories');
app()->post('/crear-publicacion-producto','ProductController@createProductPost');
app()->get('/publicaciones-productos-activas','ProductController@getAvailableProducts');
app()->get('/publicaciones-usuario/{user_id}','ProductController@getUserProducts');
app()->get('/publicaciones-disponibles-para-usuario/{user_id}','ProductController@getUserAvailableProducts');
app()->get('/productos-pendientes-aprobacion','ProductController@productsPendingApproval');