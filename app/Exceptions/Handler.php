<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {

                if ($e instanceof ValidationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed.',
                        'errors' => $e->errors()
                    ], 422);
                }

                if ($e instanceof ModelNotFoundException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Resource not found.'
                    ], 404);
                }

                if ($e instanceof NotFoundHttpException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'API route not found.'
                    ], 404);
                }

                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Unauthenticated.'
                    ], 401);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Server error.',
                    'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong.'
                ], 500);
            }
        });
    }
}
