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
    Route::get('proveedor', 'ProveedorController@index');
    Route::patch('proveedor/{id}/estado', 'ProveedorController@cambiarEstado')->middleware('role:administrador,supervisor');
    Route::post('proveedor', 'ProveedorController@store')->middleware('role:administrador,supervisor,operador');
    Route::post('proveedor/rubro/{id}', 'ProveedorController@storeRubro')->middleware('role:administrador,supervisor,operador,proveedor');
    Route::get('proveedor/{id}/perfil', 'ProveedorController@PerfilProveedor')->middleware('role:administrador,supervisor,operador,proveedor');
    
    Route::get('proveedor/{id}/rubro', 'ProveedorController@rubros');

    Route::get('localidades', 'LocalidadController@index');
    Route::get('localidades/{id}', 'LocalidadController@show');
    Route::get('roles', 'UsuarioController@roles');

    Route::get('busqueda/usuarios', 'UsuarioController@buscarUsuarios');

    Route::get('user/actividad/{id}', 'UsuarioController@activity');
    Route::get('actividad/{id}', 'LogController@show');
});
