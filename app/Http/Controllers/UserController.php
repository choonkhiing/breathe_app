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

	public function profile()
	{	
		//dd(Auth::user());
		$user=User::find(Auth::user()->id);
		//User::where("phone", '23123')->where('name', '>=', 'suping')->orderBy('price', DESC)->first();

		
		// if (empty($users)) {

		// }
		// else {
		// 	for ($users as $user) {

		// 	}
		// }
		
		//dd($user);
		return view("user/profile",compact("user"));
	}

	public function register(Request $request)
	{
		try
		{
			$checkEmail = User::where("email", $request->email)->count();
			if ($checkEmail > 0){
				Session::flash("error", "Your email is already taken.");
				return redirect::back();
			}
			else {
				$user = new User();
				$user->name = $request->username;
				$user->email = $request->email;
				$user->password = bcrypt($request->password);
				$user->phone = $request->phone;
				$user->save();

				if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
   		 			// The user is active, not suspended, and exists.
					return redirect('/dashboard');
				}
			}
		}
		catch (\Exception $e) {
			Session::flash("error", $e->getMessage());
			return redirect::back();
		}
	}

	public function showregister()
	{
		return view("register");
	}

	public function logout() {
		Auth::logout();
		return redirect::route("login");
	}
}