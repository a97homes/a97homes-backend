<?php

use App\Exceptions\NotFoundHttpExceptionRenderer;
use App\Exceptions\UnauthorizedExceptionRenderer;
use App\Exceptions\UniqueConstraintViolationExceptionRendor;
use App\Http\Middleware\SetLanguageMiddleware;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware(['api', SetLanguageMiddleware::class])->prefix('api/v1')->group(base_path('routes/v1/api.php'));
            Route::middleware(['api', 'auth:sanctum', SetLanguageMiddleware::class])->prefix('api/admin/v1')->group(base_path('routes/v1/admin.php'));
            Route::middleware(['api', 'auth:sanctum', SetLanguageMiddleware::class])->prefix('api/owner/v1')->group(base_path('routes/v1/user.php'));
            Route::middleware(['web'])->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        if (request()->is('api/*')) {

            $exceptions->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e) {
                return (new UnauthorizedExceptionRenderer)->handle($e);
            });
            $exceptions->renderable(function (UniqueConstraintViolationException $e) {
                return (new UniqueConstraintViolationExceptionRendor)->handle($e);
            });
            $exceptions->renderable(function (NotFoundHttpException $e) {
                return (new NotFoundHttpExceptionRenderer)->handle($e);
            });

        }
    })->create();
