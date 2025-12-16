<?php

Route::get('user', function() {
	if (!Session::has('year')) {
		Session::put('year',date('Y'));
	}
	return Redirect::to('/user/folder/inbox');
})->before('auth');

	Route::get('user/folder/{fid}', array('uses' => 'FileController@showFiles'));
	Route::get('user/folder/{fid}/{ajax}', array('uses' => 'FileController@showFiles'));
	Route::get('user/ongeboekt/ajax', array('uses' => 'FileController@getOngeboekt'));
	Route::any('user/search/{search}', array('uses' => 'FileController@showFiles')); 
		Route::any('user/search/{search}/ajax/', array('uses' => 'FileController@searchFiles'));

	Route::get('user/upload', array('before' => 'auth|folders', 'uses' => function() {
		return View::make('users.upload', array(
        	'title' => 'Bestanden toevoegen'
        ));
	}));
	Route::get('user/ajax/messages', array('before' => 'auth', 'uses' => function() {
		return View::make('users.ajax.messages');
	}));

	Route::get('user/files', array('uses' => 'CloudsController@showFiles'));


	Route::post('user/upload/post', array('uses' => 'FileController@upload'));
	Route::post('user/file/edit/{id}', array('uses' => 'FileController@editFile'));
	Route::post('user/file/editdetails/{id}', array('uses' => 'CloudsController@editFile'));

	Route::post('user/files/bulk', array('before' => 'auth|folders', 'uses' => function() {
		if (is_array(Input::get('fileid'))) {
			if (Input::has('delete')) {
				if (Input::has('sure')) {
					if (Input::get('sure') == 'true') {
						foreach(Input::get('fileid') as $fid => $file) {
							 FileController::deleteFile($fid);
						}
						Alert::success('Bestand(en) verwijderd')->flash();
						return Redirect::to('/user/folder/inbox');
					}else{
						return Redirect::to('/user/folder/inbox');
					}

				}else{
					return View::make('users.deletefiles', array(
						'title' => 'Bestanden verwijderen',
						'filesArray' => Input::get('fileid')
					));
				}
			}elseif (Input::has('split')) {
				foreach(Input::get('fileid') as $fid => $file) {
					 FileController::splitFiles($fid);
				}
				Alert::success('Bestanden opgesplitst')->flash();
				return Redirect::to('/user/folder/inbox');
			}elseif (Input::has('combine')) {
				FileController::combineFiles(Input::get('fileid'));
				return Redirect::to('/user/folder/inbox');
			}elseif (Input::has('download')) {
				FileController::downloadFiles(Input::get('fileid'));
				return Redirect::back()->with('download',true);
			}elseif (Input::has('booked')) {
				FileController::markBooked(Input::get('fileid'));
				return Redirect::back();
			}elseif (Input::has('sendmail')) {
				return View::make('users.sendfiles', array(
		        	'title' => 'Bestanden versturen',
		        	'files' => Input::get('fileid')
		        ));
			}elseif (Input::has('movefiles')) {
				FileController::moveToFolder(Input::get('fileid'),Input::get('folder'));
				return Redirect::back();
			}else{
				Alert::error('Onbekende handeling.')->flash();
				return Redirect::back()->withInput();
			}
		}else{
			Alert::info('Selecteer selecteer eerst minimaal een document.')->flash();
			return Redirect::to('/user/folder/inbox');
		}
	}));
	Route::get('user/files/bulk', array('before' => 'auth|folders', 'uses' => function() {
		if (Input::old('sendmail')) {
			return View::make('users.sendfiles', array(
	        	'title' => 'Bestanden versturen',
	        	'files' => Input::old('files')
	        ));
		}else{
			// dd(Input::old('fileid'));
			return Redirect::to('/user/folder/inbox');
		}
	}));
	Route::post('user/sendmail', array('uses' => 'FileController@sendmail'));

	Route::post('user/files/download', array('before' => 'auth|folders', 'uses' => function() {
		if (is_array(Input::get('fileid'))) {
			if (Input::has('download')) {
				FileController::downloadFiles(Input::get('fileid'));
				return Redirect::back();
			}
		}else{
			Alert::info('Selecteer selecteer eerst minimaal een document.')->flash();
			return Redirect::back();
		}
	}));

	Route::post('user/cloud/bulk', array('before' => 'auth|folders', 'uses' => function() {
		if (is_array(Input::get('fileid'))) {
			if (Input::has('delete')) {
				if (Input::has('sure')) {
					if (Input::get('sure') == 'true') {
						foreach(Input::get('fileid') as $fid => $file) {
							 CloudsController::deleteFile($fid);
						}
						Alert::success('Bestanden verwijderd')->flash();
						return Redirect::to('/user/files');
					}else{
						return Redirect::to('/user/files');
					}

				}else{
					return View::make('users.deletecloudfiles', array(
						'title' => 'Bestanden verwijderen',
						'filesArray' => Input::get('fileid')
					));
				}
			}
		}else{
			Alert::info('Selecteer selecteer eerst minimaal een document.')->flash();
			return Redirect::to('/user/folder/inbox');
		}
	}));


	Route::get('user/download/file/{fid}/{nothing}', array('before' => 'auth', 'uses' => 'CloudsController@downloadFile'));

	Route::get('user/viewfile/{fid}', array('uses' => 'FileController@viewfile'));
	Route::get('user/viewdetails/{fid}', array('uses' => 'CloudsController@viewdetails'));
	Route::get('user/loadpdf/{fid}', array('uses' => 'FileController@loadpdf'));
	Route::get('user/loadfile/{fid}/{viewer}', array('uses' => 'FileController@loadfile'));
	Route::get('user/loadfile/{fid}', array('uses' => 'FileController@loadfile'));
		Route::get('user/loadfile/', array('uses' => 'FileController@loadfile'));
	Route::get('user/geboektchecker/{fid}', array('uses' => 'FoldersController@geboektcheck'));
	Route::get('user/year/{year}', function($year) {
		Session::put('year', $year);
		return Redirect::back();
	});
