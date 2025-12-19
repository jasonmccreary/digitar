<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Files extends Model
{
    public static function getNumUnsorted($cid)
    {
        return Files::where('cid', '=', $cid)->where('fid', '=', '0')->count();
    }

    public static function getNumUnbooked($cid)
    {
        $total = 0;
        foreach (Folderright::where('uid', '=', $cid)->get() as $fr) {
            foreach (Folder::where('id', '=', $fr->fid)->where('bookedcheck', '=', '1')->get() as $folder) {
                $total = $total + Files::where('cid', '=', $cid)->where('fid', '=', $folder->id)->whereNull('geboekt')->count();
                // $total = $folder->id;
            }
        }

        return $total;
    }

    public static function getYears()
    {
        $cid = Auth::user()->cid;
        $return = Files::select(DB::raw('year(date) as year'))->where('cid', '=', $cid)->groupBy('year')->get();

        $check = false;
        foreach ($return as $y) {
            if ($y->year == date('Y')) {
                $check = true;
            }
        }
        if (! $check) {
            $return[] = (object) ['year' => date('Y')];
        }

        return $return;
    }

    public static function getFileById($id)
    {
        return Files::where('id', '=', $id)->first();
    }

    public static function countTotal($uid)
    {
        return Files::where('cid', '=', $uid)->count();
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(\App\Folder::class, 'fid', 'id');
    }

    // public function userss() {
    // 	return $this->belongsTo('App\User','uid','id');
    // }

    public function rights()
    {
        $this->folder()->first()->rights();
    }
}
