<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Auth;
use Carbon\Carbon;
use DB;

use \App\Task;
use \App\Setting;
use \App\Collection;
use \App\Group;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $group_id = $request->id;
        $cls = Collection::where("user_id", \Auth::user()->id)->get();
        $organizedTasks = $this->organizeTasks($group_id);
        $group = null;
        //Check if group id exist or not
        if ($group_id != 0 && $group_id != null) {
            $group = DB::table('groups')
            ->select('groups.*')
            ->join('group_members', 'groups.id', '=', 'group_members.group_id')
            ->where('groups.status', '=', 1)
            ->where('group_members.status', '=', 1)
            ->where('groups.id', '=', $group_id)
            ->where('group_members.user_id', '=', Auth::user()->id)
            ->first();
     
            if ($group == null) {
                $this::errorMessage("Group not found!");
                return redirect('/dashboard');
            }
        }
        
        return view("user/tasks/index", compact("organizedTasks", "cls", "group"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->priority = $request->priority;

            //Parse date accepted from front-end to MySQL acceptable date format
            $task->start_date = ($request->startdate) ? Carbon::createFromFormat('d/m/Y', $request->startdate) : null; 
            $task->due_date = ($request->duedate) ? Carbon::createFromFormat('d/m/Y', $request->duedate) : null; 
            
            $task->user_id = Auth::user()->id;

            $task->collection_id = $request->collection_id;

            $task->save();

            if ($request->reminderCheckbox == "on") {
                $setting = new Setting();
                $setting->task_id = $task->id;
                $setting->reminder_time = $request->reminder_time;
                $setting->day_before_remind = $request->day_before_remind;
                $setting->save();
            }

            $this::successMessage("New task added!");
        } catch (\Exception $e) {
            $this::errorMessage($e->getMessage());
        }
        return redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $task = Task::with("settings")->find($id);
            $task->shortStartDate = optional($task->start_date)->format('d/m/Y');
            $task->shortDueDate = optional($task->due_date)->format('d/m/Y');

            foreach (Task::TASK_PRIORITY_LEVEL AS $key => $percentage) {
                if ($key == $task->priority) {
                    $task->weightage = $percentage;
                }
            }

            return response()->json(['success' => true, 'data' => $task]);
        } catch (Exception $e) {
           return response()->json(['success' => false, 'msg' => $e->getMessage()]);
       }
   }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $task = Task::find($id);
            $task->title = $request->title;
            $task->description = $request->description;
            $task->priority = $request->priority;

            //Parse date accepted from front-end to MySQL acceptable date format
            $task->start_date = ($request->startdate) ? Carbon::createFromFormat('d/m/Y', $request->startdate) : null; 
            $task->due_date = ($request->duedate) ? Carbon::createFromFormat('d/m/Y', $request->duedate) : null; 
            
            $task->user_id = Auth::user()->id;

            $task->collection_id = $request->collection_id;

            $task->save();

            if ($request->reminderCheckbox == "on") {
                if (Setting::where("task_id", $task->id)->count() > 0) {
                    $setting = Setting::where("task_id", $task->id)->first();
                    $setting->reminder_time = $request->reminder_time;
                    $setting->day_before_remind = $request->day_before_remind;
                    $setting->save();
                }
                else {
                    $setting = new Setting();
                    $setting->task_id = $task->id;
                    $setting->reminder_time = $request->reminder_time;
                    $setting->day_before_remind = $request->day_before_remind;
                    $setting->save();
                }
            }

            $this::successMessage("Task updated!");
            return redirect::back();
        } catch (Exception $e) {
         $this::errorMessage($e->getMessage());
         return redirect::back();
     }
 }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $task = Task::find($id);
            $task->delete();

            $this::successMessage("Task deleted!");
            return response()->json(["success" => true]);
        } catch (Exception $e) {
            return response()->json(["success" => false, "msg" => $e->getMessage()]);
        }
    }

    public function completeTask($id)
    {
        try {
            $task = Task::find($id);
            $task->status = 1;
            $task->save();
            return response()->json(["success" => true]);
        } catch(Exception $e) {
            return response()->json(["success" => false, "msg" => $e->getMessage()]); 
        }
    }
}
