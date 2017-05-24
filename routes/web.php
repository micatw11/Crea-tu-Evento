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

Route::get('/ejemplo', function () {
    return view('ejemplo');
});

Route::get('/calendario', function () {
    return view('calendar');
});


Route::group(['prefix' => 'api'], function () {
    // Rutas del Login
    Route::post('login', 'Auth\AuthController@login');
    Route::post('logout', 'Auth\AuthController@logout');
    // Rutas para el Registro
    Route::post('register', 'Auth\RegisterUserController@register'); 

    Route::get('user', 'Auth\AuthController@getAuth');

    Route::resource('usuario', 'UsuarioController');
});
