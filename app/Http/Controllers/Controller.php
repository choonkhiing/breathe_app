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

    public function organizeTasks($group_id) {
        $tasks = collect();

        if ($group_id == 0 || $group_id == null) {
            $tasks->todayTasks = Task::with("settings")->whereDate("due_date", ">=", Carbon::today())
            ->whereDate("start_date", "<=", Carbon::today())
            ->where("status", 0)
            ->orderBy("due_date", "ASC")
            ->orderBy("priority", "DESC")->get();

            
            $tasks->stressLevel = $this->calculateStressLevel($tasks->todayTasks);

            //Retrieve upcomming tasks
            $tasks->upcomingTasks = Task::with("settings")->whereDate("due_date", ">=", Carbon::today())
            ->whereDate("start_date", ">", Carbon::today())
            ->where("status", 0)
            ->orderBy("due_date", "ASC")
            ->orderBy("priority", "DESC")->get();

            //Retrieve completed tasks
            $tasks->completedTasks = Task::with("settings")->where("status", 1)->get();
        }
        else {
            $tasks->todayTasks = Task::with("settings")->whereDate("due_date", ">=", Carbon::today())
            ->whereDate("start_date", "<=", Carbon::today())
            ->where("status", 0)
            ->where("group_id", $group_id)
            ->orderBy("due_date", "ASC")
            ->orderBy("priority", "DESC")->get();

            
            $tasks->stressLevel = $this->calculateStressLevel($tasks->todayTasks);

            //Retrieve upcomming tasks
            $tasks->upcomingTasks = Task::with("settings")->whereDate("due_date", ">=", Carbon::today())
            ->whereDate("start_date", ">", Carbon::today())
            ->where("status", 0)
            ->where("group_id", $group_id)
            ->orderBy("due_date", "ASC")
            ->orderBy("priority", "DESC")->get();

            //Retrieve completed tasks
            $tasks->completedTasks = Task::with("settings")->where("status", 1)->where("group_id", $group_id)->get();
        }

        //Group the task by priority
        $tasks->todayTasks = $tasks->todayTasks->groupBy("priority");
        $tasks->upcomingTasks = $tasks->upcomingTasks->groupBy("priority");
        $tasks->completedTasks = $tasks->completedTasks->groupBy("priority");
        return $tasks;
    }

    public function calculateStressLevel($tasks) {
        $stressLevel = 0;
        //Calculate Stress Level
        foreach ($tasks AS $task) {
          foreach (Task::TASK_PRIORITY_LEVEL AS $key => $percentage) {
                if ($key == $task->priority) {
                    $stressLevel += $percentage;
                }
           }
        }

        return round($stressLevel);
    }
}
