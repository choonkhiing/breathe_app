<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Auth;
use Carbon\Carbon;

use \App\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::orderBy("due_date", "ASC")
        ->orderBy("priority", "ASC")
        ->get();

        $tasks = $tasks->groupBy("priority");

        return view("user.tasks.index", compact("tasks"));
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
            $task = Task::find($id);
            $task->shortStartDate = optional($task->start_date)->format('d/m/Y');
            $task->shortDueDate = optional($task->due_date)->format('d/m/Y');
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
}
