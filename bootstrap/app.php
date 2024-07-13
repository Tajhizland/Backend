<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            App\Http\Middleware\TransactionMiddleware::class,
            App\Http\Middleware\Fa2EnMiddleware::class
        ]);
        $middleware->api(append: [
            App\Http\Middleware\TransactionMiddleware::class,
            App\Http\Middleware\Fa2EnMiddleware::class
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
        $exceptions->render(function (ValidationException $exception, Request $request) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $exception->getMessage(),
                    'errors' => $exception->errors()
                ],
                422
            );
        });
        $exceptions->render(function (NotFoundHttpException $exception, Request $request) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'ضفحه مورد نظر یافت نشد'
                ],
                404
            );
        });

        $exceptions->render(function (ThrottleRequestsException $exception, Request $request) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'خطا . تعداد درخواست ها زیاد شده است !',
                    'exception' => $exception
                ],
                429
            );
        }); $exceptions->render(function (AuthorizationException $exception, Request $request) {
            return response()->json(
                [
                    'success' => false,
                    'message' => "شما دسترسی لازم را ندارید",
                    'exception' => $exception
                ],
                403
            );
        });
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'توکن شما منقضی شده است . لطفا دوباره لاگین کنید .',
                    'exception' => $exception->getMessage()
                ],
                401
            );
        });
        $exceptions->render(function (Error $exception, Request $request) {
            return response()->json(
                [
                    'success' => false,
                    'message' => "خطای سمت سرور رخ داد .",
                    'exception' => $exception->getMessage()
                ],
                500
            );
        });
     })->create();
