<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip redirection if the request is for an installer route
        if ($request->is('install*')) {
            if (file_exists(storage_path('installed'))) {
                return redirect()->route('root'); // Ensure this route is correct
            }
            return $next($request);
        }
        if (!file_exists(storage_path('installed'))) {
            return redirect()->route('installer.welcome'); // Ensure this route is correct
        }
        return $next($request);
    }
}
