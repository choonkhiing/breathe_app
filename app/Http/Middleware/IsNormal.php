<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class IsNormal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->type == 0) {
            return $next($request);
        }

        return redirect('/admin/users');
    }
}
