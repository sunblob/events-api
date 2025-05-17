<?php

use App\Exceptions\NotFoundError;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        })->renderable(function (Throwable $e) {
            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], 422);
            }


            if ($e instanceof NotFoundError) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'error_line' => $e->getFile() . ':' . $e->getLine(),
                ], $e->getCode());
            }

            return response()->json([
                'message' => $e->getMessage(),
                'exception' => class_basename($e),
                'error_line' => $e->getFile() . ':' . $e->getLine(),
            ], 500);
        });
    })->create();
