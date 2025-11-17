<?php

namespace App\Http\Middleware;

use Session;
use Closure;
use Illuminate\Http\Request;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (($request->user()->role == 'Admin' || $request->user()->role == 'User')) {
            return $next($request);
        }
        Session::flush();
        abort(403, 'Unauthorized action.');
    }
}
