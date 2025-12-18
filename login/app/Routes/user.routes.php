<?php

use Illuminate\Support\Facades\Request;

Route::get('user', function () {
    if (! Session::has('year')) {
        Session::put('year', date('Y'));
    }

    return redirect('/user/folder/inbox');
})->before('auth');

Route::get('user/folder/{fid}', ['uses' => 'FileController@showFiles']);
Route::get('user/folder/{fid}/{ajax}', ['uses' => 'FileController@showFiles']);
Route::get('user/ongeboekt/ajax', ['uses' => 'FileController@getOngeboekt']);
Route::any('user/search/{search}', ['uses' => 'FileController@showFiles']);
Route::any('user/search/{search}/ajax/', ['uses' => 'FileController@searchFiles']);

Route::get('user/upload', ['before' => 'auth|folders', 'uses' => function () {
    return view('users.upload', [
        'title' => 'Bestanden toevoegen',
    ]);
}]);
Route::get('user/ajax/messages', ['before' => 'auth', 'uses' => function () {
    return view('users.ajax.messages');
}]);

Route::get('user/files', ['uses' => 'CloudsController@showFiles']);

Route::post('user/upload/post', ['uses' => 'FileController@upload']);
Route::post('user/file/edit/{id}', ['uses' => 'FileController@editFile']);
Route::post('user/file/editdetails/{id}', ['uses' => 'CloudsController@editFile']);

Route::post('user/files/bulk', ['before' => 'auth|folders', 'uses' => function () {
    if (is_array(Request::get('fileid'))) {
        if (Request::has('delete')) {
            if (Request::has('sure')) {
                if (Request::get('sure') == 'true') {
                    foreach (Request::get('fileid') as $fid => $file) {
                        FileController::deleteFile($fid);
                    }
                    Alert::success('Bestand(en) verwijderd')->flash();

                    return redirect('/user/folder/inbox');
                } else {
                    return redirect('/user/folder/inbox');
                }

            } else {
                return view('users.deletefiles', [
                    'title' => 'Bestanden verwijderen',
                    'filesArray' => Request::get('fileid'),
                ]);
            }
        } elseif (Request::has('split')) {
            foreach (Request::get('fileid') as $fid => $file) {
                FileController::splitFiles($fid);
            }
            Alert::success('Bestanden opgesplitst')->flash();

            return redirect('/user/folder/inbox');
        } elseif (Request::has('combine')) {
            FileController::combineFiles(Request::get('fileid'));

            return redirect('/user/folder/inbox');
        } elseif (Request::has('download')) {
            FileController::downloadFiles(Request::get('fileid'));

            return Redirect::back()->with('download', true);
        } elseif (Request::has('booked')) {
            FileController::markBooked(Request::get('fileid'));

            return Redirect::back();
        } elseif (Request::has('sendmail')) {
            return view('users.sendfiles', [
                'title' => 'Bestanden versturen',
                'files' => Request::get('fileid'),
            ]);
        } elseif (Request::has('movefiles')) {
            FileController::moveToFolder(Request::get('fileid'), Request::get('folder'));

            return Redirect::back();
        } else {
            Alert::error('Onbekende handeling.')->flash();

            return Redirect::back()->withInput();
        }
    } else {
        Alert::info('Selecteer selecteer eerst minimaal een document.')->flash();

        return redirect('/user/folder/inbox');
    }
}]);
Route::get('user/files/bulk', ['before' => 'auth|folders', 'uses' => function () {
    if (Request::old('sendmail')) {
        return view('users.sendfiles', [
            'title' => 'Bestanden versturen',
            'files' => Request::old('files'),
        ]);
    } else {
        // dd(Request::old('fileid'));
        return redirect('/user/folder/inbox');
    }
}]);
Route::post('user/sendmail', ['uses' => 'FileController@sendmail']);

Route::post('user/files/download', ['before' => 'auth|folders', 'uses' => function () {
    if (is_array(Request::get('fileid'))) {
        if (Request::has('download')) {
            FileController::downloadFiles(Request::get('fileid'));

            return Redirect::back();
        }
    } else {
        Alert::info('Selecteer selecteer eerst minimaal een document.')->flash();

        return Redirect::back();
    }
}]);

Route::post('user/cloud/bulk', ['before' => 'auth|folders', 'uses' => function () {
    if (is_array(Request::get('fileid'))) {
        if (Request::has('delete')) {
            if (Request::has('sure')) {
                if (Request::get('sure') == 'true') {
                    foreach (Request::get('fileid') as $fid => $file) {
                        CloudsController::deleteFile($fid);
                    }
                    Alert::success('Bestanden verwijderd')->flash();

                    return redirect('/user/files');
                } else {
                    return redirect('/user/files');
                }

            } else {
                return view('users.deletecloudfiles', [
                    'title' => 'Bestanden verwijderen',
                    'filesArray' => Request::get('fileid'),
                ]);
            }
        }
    } else {
        Alert::info('Selecteer selecteer eerst minimaal een document.')->flash();

        return redirect('/user/folder/inbox');
    }
}]);

Route::get('user/download/file/{fid}/{nothing}', ['before' => 'auth', 'uses' => 'CloudsController@downloadFile']);

Route::get('user/viewfile/{fid}', ['uses' => 'FileController@viewfile']);
Route::get('user/viewdetails/{fid}', ['uses' => 'CloudsController@viewdetails']);
Route::get('user/loadpdf/{fid}', ['uses' => 'FileController@loadpdf']);
Route::get('user/loadfile/{fid}/{viewer}', ['uses' => 'FileController@loadfile']);
Route::get('user/loadfile/{fid}', ['uses' => 'FileController@loadfile']);
Route::get('user/loadfile/', ['uses' => 'FileController@loadfile']);
Route::get('user/geboektchecker/{fid}', ['uses' => 'FoldersController@geboektcheck']);
Route::get('user/year/{year}', function ($year) {
    Session::put('year', $year);

    return Redirect::back();
});
