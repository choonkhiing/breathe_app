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

    public function getCollection(){
        return $this->hasOne('App\Collection', 'id', 'collection_id');
    }
}
