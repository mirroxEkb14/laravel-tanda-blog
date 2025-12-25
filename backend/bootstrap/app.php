<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Returns consistent JSON like exceptions for API requests only (not HTML error / default Laravel debug pages).
 * Invalid input returns "400 Bad Request" (not "422 Unprocessable Entity").
 * For any other than 400, 403, 404 errors, it returns 'Server error'.
 * 500 errors are logged, client gets generic "Server error" message.
 */
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            $isApi = $request->is('api/*') || $request->expectsJson();
            if (! $isApi) {
                return null;
            }
            if ($e instanceof ValidationException) {
                return response()->json([
                    'errors' => $e->errors(),
                ], 400);
            }
            if ($e instanceof AuthorizationException) {
                return response()->json(['error' => 'Forbidden'], 403);
            }
            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                return response()->json(['error' => 'Not found'], 404);
            }
            if ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                return response()->json([
                    'error' => $status === 403 ? 'Forbidden' : 'Server error',
                ], $status);
            }
            report($e);
            return response()->json(['error' => 'Server error'], 500);
        });
    })->create();
