<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App\User;

class AdminController extends Controller
{
    public function users(){
    	$users = User::where("type", 0)->get();
    	return view("admin.user", compact("users"));
    }
}
