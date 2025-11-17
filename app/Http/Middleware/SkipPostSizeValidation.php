<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SkipPostSizeValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Temporarily increase PHP limits for file uploads
        ini_set('post_max_size', '50M');
        ini_set('upload_max_filesize', '20M');
        ini_set('memory_limit', '512M');

        // Skip the ValidatePostSize middleware check by not calling it
        // This middleware should run BEFORE ValidatePostSize in the stack

        return $next($request);
    }
}
