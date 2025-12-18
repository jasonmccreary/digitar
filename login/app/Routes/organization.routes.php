<?php

Route::get('organization/superlogin', function () {
    $u = new User;

    return view('login.superlogin', [
        'superlogin' => 'true',
    ]);
})->before('auth');
Route::post('organization/supersearch', 'UserController@supersearch')->before('auth');

Route::get('organization', function () {
    return redirect('/organization/clients');
})->before('auth');
Route::get('organization/clients', function () {
    $users = new User;
    $u = $users->where('rights', '=', '2')->where('oid', '=', Auth::user()->id)->get();
    if ($u->count() > 0) {
        $aUsers = $u;
    } else {
        $aUsers = [];
    }

    return view('organization.clients.overview', [
        'title' => 'Klanten',
        'users' => $aUsers,
    ]);
})->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Add new Client
 |--------------------------------------------------------------------------
*/
Route::get('organization/client/add', function () {

    $sfolders = Folder::where('uid', '=', Auth::user()->id)->whereNull('pid')->get();

    return view('organization.clients.add', [
        'title' => 'Klant toevoegen',
        'sfolders' => $sfolders,
    ]);
})->before('auth');
Route::post('organization/client/add', [
    'before' => 'auth',
    'uses' => 'UserController@addClient',
]);
/*
 |--------------------------------------------------------------------------
 |	Edit a Client
 |--------------------------------------------------------------------------
*/
Route::get('organization/client/edit/{id}', function ($id) {

    $u = new User;
    $user = $u->find($id);

    $sfolders = Folder::where('uid', '=', Auth::user()->id)->whereNull('pid')->get();

    return view('organization.clients.edit', [
        'title' => 'Klant bewerken',
        'user' => $user,
        'sfolders' => $sfolders,
    ]);
})->before('auth')->before('user');
Route::post('organization/client/edit/{id}', 'UserController@editClient')->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Delete a Client
 |--------------------------------------------------------------------------
*/
Route::get('organization/client/delete/{id}', function ($id) {
    $u = new User;
    $user = $u->find($id);

    return view('organization.clients.delete', [
        'title' => 'Klant verwijderen',
        'user' => $user,
    ]);
})->before('auth|user');
Route::post('organization/client/delete/{id}', 'UserController@deleteClient')->before('auth');

/*
 |--------------------------------------------------------------------------
 |	MODERATORS SECTION BELOW
 |--------------------------------------------------------------------------
*/

Route::get('organization/moderators', function () {
    $users = new User;
    $u = $users->where('rights', '=', '3')->where('oid', '=', Auth::user()->id)->get();
    if ($u->count() > 0) {
        $aUsers = $u;
    } else {
        $aUsers = [];
    }

    return view('organization.moderator.overview', [
        'title' => 'Beheerders',
        'users' => $aUsers,
    ]);
})->before('auth');
/*
    |--------------------------------------------------------------------------
    |	Add new moderator
    |--------------------------------------------------------------------------
   */
Route::get('organization/moderator/add', function () {
    return view('organization.moderator.add', [
        'title' => 'Beheerder toevoegen',
    ]);
})->before('auth');
Route::post('organization/moderator/add', [
    'before' => 'auth',
    'uses' => 'UserController@addModerator',
]);
/*
 |--------------------------------------------------------------------------
 |	Edit a moderator
 |--------------------------------------------------------------------------
*/
Route::get('organization/moderator/edit/{id}', function ($id) {

    $u = new User;
    $user = $u->find($id);

    return view('organization.moderator.edit', [
        'title' => 'Beheerder bewerken',
        'user' => $user,
    ]);
})->before('auth|user');
Route::post('organization/moderator/edit/{id}', 'UserController@editModerator')->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Delete a moderator
 |--------------------------------------------------------------------------
*/
Route::get('organization/moderator/delete/{id}', function ($id) {
    $u = new User;
    $user = $u->find($id);

    return view('organization.moderator.delete', [
        'title' => 'Beheerder verwijderen',
        'user' => $user,
    ]);
})->before('auth|user');
Route::post('organization/moderator/delete/{id}', 'UserController@deleteModerator')->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Link user to a moderator
 |--------------------------------------------------------------------------
