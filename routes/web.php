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
Route::get('/register', 'UserController@showregister');
Route::post('/register', 'UserController@register');

//Test Reminder
Route::get('/test-reminder', 'UserController@testReminder');

//Facebook Login
Route::get('/fb/redirect', 'SocialAuthFacebookController@redirect');
Route::get('/fb/callback', 'SocialAuthFacebookController@callback');

//Google Login
Route::get('/google/redirect', 'GoogleController@redirect');
Route::get('/google/callback', 'GoogleController@callback');

Route::middleware(['auth'])->group(function () {
	Route::get('/dashboard', 'UserController@dashboard');
	Route::get('/profile', 'UserController@profile');
	Route::get('/settings', 'SettingsController@showSettings');
	Route::post('/settings/save', 'SettingsController@saveSettings');
	Route::post('/profile/edit', 'UserController@editProfile');
	//Route::post('/profile/edit', 'UserController@profileEdit');
	Route::resource('tasks', 'TaskController');
	Route::post('/task/completetask/{id}', 'TaskController@completeTask');
	Route::resource('collections', 'CollectionController');

	Route::post('/processInvitation', 'UserController@processInvitation');
});

Route::get('/admin/users', 'AdminController@users');

