<?php

Route::get('moderator', function () {
    return redirect('/moderator/users');
})->middleware('auth');
Route::get('moderator/users', function () {
    $users = new Usermods;
    $u = Usermods::join('users', 'users.id', '=', 'usermods.uid');
    $u->where('users.rights', '=', '2');
    $u->where('users.oid', '=', Auth::user()->oid);
    $u->where('usermods.modid', '=', Auth::user()->id);

    $users = $u->get();

    if ($u->count() > 0) {
        $aUsers = $u->get();
    } else {
        $aUsers = [];
    }

    return view('moderator.users.overview', [
        'title' => 'Gebruikers',
        'users' => $aUsers,
    ]);
})->middleware('auth');
