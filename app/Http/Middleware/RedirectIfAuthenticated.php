<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::check()) {
           
                     
            if (Auth::user()->role != 0 &&  Auth::user()->role != 4) {
               
                return redirect('/admin/home');
            } else if (Auth::user()->role == 4){
                
                return redirect('/sadmin/home');
            }
            return redirect('/');
        }

        
        return $next($request);
    }
}
