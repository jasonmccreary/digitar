<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Folder extends Model
{
    protected $guarded = [];

    public static $rules = [];

    public static function getSubFolders($pid, $useronly = false)
    {
        if ($useronly) {
            return Folder::where('pid', '=', $pid)->where('uid', '=', Auth::user()->id)->get();
        } else {
            return Folder::whereExists(function ($query) {
                $query->from('folderrights')
                    ->whereRaw('folderrights.fid = folders.id')
                    ->where('folderrights.uid', '=', Auth::user()->id);
            })
                ->where('pid', '=', $pid)
                ->orWhere('uid', '=', Auth::user()->id)
                ->where('pid', '=', $pid)->get();
        }
    }

    public function checked($fid, $uid)
    {
        $num = Folderright::where('uid', '=', $uid)->where('fid', '=', $fid)->count();
        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getAllUserFolders($resource = false)
    {
        if (! Auth::check()) {
            return redirect('/');
        }
        $folder = Folder::whereExists(function ($query) {
            $query->from('folderrights')
                ->whereRaw('folderrights.fid = folders.id')
                ->where('folderrights.uid', '=', Auth::user()->id);
        })
            ->whereNull('pid')
            ->orWhere('uid', '=', Auth::user()->id)
            ->orderBy('order')
            ->whereNull('pid');
        if ($resource) {
            return $folder;
        } else {
            return $folder->get();
        }

        // return Folder::whereRaw("uid = '". Auth::user()->oid ."' AND pid IS NULL OR uid = '". Auth::user()->id."' AND pid IS NULL")->orderBy('order')->get();
    }

    public function rights()
    {
        return $this->belongsTo('App\Folderright', 'id', 'fid');
    }
}
