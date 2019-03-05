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

	public function index() {
		if (Auth::check()) {
    		// The user is logged in...
    		return redirect('/dashboard');
		}
		else {
			return view('login');
		}
	}

	public function login(Request $request) {
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
   		 	// The user is active, not suspended, and exists.

			return redirect('/dashboard');
		}
		else
		{
			return redirect::back()->with("error", "Username or Password Incorrect!");
		}
	}

	public function dashboard()
	{
		return view("user/dashboard");
	}

	public function logout() {
		Auth::logout();
		return redirect::route("login");
	}
}