<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use \App\Setting;
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
		$setting = Setting::where('user_id',Auth::user()->id)->first();
		return view("/user/settings", compact("setting"));
	}

	public function saveSettings(Request $request)
    {
    	try
		{
			$setting = Setting::where('user_id',Auth::user()->id)->first();
        	$setting->max_hour = $request->max_hour;
        	$setting->reminder_time = $request->reminder_time;
        	$setting->day_before_remind = $request->day_before_remind;
        	$setting->save();

        	Session::flash("success", "Setting updated!");
        	return redirect::back();
        }
        catch (\Exception $e) {
			Session::flash("error", $e->getMessage());
			return redirect::back();
		}
    }
}
