<?php

namespace App\Http\Controllers;

use App\Files;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;
use Prologue\Alerts\Facades\Alert;

class ToolsController extends Controller
{
    public function savePdfContents(): View
    {

        $files1 = Files::whereNull('contents')
            ->where('updated_at', '<', Carbon::today())->orderBy('ID', 'DESC');
        $files = $files1->limit(1000)
            ->get();

        $delete = 0;
        $exec = 0;

        foreach ($files as $f) {

            $client = User::find($f->cid);
            if ($client !== null) {
                $compressPath = '../../../clients/'.User::getUserUsername($client->oid).'/'.$client->username.'/';
                $tmpPath = '../tmp/';
                $filenameTxt = str_replace('.pdf', '', $f->file).'.txt';

                $io = shell_exec("pdftotext '".$compressPath.$f->file."' '".$tmpPath.$filenameTxt."'");
                if (file_exists($tmpPath.$filenameTxt)) {
                    $fileContents = @file_get_contents($tmpPath.$filenameTxt);
                    unlink($tmpPath.$filenameTxt);
                } else {
                    $fileContents = '';
                }

                $f->contents = $fileContents;
                $f->save();
                $exec++;
            } else {
                $f->delete();
                $delete++;
            }

        }

        return view('admin.tools.savepdfcontents', [
            'title' => 'Save PDF contents',
            'exec' => $exec,
            'amount' => $files1->count(),
            'deleted' => $delete,
        ]);

    }

    public static function checkForwarders()
    {

        require_once app_path().'/controllers/xmlapi.class.php';

        // api call to add ftp user and its home directory
        $xmlapi = new xmlapi('31.7.4.236');
        $xmlapi->password_auth('root', 'HOLME7OmsFNW');
        $xmlapi->set_output('json');
        $xmlapi->set_debug(0);

        $p['domain'] = 'digitar.nu';
        $res = $xmlapi->api2_query('digitar', 'Email', 'listforwards', $p);
        $result = json_decode($res);
        dd($result);
        foreach ($result->cpanelresult->data as $row) {
            $user = explode('@', $row->dest);
            $emails[$user[0]] = $row;
        }

        $u = new User;
        $return = [];
        foreach ($u->where('rights', '=', 2)->get() as $user) {
            if (! isset($emails[$user->username])) {
                $return[] = $user;
            }
        }

        return $return;
    }

    public function createForwarder(): RedirectResponse
    {

        require_once app_path().'/controllers/xmlapi.class.php';

        $xmlapi = new xmlapi('31.7.4.236');
        $xmlapi->password_auth('root', 'HOLME7OmsFNW');
        $xmlapi->set_output('json');
        $xmlapi->set_debug(0);

        $p['domain'] = 'digitar.nu';
        $p['email'] = strtolower(Request::get('username')).'@digitar.nu';
        $p['fwdopt'] = 'pipe';
        $p['pipefwd'] = '/home/digitar/crons/mailPipe.php';
        $res = $xmlapi->api2_query('digitar', 'Email', 'addforward', $p);

        Alert::success('Een nieuwe forwarder is aangemaakt voor: '.Request::get('username'))->flash();

        return redirect('/admin/tools/forwardcheck');
    }

    public static function checkFtp()
    {

        require_once app_path().'/controllers/xmlapi.class.php';

        // api call to add ftp user and its home directory
        $xmlapi = new xmlapi('31.7.4.236');
        $xmlapi->password_auth('root', 'HOLME7OmsFNW');
        $xmlapi->set_output('json');
        $xmlapi->set_debug(1);

        $result = json_decode($xmlapi->listftp('digitar'));
        foreach ($result->cpanelresult->data as $row) {
            $ftps[$row->user] = $row;
        }

        $u = new User;
        $return = [];
        foreach ($u->where('rights', '=', 2)->get() as $user) {
            if (! isset($ftps[$user->username])) {
                $return[] = $user;
            }
        }

        return $return;
    }

    public function createFtp(): RedirectResponse
    {

        require_once app_path().'/controllers/xmlapi.class.php';

        $xmlapi = new xmlapi('31.7.4.236');
        $xmlapi->password_auth('root', 'HOLME7OmsFNW');
        $xmlapi->set_output('json');
        $xmlapi->set_debug(1);

        $args = [
            'user' => strtolower(Request::get('username')),
            'pass' => Request::get('password'),
            'quota' => 0,
            'homedir' => 'clients/'.strtolower(Request::get('organization')).'/'.strtolower(Request::get('username')).'/unsorted',
        ];
        $obj = $xmlapi->api2_query('digitar', 'Ftp', 'addftp', $args);

        $obj = json_decode($obj);
        if (isset($obj->cpanelresult->error)) {
            Alert::error($obj->cpanelresult->error)->flash();
        } else {
            Alert::success('Een nieuw FTP account is aangemaakt voor: '.Request::get('username'))->flash();
        }

        return redirect('/admin/tools/ftpcheck');
    }

    public static function getUserDirSize($uid)
    {
        $u = new User;
        $user = $u->where('id', '=', $uid)->first();

        $org = User::getUserUsername($user->oid);
        $client = User::getUserUsername($user->cid);

        $f = '/home/digitar/clients/'.$org.'/'.$client;
        $io = shell_exec('du -hs '.$f.' --block-size=1024');
        $size = substr($io, 0, strpos($io, "\t"));

        return $size;
    }
}
