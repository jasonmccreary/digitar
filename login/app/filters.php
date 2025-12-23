<?php

use App\Models\Folder;
use App\Models\User;
use Illuminate\Support\Facades\Request;

Validator::extend('alpha_space', function ($attr, $value) {
    return preg_match('/^[A-Za-z0-9_\- ]+$/', $value);
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
                return redirect()->to('/admin/organizations');
            }
            break;
        case 4:
            if (! Request::is('organization*')) {
                return redirect()->to('/organization');
            }
            break;
        case 3:
            if (! Request::is('moderator*')) {
                return redirect()->to('/moderator');
            }
            break;
        case 2:
            if (! Request::is('client*')) {
                return redirect()->to('/client');
            }
            break;
        case 1:
            if (! Request::is('user*') && ! Request::is('billing*')) {
                return redirect()->to('/user');
            }
            break;
        default:
            return redirect()->to('/');
            break;
    }
});

Route::filter('folders', function () {
    View::share('aFolders', Folder::getAllUserFolders());
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
        return redirect()->to('/admin');
    }
});
