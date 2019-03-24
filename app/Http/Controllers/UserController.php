<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use Auth;
use Session;
use Carbon\Carbon;
use File;
use Storage;
use Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use \App\Task;
use \App\Collection;

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
		$tasks = Task::orderBy("due_date", "ASC")
        ->orderBy("priority", "ASC")
        ->get();

        $cls = Collection::where("user_id", \Auth::user()->id)->get();

        $tasks = $tasks->groupBy("priority");

		return view("user/dashboard", compact("tasks", "cls"));
	}

	public function profile()
	{	
		$user = User::find(Auth::user()->id);
		return view("user/profile", compact("user"));
	}

	public function editProfile(Request $request)
	{	
		try
		{
			$user = User::findOrFail($request->user_id);
			$user->name = $request->name;
			$user->phone = $request->phone;

			$image = $request->file('avatar');

			if($image != null) {
                $extension = $image->extension();
               	if ($extension == "jpeg") {
               		$extension = "jpg";
               	}
            
                $date = Carbon::now()->format('YmdHis');
                Storage::disk('public')->put($date.'.'.$extension, File::get($image));
                $user->profile_pic = '/img/uploads/profile/'.$date.'.'.$extension;
            }

			$user->save();
			Auth::user()->name = $user->name;
			Auth::user()->phone = $user->phone;
			Auth::user()->profile_pic = $user->profile_pic;

			Session::flash("success", "Profile details is updated!");
			return redirect('/profile');
		}
		catch (\Exception $e) {
			Session::flash("error", $e->getMessage());
			return redirect::back();
		}
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