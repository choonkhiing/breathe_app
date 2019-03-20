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

use \App\Task;

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

        $tasks = $tasks->groupBy("priority");

		return view("user/dashboard", compact("tasks"));
	}

	public function profile()
	{	
		return view("user/profile");
	}

	public function editProfile(Request $request)
	{	
		try
		{
			$user = User::findOrFail($request->user_id);
			$user->name = $request->name;

			// if($user->email!=$request->email){
			// 	$checkEmail=User::where("email", $request->email)->count();
			// 	if($checkEmail>0){
			// 		Session::flash("error", "Your email is already taken!");
			// 		return redirect::back();
			// 	}
			// 	$user->email = $request->email;
			// }

			$user->phone = $request->phone;
			dd($request->avatar, $request->file('avatar'));
			$image = $request->file('avatar');

            if ($image != null) {
                $imageFile = fopen($image->path(), 'r');
            }
            else {
                $imageFile = null;
            }
            dd($image);
			if($imageFile != null) {
                $avatar = $imageFile->avatar('file');
                $extention = $avatar->extension();
                $date = Carbon::now()->format('YmdHis');
                Storage::disk('public')->put('/img/uploads/profile/'.$date.'.'.$extention, File::get($avatar));
                $user->profile_pic = '/img/uploads/profile/'.$date.'.'.$extention;
            }


			$user->save();
			Auth::user()->name = $user->name;
			Auth::user()->phone = $user->phone;
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