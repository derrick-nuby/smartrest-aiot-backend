<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $e): Response
    {
        if ($request->expectsJson()) {

            if ($e instanceof ValidationException) {
                Log::error('Validation failed', ['errors' => $e->errors()]);

                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }

            if ($e instanceof HttpExceptionInterface) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'HTTP error',
                ], $e->getStatusCode());
            }

            return response()->json([
                'message' => 'Server Error',
            ], 500);
        }

        return parent::render($request, $e);
    }
}
