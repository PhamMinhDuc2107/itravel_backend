<?php

use App\Exceptions\BaseException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (BaseException $exception, Request $request) {
            $response = [
                'success' => false,
                'message' => $exception->getMessage(),
                'errors' => $exception->getErrors(),
                'status_code' => $exception->getStatusCode(),
            ];

            if (App::environment(['local', 'testing'])) {
                $response['debug'] = [
                    'exception' => get_class($exception),
                    'trace' => $exception->getTrace(),
                ];
            }

            return response()->json($response, $exception->getStatusCode());
        });
    })->create();
