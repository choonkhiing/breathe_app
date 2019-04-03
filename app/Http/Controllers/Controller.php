<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function successMessage($message){
    	Session::flash("type", "success");
    	Session::flash("message", $message);
    }

    public function warningMessage($message){
    	Session::flash("type", "warning");
    	Session::flash("message", $message);
    }  

    public function errorMessage($message){
    	Session::flash("type", "danger");
    	Session::flash("message", $message);
    }  

    public function calStressLevel($used_hour, $max_hour) {
        if ($max_hour == 0) {
            $stressLevel = 0;
        }
        else {
            $stressLevel = $used_hour / $max_hour * 100;
        }

        return ceil($stressLevel);
    }
}
