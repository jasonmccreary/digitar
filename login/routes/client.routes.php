<?php

use App\Http\Controllers\FoldersController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('client', function () {
    return redirect('/client/users');
})->before('auth');
Route::get('client/users', function () {
    $users = new User;
    $u = $users->where('rights', '=', '1')->where('oid', '=', Auth::user()->oid)->where('cid', '=', Auth::user()->id)->get();
    if ($u->count() > 0) {
        $aUsers = $u;
    } else {
        $aUsers = [];
    }

    return view('client.users.overview', [
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

    return view('client.users.add', [
        'title' => 'Gebruiker toevoegen',
        'sfolders' => $sfolders,
    ]);
})->before('auth');
Route::post('client/user/add', [
    'before' => 'auth',
    'uses' => [UserController::class, 'addUser'],
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

    return view('client.users.edit', [
        'title' => 'Gebruiker bewerken',
        'user' => $user,
        'sfolders' => $sfolders,
    ]);
})->before('auth|user');
Route::post('client/user/edit/{id}', [UserController::class, 'editUser'])->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Delete a User
 |--------------------------------------------------------------------------
*/
Route::get('client/user/delete/{id}', function ($id) {
    $u = new User;
    $user = $u->find($id);

    return view('client.users.delete', [
        'title' => 'Gebruiker verwijderen',
        'user' => $user,
    ]);
})->before('auth|user');
Route::post('client/user/delete/{id}', [UserController::class, 'deleteUser'])->before('auth');

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

        return redirect('/client/users');
    } else {
        Alert::error('Gebruiker heeft geen email adres!')->flash();

        return redirect('/client/users');
    }
})->before('auth|user');

/*
 |--------------------------------------------------------------------------
 |	STANDARD FOLDERS SECTION BELOW
 |--------------------------------------------------------------------------
*/

Route::get('client/folders', function () {
    $f = new Folder;
    $aFolders = $f->getAllUserFolders();

    return view('client.folders.overview', [
        'title' => 'Mappen',
        'folders' => $aFolders,
    ]);
})->name('folders')->before('auth');

Route::get('client/folder/add', function () {

    $f = new Folder;
    $foldersArray = $f->getAllUserFolders();

    $aFolders['-'] = 'Geen';
    foreach ($foldersArray as $folder) {
        $aFolders[$folder->id] = $folder->name;
    }

    return view('client.folders.add', [
        'title' => 'Nieuwe map toevoegen',
        'folders' => $aFolders,
    ]);
})->before('auth');
Route::post('client/folder/add', [FoldersController::class, 'add'])->before('auth');

Route::get('client/folder/edit/{id}', function ($id) {
    $folder = Folder::where('uid', '=', Auth::user()->id)->where('id', '=', $id)->first();

    $aFolders['-'] = 'Geen';
    foreach (Folder::getAllUserFolders() as $foldera) {
        $aFolders[$foldera->id] = $foldera->name;
    }

    return view('client.folders.edit', [
        'title' => 'Map bewerken',
        'folder' => $folder,
        'folders' => $aFolders,
    ]);
})->before('auth|folder');
Route::post('client/folder/edit/{id}', [FoldersController::class, 'edit'])->before('auth');

Route::get('client/folder/delete/{id}', function ($id) {
    if (Files::where('fid', '=', $id)->count() > 1) {
        Alert::error('Deze map kan niet worden verwijderd omdat er nog bestanden in staan!')->flash();

        return Redirect::route('folders');
    } else {
        return view('client.folders.delete', [
            'title' => 'Map verwijderen',
        ]);
    }
})->before('auth|folder');
Route::post('client/folder/delete/{id}', [FoldersController::class, 'delete'])->before('auth');
