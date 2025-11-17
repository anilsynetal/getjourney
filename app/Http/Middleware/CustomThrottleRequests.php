<?php

namespace App\Http\Middleware;

use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class CustomThrottleRequests
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $maxAttempts = 3, $decayMinutes = 5)
    {
        $key = $this->resolveRequestKey($request);

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            $seconds = $this->limiter->availableIn($key);

            return redirect()->back()
                ->withInput($request->except('password')) // Keep old input except password
                ->withErrors(['email' => "Too many login attempts. Please try again after $seconds seconds."]);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        return $next($request);
    }

    protected function resolveRequestKey(Request $request)
    {
        return $request->ip();
    }
}
