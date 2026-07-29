<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(function (Request $request): string {
            $user = $request->user();

            if (! $user) {
                return route('home');
            }

            return $user->isAdmin()
                ? route('admin.dashboard')
                : route('parent.dashboard');
        });

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (PostTooLargeException $exception, Request $request) {
            $message = 'Total ukuran file terlalu besar. Pastikan setiap file maksimal 2 MB.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            $previousUrl = url()->previous();
            $separator = str_contains($previousUrl, '?') ? '&' : '?';

            return redirect()->to($previousUrl.$separator.'upload_error=too_large')
                ->with('error', $message);
        });
    })->create();
