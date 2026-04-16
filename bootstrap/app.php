<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware stack
        $middleware->web(append: [
            \App\Http\Middleware\DetectStore::class,
            \App\Http\Middleware\SetLanguage::class,
            \App\Http\Middleware\SetCurrency::class,
            // \App\Http\Middleware\IpBlocker::class, // Temporarily disabled
        ]);
        
        // Exclude PayPal callback routes from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'Payments/paypal_ipn/*',
            'Payments/paypal_success/*',
            'Payments/paypal_cancel/*',
            'admin/Products/searchProduct',
            'admin/*/searchProduct',
            '*/searchProduct',
            'admin/Products/AttributesMap',
            'admin/Products/AttributeItemsMap/*',
            'admin/Products/attributeUpdate',
            'admin/Products/attributeDeletePost',
            'admin/Products/attributeCreateMap',
            'admin/Products/attributeItemCreateMap',
            'admin/Products/attributeItemUpdateMap',
            'admin/Products/attributeItemDeleteMap',
        ]);
        
        // Route-specific middleware aliases
        $middleware->alias([
            'public.auth' => \App\Http\Middleware\PublicAuth::class,
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
            'admin.permissions' => \App\Http\Middleware\CheckAdminPermissions::class,
            'csrf.except' => \App\Http\Middleware\VerifyCsrfTokenExcept::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
