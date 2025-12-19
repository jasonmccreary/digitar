<?php

namespace App\Http\Controllers;

use App\Files;
use App\Folder;
use App\Folderright;
use App\Messages;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Prologue\Alerts\Facades\Alert;
use Symfony\Component\Process\Process;

class FileController extends Controller
{
    private $fileurl;

    public function __construct()
    {

        // $this->middleware('auth');
        $this->middleware('folders');
    }

    public function showFiles($fid, $ajax = false)
    {
        if (! Auth::check() || Auth::user()->rights != 1) {
            return redirect('/');
        }
        View::share('fid', $fid);

        if (! is_numeric($fid)) {
            if ($fid == 'inbox' && Auth::user()->onverwerkt == 0) {
                $folder = Folderright::where('uid', '=', Auth::user()->id)->orderBy('id', 'desc')->first();

                // dd($folder);
                return redirect('/user/folder/'.$folder->fid);
            }
            switch ($fid) {
                case 'inbox':
                    $title = 'Onverwerkt';
                    break;
                default:
                    $title = 'Zoeken naar: '.ucfirst($fid);
                    break;
            }
            $bookedcheck = 1;
        } else {
            if (Folderright::where('fid', '=', $fid)->where('uid', '=', Auth::user()->id)->count() <= 0) {
                return redirect('403');
            } // IF A USER IS NOT AUTHORIZED TO VIEW THE FOLDER
            $fo = Folder::where('id', '=', $fid)->first();
            $title = $fo->name;
            $bookedcheck = $fo->bookedcheck;
        }

        $files = Files::select('id', 'fid', 'name', 'geboekt', 'uid', 'date');
        $files->where('cid', '=', Auth::user()->cid);
        $files->where('fid', '=', $fid);
        if ($fid != 'inbox' && $fid != 'geboekt') {
            $files->where('date', 'like', Session::get('year').'%');
        }
        if ($fid == 'geboekt') {
            $files->where('geboekt', '=', '0');
        }
        $files->orderBy('date', 'desc');

        if ($ajax == false) {
            return view('users.files', [
                'title' => $title,
                'noGrid' => 'true',
            ]);
        } elseif (Request::is('*ajax*')) {
            $jsonfiles = [];

            foreach ($files->get() as $key => $file) {
                $jsonfiles[] = (object) [
                    'id' => $file->id,
                    'fid' => $file->fid,
                    'name' => $file->name,
                    'geboekt' => $file->geboekt,
                    'uid' => $file->uid,
                    'username' => User::getUserName($file->uid),
                    'date' => strtotime($file->date),
                    'nicedate' => nice_date($file->date),
                    'highrank' => Session::has('highrank'),
                    'bookedcheck' => $bookedcheck,
                    'rights' => Auth::user()->rights,
                ];
            }

            return Response::json(['data' => $jsonfiles], 200);
        }

    }

    public static function getOngeboekt()
    {
        if (! Auth::check() || Auth::user()->rights != 1) {
            return Response::json(['data' => []], 200);
        }
        $user = User::where('id', '=', Auth::user()->id)->first();
        $files = Files::where('geboekt', '=', 0)->whereExists(function ($query) {
            $query->from('folders')
                ->whereRaw('folders.id = files.fid')
                ->where('folders.bookedcheck', '=', 1);
        })->whereExists(function ($query) {
            $query->from('folderrights')
                ->whereRaw('files.fid = folderrights.fid')
                ->where('folderrights.uid', '=', Auth::user()->id);
        })
            ->where('cid', '=', $user->cid)
            ->orWhere('geboekt', '=', null)->whereExists(function ($query) {
                $query->from('folders')
                    ->whereRaw('folders.id = files.fid')
                    ->where('folders.bookedcheck', '=', 1);
            })->whereExists(function ($query) {
                $query->from('folderrights')
                    ->whereRaw('files.fid = folderrights.fid')
                    ->where('folderrights.uid', '=', Auth::user()->id);
            })
            ->where('cid', '=', $user->cid)
            ->orderBy('date', 'desc');

        if (Request::is('*ajax*')) {
            $jsonfiles = [];

            foreach ($files->get() as $key => $file) {
                $jsonfiles[] = (object) [
                    'id' => $file->id,
                    'fid' => $file->fid,
                    'name' => $file->name,
                    'geboekt' => $file->geboekt,
                    'uid' => $file->uid,
                    'username' => User::getUserName($file->uid),
                    'date' => strtotime($file->date),
                    'nicedate' => nice_date($file->date),
                    'highrank' => Session::has('highrank'),
                    'bookedcheck' => 1,
                    'rights' => Auth::user()->rights,
                ];
            }

            return Response::json(['data' => $jsonfiles], 200);
        } else {
            return $files->get();
        }
    }

