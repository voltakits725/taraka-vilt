<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Redirect unauthenticated users ke halaman login pelanggan
        $middleware->redirectGuestsTo(fn (Request $request) => 
            $request->is('admin/*') ? route('login') : route('customer.login')
        );

        // Redirect authenticated users yang akses halaman guest
        $middleware->redirectUsersTo(function (Request $request) {
            $role = $request->user()?->role;
            if (in_array($role, ['barista', 'admin'])) return '/admin/orders';
            if ($role === 'owner') return '/admin/dashboard';
            return '/';
        });

        // Kecualikan route webhook Midtrans dari pengecekan CSRF
        $middleware->validateCsrfTokens(except: [
            'api/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

