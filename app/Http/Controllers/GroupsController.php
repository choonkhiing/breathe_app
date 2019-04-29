<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\User;
use \App\Group;
use \App\GroupMember;
use \App\GroupInvitation;
use \App\Task;

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
        return view("user.tasks.groups");
    }

    public function validateEmail(Request $request)
    {
        $success = true;
        $msg = "";

        $user = User::where("email", $request->email)->where("id", "!=", Auth::user()->id)->where("status", 1)->first();
        if(!empty($request->group_id) && !empty($user)){
            $inv = GroupInvitation::where("invitee", $user->id)->where("group_id", $request->group_id)->where("status", ">" ,-1)->orderBy('id', 'DESC')->first();

            //Check if user is a group member
            $gm = GroupMember::where("group_id", $request->group_id)->where("user_id", $user->id)->first();

            if(!empty($inv)){
                if($inv->status == 0){
                    $msg = "You have already sent an invitation to " . $user->email;
                } else if($inv->status == 1 && $gm) {
                    $msg = "User has already been added to this group.";
                } else{
                    $msg = "";
                }
            }
        } else if (empty($user)) {
            if($request->email == Auth::user()->email) {
                $msg = "Unable to invite yourself to the group!";
            } else {
                $msg = $request->email . " is not registered in Breathe.";
            }
        }


        if(empty($user) || !empty($gm) || (!empty($inv) && $inv->status > -1 && !empty($gm))){
            $success = false;
        }

        return response()->json(['success' => $success, 'msg' => $msg ]);
    }

    public function viewDetails($id)
    {
        try {
            $group = Group::findOrFail($id);

            $gm = GroupMember::where("user_id", Auth::user()->id)->where("group_id", $id)->first();

            if(empty($gm)){
                $this::errorMessage("You do not belong in this group!");
                 return redirect('/dashboard');
            }

            return view("user.groupdetails", compact("group"));
        } catch (Exception $e) {
            return redirect::back();
        }
    }

    public function getGroupMember($id)
    {
        try {
            $groupMember = GroupMember::findOrFail($id);
            $groupMember->memberName = $groupMember->user->name;
            return response()->json(['success' => true, 'member' => $groupMember]);
        } catch (Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    public function updateGroupMember(Request $request)
    {
        try {
            $groupMember = GroupMember::findOrFail($request->gmID);
            $groupMember->type = $request->type;
            $groupMember->save();
            return response()->json(['success' => true, 'member' => $groupMember]);
        } catch (Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    public function removeGroupMember(Request $request)
    {
        try {
            $groupMember = GroupMember::findOrFail($request->gmID);

            $username = $groupMember->user->name;

            // Remove all tasks in this group by this user
            $tasks = Task::where("group_id", $groupMember->group_id)
            ->where("user_id", $groupMember->user_id)
            ->delete();

            $groupMember->delete();

            return response()->json(['success' => true, 'username' => $username]);
        } catch (Exception $e) {
            return response()->json(['success' => false]);
        }
    }

    public function inviteGroupMember(Request $request)
    {
        try {
            $group = Group::findOrFail($request->groupID);

            foreach($request->invitation AS $email){
                $user = User::where("email", $email)->first();
                $inv = new GroupInvitation();
                $inv->inviter = Auth::user()->id;
                $inv->invitee = $user->id;
                $inv->group_id = $group->id;
                $inv->save();
            }
            $this::successMessage("Invitation to this group has been sent out!");
            return redirect::back();
        } catch (Exception $e) {
            $this::errorMessage($e->getMessage());
            return redirect::back();
        }
    }

    public function leaveGroup($id)
    {
        try {
            $group = Group::findOrFail($id);

            $tasks = Task::where("user_id", Auth::user()->id)->where("group_id", $id)->delete();

            $groupmember = GroupMember::where("user_id", Auth::user()->id)->where("group_id", $id)->delete();

            $this::successMessage("You have left " . $group->title);
            return redirect('/dashboard');
        } catch (Exception $e) {
            $this::errorMessage($e->getMessage());
            return redirect::back();
        }
    }
    
    public function store(Request $request)
    {
      try {
        $group = new Group();
        $group->title = $request->title;
        $group->description = $request->description ?? '';
        $group->created_by = Auth::user()->id;
        $group->save();

        $group_member = new GroupMember();
        $group_member->group_id = $group->id;
        $group_member->user_id = Auth::user()->id;
        $group_member->type = "admin";
        $group_member->status = 1;
        $group_member->save();

        foreach($request->invitation AS $email){
            $user = User::where("email", $email)->first();
            $inv = new GroupInvitation();
            $inv->inviter = Auth::user()->id;
            $inv->invitee = $user->id;
            $inv->group_id = $group->id;
            $inv->save();
        }

            // dd($request->invitation);


        $this::successMessage("New group added!");
    } catch (\Exception $e) {
        $this::errorMessage($e->getMessage());
    }
    return redirect::back();
}
}
