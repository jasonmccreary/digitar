<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

#[Fillable('name', 'email', 'password')]
#[Hidden('password', 'remember_token')]
class User extends Authenticatable
{
    use HasFactory;

    public function getReminderEmail()
    {
        return $this->email;
    }

    public static function byID($id)
    {
        return User::where('id', '=', $id);
    }

    public static function byUsername($username)
    {
        return User::where('username', '=', $username);
    }

    public static function checkBilling()
    {
        if (Auth::user()->billing) {
            $org = User::byID(Auth::user()->oid);
            $client = User::byID(Auth::user()->cid);

            if ($org->count() > 0) {
                $org = $org->first();
                if (! $org->billing) {
                    return false;
                }
            }

            if ($client->count() > 0) {
                $client = $client->first();
                if (! $client->billing) {
                    return false;
                }
            }

            return true;
        } else {
            return false;
        }
    }

    public static function getAllUsers($oid, $cid, $username = false)
    {
        if ($username) {
            $user = new User;
            $user = $user->where('rights', '=', '1')->where('oid', '=', $oid)->where('cid', '=', $cid)->whereRaw("(name like '%".$username."%' OR username like '%".$username."%')");
            if ($user->count() > 0) {
                return $user->get();
            }
        }

        $users = new User;
        $u = $users->where('rights', '=', '1')->where('oid', '=', $oid)->where('cid', '=', $cid)->get();
        // dd(DB::getQueryLog());

        return $u;
    }

    public static function getClientName()
    {
        if (Auth::user()->cid != 0) {
            $u = new User;
            $user = $u->where('id', '=', Auth::user()->cid)->first();
        } elseif (Auth::user()->rights >= 2) {
            $u = new User;
            $user = $u->where('id', '=', Auth::user()->id)->first();
        } elseif (Auth::user()->oid != 0) {
            $u = new User;
            $user = $u->where('id', '=', Auth::user()->oid)->first();
        } else {
            $u = new User;
            $user = $u->where('id', '=', 1)->first();
        }

        return ucwords($user->name);
    }

    public static function getFirstUser($oid, $cid, $username = false)
    {
        if ($username) {
            $user = new User;
            $user = $user->where('rights', '=', '1')->where('oid', '=', $oid)->where('cid', '=', $cid)->whereRaw("(name like '%".$username."%' OR username like '%".$username."%')");
            dd(DB::getQueryLog());
            if ($user->count() > 0) {
                return $user->get();
            }
        }
        $users = new User;
        $u = $users->where('rights', '=', '1')->where('oid', '=', $oid)->where('cid', '=', $cid)->first();
        // 		dd(DB::getQueryLog());

        return $u;
    }

    public static function getOrganizationName()
    {
        if (Auth::user()->oid != 0) {
            $u = new User;
            $user = $u->where('id', '=', Auth::user()->oid)->first();
        } else {
            $u = new User;
            $user = $u->where('id', '=', 1)->first();
        }

        if (! is_object($user)) {
            if (Auth::user()->oid != 0) {
                $u = new Organizations;
                $user = $u->where('id', '=', Auth::user()->oid)->first();
            } else {
                $u = new Organizations;
                $user = $u->where('id', '=', 1)->first();
            }
        }

        return ucwords($user->name);
    }

    public static function getUserName($uid)
    {
        $u = new User;
        $u = $u->where('id', '=', $uid);

        if ($u->count() > 0) {
            $user = $u->first();

            return ucwords($user->name);
        } else {
            $u = new User;
            $u = $u->where('id', '=', Auth::user()->cid);
        }

        return '';
    }

    public static function getUserUsername($uid)
    {
        $u = new User;
        $u = $u->where('id', '=', $uid);

        if ($u->count() > 0) {
            $user = $u->first();

            return $user->username;
        }

        return '';
    }
}
