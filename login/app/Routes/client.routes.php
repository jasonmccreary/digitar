<?php

Route::get('client', function () {
    return Redirect::to('/client/users');
})->before('auth');
Route::get('client/users', function () {
    $users = new User;
    $u = $users->where('rights', '=', '1')->where('oid', '=', Auth::user()->oid)->where('cid', '=', Auth::user()->id)->get();
    if ($u->count() > 0) {
        $aUsers = $u;
    } else {
        $aUsers = [];
    }

    return View::make('client.users.overview', [
        'title' => 'Gebruikers',
        'users' => $aUsers,
    ]);
})->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Add new User
 |--------------------------------------------------------------------------
*/
Route::get('client/user/add', function () {

    $sfolders = Folder::getAllUserFolders();

    return View::make('client.users.add', [
        'title' => 'Gebruiker toevoegen',
        'sfolders' => $sfolders,
    ]);
})->before('auth');
Route::post('client/user/add', [
    'before' => 'auth',
    'uses' => 'UserController@addUser',
]);
/*
 |--------------------------------------------------------------------------
 |	Edit a User
 |--------------------------------------------------------------------------
*/
Route::get('client/user/edit/{id}', function ($id) {
    $u = new User;
    $user = $u->find($id);

    $sfolders = Folder::getAllUserFolders();

    return View::make('client.users.edit', [
        'title' => 'Gebruiker bewerken',
        'user' => $user,
        'sfolders' => $sfolders,
    ]);
})->before('auth|user');
Route::post('client/user/edit/{id}', 'UserController@editUser')->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Delete a User
 |--------------------------------------------------------------------------
*/
Route::get('client/user/delete/{id}', function ($id) {
    $u = new User;
    $user = $u->find($id);

    return View::make('client.users.delete', [
        'title' => 'Gebruiker verwijderen',
        'user' => $user,
    ]);
})->before('auth|user');
Route::post('client/user/delete/{id}', 'UserController@deleteUser')->before('auth');

/*
 |--------------------------------------------------------------------------
 |	Send Credentials
 |--------------------------------------------------------------------------
*/
Route::get('client/user/credentials/{id}', function ($id) {
    global $useremail;
    $u = User::where('id', '=', $id)->first();
    $c = User::where('id', '=', $u->cid)->first();
    $useremail = $u->email;
    if (strlen($u->email) > 3) {
        Mail::send('emails.credentials', ['name' => $u->name, 'username' => $u->username, 'password' => $u->password, 'client' => $c->name], function ($message) {
            global $useremail;
            $message->from('noreply@digitar.nu', 'Digitar');
            $message->to($useremail)->subject('Inlog gegevens');

        });
        Alert::success('Inlog gegevens zijn verstuurd!')->flash();

        return Redirect::to('/client/users');
    } else {
        Alert::error('Gebruiker heeft geen email adres!')->flash();

        return Redirect::to('/client/users');
    }
})->before('auth|user');

/*
 |--------------------------------------------------------------------------
 |	STANDARD FOLDERS SECTION BELOW
 |--------------------------------------------------------------------------
*/

Route::get('client/folders', ['as' => 'folders', function () {
    $f = new Folder;
    $aFolders = $f->getAllUserFolders();

    return View::make('client.folders.overview', [
        'title' => 'Mappen',
        'folders' => $aFolders,
    ]);
}])->before('auth');

Route::get('client/folder/add', function () {

    $f = new Folder;
    $foldersArray = $f->getAllUserFolders();

    $aFolders['-'] = 'Geen';
    foreach ($foldersArray as $folder) {
        $aFolders[$folder->id] = $folder->name;
    }

    return View::make('client.folders.add', [
        'title' => 'Nieuwe map toevoegen',
        'folders' => $aFolders,
    ]);
})->before('auth');
Route::post('client/folder/add', 'FoldersController@add')->before('auth');

Route::get('client/folder/edit/{id}', function ($id) {
    $folder = Folder::where('uid', '=', Auth::user()->id)->where('id', '=', $id)->first();

    $aFolders['-'] = 'Geen';
    foreach (Folder::getAllUserFolders() as $foldera) {
        $aFolders[$foldera->id] = $foldera->name;
    }

    return View::make('client.folders.edit', [
        'title' => 'Map bewerken',
        'folder' => $folder,
        'folders' => $aFolders,
    ]);
})->before('auth|folder');
Route::post('client/folder/edit/{id}', 'FoldersController@edit')->before('auth');

Route::get('client/folder/delete/{id}', function ($id) {
    if (Files::where('fid', '=', $id)->count() > 1) {
        Alert::error('Deze map kan niet worden verwijderd omdat er nog bestanden in staan!')->flash();

        return Redirect::route('folders');
    } else {
        return View::make('client.folders.delete', [
            'title' => 'Map verwijderen',
        ]);
    }
})->before('auth|folder');
Route::post('client/folder/delete/{id}', 'FoldersController@delete')->before('auth');
