<?php

namespace App\Http\Controllers;

use App\Usermods;
use Illuminate\Support\Facades\Request;

class ModeratorController extends Controller
{
    public static function edit($uid)
    {

        // Alert::info(print_r(Request::get('mod'),true))->flash();
        // return Redirect::back();

        $dd = [];
        $add = [];

        if (is_array(Request::get('mod'))) {
            foreach (Request::get('mod') as $modid => $val) {
                $um = Usermods::where('modid', '=', $modid);
                $um->where('uid', '=', $uid);

                if ($um->count() > 0) {
                    $dd[] = $modid;
                } else {
                    $dd[] = $modid;
                    $add[] = $modid;
                }
            }
            foreach ($add as $m) {
                $am = new Usermods;
                $am->uid = $uid;
                $am->modid = $m;
                $am->save();
            }
        }

        $dm = Usermods::where('uid', '=', $uid);
        foreach ($dm->get() as $m) {
            if (! in_array($m->modid, $dd)) {
                $am = Usermods::where('modid', '=', $m->modid);
                $am->where('uid', '=', $uid);
                $am->delete();
            }
        }

        return redirect('/organization/moderator/link');
    }
}
