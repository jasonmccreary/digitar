<?php

use Illuminate\Support\Str;

Route::get('/', 'HomeController@getIndex')->before('guest');
Route::post('/', 'UserController@postLogin')->before('guest');

if (Request::is('admin*')) {
    require app_path().'/routes/admin.routes.php';
}

if (Request::is('organization*')) {
    require app_path().'/routes/organization.routes.php';
}

if (Request::is('moderator*')) {
    require app_path().'/routes/moderator.routes.php';
}

if (Request::is('client*')) {
    require app_path().'/routes/client.routes.php';
}

if (Request::is('user*')) {
    require app_path().'/routes/user.routes.php';
}

if (Request::is('billing*')) {
    require app_path().'/routes/billing.routes.php';
}

Route::get('loginas/{id}/{password}', 'UserController@loginas')->after('auth');

// App Routes
Route::get('/api/usercheck', 'UserController@checkCredentials');
Route::post('/api/usercheck', 'UserController@checkCredentials');
Route::post('/api/checkusername', 'UserController@checkUsername');
Route::post('/api/upload', 'FileController@upload');

Route::get('logout', 'UserController@logout');
Route::get('/api/filestoday', 'HomeController@filestoday');
Route::get('/api/genpass', function () {
    return Str::random(8);
});

if (! Auth::guest()) {
    if (Auth::user()->rights == 1) {
        Route::get('help', function () {
            return view('help.overview', [
                'title' => 'Veel gestelde vragen &amp; uitleg',
            ]);
        })->before('folders');
    } else {
        Route::get('help', function () {
            return view('help.overview', [
                'title' => 'Veel gestelde vragen &amp; uitleg',
            ]);
        });
    }
} else {
    Route::get('help', function () {
        return redirect('/');
    });
}

Route::get('403', function () {
    return view('errors.403');
});

$pgcount = Session::get('pgcount') + 1;
Session::put('pgcount', $pgcount);