*/
Route::get('organization/moderator/link', function () {

    $users = new User;
    $u = $users->where('rights', '=', '2')->where('oid', '=', Auth::user()->id)->get();
    if ($u->count() > 0) {
        $aUsers = $u;
    } else {
        $aUsers = [];
    }

    $sfolders = Folder::where('uid', '=', Auth::user()->id)->whereNull('pid')->get();

    return view('organization.moderator.link', [
        'title' => 'Beheerders koppelen',
        'users' => $aUsers,
        'sfolders' => $sfolders,
    ]);
})->before('auth|user');
Route::post('organization/moderator/edit/{id}', 'UserController@editModerator')->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Edit a moderator
 |--------------------------------------------------------------------------
*/
Route::get('organization/moderator/linkedit/{id}', function ($id) {

    $u = new User;
    $user = $u->find($id);

    $m = User::where('oid', '=', Auth::user()->id);
    $m->where('rights', '=', '3');
    $mods = $m->get();

    return view('organization.moderator.linkedit', [
        'title' => 'Beheerder(s) koppelen aan gebruiker',
        'user' => $user,
        'mods' => $mods,
    ]);
})->before('auth|user');
Route::post('organization/moderator/linkedit/{id}', 'ModeratorController@edit')->before('auth');

/*
 |--------------------------------------------------------------------------
 |	STANDARD FOLDERS SECTION BELOW
 |--------------------------------------------------------------------------
*/

Route::get('organization/folders', function () {
    $aFolders = Folder::where('uid', '=', Auth::user()->id)->whereNull('pid')->orderBy('order')->get();

    return view('organization.folders.overview', [
        'title' => 'Standaard mappen',
        'standardfolders' => $aFolders,
    ]);
})->name('folders')->before('auth');

Route::get('organization/folder/add', function () {

    $aFolders['-'] = 'Geen';
    foreach (Folder::where('uid', '=', Auth::user()->id)->whereNull('pid')->get() as $folder) {
        $aFolders[$folder->id] = $folder->name;
    }

    return view('organization.folders.add', [
        'title' => 'Nieuwe standaard map toevoegen',
        'folders' => $aFolders,
    ]);
})->before('auth');
Route::post('organization/folder/add', 'FoldersController@add')->before('auth');

Route::get('organization/folder/edit/{id}', function ($id) {
    $aFolders['-'] = 'Geen';
    foreach (Folder::where('uid', '=', Auth::user()->id)->whereNull('pid')->get() as $folder) {
        $aFolders[$folder->id] = $folder->name;
    }

    $folder = Folder::where('uid', '=', Auth::user()->id)->where('id', '=', $id)->first();

    return view('organization.folders.edit', [
        'title' => 'Map bewerken',
        'folder' => $folder,
        'folders' => $aFolders,
    ]);
})->before('auth');
Route::post('organization/folder/edit/{id}', 'FoldersController@edit')->before('auth');

Route::get('organization/folder/delete/{id}', function ($id) {
    return view('organization.folders.delete', [
        'title' => 'Standaard map verwijderen',
    ]);
})->before('auth');
Route::post('organization/folder/delete/{id}', 'FoldersController@delete')->before('auth');

Route::any('organization/folder/sort', 'FoldersController@sort')->before('auth');

/*
 |--------------------------------------------------------------------------
 |	ORGANISATION SECTION BELOW
 |--------------------------------------------------------------------------
*/

Route::get('organization/linkedorganizations', function () {
    $org = new Organizations;
    if ($org->where('uid', '=', Auth::user()->id)->count() > 0) {
        $users = new User;
        $u = $users->where('rights', '=', '4')->where('oid', '=', Auth::user()->oid)->where('id', '!=', Auth::user()->id)->get();
        if ($u->count() > 0) {
            $aOrgs = $u;
        } else {
            $aOrgs = [];
        }

        return view('organization.organization.overview', [
            'title' => 'Gekoppelde organisaties',
            'users' => $aOrgs,
        ]);
    } else {
        return false;
    }
})->before('auth');
