<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $table = 'group_members';

    public function user(){
    	return $this->hasOne('\App\User', 'id', 'user_id');
    }
}
