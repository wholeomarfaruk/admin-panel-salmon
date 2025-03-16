<?php

use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',

        health: '/up',

        then: function ($router) {


            Route::prefix('api/v1')
                ->middleware('api')
                ->name('api.v1.')
                ->group(base_path('routes/api.php'));




            // Route::prefix('api/v2')
            //     ->middleware('api')
            //     ->name('api.v2.')
            //     ->group(base_path('routes/auth.php'));

        }
    )
    ->withMiddleware(function (Middleware $middleware) {

        //-------------------------------------------
        //Admin Middleware Start
       
        $middleware->alias(['admin' => Admin::class]);
        //Admin Middleware End
        //-------------------------------------------

        // $middleware->appendToGroup('api', [
        //     \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        //     'throttle:api',
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
