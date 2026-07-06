<?php

use App\Http\Middleware\Admin;
use App\Http\Middleware\CheckUserEnabled;
use App\Http\Middleware\Guest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->group(base_path('routes/backend.php'));

            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->group(base_path('routes/admin_setting.php'));

            Route::middleware(['web'])
                ->group(base_path('routes/frontend.php'));
            Route::middleware(['web', 'auth', 'admin'])
                ->prefix('admin')
                ->group(base_path('routes/cms.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'   => Admin::class,
            'guest'   => Guest::class,
            'enabled' => CheckUserEnabled::class,
        ]);

        // Ensure web middleware group includes CSRF protection
        $middleware->web(append: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);

        $middleware->appendToGroup('api', [
            EnsureFrontendRequestsAreStateful::class,
        ]);

        // CSRF token validation exceptions
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'api/v1/webhooks/stripe',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => __('Unauthenticated'),
                ], 401);
            }
            return redirect()->route('login');
        });

        $exceptions->render(function (ValidationException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status'  => false,
                    'message' => $e->errors() ? collect($e->errors())->flatten()->first() : $e->getMessage(),
                    'data'    => null,
                    'code'    => 422,
                ], 422);
            }
        });
    })
    ->withSchedule(function (Schedule $schedule) {
        // $schedule->job(new SyncShopifyProductsJob())->dailyAt('02:00');

    })
    ->create();
