<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use DB;
use Redirect;
use File;
use Auth;
use \App\User;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
         try
        {
            $user = Socialite::driver("google")->stateless()->user();
            
            //if email is registered
            if(DB::table('users')->where('email', $user->email)->count())
            {
                if(DB::table('users')->where('google_id', $user->id)->count())
                {
                    $exists = User::where('email', $user->email)->where('google_id', $user->id)->first();

                    if(Auth::loginUsingId($exists->id))
                    {
                       if (Auth::user()->status == 0) {
                            Auth::logout();
                            return redirect::back()->with("error", "Your account is inactive.");
                        }
                        else {
                            return redirect('/dashboard');
                        }
                    }
                    else
                    {
                        return redirect::back()->with("error", "Unable to login with Google.");
                    }
                }
                else {
                    //update google id
                    $existingUser = User::where('email', $user->email)->first();

                    $existingUser->google_id = $user->id;
                    $existingUser->save();
                }
            }
            else {
                //Create a new user
                $new_user = new User();
                $new_user->email = $user->email;
                $new_user->name = $user->name;
                $new_user->password = '';
                $new_user->phone = '-';
                $new_user->google_id = $user->id;
                $new_user->status = 1;

                $fileContents = file_get_contents($user->getAvatar());
                File::put(public_path() . '/img/uploads/profile/' . $user->getId() . ".jpg", $fileContents);

                //To show picture 
                $new_user->profile_pic = ('img/uploads/profile/' . $user->getId() . ".jpg");

                $new_user->save();

                auth()->login($new_user);
                return redirect('/dashboard');
            }
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
            return redirect::back()->with("error", $e->getMessage());
        }
       
    }
}
