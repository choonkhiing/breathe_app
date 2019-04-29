<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Auth;

use \App\Collection;
use \App\Task;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Collection::where("user_id", Auth::user()->id)->get();
        return view("user.collections.index", compact("collections"));
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
        $cl = new Collection();
        $cl->title = $request->title;
        $cl->description = $request->description;
        $cl->user_id = \Auth::user()->id;
        $cl->save();

        $this::successMessage("New collection added!");
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
            $collection = Collection::findOrFail($id);
            return response()->json(['success' => true, 'data' => $collection]);
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
            $collection = Collection::find($id);
            $collection->title = $request->title;
            $collection->description = $request->description;
            $collection->save();
            $this->successMessage("Successfully edited collection.");
            return redirect::back();
        } catch (Exception $e) {
            $this->errorMessage("Unable to edit collection. " . $e->getMessage());
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
        // Remove collections from tasks
        $task = Task::where("collection_id", $id)->update(['collection_id' => null]);
        $collection = Collection::find($id)->delete();
        return response()->json(['success' => true, 'msg' => "Successfully deleted collection."]);
    }
}
