<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Routing\Exceptions\ThrottleRequestsException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException as HttpThrottleException;

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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception)
    {
        // ========== CUSTOM 429 (Rate Limit) RESPONSE ==========
        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'error' => 'Too many requests. Please wait a moment before trying again.',
                'retry_after' => $exception->getHeaders()['Retry-After'] ?? 60
            ], 429);
        }

        // ========== CUSTOM 404 RESPONSE (for API) ==========
        if ($exception instanceof NotFoundHttpException || $exception instanceof ModelNotFoundException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Resource not found.'
                ], 404);
            }
        }

        // ========== CUSTOM VALIDATION RESPONSE ==========
        if ($exception instanceof ValidationException && $request->expectsJson()) {
            return response()->json([
                'error' => 'Validation failed.',
                'errors' => $exception->errors()
            ], 422);
        }

        // ========== CUSTOM AUTHENTICATION RESPONSE ==========
        if ($exception instanceof AuthenticationException && $request->expectsJson()) {
            return response()->json([
                'error' => 'Unauthenticated. Please log in.'
            ], 401);
        }

        return parent::render($request, $exception);
    }
}