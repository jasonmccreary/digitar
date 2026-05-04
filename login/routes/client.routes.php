<?php

use App\Http\Controllers\FoldersController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::redirect('client', '/client/users');
Route::get('client/users', function () {
    $u = User::where('rights', '=', '1')->where('oid', '=', Auth::user()->oid)->where('cid', '=', Auth::user()->id)->get();

    return view('client.users.overview', [
        'title' => 'Gebruikers',
        'users' => $u->isNotEmpty() ? $u : [],
    ]);
})->middleware('auth');
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
})->middleware('auth');
Route::post('client/user/add', [UserController::class, 'addUser'])->middleware('auth');
/*
 |--------------------------------------------------------------------------
 |	Edit a User
 |--------------------------------------------------------------------------
*/
Route::get('client/user/edit/{id}', function ($id) {
    $user = User::find($id);

    $sfolders = Folder::getAllUserFolders();

    return view('client.users.edit', [
        'title' => 'Gebruiker bewerken',
        'user' => $user,
        'sfolders' => $sfolders,
    ]);
})->middleware('auth|user');
Route::post('client/user/edit/{id}', [UserController::class, 'editUser'])->middleware('auth');
/*
 |--------------------------------------------------------------------------
 |	Delete a User
 |--------------------------------------------------------------------------
*/
Route::get('client/user/delete/{id}', function ($id) {
    $user = User::find($id);

    return view('client.users.delete', [
        'title' => 'Gebruiker verwijderen',
        'user' => $user,
    ]);
})->middleware('auth|user');
Route::post('client/user/delete/{id}', [UserController::class, 'deleteUser'])->middleware('auth');

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

        return redirect()->to('/client/users');
    } else {
        Alert::error('Gebruiker heeft geen email adres!')->flash();

        return redirect()->to('/client/users');
    }
})->middleware('auth|user');

/*
 |--------------------------------------------------------------------------
 |	STANDARD FOLDERS SECTION BELOW
 |--------------------------------------------------------------------------
*/

Route::get('client/folders', function () {
    $aFolders = Folder::getAllUserFolders();

    return view('client.folders.overview', [
        'title' => 'Mappen',
        'folders' => $aFolders,
    ]);
})->name('folders')->middleware('auth');

Route::get('client/folder/add', function () {

    $foldersArray = Folder::getAllUserFolders();

    $aFolders['-'] = 'Geen';
    foreach ($foldersArray as $folder) {
        $aFolders[$folder->id] = $folder->name;
    }

    return view('client.folders.add', [
        'title' => 'Nieuwe map toevoegen',
        'folders' => $aFolders,
    ]);
})->middleware('auth');
Route::post('client/folder/add', [FoldersController::class, 'add'])->middleware('auth');

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
})->middleware('auth|folder');
Route::post('client/folder/edit/{id}', [FoldersController::class, 'edit'])->middleware('auth');

Route::get('client/folder/delete/{id}', function ($id) {
    if (Files::where('fid', '=', $id)->count() > 1) {
        Alert::error('Deze map kan niet worden verwijderd omdat er nog bestanden in staan!')->flash();

        return Redirect::route('folders');
    } else {
        return view('client.folders.delete', [
            'title' => 'Map verwijderen',
        ]);
    }
})->middleware('auth|folder');
Route::post('client/folder/delete/{id}', [FoldersController::class, 'delete'])->middleware('auth');
