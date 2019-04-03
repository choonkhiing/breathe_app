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
use \App\Collection;
use \App\Setting;

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
		$tasks = Task::whereDate("start_date", "<=", Carbon::today())
		->whereDate("due_date", ">=", Carbon::today())
		->where("status", 0)->orderBy("due_date", "ASC")
		->orderBy("priority", "DESC")
		->get();

        //User setting
		$setting = Setting::find(Auth::user()->id);
		$used_hour = 0;

        //$cls = Collection::where("user_id", \Auth::user()->id)->get();
		$cls = null;

		$todayTasks = collect(new Task);
		$upcomingTasks = collect(new Task);

        //Split the tasks into today's and upcoming's task(s)
		foreach ($tasks AS $task) {
        	//Prioritize on due date 
			if ($task->due_date->isToday()) {
				$todayTasks->push($task);	
        		$used_hour = $used_hour + $task->min_duration; //See how many hours left for today
        	}
        	else {
        		if ($task->start_date->format('Y-m-d') <= Carbon::today()->format('Y-m-d')) {
        			if ($setting->max_hour - ($used_hour + $task->min_duration) >= 0) { 
        				//Add some 	tasks to today if there is time
        				$todayTasks->push($task);
        				$used_hour = $used_hour + $task->min_duration; //See how many hours left for today
        			}
        		}
        		else {
        			$upcomingTasks->push($task);
        		}
        	}
        }

        $tasks = Task::whereDate("start_date", ">", Carbon::today())
		->whereDate("due_date", ">=", Carbon::today())
		->where("status", 0)->orderBy("due_date", "ASC")
        ->orderBy("priority", "DESC")
        ->get();
        foreach ($tasks AS $task) {
        	//Prioritize on due date 
        	if ($task->due_date->isToday()) {
        		$todayTasks->push($task);	
        		$used_hour = $used_hour + $task->min_duration; //See how many hours left for today
        	}
        	else {
        		$upcomingTasks->push($task);
        	}
        }


        $stressLevel = $this->calStressLevel($used_hour, $setting->max_hour);

		$todayTasks = $todayTasks->groupBy("priority");
		$upcomingTasks = $upcomingTasks->groupBy("priority");

		return view("user/dashboard", compact("todayTasks", "upcomingTasks", "used_hour", "stressLevel", "cls", "setting"));
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

				//Save settings
    			$setting = new Setting();
    			$setting->user_id = $new_user->id;
    			$setting->save();

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


	public function calStressLevel($used_hour, $max_hour) {
		if ($max_hour == 0) {
			$stressLevel = 0;
		}
		else {
			$stressLevel = $used_hour / $max_hour * 100;
		}
		
		return ceil($stressLevel);
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
    						->from('admin@pmxglobal.com.my', 'Breathe')
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



}