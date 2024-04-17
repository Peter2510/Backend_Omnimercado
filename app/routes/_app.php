<?php
#sudo chmod -R 777 /opt/lampp/htdocs/omnimercado
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
app()->get('/perfil-usuario/{user_id}','UserController@userProfile');
app()->post('/actualizar-perfil-usuario/{user_id}','UserController@updateUser');
app()->get('/obtener-cantidad-monedas/{user_id}','UserController@getCoins');
app()->post('/recargar-monedas','UserController@chargeCoins');
app()->get('/obtener-divisa','UserController@getBadge');


/** ADMIN */
app()->get('/obtener-roles-administrativo','AdminController@getRoles');
app()->post('/crear-admin','AdminController@createAdmin');
app()->get('/perfil-admin/{user_id}','AdminController@adminProfile');


/** PRODUCTS */
app()->get('/obtener-condicion-productos','ProductController@getAllProductConditionType');
app()->get('/obtener-categorias-productos','ProductController@getAllProductCategories');
app()->post('/crear-publicacion-producto','ProductController@createProductPost');
app()->get('/publicaciones-productos-activas','ProductController@getAvailableProducts');
app()->get('/publicaciones-usuario/{user_id}','ProductController@getUserProducts');
app()->get('/productos-disponibles-para-usuario/{user_id}','ProductController@getUserAvailableProducts');
app()->get('/productos-pendientes-aprobacion','ProductController@productsPendingApproval');
app()->get('/producto/{id_producto}','ProductController@getProductById');
app()->patch('/cambiar-estado-producto-a-pendiente/{id_producto}','ProductController@setProductToPending');
app()->patch('/cambiar-estado-producto-a-disponible/{id_producto}','ProductController@setProductToAvailable');
app()->patch('/cambiar-estado-producto-a-vendido/{id_producto}','ProductController@setProductToSold');
app()->patch('/cambiar-estado-producto-a-rechazado/{id_producto}','ProductController@setProductToRejected');
app()->get('/cantidad-productos-pendientes-aprobacion','ProductController@countProductsPendingApproval');
app()->get('/precio-producto/{id_producto}','ProductController@getPriceProduct');
app()->get('/estado-producto/{id_producto}','ProductController@getStateProduct');
app()->post('/crear-venta','ProductController@createSale');
app()->get('/obtener-compras-usuario/{user_id}','ProductController@getUserPurchaseProducts');


/*/ BARTER PRODUCTS */
app()->get('/publicacion-producto-trueque/{id_publicacion}','BarterProductController@getBarterProductById');
app()->post('/crear-publicacion-producto-trueque','BarterProductController@createBarterProduct');
app()->get('/publicaciones-intercambio-productos-activas','BarterProductController@getAvailableBarterProducts');
app()->get('/productos-intercambio-pendientes-aprobacion','BarterProductController@barterProductsPendingApproval');
app()->get('/productos-intercambio-usuario/{user_id}','BarterProductController@getUserBarterProducts');
app()->get('/publicaciones-disponibles-para-usuario/{user_id}','BarterProductController@getUserAvailableBarterProducts');
app()->patch('/cambiar-estado-publicacion-intercambio-a-pendiente/{id_producto_trueque}','BarterProductController@setBarterProductToPending');
app()->patch('/cambiar-estado-publicacion-intercambio-a-disponible/{id_producto_trueque}','BarterProductController@setBarterProductToAvailable');
app()->patch('/cambiar-estado-publicacion-intercambio-a-realizado/{id_producto_trueque}','BarterProductController@setBarterProductToRealized');
app()->patch('/cambiar-estado-publicacion-intercambio-a-rechazado/{id_producto_trueque}','BarterProductController@setBarterProductToRejected');
app()->patch('/cambiar-estado-publicacion-intercambio-a-eliminado/{id_producto_trueque}','BarterProductController@setBarterProductToDeleted');
app()->get('/cantidad-intercambios-pendientes-aprobacion','BarterProductController@countBarterProductsPendingApproval');
app()->get('/estado-intercambio/{id_producto_trueque}','BarterProductController@getStateBarterProduct');

app()->post('/crear-intercambio','BarterProductController@createBarter');
app()->get('/obtener-intercambios-usuario/{user_id}','BarterProductController@getUserExchanges');

/*Volunteering*/
app()->get('/obtener-categorias-voluntariados','VolunteeringsController@getAllVolunteeringCategories');
app()->post('/crear-voluntariado','VolunteeringsController@createVolunteering');

app()->get('/voluntariados-activos','VolunteeringsController@getAvailableVolunteerings');
app()->get('/voluntariados-usuario/{user_id}','VolunteeringsController@getUserVolunteerings');
app()->get('/voluntariados-disponibles-para-usuario/{user_id}','VolunteeringsController@getUserAvailableVolunteering');
app()->get('/voluntariados-pendientes-aprobacion','VolunteeringsController@volunteeringPendingApproval');
app()->get('/voluntariado/{id_voluntariado}','VolunteeringsController@getVolunteeringById');
app()->patch('/cambiar-estado-voluntariado-a-pendiente/{id_voluntariado}','VolunteeringsController@setVolunteeringToPending');
app()->patch('/cambiar-estado-voluntariado-a-disponible/{id_voluntariado}','VolunteeringsController@setVolunteeringToAvailable');
app()->patch('/cambiar-estado-voluntariado-a-vendido/{id_voluntariado}','VolunteeringsController@setVolunteeringToSold');
app()->patch('/cambiar-estado-voluntariado-a-rechazado/{id_voluntariado}','VolunteeringsController@setVolunteeringToRejected');
app()->get('/cantidad-voluntariados-pendientes-aprobacion','VolunteeringsController@countVolunteeringsPendingApproval');
app()->get('/estado-voluntariado/{id_voluntariado}','VolunteeringsController@getStateVolunteering');

app()->get('/cantidad-publicaciones-pendientes-aprobacion','ReportsController@countPostPendingApproval');