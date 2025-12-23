<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    protected $guarded = [];

    public static $rules = [];

    protected $table = 'messages';

    public static function newMessage($cid, $bid, $title, $message, $type = false)
    {
        $m = new Messages;
        $m->cid = $cid;
        $m->bid = $bid;
        if ($type != false) {
            $m->type = $type;
        }
        $m->title = $title;
        $m->message = $message;
        $m->save();
    }

    public static function count($cid)
    {
        return Messages::where('cid', '=', $cid)->whereNull('read')->count();
    }

    public static function get($cid, $old = false)
    {
        if (! $old) {
            return Messages::where('cid', '=', $cid)->whereNull('read')->orderByDesc('created_at')->get();
        } else {
            return Messages::where('cid', '=', $cid)->where('read', '!=', 'NULL')->orderByDesc('created_at')->get();
        }
    }
}
