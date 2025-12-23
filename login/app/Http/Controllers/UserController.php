<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cloud;
use App\Models\Debtors;
use App\Models\Files;
use App\Models\Folderright;
use App\Models\Invoicerows;
use App\Models\Invoices;
use App\Models\Layouts;
use App\Models\Messages;
use App\Models\Products;
use App\Models\User;
use App\Models\Usermods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Prologue\Alerts\Facades\Alert;

class UserController extends Controller
{
    public $redirect = 'admin';

    public $rules;

    public function postLogin(Request $request): RedirectResponse
    {
        $input = $request->all();

        $rules = [
            'username' => 'required|alpha_num',
            'password' => 'required',
        ];

        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect()->to('/');
        } else {

            $query = DB::table('users')->select('id', 'password', 'rights')->where('username', strtolower($input['username']));
            $users = $query->get();

            if (count($users) == 1 && Crypt::decrypt($users[0]->password) == $input['password']) {

                $dt = new \DateTime;
                $updateUser = User::find($users[0]->id);
                $updateUser->lastlogin = $dt->format('Y-m-d H:i:s');
                $updateUser->save();

                Auth::loginUsingId($users[0]->id);

                if ($users[0]->rights > 2) {
                    $request->session()->put('highrank', true);
                }
                if ($users[0]->rights == 5) {
                    return redirect()->to('/admin/superlogin');
                } elseif ($users[0]->rights == 4) {
                    return redirect()->to('/organization/superlogin');
                } else {
                    if (strpos($_SERVER['HTTP_HOST'], 'beta') !== false) {
                        Auth::logout();

                        return redirect()->to(config('app.liveurl'));
                    } else {
                        return redirect()->to('/');
                    }
                }
            } else {
                Alert::error('Gebruikersnaam of wachtwoord is niet juist')->flash();

                return redirect()->to('/');
            }
        }
    }

    public function supersearch(Request $request): View
    {
        $u = $request->user();
        if ($u->rights == 5) {
            $c = DB::select("select
				u1.name,
				u1.username,
				u1.password,
				u1.id,
				u1.oid
			from users as u1
			where
				rights = 2 and
				(
					name like '%".$request->get('search')."%' or
					username like '%".$request->get('search')."%'
				) or exists (
					select 1 from users as u2
					where
						rights = 1 and
						cid = u1.id and
						(
							u2.name like '%".$request->get('search')."%' or
							u2.username like '%".$request->get('search')."%'
						)
				)
			LIMIT 5");
        } elseif ($u->rights == 4) {
            $c = DB::select("select
				u1.name,
				u1.username,
				u1.password,
				u1.id,
				u1.oid
			from users as u1
			where
				rights = 2 and
				oid = '".$u->id."' and
				(
					name like '%".$request->get('search')."%' or
					username like '%".$request->get('search')."%'
				) or exists (
					select 1 from users as u2
					where
						rights = 1 and
						cid = u1.id and
						oid = '".$u->id."' and
						(
							u2.name like '%".$request->get('search')."%' or
							u2.username like '%".$request->get('search')."%'
						)
				)
			LIMIT 5");
            // 			dd(DB::getQueryLog());
        }
        $clients = $c;

        if (count($c) > 0) {
            return view('login.supersearch', [
                'clients' => $clients,
                'username' => $request->get('search'),
            ]);
        }

        return view('login.supersearch', [
            'clients' => [],
        ]);
    }

    /*
     |--------------------------------------------------------------------------
     |	ADD users section
     |--------------------------------------------------------------------------
    */

    public function addOrganization()
    {
        $this->redirect = 'admin/user';

        return $this->add(
            1,
            false,
            4
        );
    }

    public function addClient(Request $request)
    {
        $this->redirect = 'organization/client';

        return $this->add(
            $request->user()->id,
            false,
            2,
            true
        );
    }

    public function addModerator(Request $request)
    {
        $this->redirect = 'organization/moderator';
        $this->rules = [
            'username' => 'required|alpha_num',
            'name' => 'required',
            'tell' => 'alpha_dash',
            'email' => 'email',
        ];

        return $this->add(
            $request->user()->id,
            false,
            3
        );
    }

    public function addUser(Request $request)
    {
        $this->redirect = 'client/user';
        $this->rules = [
            'username' => 'required|alpha_num',
            'name' => 'required',
            'tell' => 'alpha_dash',
            'email' => 'email',
        ];

        return $this->add(
            $request->user()->oid,
            $request->user()->id,
            1,
            true
        );
    }

    public function add(Request $request, $organization_id, $clientid = false, $rights = 1, $folders = false): RedirectResponse
    {
        $input = $request->all();

        if (! is_array($this->rules)) {
            $rules = [
                'username' => 'required|alpha_num',
                'name' => 'required',
                'address' => 'required',
                'zipcode' => 'required',
                'city' => 'required',
                'tell' => 'required|alpha_dash',
                'email' => 'required|email',
            ];
        } else {
            $rules = $this->rules;
        }

        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect('/'.$this->redirect.'/add')->withInput();
        } else {
            $u = new User;
            $un = User::where('username', '=', strtolower($request->get('username')));
            if ($un->count() > 0) {
                Alert::error('Deze gebruikersnaam bestaat al.')->flash();

                return redirect()->back()->withInput();
            }

            $u->oid = $organization_id;
            if ($clientid != false) {
                $u->cid = $clientid;
            }
            $pass = Crypt::encrypt(Str::random(8));
            $u->username = strtolower($request->get('username'));
            $u->billing = $request->get('billing');
            $u->password = $pass;
            $u->rights = $rights;
            $u->name = $request->get('name');
            $u->address = $request->get('address');
            $u->zipcode = $request->get('zipcode');
            $u->city = $request->get('city');
            $u->tell = $request->get('tell');
            $u->email = $request->get('email');
            $u->website = $request->get('website');
            $u->lookonly = $request->get('lookonly', '0');
            $u->onverwerkt = $request->get('onverwerkt', '1');
            $u->listed = 1;
            $u->save();

            if ($request->get('billing') > 0) {
                Layouts::setUp($u); // setup invoice layouts
            }

            if (is_array($request->get('fid'))) {
                Folderright::where('uid', '=', $u->id)->delete();
                foreach ($request->get('fid') as $id => $v) {
                    $nf = new Folderright;
                    $nf->fid = $id;
                    $nf->uid = $u->id;
                    $nf->save();
                }

            }

            if ($rights == 2) {
                require_once app_path().'/controllers/xmlapi.class.php';

                $org = User::where('id', '=', $organization_id);
                if ($org->count() != 1) {
                    echo $organization_id.'<br />';
                    dd($org->first());
                }
                $org = $org->first();

                // api call to add ftp user and its home directory
                $xmlapi = new xmlapi('31.7.4.236');
                $xmlapi->password_auth('root', 'HOLME7OmsFNW');
                $xmlapi->set_output('json');
                $xmlapi->set_debug(0);
                $args = [
                    'user' => strtolower($request->get('username')),
                    'pass' => $pass,
                    'quota' => 0,
                    'homedir' => 'clients/'.$org->username.'/'.strtolower($request->get('username')).'/unsorted',
                ];
                $obj = $xmlapi->api2_query('digitar', 'Ftp', 'addftp', $args);

                // api call to add email adress and set forwarder to pipe script
                // $p['domain']    = 'digitar.nu';
                // $p['email']     = $request->get('username').'@digitar.nu';
                // $p['fwdopt']    = 'pipe';
                // $p['pipefwd']   = '/home/digitar/crons/mailPipe.php';
                // $res = $xmlapi->api2_query('digitar', 'Email', 'addforward', $p);
            }

            Alert::success('Een nieuwe gebruiker is toegevoegd')->flash();

            return redirect('/'.$this->redirect.'s');
        }
    }

    /*
     |--------------------------------------------------------------------------
     |	EDIT user section
     |--------------------------------------------------------------------------
    */

    public function editAdmin(Request $request, $id)
    {
        $this->redirect = 'admin/user';

        return $this->edit(
            $id,
            $request->get('oid'),
            false,
            4
        );
    }

    public function editClient(Request $request, $id)
    {
        $this->redirect = 'organization/client';

        return $this->edit(
            $id,
            $request->user()->id,
            false,
            2,
            true
        );
    }

    public function editModerator(Request $request, $id)
    {
        $this->redirect = 'organization/moderator';
        $this->rules = [
            'username' => 'required|alpha_num',
            'name' => 'required',
            'tell' => 'alpha_dash',
            'email' => 'email',
        ];

        return $this->edit(
            $id,
            $request->user()->id,
            false,
            3
        );
    }

    public function editUser(Request $request, $id)
    {
        $this->redirect = 'client/user';
        $this->rules = [
            'username' => 'required|alpha_num',
            'name' => 'required',
            'tell' => 'alpha_dash',
            'email' => 'email',
        ];

        return $this->edit(
            $id,
            $request->user()->oid,
            $request->user()->id,
            1,
            true
        );
    }

    private function edit($id, $organization_id, $clientid = false, $rights = 1, $folders = false)
    {
        $input = Request::all();
        $input['tell'] = str_replace(' ', '', Request::get('tell'));

        if (! is_array($this->rules)) {
            $rules = [
                'username' => 'required|alpha_num',
                'name' => 'required',
                'address' => 'required',
                'zipcode' => 'required',
                'city' => 'required',
                'tell' => 'required|alpha_dash',
                'email' => 'required|email',
            ];
        } else {
            $rules = $this->rules;
        }

        $v = Validator::make($input, $rules);
        if ($v->fails()) {
            foreach ($v->messages()->all() as $message) {
                Alert::error($message)->flash();
            }

            return redirect('/'.$this->redirect.'/edit/'.$id)->withInput();
        } else {
            $did = User::where('id', '=', $id)->first();
            $cu = Auth::user();
            if ($did->rights >= $cu->rights || $did->rights >= $cu->rights && $did->cid != $cu->cid) {
                return view('blank', [
                    'title' => 'We hebben een probleem!',
                    'content' => 'U hebt niet genoeg rechten om dit te doen.',
                ]);
            } else {
                $user = new User;
                $u = $user->find($id);
                $u->oid = $organization_id;
                if ($clientid != false) {
                    $u->cid = $clientid;
                } else {
                    $clientid = $u->id;
                }
                if (Request::has('password')) {
                    $u->password = Crypt::encrypt(Request::get('password'));
                }
                $u->username = strtolower(Request::get('username'));
                $u->rights = $rights;
                $u->name = Request::get('name');
                $u->billing = Request::get('billing');
                $u->address = Request::get('address');
                $u->zipcode = Request::get('zipcode');
                $u->city = Request::get('city');
                $u->tell = $input['tell'];
                $u->email = Request::get('email');
                $u->website = Request::get('website');
                $u->lookonly = Request::get('lookonly');
                $u->onverwerkt = Request::get('onverwerkt');
                $u->save();

                if (Request::get('billing') > 0) {
                    Layouts::setUp($u); // setup invoice layouts
                }

                if ($folders && is_array(Request::get('fid'))) {
                    Folderright::where('uid', '=', $u->id)->delete();
                    foreach (Request::get('fid') as $id => $v) {
                        $nf = new Folderright;
                        $nf->fid = $id;
                        $nf->uid = $u->id;
                        $nf->save();
                    }

                }
                if ($u->rights == 2 && Request::has('password')) {
                    require_once app_path().'/controllers/xmlapi.class.php';

                    $org = User::where('id', '=', $did->oid);
                    $org = $org->first();

                    // api call to change password
                    $xmlapi = new xmlapi('31.7.4.236');
                    $xmlapi->password_auth('root', 'HOLME7OmsFNW');
                    $xmlapi->set_output('json');
                    $xmlapi->set_debug(0);
                    $args = [
                        'user' => $did->username,
                        'pass' => Request::get('password'),
                    ];
                    $obj = $xmlapi->api2_query('digitar', 'Ftp', 'passwd', $args);
                    $returnaa = json_decode($obj);
                    if ($returnaa->cpanelresult->data[0]->result == 0) {
                        $args = [
                            'user' => strtolower(Request::get('username')),
                            'pass' => Request::get('password'),
                            'quota' => 0,
                            'homedir' => 'clients/'.$org->username.'/'.strtolower(Request::get('username')).'/unsorted',
                        ];
                        $obj = $xmlapi->api2_query('digitar', 'Ftp', 'addftp', $args);
                    }
                }

                Alert::success('Gebruiker opgeslagen')->flash();

                return redirect('/'.$this->redirect.'s');
            }
        }
    }

    /*
     |--------------------------------------------------------------------------
     |	DELETE users section
     |--------------------------------------------------------------------------
    */

    public function deleteAdmin($id)
    {
        $this->redirect = 'admin/users';

        return $this->delete($id);
    }

    public function deleteClient($id)
    {
        $this->redirect = 'organization/clients';

        return $this->delete($id);
    }

    public function deleteModerator($id)
    {
        $this->redirect = 'organization/moderators';

        return $this->delete($id);
    }

    public function deleteUser($id)
    {
        $this->redirect = 'client/users';

        return $this->delete($id);
    }

    public function delete(Request $request, $id)
    {
        if ($request->get('delete') == 'true') {
            $did = User::where('id', '=', $id)->first();
            $cu = $request->user();
            if ($did->rights >= $cu->rights || $did->rights >= $cu->rights && $did->cid != $cu->cid) {
                return view('blank', [
                    'title' => 'We hebben een probleem!',
                    'content' => 'U hebt niet genoeg rechten om dit te doen.',
                ]);
            } else {
                $user = new User;
                $u = $user->find($id);

                if ($u->rights == '2') {
                    // delete all folders
                    $fc = new FoldersController;
                    $fc->deleteUser($id);

                    // delete all files
                    Files::where('cid', '=', $u->id)->delete();

                    // delete all user related stuff
                    $subusers = User::where('cid', '=', $id);
                    foreach ($subusers->get() as $subuser) {
                        // delete user folder rights
                        Folderright::where('uid', '=', $subuser->id)->delete();
                        Usermods::where('uid', '=', $subuser->id)->delete();
                    }
                    $subusers->delete();

                    // delete all invoices
                    $invoices = Invoices::where('cid', '=', $u->id);
                    foreach ($invoices->get() as $invoice) {
                        Invoicerows::where('iid', '=', $invoice->id)->delete();
                    }
                    $invoices->delete();

                    // delete all layouts
                    Layouts::where('cid', '=', $u->id)->delete();

                    // delete all products
                    Products::where('cid', '=', $u->id)->delete();

                    // delete all debtors
                    Debtors::where('cid', '=', $u->id)->delete();

                    // delete all cloud files
                    Cloud::where('cid', '=', $u->id)->delete();

                    // delete all messages
                    Messages::where('cid', '=', $u->id)->delete();

                }
                $u->delete();

                if ($did->rights == 2) {
                    require_once app_path().'/controllers/xmlapi.class.php';

                    // api call to remove ftp user
                    $xmlapi = new xmlapi('31.7.4.236');
                    $xmlapi->password_auth('root', 'HOLME7OmsFNW');
                    $xmlapi->set_output('json');
                    $xmlapi->set_debug(0);
                    $args = [
                        'user' => $did->username,
                    ];
                    $obj = $xmlapi->api2_query('digitar', 'Ftp', 'delftp', $args);

                    // api call to remove email forwarder
                    $value_to_delete = $did->username.'@http://login.digitar.nu=|/home/digitar/crons/mailPipe.php';
                    $vars = [$value_to_delete];
                    $res = $xmlapi->api1_query('digitar', 'Email', 'delforward', $vars);
                }

                Alert::success('Gebruiker succesvol verwijderd')->flash();
            }
        }

        return redirect()->to($this->redirect);
    }

    public function loginas(Request $request, $id, $password): RedirectResponse
    {
        if (Auth::guest()) {
            return redirect()->to('/');
        }

        $query = DB::table('users')->select('id', 'password', 'rights')->where('id', $id)->where('password', $password)->get();

        if (count($query) == 1) {
            if ($query[0]->rights > 2) {
                $request->session()->put('highrank', true);
            }
            $newpuid = count($request->session()->get('prevuid'));
            $request->session()->put('prevuid.'.$newpuid, $request->user()->id);
            Auth::loginUsingId($query[0]->id);

            return redirect()->to('/');
        } else {
            Alert::error('Fout tijdens het inloggen')->flash();

            return redirect()->to('/logout');
        }
    }

    public function checkCredentials(Request $request): JsonResponse
    {
        // Check authorozation
        if ($request->get('safe') !== 'AIzaSyAyXmJSzBExyYfIqKnqYNh_3jRt9XaJlvM') {
            return response()->json('Not Authorized!', 400);
        }
        $user = DB::table('users')->select('id', 'name', 'username', 'password', 'rights', 'cid', 'oid')->where('username', strtolower($request->get('username')))->where('cid', '<>', '0')->first();

        if (count($user) == 1 && Crypt::decrypt($user->password) == $request->get('password')) {
            return response()->json(['username' => $user->username, 'uid' => $user->id, 'cid' => $user->cid, 'oid' => $user->oid], 200);
        } else {
            return response()->json('Not found!', 400);
        }
    }

    public function checkUsername(Request $request): JsonResponse
    {
        $username = $request->get('username');
        $user = DB::table('users')->select('id', 'name', 'username', 'rights', 'cid', 'oid')->where('username', $username);
        if ($user->count() > 0) {
            $u = $user->first();

            return response()->json(['username' => $u->username, 'uid' => $u->id, 'cid' => $u->cid, 'oid' => $u->oid], 200);
        } else {
            return response()->json('Not found!', 400);
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        if ($request->session()->has('prevuid.'.(count($request->session()->get('prevuid')) - 1))) {
            Auth::loginUsingId($request->session()->get('prevuid.'.(count($request->session()->get('prevuid')) - 1)));
            $request->session()->forget('prevuid.'.(count($request->session()->get('prevuid')) - 1));
        } else {
            $request->session()->forget('highrank');
            $request->session()->forget('pgcount');
            Auth::logout();
        }

        return redirect()->to('/');
    }
}
