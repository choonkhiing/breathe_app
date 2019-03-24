<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $dates = ['start_date', 'due_date'];
    const TASK_PRIORITY = [
    	"3" => "Low",
    	"2" => "Medium",
    	"1" => "High"
    ];

    const TASK_PRIORITY_CLASS = [
    	"3" => "label-green",
    	"2" => "label-warning",
    	"1" => "label-danger"
    ];

    public function getCollection(){
        return $this->hasOne('App\Collection', 'id', 'collection_id');
    }
}
