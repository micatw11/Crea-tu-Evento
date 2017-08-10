<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::group(['prefix' => 'api'], function () {

    // Rutas del Login
    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');
    Route::get('user', 'Auth\AuthController@getAuth');

    // Rutas para el Registro
    Route::post('register', 'Auth\RegisterUserController@register'); 

    //Password Reset
    //Route::post('password/reset/{token?}', 'Auth\PasswordController@resetForm');
    Route::post('password/email','Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');


    Route::resource('usuario', 'UsuarioController', 
    [ 
        'except' => ['destroy', 'edit', 'create', 'store']
	]);

    Route::post('user/{id}/perfil/avatar', 'UsuarioController@updateAvatar');
    Route::patch('user/{id}/password', 'UsuarioController@changePassword');
    Route::delete('user/{id}/account', 'UsuarioController@destroy');
    Route::patch('user/{id}/block', 'UsuarioController@bloquear');
    Route::patch('user/{id}/rol', 'UsuarioController@cambiarRol')->middleware('role:administrador,supervisor');

    //proveedores
    Route::get('proveedor', 'ProveedorController@index')->middleware('role:administrador,supervisor,operador');

    Route::patch('proveedor/{id}/estado', 'ProveedorController@cambiarEstado')->middleware('role:administrador,supervisor');
    Route::post('proveedor', 'ProveedorController@store')->middleware('role:administrador,supervisor,operador');
    Route::patch('proveedor/{id}/edit', 'ProveedorController@update')->middleware('role:administrador,supervisor,operador');
    Route::get('proveedor/{id}', 'ProveedorController@proveedor');


    Route::post('proveedor/rubro/{id}', 'RubrosDetalleController@store')->middleware('role:administrador,supervisor,operador,proveedor');
    Route::patch('proveedor/rubro/{id}/edit', 'RubrosDetalleController@update')->middleware('role:administrador,supervisor,operador,proveedor');
    //Route::get('proveedor/{idProveedor}/rubro/{idRubro}', 'RubrosDetalleController@show');
    Route::get('proveedor/{id}/rubro', 'RubrosDetalleController@getAll');


    Route::get('localidades', 'LocalidadController@index');
    Route::get('localidades/{id}', 'LocalidadController@show');
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
        
    //Route::get('proveedor/{proveedorId}/rubros/search', 'ProveedorController@buscarRubro');
    Route::get('proveedor/{proveedorId}/publicacion','PublicacionController@publicacionesProveedor');

    Route::post('categoria', 'CategoriaController@store')->middleware('role:administrador,supervisor');
    Route::get('categoria','CategoriaController@index')
        ->middleware('role:administrador,supervisor,operador,usuario,proveedor');
    Route::patch('categoria/{id}', 'CategoriaController@update')->middleware('role:administrador,supervisor');
    Route::get('categoria/all', 'CategoriaController@searchCategoria');
    Route::get('categoria/{id}', 'CategoriaController@show');


    Route::get('subcategoria', 'SubcategoriaController@index');
    Route::get('subcategoria/{id}', 'SubcategoriaController@show');
    Route::get('subcategorias/{categoria}','SubcategoriaController@searchSubcategoria');
    Route::post('subcategoria', 'SubcategoriaController@store');
    Route::patch('subcategoria/{id}','SubcategoriaController@update');

    Route::get('rubro', 'RubroController@index');
    Route::get('rubro/{id}', 'RubroController@show');
    Route::get('rubros/{subcategoria}','RubroController@searchRubros');
    Route::post('rubro', 'RubroController@store');
    Route::patch('rubro/{id}','RubroController@update');
});
