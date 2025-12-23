<?php

namespace App\Http\Controllers;

use App\Models\Files;
use App\Models\Folder;
use App\Models\Folderright;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Prologue\Alerts\Facades\Alert;

class FoldersController extends Controller
{
    public function add(Request $request): RedirectResponse
    {
        $input = $request->all();

        $rules = [
            'mapname' => 'alpha_space',
            'color' => 'required',
        ];

        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect()->to('/organization/folder/add')->withInput();
        } else {
            $f = new Folder;

            $check = $f->where('name', '=', $request->get('mapname'))->where('uid', '=', $request->user()->id);
            if ($check->count() > 0) {
                Alert::error('De map naam bestaat al.')->flash();

                return redirect()->back()->withInput();
            }

            $order = Folder::getAllUserFolders(true)->max('order') + 1;

            $f->uid = $request->user()->id;
            $f->name = $request->get('mapname');
            $f->color = $request->get('color');
            if (is_numeric($request->get('parent'))) {
                $pf = Folder::where('id', '=', $request->get('parent'));
                if ($pf->count() > 0) {
                    $f->pid = $request->get('parent');
                    $pf = $pf->first();
                } else {
                    unset($pf);
                }
            } else {
                $f->pid = null;
            }
            if ($request->has('geboekt') || isset($pf) && $pf->bookedcheck == 1) {
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

    public function edit(Request $request, $id): RedirectResponse
    {
        $rules = [
            'mapname' => 'alpha_space',
            'color' => 'required',
        ];

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();

                return redirect()->back()->withInput();
            }
        } else {
            if ($request->get('parent') == $id) {
                Alert::error('De hoofdmap mag niet het zelfde zijn.')->flash();

                return redirect()->back()->withInput();
            }
            $folder = new Folder;
            $f = $folder->find($id);

            $f->name = $request->get('mapname');
            $f->color = $request->get('color');
            if (is_numeric($request->get('parent'))) {
                $pf = $folder->where('id', '=', $request->get('parent'));
                if ($pf->count() > 0) {
                    $f->pid = $request->get('parent');
                    $pf = $pf->first();
                } else {
                    unset($pf);
                }
            } else {
                $f->pid = null;
            }
            if ($request->has('geboekt') || isset($pf) && $pf->bookedcheck == 1) {
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

    public function delete(Request $request, $id): RedirectResponse
    {
        if ($request->get('delete') == 'true') {
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

    public function deleteUser(Request $request, $uid): RedirectResponse
    {
        if ($request->get('delete') == 'true') {

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

        return redirect()->to('/organization/folders');
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

    public function geboektcheck(Request $request, $id, $json = true)
    {
        $data = false;
        if (Folder::where('id', '=', $id)->where('bookedcheck', '=', 1)->count() > 0 && $request->session()->has('highrank')) {
            $data = true;
        }
        if ($json) {
            return response()->json($data);
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
