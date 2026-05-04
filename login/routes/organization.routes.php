<?php

use App\Http\Controllers\FoldersController;
use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\UserController;
use App\Models\Organizations;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('organization/superlogin', function () {
    return view('login.superlogin', [
        'superlogin' => 'true',
    ]);
})->middleware('auth');
Route::post('organization/supersearch', [UserController::class, 'supersearch'])->middleware('auth');

Route::redirect('organization', '/organization/clients');
Route::get('organization/clients', function () {
    $u = User::where('rights', '=', '2')->where('oid', '=', Auth::user()->id)->get();

    return view('organization.clients.overview', [
        'title' => 'Klanten',
        'users' => $u->isNotEmpty() ? $u : [],
    ]);
})->middleware('auth');
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
})->middleware('auth');
Route::post('organization/client/add', [UserController::class, 'addClient'])->middleware('auth');
/*
 |--------------------------------------------------------------------------
 |	Edit a Client
 |--------------------------------------------------------------------------
*/
Route::get('organization/client/edit/{id}', function ($id) {
    $user = User::find($id);

    $sfolders = Folder::where('uid', '=', Auth::user()->id)->whereNull('pid')->get();

    return view('organization.clients.edit', [
        'title' => 'Klant bewerken',
        'user' => $user,
        'sfolders' => $sfolders,
    ]);
})->middleware('auth')->middleware('user');
Route::post('organization/client/edit/{id}', [UserController::class, 'editClient'])->middleware('auth');
/*
 |--------------------------------------------------------------------------
 |	Delete a Client
 |--------------------------------------------------------------------------
*/
Route::get('organization/client/delete/{id}', function ($id) {
    $user = User::find($id);

    return view('organization.clients.delete', [
        'title' => 'Klant verwijderen',
        'user' => $user,
    ]);
})->middleware('auth|user');
Route::post('organization/client/delete/{id}', [UserController::class, 'deleteClient'])->middleware('auth');

/*
 |--------------------------------------------------------------------------
 |	MODERATORS SECTION BELOW
 |--------------------------------------------------------------------------
*/

Route::get('organization/moderators', function () {
    $u = User::where('rights', '=', '3')->where('oid', '=', Auth::user()->id)->get();

    return view('organization.moderator.overview', [
        'title' => 'Beheerders',
        'users' => $u->isNotEmpty() ? $u : [],
    ]);
})->middleware('auth');
/*
    |--------------------------------------------------------------------------
    |	Add new moderator
    |--------------------------------------------------------------------------
   */
Route::view('organization/moderator/add', 'organization.moderator.add', ['title' => 'Beheerder toevoegen'])->middleware('auth');
Route::post('organization/moderator/add', [UserController::class, 'addModerator'])->middleware('auth');
/*
 |--------------------------------------------------------------------------
 |	Edit a moderator
 |--------------------------------------------------------------------------
*/
Route::get('organization/moderator/edit/{id}', function ($id) {
    $user = User::find($id);

    return view('organization.moderator.edit', [
        'title' => 'Beheerder bewerken',
        'user' => $user,
    ]);
})->middleware('auth|user');
Route::post('organization/moderator/edit/{id}', [UserController::class, 'editModerator'])->middleware('auth');
/*
 |--------------------------------------------------------------------------
 |	Delete a moderator
 |--------------------------------------------------------------------------
*/
Route::get('organization/moderator/delete/{id}', function ($id) {
    $user = User::find($id);

    return view('organization.moderator.delete', [
        'title' => 'Beheerder verwijderen',
        'user' => $user,
    ]);
})->middleware('auth|user');
Route::post('organization/moderator/delete/{id}', [UserController::class, 'deleteModerator'])->middleware('auth');
/*
 |--------------------------------------------------------------------------
 |	Link user to a moderator
 |--------------------------------------------------------------------------
*/
Route::get('organization/moderator/link', function () {
    $u = User::where('rights', '=', '2')->where('oid', '=', Auth::user()->id)->get();

    $sfolders = Folder::where('uid', '=', Auth::user()->id)->whereNull('pid')->get();

    return view('organization.moderator.link', [
        'title' => 'Beheerders koppelen',
        'users' => $u->isNotEmpty() ? $u : [],
        'sfolders' => $sfolders,
    ]);
})->middleware('auth|user');
Route::post('organization/moderator/edit/{id}', [UserController::class, 'editModerator'])->middleware('auth');
/*
 |--------------------------------------------------------------------------
 |	Edit a moderator
 |--------------------------------------------------------------------------
*/
Route::get('organization/moderator/linkedit/{id}', function ($id) {
    $user = User::find($id);

    $mods = User::where('oid', '=', Auth::user()->id)->where('rights', '=', '3')->get();

    return view('organization.moderator.linkedit', [
        'title' => 'Beheerder(s) koppelen aan gebruiker',
        'user' => $user,
        'mods' => $mods,
    ]);
})->middleware('auth|user');
Route::post('organization/moderator/linkedit/{id}', [ModeratorController::class, 'edit'])->middleware('auth');

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
})->name('folders')->middleware('auth');

Route::get('organization/folder/add', function () {

    $aFolders['-'] = 'Geen';
    foreach (Folder::where('uid', '=', Auth::user()->id)->whereNull('pid')->get() as $folder) {
        $aFolders[$folder->id] = $folder->name;
    }

    return view('organization.folders.add', [
        'title' => 'Nieuwe standaard map toevoegen',
        'folders' => $aFolders,
    ]);
})->middleware('auth');
Route::post('organization/folder/add', [FoldersController::class, 'add'])->middleware('auth');

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
})->middleware('auth');
Route::post('organization/folder/edit/{id}', [FoldersController::class, 'edit'])->middleware('auth');

Route::get('organization/folder/delete/{id}', function ($id) {
    return view('organization.folders.delete', [
        'title' => 'Standaard map verwijderen',
    ]);
})->middleware('auth');
Route::post('organization/folder/delete/{id}', [FoldersController::class, 'delete'])->middleware('auth');

Route::any('organization/folder/sort', [FoldersController::class, 'sort'])->middleware('auth');

/*
 |--------------------------------------------------------------------------
 |	ORGANISATION SECTION BELOW
 |--------------------------------------------------------------------------
*/

Route::get('organization/linkedorganizations', function () {
    if (Organizations::where('uid', '=', Auth::user()->id)->count() > 0) {
        $u = User::where('rights', '=', '4')->where('oid', '=', Auth::user()->oid)->where('id', '!=', Auth::user()->id)->get();

        return view('organization.organization.overview', [
            'title' => 'Gekoppelde organisaties',
            'users' => $u->isNotEmpty() ? $u : [],
        ]);
    } else {
        return false;
    }
})->middleware('auth');
