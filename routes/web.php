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

// Rutas del Login

Route::post('api/login', 'Auth\AuthController@login');
Route::post('api/logout', 'Auth\AuthController@logout');

// Rutas para el Registro
Route::post('api/register', 'Auth\RegisterController@create'); 

Route::get('api/user', 'Auth\AuthController@getAuth');
//Auth::routes();

Route::get('/home', 'HomeController@index');