    public function searchFiles($search): JsonResponse
    {
        $jsonfiles = [];
        $f = new Files;
        $files = $f->selectRaw("*, MATCH (name,note,contents) AGAINST ('".$search."' IN BOOLEAN MODE) AS relevance_score")
            ->where(
                'cid', '=', Auth::user()->cid
            )->where(function ($query) use ($search) {
                // $query->where('name', 'LIKE', '%'.$search.'%')
                // 	->orWhere('note', 'LIKE', '%'.$search.'%')
                // 	->orWhere('contents', 'LIKE', '%'.$search.'%');
                $query->whereRaw("MATCH (name,note,contents) AGAINST ('".$search."' IN BOOLEAN MODE)");
            })->orderBy('date', 'DESC')->orderBy('relevance_score', 'DESC')->paginate(250);

        foreach ($files as $key => $file) {
            if (Folderright::where('fid', '=', $file->fid)->where('uid', '=', Auth::user()->id)->count() > 0) { // Only view files from folders wich user is authorized to, otherwise skip the file.
                $jsonfiles[] = (object) [
                    'id' => $file->id,
                    'fid' => $file->fid,
                    'name' => $file->name,
                    'geboekt' => $file->geboekt,
                    'uid' => $file->uid,
                    'username' => User::getUserName($file->uid),
                    'date' => strtotime($file->date),
                    'nicedate' => nice_date($file->date),
                    'highrank' => Session::has('highrank'),
                    'rights' => Auth::user()->rights,
                    'relevance_score' => $file->relevance_score,
                ];
            }
        }

        return Response::json(['data' => $jsonfiles], 200);

    }

    public static function addFile($name, $filename, $cid, $fileContents = '')
    {

        $name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $name);

        $f = new Files;
        $f->cid = $cid;
        $f->fid = 0;
        if (Session::has('prevuid.0')) {
            $user = User::where('id', '=', Session::get('prevuid.0'))->first();
            if ($user->rights > 2) {
                $f->uid = Session::get('prevuid.0');
                Messages::newMessage(Auth::user()->cid, $user->id, 'Nieuw bestand!', $user->name.' heeft het bestand "'.$name.'" toegevoegd!');
            } else {
                $f->uid = Auth::user()->id;
            }
        } else {
            $f->uid = Auth::user()->id;
        }
        if ($fileContents != '') {
            $f->contents = $fileContents;
        } else {
            $client = User::find($cid);
            $compressPath = '../../../clients/'.User::getUserUsername($client->oid).'/'.$client->username.'/';
            $tmpPath = '../tmp/';
            $filenameTxt = str_replace('.pdf', '', $filename).'.txt';

            $io = shell_exec("pdftotext '".$compressPath.$filename."' '".$tmpPath.$filenameTxt."'");
            if (file_exists($tmpPath.$filenameTxt)) {
                $fileContents = file_get_contents($tmpPath.$filenameTxt);
                unlink($tmpPath.$filenameTxt);
            } else {
                $fileContents = null;
            }

            $f->contents = $fileContents;
        }
        $f->file = $filename;
        $f->name = $name;
        $f->save();

