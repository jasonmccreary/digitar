<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function () {
            Alert::error('Je moet eerst inloggen om deze pagina te bekijken')->flash();

            return url('/');
        });
        $middleware->redirectUsersTo(function () {
            return match (auth()->user()->rights) {
                5 => url('/admin/organizations'),
                4 => url('/organization'),
                3 => url('/moderator'),
                2 => url('/client'),
                1 => url('/user'),
                default => url('/'),
            };
        });

        $middleware->alias([
            'folder' => App\Http\Middleware\FolderAccess::class,
            'users' => App\Http\Middleware\FolderAccess::class,
        ]);

        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $exception) {
            if ($exception->getCode() > 400 && ! in_array($exception->getCode(), [403, 404, 500])) {
                return response()->view('errors.default');
            }
        });
    })->create();
