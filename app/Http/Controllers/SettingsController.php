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

class SettingsController extends Controller
{
	public function showSettings()
	{
		$user = User::find(Auth::user()->id);
		return view("/user/settings", compact("user"));
	}

	public function saveSettings(Request $request)
    {
    	try
		{
			$user = User::findOrFail(Auth::user()->id);
        	$user->max_hour = $request->max_hour;
        	$user->save();

        	Session::flash("success", "Maximum hour/day updated!");
        	return redirect::back();
        }
        catch (\Exception $e) {
			Session::flash("error", $e->getMessage());
			return redirect::back();
		}
    }
}