        return $f;
    }

    public function editFile($id): RedirectResponse
    {

        if (Auth::user()->lookonly == 1) {
            Alert::error('U mag geen wijzigingen doorvoeren.')->flash();

            return Redirect::back();
        }

        $input = Request::all();

        $rules = [
            'folder' => 'required',
            'name' => 'required',
            'date' => 'after:1970|before:01-01-'.(date('Y') + 22),
        ];

        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }
        } else {

            $file = new Files;
            $f = $file->find($id);

            $client = User::find(Auth::user()->cid);
            $compressPath = '../../../clients/'.User::getUserUsername($client->oid).'/'.$client->username.'/';
            $tmpPath = '../tmp/';
            $filenameTxt = str_replace('.pdf', '', $f->file).'.txt';

            $io = shell_exec("pdftotext '".$compressPath.$f->file."' '".$tmpPath.$filenameTxt."'");
            if (file_exists($tmpPath.$filenameTxt)) {
                $fileContents = file_get_contents($tmpPath.$filenameTxt);
                unlink($tmpPath.$filenameTxt);
            } else {
                $fileContents = null;
            }

            $f->contents = $fileContents;

            // $file = new Files();
            // $f = $file->find($id);
            $f->fid = Request::get('folder');
            $f->geboekt = Request::get('geboekt');
            $f->name = Request::get('name');
            $f->date = date('Y-m-d H:i:s', strtotime(Request::get('date')));
            $f->note = Request::get('note');
            $f->save();

        }

        Alert::success('Uw wijzigingen zijn succesvol doorgevoerd.')->flash();

        return Redirect::back();

    }

    public static function deleteFile($fid)
    {
        $f = new Files;
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

    public static function markBooked($fids): RedirectResponse
    {
        $fc = new FoldersController;
        foreach ($fids as $fid => $v) {
            $f = new Files;
            $f = $f->where('id', '=', $fid)->where('cid', '=', Auth::user()->cid);
            if ($f->count() > 0) {
                $file = $f->first();
                if ($fc->geboektcheck($file->fid, false)) {
                    $file->geboekt = 1;
                    $file->save();
                }
            } else {
                Auth::logout();
                Alert::error('DE ACTIE DIE U PROBEERT UIT TE VOEREN IS NIET TOEGESTAAN!!!')->flash();

                return redirect('/');
            }

            Alert::success('De documenten zijn gemarkeerd als geboekt.')->flash();
        }
    }

    public static function moveToFolder($fids, $foid): RedirectResponse
    {
        $fc = new FoldersController;
        foreach ($fids as $fid => $v) {
            $f = new Files;
            $f = $f->where('id', '=', $fid)->where('cid', '=', Auth::user()->cid);
            if ($f->count() > 0) {
                $file = $f->first();
                // if ($fc->geboektcheck($file->fid,false)) {
                $file->fid = $foid;
                $file->save();
                // }
            } else {
                Auth::logout();
                Alert::error('DE ACTIE DIE U PROBEERT UIT TE VOEREN IS NIET TOEGESTAAN!!!')->flash();

                return redirect('/');
            }

            Alert::success('De documenten zijn verplaatst naar de map.')->flash();
        }
    }

    public static function downloadFiles($fids)
    {
        $first = $fids;
        reset($first);
        $first = key($first);

        $f = new Files;
        $f = $f
            ->where('id', '=', $first)
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

        $path = realpath($_SERVER['DOCUMENT_ROOT'].'/../../../').'/clients/'.$organization->username.'/'.$client->username.'/';
        $bind = '';
        $bindRM = '';
        foreach ($fids as $fid => $v) {

            $f = new Files;
            $f = $f
                ->where('id', '=', $fid)
                ->where('cid', '=', Auth::user()->cid);
            $file = $f->first();

            $filename = strtolower($file->name);
            $filename = str_replace([' ', '&', '|', '*', '%', '$', '@', '!', '#', '(', ')', ',', '.', '\'', '"', '/', '\\'], '', $filename);

            if (strpos($bind, $filename) !== false) {
                $filename .= uniqid();
            }

            $bind .= ''.$filename.'.pdf ';
            $bindRM .= '/home/digitar/mono/'.$file->file.' ';

            // dd('cp '.$path.$file->file.' /home/digitar/mono/'.$file->file.'');
            $process = new Process('cp '.$path.$file->file.' /home/digitar/mono/'.$filename.'.pdf');
            $process->run(function ($type, $buffer) {
                if ($type === 'err') {
                    Log::error('Error converting .ps file back to .pdf > '.$buffer);
                }
            });
        }

        $zipname = Str::random(8);
        // dd('zip /home/digitar/mono/'.$zipname.'.zip '.$bind.'');
        $process = new Process('cd /home/digitar/mono/; zip '.$zipname.'.zip '.$bind.'');
        $process->run(function ($type, $buffer) {
            if ($type === 'err') {
                Log::error('ZIP creation ERROR > '.$buffer);
            }
        });

        $process = new Process('rm -rf '.$bindRM);
        $process->run(function ($type, $buffer) {
            if ($type === 'err') {
                Log::error('Error removing all generated .ps files > '.$buffer);
            }
        });

        @ini_set('zlib.output_compression', 'Off');

        $file_ext = '.zip';
        $file_name = $zipname.'.zip';
        $file_path = '../../../mono/'.$zipname.'.zip';

        $is_attachment = isset($_REQUEST['stream']) ? false : true;

        if (is_file($file_path)) {
            $file_size = filesize($file_path);
            $file = @fopen($file_path, 'rb');
            if ($file) {

                header('Pragma: public');
                header('Expires: -1');
                header('Cache-Control: public, must-revalidate, post-check=0, pre-check=0');
                header("Content-Disposition: attachment; filename=\"$file_name\"");

                if ($is_attachment) {
                    header("Content-Disposition: attachment; filename=\"$file_name\"");
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

        Alert::success('Bestanden omgezet naar zwart/wit en worden gedownload. '.$bindRM)->flash();
    }

    public static function combineFiles($fids)
    {
        $first = $fids;
        reset($first);
        $first = key($first);

        $f = new Files;
        $f = $f
            ->where('id', '=', $first)
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

        $path = realpath($_SERVER['DOCUMENT_ROOT'].'/../../../').'/clients/'.$organization->username.'/'.$client->username.'/';
        $newName = '';
        $bind = '';
        $ids = [];
        $geboekt = 1;
        foreach ($fids as $fid => $v) {
            array_push($ids, $fid);

            $f = new Files;
            $f = $f
                ->where('id', '=', $fid)
                ->where('cid', '=', Auth::user()->cid);
            $file = $f->first();

            if ($file->geboekt < 1) {
                $geboekt = 0;
            }

            $newName .= ' + '.$file->name;
            $bind .= $path.$file->file.' ';
            if (! strpos($file->file, '.pdf')) {
                Alert::error('Bestand ('.$file->name.' is geen PDF bestand')->flash();

                return false;
            }
        }

        $newfile = Str::random(32).'.pdf';
        $process = new Process('/usr/local/bin/pdftk '.$bind.'cat output '.$path.$newfile);
        $process->run(function ($type, $buffer) {
            if ($type === 'err') {
                Log::error('Combining files ERROR > '.$buffer);
                // Log::error('- > '.$bind);
                Alert::error('Kon de bestanden niet samenvoegen')->flash();

                return false;
            }
        });

        foreach ($fids as $fid => $v) {
            FileController::deleteFile($fid);
        }

        $nf = FileController::addFile($newName, $newfile, Auth::user()->cid);
        $nf->geboekt = $geboekt;
        $nf->save();

        Alert::success('Bestanden samengevoegd.')->flash();
    }

    public static function splitFiles($fid)
    {
        $f = new Files;
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

        $path = realpath($_SERVER['DOCUMENT_ROOT'].'/../../../').'/clients/'.$organization->username.'/'.$client->username.'/';
        if (File::extension(FileController::getFolderPath(0).$file->file) == 'pdf') {

            $process = new Process('/usr/local/bin/pdftk '.$path.$file->file.' dump_data | grep NumberOfPages');
            $process->run(function ($type, $buffer) {
                if ($type === 'err') {
                    Log::error('Finding number of pages before splitting files ERROR > '.$buffer);
                }
            });
            $num = explode(':', $process->getOutput());

            if ($num[1] > 1) {
                $newFileName = Str::random(32);
                $process = new Process('/usr/local/bin/pdftk '.$path.$file->file.' burst output '.$path.$newFileName.'-%03d.pdf compress');
                $process->run(function ($type, $buffer) {
                    if ($type === 'err') {
                        Log::error('Splitting files ERROR > '.$buffer);
                    }
                });
                for ($i = 1; $i <= $num[1]; $i++) {

                    $newName = sprintf('%03d', $i).' - '.$file->name;
                    $newFile = $newFileName.'-'.sprintf('%03d', $i).'.pdf';
                    $ff = FileController::addFile($newName, $newFile, Auth::user()->cid);
                    $ff->geboekt = $file->geboekt;
                    $ff->save();

                }

                FileController::deleteFile($file->id);

            }
        }
    }

    public function upload()
    {
        $input = Request::all();
        $file = Request::file('file');
        // dd($file);
        if (! is_object($file)) {
            return response('Geen geldig bestands type.', 400);
        }

        if (Request::get('userid')) {
            Auth::loginUsingId(Request::get('userid'));
        }

        $v1 = Validator::make($input, ['file' => 'mimes:jpg,jpeg,png,pdf|max:10240']);
        if (! $v1->fails()) {
            $u = new User;
            $organization = $u
                ->where('id', '=', Auth::user()->oid)
                ->first();
            $client = $u
                ->where('id', '=', Auth::user()->cid)
                ->where('oid', '=', Auth::user()->oid)
                ->first();

            $destinationPath = '../../../clients/'.$organization->username.'/'.$client->username.'';
            $filename = Str::random(32).'.'.Request::file('file')->getClientOriginalExtension();
            while (File::exists($destinationPath.'/'.$filename)) {
                $filename = Str::random(32).'.'.Request::file('file')->getClientOriginalExtension();
            }

            $upload_success = Request::file('file')->move($destinationPath, $filename);

            // compress

            if ($upload_success) {
                $compressPath = '../../../clients/'.$organization->username.'/'.$client->username.'/';
                $tmpPath = '../tmp/';
                $filenameTxt = str_replace(Request::file('file')->getClientOriginalExtension(), '.txt', $filename);

                $io = shell_exec("pdftotext '".$compressPath.$filename."' '".$tmpPath.$filenameTxt."'");
                if (file_exists($tmpPath.$filenameTxt)) {
                    $fileContents = file_get_contents($tmpPath.$filenameTxt);
                    unlink($tmpPath.$filenameTxt);
                } else {
                    $fileContents = null;
                }

                if (in_array(Request::file('file')->getClientOriginalExtension(), ['png', 'jpg', 'jpeg'])) {
                    $filename2 = str_replace(Request::file('file')->getClientOriginalExtension(), 'pdf', $filename);
                    exec('convert '.$compressPath.$filename.' '.$compressPath.$filename2.'');
                    unlink($compressPath.$filename);
                    $filename = $filename2;
                }

                if (Request::get('filename')) {
                    $savefilename = Request::get('filename');
                } else {
                    $savefilename = $file->getClientOriginalName();
                }
                $newfile = $this->addFile($savefilename, $filename, $client->id, $fileContents);

                $fidnf = (Request::get('fid') * 1);
                if (is_numeric($fidnf) && $fidnf > 0) {
                    $newfile->fid = $fidnf;
                    $newfile->save();
                }

                if (filesize($compressPath.$filename) > 524000) {

                    $out1 = explode('.', $filename);
                    $out1 = $out1[0];
                    $output = $out1.'-printer.pdf';

                    exec('convert -compress JPEG -density 300 -quality 50 '.$compressPath.$filename.' '.$tmpPath.$output.'');
                    if (file_exists($tmpPath.$output) && filesize($tmpPath.$output) < filesize($compressPath.$filename)) {
                        unlink($compressPath.$filename);
                        rename($tmpPath.$output, $compressPath.$filename);
                        @unlink($tmpPath.$output);
                    }
                }

                return Response::json('success', 200);
            } else {
                return Response::json('error', 400);
            }
        }

        $v2 = Validator::make($input, ['file' => 'mimes:xml,csv,doc,docx,xls,xlsx,swi,txt,mut,bacpac,ssb,|max:10240']);
        if (! $v2->fails()) {
            $u = new User;
            $organization = $u
                ->where('id', '=', Auth::user()->oid)
                ->first();
            $client = $u
                ->where('id', '=', Auth::user()->cid)
                ->where('oid', '=', Auth::user()->oid)
                ->first();

            $destinationPath = '../../../clients/'.$organization->username.'/'.$client->username.'';
            $filename = Str::random(32).'.'.Request::file('file')->getClientOriginalExtension();
            while (File::exists($destinationPath.'/'.$filename)) {
                $filename = Str::random(32).'.'.Request::file('file')->getClientOriginalExtension();
            }

            $upload_success = Request::file('file')->move($destinationPath, $filename);

            // compress

            if ($upload_success) {
                CloudsController::addFile($file->getClientOriginalName(), $filename, $client->id);

                return Response::json('success', 200);
            } else {
                return Response::json('error', 400);
            }
        }
    }

    public function viewfile($fid): \Illuminate\View\View
    {

        $f = new Files;
        $file = $f
            ->where('id', '=', $fid)
            ->where('cid', '=', Auth::user()->cid)
            ->first();

        return view('users.viewfile', [
            'file' => $file,
        ]);
    }

    public function loadpdf($fid): RedirectResponse
    {
        $f = new Files;

        $file = $f
            ->where('id', '=', $fid)
            ->where('cid', '=', Auth::user()->cid)
            ->first();

        $organization = User::where('id', '=', Auth::user()->oid)->first();
        $client = User::where('id', '=', Auth::user()->cid)->where('oid', '=', Auth::user()->oid);
        if (! $client->count()) {
            return redirect('/user/folder/inbox');
        }
        $path = '/home/digitar/clients/'.$organization->username.'/'.$client->first()->username.'/';

        $filepath = $path.$file->file;
        $filename = $file->name;
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="'.$filename.'"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: '.filesize($filepath));
        header('Accept-Ranges: bytes');
        @readfile($filepath);

    }

    public function loadfile($fid = false, $viewer = 'pdfjs'): RedirectResponse
    {
        if ($fid != false) {
            $f = new Files;
            $file = $f
                ->where('id', '=', $fid)
                ->where('cid', '=', Auth::user()->cid)
                ->first();

            $uf = Folderright::where('fid', '=', $file->fid)->where('uid', '=', Auth::user()->id)->count();
            if ($uf > 0 || $file->fid == 0) {

                $fileurl = FileController::getFolderPath($file->fid, false, 'cf').$file->file;
                if (File::extension($fileurl) == 'pdf' || File::extension($fileurl) == 'PDF') {
                    $isIE10 = (preg_match('/(?i)msie [10]/', $_SERVER['HTTP_USER_AGENT']));
                    $isIE11UP = (preg_match('/(?i)trident\/[7-9]/', $_SERVER['HTTP_USER_AGENT']));
                    $isIE10UP = ($isIE10 == 1 || $isIE11UP == 1 ? 1 : 0);
                    if ($viewer == 'pdfjs') {
                        return redirect('/pdfjs/web/viewer.html?file=/user/loadpdf/'.$file->id);
                    } else {
                        return redirect('/user/loadpdf/'.$file->id);
                    }
                } else {
                    header('Content-Type: image/jpeg');
                }

                echo file_get_contents($fileurl);
            } else {
                echo '
					<br /><br /><br />
					<h1 style="color:#fff;"><center>U mag dit bestand niet bekijken</center></h1>
				';
            }
        } else {
            echo '
				<br /><br /><br />
				<h1 style="color:#fff;"><center>U mag dit bestand niet bekijken</center></h1>
			';
        }

    }

    public function sendmail(): RedirectResponse
    {
        $input = Request::all();

        $rules = [
            'to' => 'required|email',
            'subject' => 'required',
            'fileid' => 'required|array',
        ];
        $errors = [
            'to.required' => 'Het veld Ontvanger is verplicht!',
            'to.email' => 'Het veld Ontvanger bevat geen geldig email adres!',
            'subject.required' => 'Het veld Onderwerp is verplicht!',
            'fileid.required' => 'Er moet minstens één bijlage verstuurd worden!',
            'fileid.array' => 'Geen geldige bijlages mee gestuurd!',
        ];

        $v = Validator::make($input, $rules, $errors);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return Redirect::back()->withInput();
        } else {

            Mail::queue('emails.files', Request::all(), function ($message) {

                $org = User::where('id', '=', Auth::user()->oid)->first();
                $client = User::where('id', '=', Auth::user()->cid)->first();

                $message->from($client->username.'@digitar.nu', $client->name);
                $message->to(Request::get('to'))->subject(Request::get('subject'));

                foreach (Request::get('fileid') as $fileid => $filename) {
                    $file = Files::getFileById($fileid);

                    $pathToFile = '/home/digitar/clients/'.$org->username.'/'.$client->username.'/'.$file->file;

                    $message->attach($pathToFile, ['as' => $file->name.'.pdf']);
                }
            });

            Alert::success('De bestanden zijn verstuurd!')->flash();

            return redirect('/user/folder/inbox');
        }
    }

    public static function getFolderPath($folderid = false, $clientid = false, $type = 'uf', $oid = false)
    {
        if ($clientid == false) {
            $clientid = Auth::user()->cid;
        }
        if ($oid == false) {
            $oid = Auth::user()->oid;
        }
        $u = new User;
        $organization = $u
            ->where('id', '=', $oid);
        if (! $organization->count()) {
            return redirect('/user/folder/inbox');
        }
        $client = $u
            ->where('id', '=', $clientid)
            ->where('oid', '=', $oid);
        if (! $client->count()) {
            return redirect('/user/folder/inbox');
        }

        return '../../../clients/'.$organization->first()->username.'/'.$client->first()->username.'/';
    }
}
