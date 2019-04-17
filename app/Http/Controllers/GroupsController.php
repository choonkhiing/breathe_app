<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use \App\Group;
use \App\GroupMember;
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
            $group->description = $request->description;
            $group->created_by = Auth::user()->id;
            $group->save();

            $group_member = new GroupMember();
            $group_member->group_id = $group->id;
            $group_member->user_id = Auth::user()->id;
            $group_member->type = "admin";
            $group_member->status = 1;
            $group_member->save();

            $this::successMessage("New group added!");
        } catch (\Exception $e) {
            $this::errorMessage($e->getMessage());
        }
        return redirect::back();
	}
}
