<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;

use \App\User;

class AdminController extends Controller
{
	public function users(){
		$users = User::where("type", 0)->get();
		return view("admin.user", compact("users"));
	}

	public function deactivateMember(Request $request, $id) {
		try
		{
			$user = User::findOrFail($id);
			$user->status = 0;
			$user->save();
			$this->successMessage("Successfully Deactivated.");
			return redirect::back();
		}
		catch (\Exception $e)
		{
			$this->errorMessage($e->getMessage());
			return redirect::back();
		}
	}

	public function activateMember(Request $request, $id) {
		try
		{
			$user = User::findOrFail($id);
			$user->status = 1;
			$user->save();
			$this->successMessage("Successfully Activated.");
			return redirect::back();
		}
		catch (\Exception $e)
		{
			$this->errorMessage($e->getMessage());
			return redirect::back();
		}
	}

	public function getSpecificUser(Request $request, $id) {
		try
		{
			$user = User::findOrFail($id);
			return $user;
		}
		catch (\Exception $e)
		{
			$this->errorMessage($e->getMessage());
			return redirect::back();
		}
	}

	public function update(Request $request) {
		try
		{
			$user = User::findOrFail($request->id);
			$user->name = $request->name;
			$user->phone = $request->phone;
			$user->save();
			$this->successMessage("Successfully Updated User.");
			return redirect::back();
		}
		catch (\Exception $e)
		{
			$this->errorMessage($e->getMessage());
			return redirect::back();
		}
	}
}
