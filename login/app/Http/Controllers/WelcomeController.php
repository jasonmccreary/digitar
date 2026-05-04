<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Attributes\Controllers\Middleware;
use Illuminate\View\View;

#[Middleware('guest')]
class WelcomeController extends Controller
{
    /**
     * Show the application welcome screen to the user.
     */
    public function index(): View
    {
        return view('welcome');
    }
}
