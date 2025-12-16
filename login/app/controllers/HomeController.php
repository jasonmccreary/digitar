<?php

use Carbon\Carbon;

class HomeController extends BaseController {

	public function getIndex(){
		if (!Auth::check()) {
			return View::make('login.login');
		}else{
			return View::make('users.viewfile');
		}
	}

	public function filesToday()
	{
		$f = Files::leftJoin('users', 'users.id','=','files.uid')
			->select(DB::raw('files.*, users.id as userId, users.cid as userCid'))
			->where('files.created_at', '>=', Carbon::today()->toDateString())
			->whereRaw('files.cid != users.id')
			->whereRaw('files.cid != users.cid')
			->whereNotIn('files.uid',[2,3])
			->groupBy('files.cid');
		// echo '<pre>';dd(DB::getQueryLog());

		
		foreach ($f->get() as $file) {
			$user = User::findOrFail($file->cid);
			$uf = Files::leftJoin('users', 'users.id','=','files.uid')
				->select(DB::raw('files.*, users.id as userId, users.cid as userCid'))
				->where('files.created_at', '>=', Carbon::today()->toDateString())
				->whereRaw('files.cid != users.id')
				->whereRaw('files.cid != users.cid')
				->where('files.cid','=',$file->cid)
				->whereNotIn('files.uid',[2,3]);

			$files = array();

			foreach ($uf->get() as $userFiles) {
				$ufu = User::findOrFail($userFiles->uid);
				$mailto = User::findOrFail($userFiles->cid)->email;
				// $mailto = 'gerjan@heinenpartners.nl';
				if ($userFiles->fid > 0) {
					$folder = $userFiles->folder()->first()->name;
				}else {
					$folder = 'Onverwerkt';
				}
				$files[] = [
					'folder' => $folder,
					'file' => $userFiles->name,
					'by' => $ufu->name,
				];
			}

			$forView = array(
	        	'name' => $user->name,
	        	'count' => $uf->count(),
	        	'files' => $files,
	        );

			Mail::queue('emails.notification', $forView, function($message) use ($mailto) {
			    $message->from('notifications@digitar.nu', 'Digitar');
			    $message->to($mailto)->subject('Nieuwe Documenten');

			});

			// return View::make('emails.notification', $forView);
		}
		
		
		// dd($mail);
	}

}