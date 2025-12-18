<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Usermods extends Model
{
    public static function getModNames($uid)
    {
        $u = Usermods::join('users', 'users.id', '=', 'usermods.modid');
        $u->where('users.rights', '=', '3');
        $u->where('users.oid', '=', Auth::user()->id);
        $u->where('usermods.uid', '=', $uid);
        $u->select('users.*');

        $mods = '';
        foreach ($u->get() as $mod) {
            $mods .= $mod->name.', ';
        }
        $mods = substr($mods, 0, -2);

        return $mods;
    }

    public static function checked($modid, $uid)
    {
        $u = Usermods::where('modid', '=', $modid);
        $u->where('uid', '=', $uid);

        if ($u->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function countClients($modid)
    {
        return Usermods::where('modid', '=', $modid)->count();
    }
}
