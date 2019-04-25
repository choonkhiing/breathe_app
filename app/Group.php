<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Group extends Model
{
    protected $table = 'groups';

    public function members(){
    	return $this->hasMany('\App\GroupMember', 'group_id', 'id')->orderBy('type');
    }

    public function invitations($status = 0){
    	return $this->hasMany('\App\GroupInvitation', 'group_id', 'id')->where("status", $status);
    }

}