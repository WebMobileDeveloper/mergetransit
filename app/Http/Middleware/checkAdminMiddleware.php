<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class checkAdminMiddleware
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
      
        if(Auth::check()) {
            if($request->is("admin/*") && (Auth::user()->role == 0) || $request->is("sadmin/*") && (Auth::user()->role == 0)  )
            {
                return redirect(url("/"));
            } else if ($request->is("admin/*") && (Auth::user()->role == 4))  {
                return redirect(url("sadmin/"));
            } else if ($request->is("sadmin/*") && (Auth::user()->role != 4)) {
                return redirect(url("admin/"));
            }
        }
        return $next($request);
    }
}
