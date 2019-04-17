<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use Carbon\Carbon;
use DB;
use File;
use Storage;
use Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use \App\User;
use \App\Task;
use \App\Group;
use \App\GroupMember;
use \App\Collection;
use \App\Setting;
use \App\GroupInvitation;
use \App\ResetPassword;

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
        $groups = DB::table('groups')
                ->select('groups.*')
                ->join('group_members', 'groups.id', '=', 'group_members.group_id')
                ->where('groups.status', '=', 1)
                ->where('group_members.status', '=', 1)
                ->where('group_members.user_id', '=', Auth::user()->id)
                ->get();
  
        $tasks = Task::whereDate("due_date", ">=", Carbon::today())
        ->whereDate("start_date", "<=", Carbon::today())
        ->where("status", 0)->get();


        $stressLevel = $this->calculateStressLevel($tasks);

        return view("user/dashboard", compact("groups", "stressLevel"));
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

	public function testReminder() 
	{
        //Check user's reminder time.
		$users = User::all();

		foreach ($users as $user) {
			$settings = Setting::where('reminder_time', Carbon::now()->format('H:i'))->get();
			$currDate = Carbon::today();

			if ($settings->isEmpty()) {
				dd("No one");
			}

			foreach ($settings as $setting) {
				$tasks = Task::where('user_id', $user->id)
				->where('status', 0)
				->whereDate('start_date', '<=', $currDate)
				->whereDate('due_date', '>=', $currDate)
				->get();

				$remindTasks = collect(); 

        	 	//Get the task is cloased to duedate
				foreach ($tasks as $task) {
					if ($currDate->diffInDays($task->due_date) <= $setting->day_before_remind) {
						$remindTasks->push($task);
					}	
				}

				if (!empty($remindTasks)) {
        	 		//Send email to remind the user
					try {
						$beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
						$beautymail->send('emails.reminder', ['user' => $user, 'tasks' => $remindTasks], function($message) use ($user)
						{
							$message
							->from('ckooi@geekycs.com', 'Breathe')
							->to($user->email, $user->name)
							->subject('Breathe Reminder');
						});
					}
					catch (\Exception $e)
					{

					}
				} 		
			}
		}
	}

	public function inviteUser($user_id, $group_id)
	{
		try {
			$inv = new GroupInvitation();
			$inv->inviter = Auth::user()->id;
			$inv->invitee = $user_id;
			$inv->group_id = $group_id;
			$inv->save();

			return response()->json(['success' => true]);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'msg' => $e->getMessage()]);
		}
	}

	public function processInvitation($action, $group_id)
	{
		try {
			switch($action){
				case "approve":
				$status = 1;
				break;
				case "reject":
				$status = 0;
				break;
				default:
				throw new \Exception('Invalid action');
				break;
			}

			$inv = GroupInvitation::find($group_id);
			$inv->status = $status;
			$inv->save();

            // Adds user into group
			if($status == 1){
				$grp = new GroupDetail();
				$grp->group_id = $group_id;
				$grp->user_id = Auth::user()->id;
				$grp->save();

				$msg = "Successfully approved invitation";
			} else {
				$msg = "Successfully rejected invitation.";
			}

			return response()->json(['success' => true, 'msg' => $msg]);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'msg' => $e->getMessage()]);
		}
	}

	public function removeUser(Request $request)
	{
		try {
			$grp = GroupDetail::where("group_id", $request->group_id)->where("user_id", $request->user_id)->first();
			$grp->remove();

			$this::successMessage("Successfully removed member from group.");
		} catch (\Exception $e) {
			$this::errorMessage($e->getMessage());
		}
	}

	public function forgotPassword(Request $request)
	{
		try {  
			if(DB::table('users')->where('email', $request->email)->count()) {
				$random_string = $this->randString(10);
				$is_unique = false;

				while (!$is_unique) {
					if(DB::table('reset_password')->where('token', $random_string)->count()) {
						$random_string = $this->randString(10);
					}
					else {
						$is_unique = true;
						$reset_password = new ResetPassword();
						$reset_password->email = $request->email;
						$reset_password->token = $random_string;
						$reset_password->save();

						try {
							$beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
							$beautymail->send('emails.forgot-password', ['token' => $reset_password->token], function($message) use ($reset_password)
							{
								$message
								->from('ckooi@geekycs.com', 'Breathe')
								->to($reset_password->email)
								->subject('Forgot Password?');
							});
							return response()->json(["success" => "Reset password has been sent to your email."]);
						}
						catch (\Exception $e)
						{
							return response()->json(["error"=>$e->getMessage()]);
						}
					}
				}
			}
			else {
				return response()->json(["error" => "Email not found!"]);
			}
		} catch (\Exception $e) {
			return response()->json(["error"=>$e->getMessage()]);
		}
	}

	function randString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789') {
		$str = '';
		$count = strlen($charset);
		while ($length--) {
			$str .= $charset[mt_rand(0, $count-1)];
		}
		return $str; 
	}

	public function showResetPassword(Request $request, $token)
	{    
		try 
		{
			$isExpired = true;
			$reset_password = "";
			$reset_password = DB::table('reset_password')->where('token', $token)
			->where('created_at', '>=', Carbon::now()->subDays(1)->toDateTimeString())->orderBy('created_at', 'desc')->first();

			if ($reset_password === NULL || empty($reset_password)) {
				$isExpired = true;
				return view('reset-password', compact("reset_password", "isExpired"));
			}
			else {
				$isExpired = false;
				return view('reset-password', compact("reset_password", "isExpired", "token"));
			}
		}
		catch (\Exception $e)
		{
			$isExpired = true;
			return view('reset-password', compact("reset_password", "isExpired"));
		}
	}

	public function resetPassword(Request $request)
	{    
		try {  
			$user = DB::SELECT("SELECT * FROM users AS u JOIN reset_password AS rp ON u.email = rp.email WHERE rp.token='".$request->token."' AND rp.created_at >='".Carbon::now()->subDays(1)->toDateTimeString()."'");

			if ($user === NULL || empty($user)) {
				return response()->json(["success" => "The token has been expired! Please request again."]);
			}
			else {
				DB::update("update users set password ='".bcrypt($request->password)."' WHERE email = '".$request->email."'");
				Session::flash('success', "Your password has been updated.");
				return response()->json(["success" => "Your password has been updated."]);
			}

		} catch (\Exception $e) {
			return response()->json(["success" => $e->getMessage()]);
		}
	}
}