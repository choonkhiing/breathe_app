<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use \App\Group;
use Auth;
use Session;
use Carbon\Carbon;
use File;
use Storage;
use Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class GroupsController extends Controller
{
    public function showGroups()
	{
		// $setting = Setting::where('user_id',Auth::user()->id)->first();
		return view("/user/tasks/groups");
    }
    
    public function store(Request $request)
	{
		try {
            $group = new Group();
            $group->title = $request->title;
            $group->description = $request->description ?? '';
            $group->created_by = Auth::user()->id;

            $group->save();

            $this::successMessage("New group added!");
        } catch (\Exception $e) {
            $this::errorMessage($e->getMessage());
        }
        return redirect::back();
	}
}
