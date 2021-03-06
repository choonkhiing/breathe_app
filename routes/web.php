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

Route::post('/forgot-password', 'UserController@forgotPassword');
Route::get('/reset-password/{token}', 'UserController@showResetPassword');
Route::post('/reset-password', 'UserController@resetPassword');

Route::middleware(['auth'])->group(function () {
	Route::group(['middleware' => 'normal'], function () {
		Route::get('/dashboard', 'UserController@dashboard');
		Route::get('/profile', 'UserController@profile');
		Route::get('/settings', 'SettingsController@showSettings');
		Route::post('/settings/save', 'SettingsController@saveSettings');
		Route::post('/profile/edit', 'UserController@editProfile');

		// Route::resource('groups', 'GroupsController');
		Route::get('/groups', 'GroupsController@showGroups');
		Route::post('/groups', 'GroupsController@store');
		Route::post('/groups/validateEmail', 'GroupsController@validateEmail');
		Route::get('/groups/{id}/details', 'GroupsController@viewDetails');
		Route::get('/groupMember/{id}', 'GroupsController@getGroupMember');
		Route::post('/updateGroupMember', 'GroupsController@updateGroupMember');
		Route::post('/removeGroupMember', 'GroupsController@removeGroupMember');
		Route::post('/inviteGroupMember', 'GroupsController@inviteGroupMember');
		Route::post('/leaveGroup/{id}', 'GroupsController@leaveGroup');

		//Route::post('/profile/edit', 'UserController@profileEdit');
		Route::get('/tasks', 'TaskController@showTasks');
		Route::resource('tasks', 'TaskController');
		Route::post('/task/completetask/{id}', 'TaskController@completeTask');
		Route::resource('collections', 'CollectionController');

		Route::post('/processInvitation', 'UserController@processInvitation');
	});
	
	Route::group(['middleware' => 'admin'], function () {
		Route::get('/admin/users', 'AdminController@users');
		Route::post('/admin/user/{id}', 'AdminController@getSpecificUser');
		Route::post('/admin/edit', 'AdminController@update');
		Route::post('/admin/deactivate/{id}', 'AdminController@deactivateMember');
		Route::post('/admin/activate/{id}', 'AdminController@activateMember');
	});
});



