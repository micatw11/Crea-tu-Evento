<?php

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {

    // Rutas del Login
    Route::get('terminos-condiciones', 'TerminoCondicionController@show');
    Route::post('terminos-condiciones', 'TerminoCondicionController@store');
    Route::patch('terminos-condiciones', 'TerminoCondicionController@update');

    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::get('user', 'Auth\AuthController@getAuth');

    // Rutas para el Registro
    Route::post('register', 'Auth\RegisterUserController@register'); 

    //Password Reset
    //Route::post('password/reset/{token?}', 'Auth\PasswordController@resetForm');
    Route::post('password/email','Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');

    Route::get('supervisor/{id}/actividad/proveedores', 'UsuarioController@proveedoresBySupervisor');
    Route::get('operador/{id}/actividad/proveedores', 'UsuarioController@proveedoresByOperador');

    Route::get('usuario/{id}/notificaciones', 'NotificacionesController@indexOfUser');
    Route::get('notificacion/{id}', 'NotificacionesController@vista');
    Route::resource('usuario', 'UsuarioController', 
    [ 
        'except' => ['destroy', 'edit', 'create', 'store']
	]);

    Route::get('usuario/active/count', 'UsuarioController@activityCount');

    Route::post('user/{id}/perfil/avatar', 'UsuarioController@updateAvatar');
    Route::patch('user/{id}/password', 'UsuarioController@changePassword');
    Route::delete('user/{id}/account', 'UsuarioController@destroy');
    Route::patch('user/{id}/block', 'UsuarioController@bloquear');
    Route::patch('user/{id}/rol', 'UsuarioController@cambiarRol')->middleware('role:administrador,supervisor');

    //proveedores
    Route::get('proveedor', 'ProveedorController@index')->middleware('role:administrador,supervisor,operador');

    Route::patch('proveedor/{id}/estado', 'ProveedorController@cambiarEstado')->middleware('role:administrador,supervisor');
    Route::post('proveedor', 'ProveedorController@store')->middleware('role:administrador,supervisor,operador');
    Route::patch('proveedor/{id}/edit', 'ProveedorController@update')->middleware('role:administrador,supervisor,operador,proveedor');
    Route::get('proveedor/{id}', 'ProveedorController@show');


    Route::post('proveedor/rubro/', 'PrestacionController@store')
        ->middleware('role:administrador,supervisor,operador,proveedor');
    Route::patch('proveedor/rubro/{id}', 'PrestacionController@update')
        ->middleware('role:administrador,supervisor,operador,proveedor');
    Route::get('proveedor/rubro/{id}', 'PrestacionController@show');
    Route::get('proveedor/{id}/rubro', 'PrestacionController@getAll');

    Route::get('localidadesAll', 'LocalidadController@index');
    Route::get('localidades/{id}', 'LocalidadController@show');
    Route::get('provincias', 'LocalidadController@provincias');
    Route::get('localidades', 'LocalidadController@list_options');
    Route::post('localidad', 'LocalidadController@store');
    Route::patch('localidad/{id}/edit', 'LocalidadController@update');
    Route::delete('localidad/{id}', 'LocalidadController@destroy');
    Route::get('roles', 'UsuarioController@roles');

    Route::get('busqueda/usuarios', 'UsuarioController@buscarUsuarios');
    Route::get('user/{id}/actividad', 'UsuarioController@activity');
    Route::get('actividad/{id}', 'LogController@show');

    Route::get('publicacion/', 'PublicacionController@index');
    Route::post('publicacion/', 'PublicacionController@store')
        ->middleware('role:proveedor');
    Route::get('publicacion/{id}', 'PublicacionController@show');
    Route::patch('publicacion/{id}', 'PublicacionController@update')
        ->middleware('role:proveedor');
    Route::delete('/publicacion/{id}', 'PublicacionController@destroy')
        ->middleware('role:proveedor');

    Route::get('proveedor/{proveedorId}/publicacion','PublicacionController@publicacionesProveedor');

    Route::post('categoria', 'CategoriaController@store')->middleware('role:administrador,supervisor');
    Route::get('categoria','CategoriaController@index');
    Route::patch('categoria/{id}', 'CategoriaController@update')->middleware('role:administrador,supervisor');
    Route::get('categoria/{id}', 'CategoriaController@show');


    Route::get('subcategoria', 'SubcategoriaController@index');
    Route::get('subcategoria/{id}', 'SubcategoriaController@show');
    Route::get('subcategorias/{categoria}','SubcategoriaController@searchSubcategoria');
    Route::post('subcategoria', 'SubcategoriaController@store');
    Route::patch('subcategoria/{id}','SubcategoriaController@update');

    Route::get('rubro', 'RubroController@index');
    Route::get('rubro/{id}', 'RubroController@show');
    Route::get('rubros','RubroController@searchRubros');
    Route::post('rubro', 'RubroController@store');
    Route::patch('rubro/{id}','RubroController@update');

    Route::get('caracteristica', 'CaracteristicaController@index');
    Route::get('caracteristica/{id}', 'CaracteristicaController@show');
    Route::post('caracteristica', 'CaracteristicaController@store');
    Route::patch('caracteristica/{id}','CaracteristicaController@update');

    Route::get('articulo', 'ArticuloController@index');
    Route::get('articulo/{id}', 'ArticuloController@show');
    Route::post('articulo', 'ArticuloController@store');
    Route::patch('articulo/{id}','ArticuloController@update');

    Route::post('favoritos','FavoritoController@store');
    Route::get('favoritos/{user_id}', 'FavoritoController@show');

    Route::get('reserva/{id}', 'ReservaController@show');
    Route::get('user/{user_id}/reserva', 'ReservaController@reservasUser');
    Route::patch('reserva/{id}/estado', 'ReservaController@cambiarEstado');
    Route::post('publicacion/{publicacion_id}/reserva', 'ReservaController@store');
    Route::get('publicacion/{id}/reserva', 'PublicacionController@reservas');
    Route::post('publicacion/{publicacion_id}/presupuesto', 'ReservaController@storePresupuesto');
    Route::patch('presupuesto/{id}', 'ReservaController@updatePresupuesto');
    Route::get('proveedor/{id}/reserva', 'ReservaController@reservasProveedor');
    
    Route::post('horario','HorarioController@store');
    Route::get('horarios/{id}', 'HorarioController@index');
    Route::get('publicacion/{publicacion_id}/horarios', 'HorarioController@indexPublicacion');
    Route::get('horario/{id}', 'HorarioController@show');
    Route::patch('horario/{id}','HorarioController@update');

    Route::delete('horario/{id}', 'HorarioController@destroy');
    Route::get('publicacion/{publicacion_id}/horario/{fecha}', 'HorarioController@horariosPorFecha');
    Route::get('mensaje','MensajesController@index');
    Route::get('mensaje/{id}','MensajesController@show');
    Route::post('mensaje','MensajesController@store');
    Route::patch('mensaje/{id}','MensajesController@update');

    Route::get('user/{user_id}/calificaciones/pendientes','CalificacionController@indexPendientes');
    Route::get('user/{user_id}/calificaciones','CalificacionController@index');
    Route::post('calificacion', 'CalificacionController@store');
    Route::patch('calificacion/{id}/reportar', 'CalificacionController@reportar');
    Route::delete('calificacion/{id}', 'CalificacionController@destroy');
    Route::get('calificacion/{id}', 'CalificacionController@show');
});
