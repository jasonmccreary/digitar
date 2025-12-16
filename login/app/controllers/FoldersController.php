<?php

class FoldersController extends BaseController {

	function add() {
		$input = Input::all();

		$rules = array(
			'mapname' => 'alpha_space',
			'color' => 'required'
		);

		$v = Validator::make($input, $rules);
		if ($v->fails()) {
			foreach ($v->messages()->all() as $message) {
				Alert::error($message)->flash();
			}
			return Redirect::to('/organization/folder/add')->withInput();
		}else{
			$f = new Folder();

			$check = $f->where('name', '=', Input::get('mapname'))->where('uid', '=', Auth::user()->id);
			if ($check->count() > 0) {
				Alert::error('De map naam bestaat al.')->flash();
				return Redirect::back()->withInput();
			}


			$order = Folder::getAllUserFolders(true)->max('order') + 1;

			$f->uid = Auth::user()->id;
			$f->name = Input::get('mapname');
			$f->color = Input::get('color');
			if (is_numeric(Input::get('parent'))) {
				$pf = Folder::where('id', '=', Input::get('parent'));
				if ($pf->count() > 0) {
					$f->pid = Input::get('parent');
					$pf = $pf->first();
				}else{
					unset($pf);
				}
			}else{
				$f->pid = NULL;
			}
			if (Input::has('geboekt') || isset($pf) && $pf->bookedcheck == 1) {
				$f->bookedcheck = 1;
			}else{
				$f->bookedcheck = 0;
			}
			$f->order = $order;

			$f->save();

			Alert::success('Standaard map succesvol toegevoegd')->flash();
			return Redirect::route('folders');
		}
	}

	function edit($id) {
		$rules = array(
			'mapname' => 'alpha_space',
			'color' => 'required'
		);

		$v = Validator::make(Input::all(), $rules);
		if ($v->fails()) {
			foreach ($v->messages()->all() as $message) {
				Alert::error($message)->flash();
				return Redirect::back()->withInput();
			}
		}else{
			if (Input::get('parent') == $id) {
				Alert::error('De hoofdmap mag niet het zelfde zijn.')->flash();
				return Redirect::back()->withInput();
			}
			$folder = new Folder();
			$f = $folder->find($id);

			$f->name = Input::get('mapname');
			$f->color = Input::get('color');
			if (is_numeric(Input::get('parent'))) {
				$pf = $folder->where('id', '=', Input::get('parent'));
				if ($pf->count() > 0) {
					$f->pid = Input::get('parent');
					$pf = $pf->first();
				}else{
					unset($pf);
				}
			}else{
				$f->pid = NULL;
			}
			if(Input::has('geboekt') || isset($pf) && $pf->bookedcheck == 1) {
				$f->bookedcheck = 1;
			}else{
				$f->bookedcheck = 0;
			}
			// echo '<pre>';dd($folder);echo '</pre>';
			$f->save();

			Alert::success('Uw wijzigingen zijn succesvol doorgevoerd.')->flash();
		}
		return Redirect::route('folders');
	}

	function delete($id) {
		if (Input::get('delete') == 'true') {
			$sf = new Folder();

			$folder = $sf->find($id);
			if($folder->count() > 0 && Files::where('fid','=',$folder->id)->count() > 0) {
				Alert::error('Kan niet verwijderen, zitten nog bestanden in de map!')->flash();
			}else {
				$folder->delete();
				Alert::success('Map succesvol verwijderd')->flash();
			}
		}

		return Redirect::route('folders');
	}

	function deleteUser($uid) {
		if (Input::get('delete') == 'true') {

			foreach(Folder::where('uid','=',$uid)->get() as $f) {

				$folder = Folder::where('id','=',$f->id);

				$uf = $folder->first();

				$u = User::where('id','=',$uf->uid)->first();
				$o = User::where('id','=',$u->oid)->first();
				$fp = '../../../clients/'.$o->username.'/'.$u->username.'/';
				$this->hardDelete($fp);

				$folder->delete();
			}

			Folderright::where('uid','=',$uid)->delete();

			Alert::success('Map succesvol verwijderd')->flash();
		}

		return Redirect::to('/organization/folders');
	}

	function sort() {
		foreach($_POST['fid'] as $order => $sfid) {
			$sf = new Folder();
			$f = $sf->find($sfid);

			echo $order.' > '.$f->name;
			$f->order = $order;
			$f->save();
		}
	}

	public function geboektcheck($id,$json = true) {
		$data = false;
		if (Folder::where('id', '=', $id)->where('bookedcheck', '=', 1)->count() > 0 && Session::has('highrank')) {
			$data = true;
		}
		if ($json) {
			return Response::json($data);
		}else{
			return $data;
		}
	}


	function hardDelete($path) {
	    if (is_dir($path) === true) {
	        $files = array_diff(scandir($path), array('.', '..'));

	        foreach ($files as $file) {
	            $this->hardDelete(realpath($path) . '/' . $file);
	        }

	        return rmdir($path);
	    }else if (is_file($path) === true) {
	        return unlink($path);
	    }

	    return false;
	}

}
