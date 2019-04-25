<?php

namespace App;

use DB;
use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getStatus(){
        if($this->status == 1){
            $class = "badge-success";
            $status = "active";
        } else {
            $class = "badge-warning";
            $status = "deactivated";
        }

        return '<span class="badge ' . $class . '">' . $status . '</span>';
    }

    public function getInvitations(){
        return $this->hasMany("\App\GroupInvitation", "invitee", "id")->where("status", 0);
    }

    // public function groups(){
    //     return $this->hasMany("\App\GroupMember", "user_id", "id")->where("GroupMember.status", 1);
    // }

    public function groups()
    {
        $groups = DB::table('groups')
        ->select('groups.*')
        ->join('group_members', 'groups.id', '=', 'group_members.group_id')
        ->where('groups.status', '=', 1)
        ->where('group_members.status', '=', 1)
        ->where('group_members.user_id', '=', Auth::user()->id)
        ->get(); 

        return $groups;
    }
}
