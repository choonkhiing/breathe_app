<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Session;
use Carbon\Carbon;
use \App\Task;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successMessage($message){
    	Session::flash("type", "success");
    	Session::flash("message", $message);
    }

    public function warningMessage($message){
    	Session::flash("type", "warning");
    	Session::flash("message", $message);
    }  

    public function errorMessage($message){
    	Session::flash("type", "danger");
    	Session::flash("message", $message);
    }  

    public function organizeTasks($tasks) {
        $tasks->todayTasks = $tasks->whereDate("start_date", "<=", Carbon::today())
        ->where("status", 0)->get();
        $tasks->stressLevel = 0;

        //Calculate Stress Level
        foreach ($tasks->todayTasks AS $task) {
          foreach (Task::TASK_PRIORITY_LEVEL AS $key => $percentage) {
                if ($key == $task->priority) {
                    $tasks->stressLevel += $percentage;
                }
           }
        }

        $tasks->upcomingTasks = $tasks->whereDate("start_date", ">", Carbon::today())
        ->where("status", 0)->get();
        $tasks->completedTasks = $tasks->where("status", 0)->get();
        
        $tasks->todayTasks = $tasks->todayTasks->groupBy("priority");
        $tasks->upcomingTasks = $tasks->upcomingTasks->groupBy("priority");
        $tasks->completedTasks = $tasks->completedTasks->groupBy("priority");
        $tasks->stressLevel = round($tasks->stressLevel);

        return $tasks;
    }
}
