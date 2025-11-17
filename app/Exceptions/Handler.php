<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {
            return redirect()->back()
                ->withInput($request->except('password')) // Retain input except password
                ->withErrors([
                    'email' => 'Too many login attempts. Please try again after ' . ($exception->getHeaders()['Retry-After'] ?? 'a few') . ' seconds.'
                ]);
        }

        return parent::render($request, $exception);
    }
}
