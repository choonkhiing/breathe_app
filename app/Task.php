<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $dates = ['due_date'];
    const task_priority = [
    	"3" => "Low",
    	"2" => "Medium",
    	"1" => "High"
    ];

    const task_priority_class = [
    	"3" => "label-green",
    	"2" => "label-warning",
    	"1" => "label-danger"
    ];
}
