<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    protected $dates = ['start_date', 'due_date'];

    const TASK_PRIORITY = [
        "1" => "High",
        "2" => "Medium",
    	"3" => "Low"
    ];

    const TASK_PRIORITY_CLASS = [
        "1" => "label-danger",
        "2" => "label-warning",
    	"3" => "label-green"
    ];

    const TASK_PRIORITY_LEVEL = [
        "1" => 33.33,
        "2" => 20,
        "3" => 12.5
    ];

    public function getCollection(){
        return $this->hasOne('App\Collection', 'id', 'collection_id');
    }

    public function settings(){
        return $this->hasOne('App\Setting', 'task_id', 'id');
    }

    public function group(){
        return $this->hasOne('App\Group', 'id', 'group_id');
    }
}
