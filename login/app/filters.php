<?php

use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Facades\Request;

Validator::extend('alpha_space', function ($attr, $value) {
    return preg_match('/^[A-Za-z0-9_\- ]+$/', $value);
});

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request) {
    //
});

App::after(function ($request, $response) {
    //
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('user', function () {
    $params = Route::current()->parameters();
    if (isset($params['id'])) {
        $did = User::where('id', '=', $params['id'])->first();
        $cu = Auth::user();
        if ($did->rights >= $cu->rights || $did->rights >= $cu->rights && $did->cid != $cu->cid) {
            return view('blank', [
                'title' => 'We hebben een probleem!',
                'content' => 'U hebt niet genoeg rechten om dit te doen.',
            ]);
        }
    }
});

Route::filter('folder', function () {
    $params = Route::current()->parameters();
    if (isset($params['id'])) {
        $did = Folder::where('id', '=', $params['id'])->first();
        if ($did->uid != Auth::user()->id) {
            return view('blank', [
                'title' => 'We hebben een probleem!',
                'content' => 'U hebt niet genoeg rechten om dit te doen.',
            ]);
        }
    }
});

Route::filter('auth', function () {
    if (Auth::guest()) {
        Alert::error('Je moet eerst inloggen om deze pagina te bekijken')->flash();

        return Redirect::guest('/');
    }

    switch (Auth::user()->rights) {
        case 5:
            if (! Request::is('admin*')) {
                return redirect('/admin/organizations');
            }
            break;
        case 4:
            if (! Request::is('organization*')) {
                return redirect('/organization');
            }
            break;
        case 3:
            if (! Request::is('moderator*')) {
                return redirect('/moderator');
            }
            break;
        case 2:
            if (! Request::is('client*')) {
                return redirect('/client');
            }
            break;
        case 1:
            if (! Request::is('user*') && ! Request::is('billing*')) {
                return redirect('/user');
            }
            break;
        default:
            return redirect('/');
            break;
    }
});

Route::filter('folders', function () {
    View::share('aFolders', Folder::getAllUserFolders());
});

Route::filter('auth.basic', function () {
    return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function () {
    if (Auth::check()) {
        return redirect('/admin');
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Request::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
