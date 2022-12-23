<?php

namespace App\Http\Middleware;
use Auth;
use Closure;

class Admin
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
        if(empty(Auth::user())){
            return redirect('/login');
        }

        if(Auth::user()->type !== 'admin') {
            return redirect('/');
        }
        return $next($request);
    }
}
