<?php

namespace App\Http\Controllers;

use App\Files;
use App\Folder;
use App\Folderright;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class FoldersController extends Controller
{
    public function add()
    {
        $input = Request::all();

        $rules = [
            'mapname' => 'alpha_space',
            'color' => 'required',
        ];

        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect('/organization/folder/add')->withInput();
        } else {
            $f = new Folder;

            $check = $f->where('name', '=', Request::get('mapname'))->where('uid', '=', Auth::user()->id);
            if ($check->count() > 0) {
                Alert::error('De map naam bestaat al.')->flash();

                return Redirect::back()->withInput();
            }

            $order = Folder::getAllUserFolders(true)->max('order') + 1;

            $f->uid = Auth::user()->id;
            $f->name = Request::get('mapname');
            $f->color = Request::get('color');
            if (is_numeric(Request::get('parent'))) {
                $pf = Folder::where('id', '=', Request::get('parent'));
                if ($pf->count() > 0) {
                    $f->pid = Request::get('parent');
                    $pf = $pf->first();
                } else {
                    unset($pf);
                }
            } else {
                $f->pid = null;
            }
            if (Request::has('geboekt') || isset($pf) && $pf->bookedcheck == 1) {
                $f->bookedcheck = 1;
            } else {
                $f->bookedcheck = 0;
            }
            $f->order = $order;

            $f->save();

            Alert::success('Standaard map succesvol toegevoegd')->flash();

            return Redirect::route('folders');
        }
    }

    public function edit($id)
    {
        $rules = [
            'mapname' => 'alpha_space',
            'color' => 'required',
        ];

        $v = Validator::make(Request::all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();

                return Redirect::back()->withInput();
            }
        } else {
            if (Request::get('parent') == $id) {
                Alert::error('De hoofdmap mag niet het zelfde zijn.')->flash();

                return Redirect::back()->withInput();
            }
            $folder = new Folder;
            $f = $folder->find($id);

            $f->name = Request::get('mapname');
            $f->color = Request::get('color');
            if (is_numeric(Request::get('parent'))) {
                $pf = $folder->where('id', '=', Request::get('parent'));
                if ($pf->count() > 0) {
                    $f->pid = Request::get('parent');
                    $pf = $pf->first();
                } else {
                    unset($pf);
                }
            } else {
                $f->pid = null;
            }
            if (Request::has('geboekt') || isset($pf) && $pf->bookedcheck == 1) {
                $f->bookedcheck = 1;
            } else {
                $f->bookedcheck = 0;
            }
            // echo '<pre>';dd($folder);echo '</pre>';
            $f->save();

            Alert::success('Uw wijzigingen zijn succesvol doorgevoerd.')->flash();
        }

        return Redirect::route('folders');
    }

    public function delete($id)
    {
        if (Request::get('delete') == 'true') {
            $sf = new Folder;

            $folder = $sf->find($id);
            if ($folder->count() > 0 && Files::where('fid', '=', $folder->id)->count() > 0) {
                Alert::error('Kan niet verwijderen, zitten nog bestanden in de map!')->flash();
            } else {
                $folder->delete();
                Alert::success('Map succesvol verwijderd')->flash();
            }
        }

        return Redirect::route('folders');
    }

    public function deleteUser($uid)
    {
        if (Request::get('delete') == 'true') {

            foreach (Folder::where('uid', '=', $uid)->get() as $f) {

                $folder = Folder::where('id', '=', $f->id);

                $uf = $folder->first();

                $u = User::where('id', '=', $uf->uid)->first();
                $o = User::where('id', '=', $u->oid)->first();
                $fp = '../../../clients/'.$o->username.'/'.$u->username.'/';
                $this->hardDelete($fp);

                $folder->delete();
            }

            Folderright::where('uid', '=', $uid)->delete();

            Alert::success('Map succesvol verwijderd')->flash();
        }

        return redirect('/organization/folders');
    }

    public function sort()
    {
        foreach ($_POST['fid'] as $order => $sfid) {
            $sf = new Folder;
            $f = $sf->find($sfid);

            echo $order.' > '.$f->name;
            $f->order = $order;
            $f->save();
        }
    }

    public function geboektcheck($id, $json = true)
    {
        $data = false;
        if (Folder::where('id', '=', $id)->where('bookedcheck', '=', 1)->count() > 0 && Session::has('highrank')) {
            $data = true;
        }
        if ($json) {
            return Response::json($data);
        } else {
            return $data;
        }
    }

    public function hardDelete($path)
    {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), ['.', '..']);

            foreach ($files as $file) {
                $this->hardDelete(realpath($path).'/'.$file);
            }

            return rmdir($path);
        } elseif (is_file($path) === true) {
            return unlink($path);
        }

        return false;
    }
}
