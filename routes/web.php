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

Route::get('/', [ 'as' => 'login', 'uses' => 'UserController@index']);
Route::post('/login', 'UserController@login');
Route::post('/logout', 'UserController@logout');
Route::get('/register', 'UserController@register');
Route::post('/register', 'UserController@register');

Route::middleware(['auth'])->group(function () {
	Route::get('/dashboard', 'UserController@dashboard');
	Route::resource('tasks', 'TaskController');
	Route::resource('collections', 'CollectionController');
});
