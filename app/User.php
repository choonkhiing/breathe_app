<?php

namespace App;

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
}
