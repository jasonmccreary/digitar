<?php

namespace App\Http\Controllers;

use App\Cloud;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Prologue\Alerts\Facades\Alert;

class CloudsController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware('folders');
    }

    public function showFiles(): \Illuminate\View\View
    {

        View::share('fid', 'files');
        $title = 'Uitwisseling bestanden';

        $files = Cloud::select('*');
        $files->where('cid', '=', Auth::user()->cid);
        $files->orderBy('date', 'asc');

        return view('users.cloud', [
            'title' => $title,
            'files' => $files->get(),
        ]);

    }

    public static function addFile($name, $filename, $cid)
    {

        $name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $name);

        $f = new Cloud;
        $f->cid = $cid;
        $f->fid = 0;
        if (Session::has('prevuid.0')) {
            $user = User::where('id', '=', Session::get('prevuid.0'))->first();
            if ($user->rights > 2) {
                $f->uid = Session::get('prevuid.0');
            } else {
                $f->uid = Auth::user()->id;
            }
        } else {
            $f->uid = Auth::user()->id;
        }
        $f->date = date('Y-m-d H:i:s');
        $f->file = $filename;
        $f->name = $name;
        $f->save();

    }

    public function editFile($id): RedirectResponse
    {

        if (Auth::user()->lookonly == 1) {
            Alert::error('U mag geen wijzigingen doorvoeren.')->flash();

            return Redirect::back();
        }

        $input = Request::all();

        $rules = [
            'name' => 'required',
            'date' => 'after:1970|before:01-01-'.(date('Y') + 22),
        ];

        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }
        } else {

            $file = new Cloud;
            $f = $file->find($id);
            $f->name = $input['name'];
            $f->date = date('Y-m-d H:i:s', strtotime($input['date']));
            $f->note = $input['note'];
            $f->save();

        }

        Alert::success('Uw wijzigingen zijn succesvol doorgevoerd.')->flash();

        return Redirect::back();

    }

    public static function deleteFile($fid)
    {
        $f = new Cloud;
        $f = $f
            ->where('id', '=', $fid)
            ->where('cid', '=', Auth::user()->cid);

        $file = $f->first();

        $u = new User;
        $organization = $u
            ->where('id', '=', Auth::user()->oid)
            ->first();
        $client = $u
            ->where('id', '=', Auth::user()->cid)
            ->where('oid', '=', Auth::user()->oid)
            ->first();

        $fileurl = '../../../clients/'.$organization->username.'/'.$client->username.'/'.$file->file;
        if (File::exists($fileurl)) {
            unlink($fileurl);
        }

        $f->delete();
    }

    public function viewdetails($fid): \Illuminate\View\View
    {

        $f = new Cloud;
        $file = $f
            ->where('id', '=', $fid)
            ->where('cid', '=', Auth::user()->cid)
            ->first();

        return view('users.viewdetails', [
            'file' => $file,
        ]);
    }

    public function downloadFile($fid)
    {
        @ini_set('zlib.output_compression', 'Off');

        $aFile = Cloud::where('id', '=', $fid)->first();
        $file_path = FileController::getFolderPath().$aFile->file;
        $path_parts = pathinfo($file_path);
        $file_name = $path_parts['basename'];
        $file_ext = $path_parts['extension'];
        $file_path = FileController::getFolderPath().$aFile->file;

        $is_attachment = isset($_REQUEST['stream']) ? false : true;

        if (is_file($file_path)) {
            $file_size = filesize($file_path);
            $file = @fopen($file_path, 'rb');
            if ($file) {

                header('Pragma: public');
                header('Expires: -1');
                header('Cache-Control: public, must-revalidate, post-check=0, pre-check=0');
                header('Content-Disposition: attachment; filename="'.$aFile->name.'.'.$file_ext.'"');

                if ($is_attachment) {
                    header('Content-Disposition: attachment; filename="'.$aFile->name.'.'.$file_ext.'"');
                } else {
                    header('Content-Disposition: inline;');
                }

                $ctype_default = 'application/octet-stream';
                $content_types = [
                    'exe' => 'application/octet-stream',
                    'zip' => 'application/zip',
                    'mp3' => 'audio/mpeg',
                    'mpg' => 'video/mpeg',
                    'avi' => 'video/x-msvideo',
                ];
                $ctype = isset($content_types[$file_ext]) ? $content_types[$file_ext] : $ctype_default;
                header('Content-Type: '.$ctype);

                if (isset($_SERVER['HTTP_RANGE'])) {
                    [$size_unit, $range_orig] = explode('=', $_SERVER['HTTP_RANGE'], 2);
                    if ($size_unit == 'bytes') {
                        [$range, $extra_ranges] = explode(',', $range_orig, 2);
                    } else {
                        $range = '';
                        header('HTTP/1.1 416 Requested Range Not Satisfiable');
                        exit;
                    }
                } else {
                    $range = '';
                }

                @[$seek_start, $seek_end] = explode('-', $range, 2);

                $seek_end = (empty($seek_end)) ? ($file_size - 1) : min(abs(intval($seek_end)), ($file_size - 1));
                $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)), 0);

                if ($seek_start > 0 || $seek_end < ($file_size - 1)) {
                    header('HTTP/1.1 206 Partial Content');
                    header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$file_size);
                    header('Content-Length: '.($seek_end - $seek_start + 1));
                } else {
                    header("Content-Length: $file_size");
                }

                header('Accept-Ranges: bytes');

                set_time_limit(0);
                fseek($file, $seek_start);

                while (! feof($file)) {
                    echo @fread($file, 1024 * 8);
                    @ob_flush();
                    flush();
                    if (connection_status() != 0) {
                        @fclose($file);
                        exit;
                    }
                }

                // file save was a success
                @fclose($file);
                exit;
            } else {
                // file couldn't be opened
                header('HTTP/1.0 500 Internal Server Error');
                exit;
            }
        } else {
            // file does not exist
            header('HTTP/1.0 404 Not Found');
            exit;
        }
    }
}
