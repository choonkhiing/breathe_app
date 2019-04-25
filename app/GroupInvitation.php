<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupInvitation extends Model
{
    protected $table = 'groupinvitations';

    public function getInviter(){
    	return $this->hasOne('\App\User', 'id', 'inviter');
    }

    public function getInvitee(){
    	return $this->hasOne('\App\User', 'id', 'invitee');
    }

    public function getGroup(){
    	return $this->hasOne('\App\Group', 'id', 'group_id');
    }
}
