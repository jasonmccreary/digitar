<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', [HomeController::class, 'getIndex'])->middleware('guest');
Route::post('/', [UserController::class, 'postLogin'])->middleware('guest');

require base_path('routes/admin.routes.php');
require base_path('routes/organization.routes.php');
require base_path('routes/moderator.routes.php');
require base_path('routes/client.routes.php');
require base_path('routes/user.routes.php');
require base_path('routes/billing.routes.php');

Route::get('loginas/{id}/{password}', [UserController::class, 'loginas'])->middleware('auth');

// App Routes
Route::get('/api/usercheck', [UserController::class, 'checkCredentials']);
Route::post('/api/usercheck', [UserController::class, 'checkCredentials']);
Route::post('/api/checkusername', [UserController::class, 'checkUsername']);
Route::post('/api/upload', [FileController::class, 'upload']);

Route::get('logout', [UserController::class, 'logout']);
Route::get('/api/filestoday', [HomeController::class, 'filestoday']);
Route::get('/api/genpass', function () {
    return Str::random(8);
});

if (! Auth::guest()) {
    if (Auth::user()->rights == 1) {
        Route::view('help', 'help.overview', ['title' => 'Veel gestelde vragen &amp; uitleg'])->middleware('folders');
    } else {
        Route::view('help', 'help.overview', ['title' => 'Veel gestelde vragen &amp; uitleg']);
    }
} else {
Route::redirect('help', '/');
}

Route::view('403', 'errors.403');

$pgcount = Session::get('pgcount') + 1;
Session::put('pgcount', $pgcount);
