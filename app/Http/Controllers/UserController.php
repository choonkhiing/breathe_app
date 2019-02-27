<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Auth;
use Session;
use Carbon\Carbon;
use Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
	public function login(Request $request) {
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
   		 // The user is active, not suspended, and exists.
			return redirect::action('HomeController@index');
		}
		else
		{
			return redirect::back();
		}
	}

}