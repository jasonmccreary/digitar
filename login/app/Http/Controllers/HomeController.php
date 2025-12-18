<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Files;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function getIndex()
    {
        if (! Auth::check()) {
            return view('login.login');
        } else {
            return view('users.viewfile');
        }
    }

    public function filesToday()
    {
        $f = Files::leftJoin('users', 'users.id', '=', 'files.uid')
            ->select(DB::raw('files.*, users.id as userId, users.cid as userCid'))
            ->where('files.created_at', '>=', Carbon::today()->toDateString())
            ->whereRaw('files.cid != users.id')
            ->whereRaw('files.cid != users.cid')
            ->whereNotIn('files.uid', [2, 3])
            ->groupBy('files.cid');
        // echo '<pre>';dd(DB::getQueryLog());

        foreach ($f->get() as $file) {
            $user = User::findOrFail($file->cid);
            $uf = Files::leftJoin('users', 'users.id', '=', 'files.uid')
                ->select(DB::raw('files.*, users.id as userId, users.cid as userCid'))
                ->where('files.created_at', '>=', Carbon::today()->toDateString())
                ->whereRaw('files.cid != users.id')
                ->whereRaw('files.cid != users.cid')
                ->where('files.cid', '=', $file->cid)
                ->whereNotIn('files.uid', [2, 3]);

            $files = [];

            foreach ($uf->get() as $userFiles) {
                $ufu = User::findOrFail($userFiles->uid);
                $mailto = User::findOrFail($userFiles->cid)->email;
                // $mailto = 'gerjan@heinenpartners.nl';
                if ($userFiles->fid > 0) {
                    $folder = $userFiles->folder()->first()->name;
                } else {
                    $folder = 'Onverwerkt';
                }
                $files[] = [
                    'folder' => $folder,
                    'file' => $userFiles->name,
                    'by' => $ufu->name,
                ];
            }

            $forView = [
                'name' => $user->name,
                'count' => $uf->count(),
                'files' => $files,
            ];

            Mail::queue('emails.notification', $forView, function ($message) use ($mailto) {
                $message->from('notifications@digitar.nu', 'Digitar');
                $message->to($mailto)->subject('Nieuwe Documenten');

            });

            // return view('emails.notification', $forView);
        }

        // dd($mail);
    }
}
