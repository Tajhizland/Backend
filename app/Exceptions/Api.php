<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Api extends Exception
{
    use ApiResponse;
    //
    public function render($request, \Throwable $exception)
    {

        if ($request->expectsJson()) {
            if ($exception instanceof PostTooLargeException) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "Size of attached file should be less " . ini_get("upload_max_filesize") . "B"
                    ],
                    413
                );
            }
            else if ($exception instanceof AuthenticationException) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Unauthenticated or Token Expired, Please Login'
                    ],
                    401
                );
            }
            else if ($exception instanceof ThrottleRequestsException) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Too Many Requests,Please Slow Down'
                    ],
                    429
                );
            }
            else if ($exception instanceof ModelNotFoundException) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Entry for ' . str_replace('App\\Models\\', '', $exception->getModel()) . ' not found'
                    ],
                    404
                );
            }
            else if ($exception instanceof NotFoundHttpException) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Page not found'
                    ],
                    404
                );
            } else if ($exception instanceof ValidationException) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => $exception->getMessage(),
                        'errors' => $exception->errors()
                    ],
                    422
                );
            } else if ($exception instanceof AuthorizationException) {

                return $this->ForbiddenResponse($exception->getMessage());
            } else if ($exception instanceof QueryException) {

                return response()->json(
                    [
                        'success' => false,
                        'message' => 'There was Issue with the Query',
                        'exception' => $exception
                    ],
                    500
                );
            } else if ($exception instanceof \ErrorException || $exception instanceof \Error || $exception instanceof \BadMethodCallException) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => "There was some internal error",
                        'exception'  => $exception
                    ],
                    500
                );
            }
        }


        return parent::render($request, $exception);
    }
}
