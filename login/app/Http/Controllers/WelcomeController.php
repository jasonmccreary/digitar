<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class WelcomeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'guest',
        ];
    }

    /**
     * Show the application welcome screen to the user.
     */
    public function index(): View
    {
        return view('welcome');
    }
}
