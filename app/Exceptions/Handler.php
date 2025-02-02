<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function register()
    {
        $this->reportable(function (\Throwable $e) {
            //
        });

        $this->renderable(function (\Throwable $exception, $request) {
            if ($request->is('api/*')) {
                if ($exception instanceof NotFoundHttpException) {
                    $statusCode = $exception->getStatusCode() ?: 404;
                    $message = str_contains($exception->getMessage(), 'The route') ? 'Endpoint not found.' : (str_contains($exception->getMessage(), 'No query results') ? str_replace(']', '', last(explode('\\', $exception->getMessage()))) . ' not found.' : $exception->getMessage());
                    return response()->json([
                        'code' => $statusCode,
                        'message' => $message
                    ], $statusCode);
                } elseif ($exception instanceof ValidationException) {
                    $statusCode = $exception->status ?: 422;
                    return response()->json([
                        'code' => $statusCode,
                        'message' => 'The given data was invalid',
                        'errors' => $exception->errors()
                    ], $statusCode);
                } elseif ($exception instanceof AuthenticationException) {
                    return response()->json([
                        'code' => 401,
                        'message' => $exception->getMessage()
                    ], 401);
                } elseif ($exception instanceof QueryException) {
                    return response()->json([
                        'code' => 500,
                        'message' => $exception->getMessage(),
                    ], 500);
                } elseif ($exception instanceof AuthorizationException || $exception instanceof AccessDeniedHttpException) {
                    $statusCode = $exception->getStatusCode() ?: 403;
                    return response()->json([
                        'code' => $statusCode,
                        'message' => $exception->getMessage(),
                    ], $statusCode);
                } elseif ($exception instanceof BadRequestHttpException || $exception instanceof BadRequestHttpException) {
                    $statusCode = $exception->getStatusCode() ?: 400;
                    $message = $exception->getMessage() ?: 'Invalid request';
                    return response()->json([
                        'code' => $statusCode,
                        'message' => $message
                    ], $statusCode);
                } elseif ($exception instanceof HttpException) {
                    $statusCode = $exception->getStatusCode() ?: 400;
                    $message = $exception->getMessage() ?: 'Invalid request';
                    return response()->json([
                        'code' => $statusCode,
                        'message' => $message
                    ], $statusCode);
                } elseif ($exception instanceof ErrorException) {
                    $statusCode = $exception->getCode() ?: 500;
                    $message = $exception->getMessage() ?: 'Failed to get service';
                    return response()->json([
                        'code' => $statusCode,
                        'message' => $message,
                    ], $statusCode);
                }
            }
        });
    }
}
