<?php

use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

Route::get('admin', function () {
    return redirect('/admin/superlogin');
})->before('auth');

Route::get('admin/superlogin', function () {
    $u = new User;

    return view('login.superlogin', [
        'superlogin' => 'true',
    ]);
})->before('auth');
Route::post('admin/supersearch', [UserController::class, 'supersearch'])->before('auth');

Route::get('admin/organizations', function () {
    $o = new Organizations;

    return view('admin.organizations.overview', [
        'title' => 'Organisaties',
        'organizations' => $o->all(),
    ]);
})->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Add New organization
 |--------------------------------------------------------------------------
*/
Route::get('admin/organizations/add', function () {
    return view('admin.organizations.add')->with('title', 'Organisatie toevoegen');
})->before('auth');
Route::post('admin/organizations/add', [OrganizationsController::class, 'add'])->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Edit a organization
 |--------------------------------------------------------------------------
*/
Route::get('admin/organization/edit/{id}', function ($id) {
    $o = new Organizations;
    $org = $o->find($id);

    return view('admin.organizations.edit', [
        'title' => 'Organisatie bewerken',
        'organization' => $org,
    ]);
})->before('auth');
Route::post('admin/organization/edit/{id}', [OrganizationsController::class, 'edit'])->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Delete a organization
 |--------------------------------------------------------------------------
*/
Route::get('admin/organization/delete/{id}', function ($id) {
    $o = new Organizations;
    $org = $o->find($id);

    return view('admin.organizations.delete', [
        'title' => 'Organisatie verwijderen',
        'organization' => $org,
    ]);
})->before('auth');
Route::post('admin/organization/delete/{id}', [OrganizationsController::class, 'delete'])->before('auth');

Route::get('admin/users', function () {
    $users = new User;
    $u = $users->where('rights', '=', '4')->where('listed', '=', '1')->get();
    if ($u->count() > 0) {
        $aUsers = $u;
    } else {
        $aUsers = [];
    }
    $o = new Organizations;

    return view('admin.users.overview', [
        'title' => 'Administrators',
        'users' => $aUsers,
        'organizations' => $o->all(),
    ]);
})->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Add new administrator
 |--------------------------------------------------------------------------
*/
Route::get('admin/user/add', function () {
    $o = new Organizations;
    $aOrganizations = $o->all();
    if (count($aOrganizations) > 0) {
        foreach ($aOrganizations as $org) {
            $organizations[$org->id] = $org->name;
        }

        return view('admin.users.add', [
            'title' => 'Administrator toevoegen',
            'organizations' => $organizations,
        ]);
    } else {
        // if there are no organizations redirect with message
        Alert::warning('Er zijn nog geen organisaties, maak eerst een organisatie.')->flash();

        return redirect('/admin/organizations/add');
    }
})->before('auth');
Route::post('admin/user/add', [
    'before' => 'auth',
    'uses' => [UserController::class, 'addOrganization'],
]);
/*
 |--------------------------------------------------------------------------
 |	Edit a administrator
 |--------------------------------------------------------------------------
*/
Route::get('admin/user/edit/{id}', function ($id) {
    $o = new Organizations;
    foreach ($o->all() as $org) {
        $organizations[$org->id] = $org->name;
    }
    $u = new User;
    $user = $u->find($id);

    return view('admin.users.edit', [
        'title' => 'Administrator bewerken',
        'user' => $user,
        'organizations' => $organizations,
    ]);
})->before('auth|user');
Route::post('admin/user/edit/{id}', [UserController::class, 'editAdmin'])->before('auth');
/*
 |--------------------------------------------------------------------------
 |	Delete a administrator
 |--------------------------------------------------------------------------
*/
Route::get('admin/user/delete/{id}', function ($id) {
    $u = new User;
    $user = $u->find($id);

    return view('admin.users.delete', [
        'title' => 'Administrator verwijderen',
        'user' => $user,
    ]);
})->before('auth|user');
Route::post('admin/user/delete/{id}', [UserController::class, 'deleteAdmin'])->before('auth');

/*
 |--------------------------------------------------------------------------
 |	Log
 |--------------------------------------------------------------------------
*/
Route::get('admin/logs', function () {
    $aLog = explode('[2015-', File::get('../app/storage/logs/laravel.log'));
    $monolog = '';
    array_shift($aLog);
    if (count($aLog) > 0) {
        foreach ($aLog as $key => $error) {
            $monolog .= '<h2>'.$key.'</h2><pre>[2014-'.print_r($error, true).'</pre>';
        }
    } else {
        $monolog .= '<h3>Logs are empty!</h3>';
    }

    return view('blank', [
        'title' => 'Logs',
        'content' => $monolog,
    ]);
})->before('auth');
Route::get('admin/logs/clear', function () {
    File::put('../app/storage/logs/laravel.log', '');

    Alert::success('Logs cleared!')->flash();

    return redirect('/admin/logs');
})->before('auth');

Route::get('admin/size', function () {

    $return = '';
    $aScanned = [];

    if (App::environment('prod') || App::environment('debug')) {
        $directory = '/home/digitar/clients/';
    } else {
        $directory = '/var/www/digitar/clients/';
    }

    $fileSPLObjects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    try {
        $i = 1;
        foreach ($fileSPLObjects as $fullFileName => $fileSPLObject) {
            $f = explode('/', $fullFileName);

            if (! isset($f[7]) && isset($f[6]) && $f[6] == '.') {
                $org = $f[4];
                $client = $f[5];
                $f = '/home/digitar/clients/'.$org.'/'.$client;
                $io = shell_exec('du -hs '.$f.' --block-size=1024');
                $size = substr($io, 0, strpos($io, "\t"));
                $user = user::byUsername($client)->first();
                $aScanned[$i]['s'] = $size;
                $aScanned[$i]['c'] = $user;
                $aScanned[$i]['o'] = $org;
                // echo '<pre>';
                // dd($user);
                $i++;
            }
        }
    } catch (UnexpectedValueException $e) {
        printf('Directory [%s] contained a directory we can not recurse into', $directory);
    }

    $return .= '<table class="table table-hover table-condensed dataTable" id="datatable"><thead><tr><th></th><th>Organisatie</th><th>Klant</th><th>Verbruik</th></tr></thead><tbody>';

    array_multisort($aScanned, SORT_DESC);
    foreach ($aScanned as $i => $client) {
        $size = round($aScanned[$i]['s'] / 1024, 2);
        if ($size > 1000) {
            $size = round(($aScanned[$i]['s'] / 1024) / 1024, 2);
            $size = explode('.', $size);
            if (count($size) > 1) {
                $sizeReturn = number_format($size[0], 0, '.', '.').'.<small style="color:#777;">'.$size[1].'</small> gb';
            } else {
                $sizeReturn = $size[0].'gb';
            }
        } elseif ($size < 1) {
            $sizeReturn = $aScanned[$i]['s'].' kb';
        } else {
            $size = explode('.', $size);
            if (count($size) > 1) {
                $sizeReturn = number_format($size[0], 0, '.', '.').'.<small style="color:#777;">'.$size[1].'</small> mb';
            } else {
                $sizeReturn = $size[0].'mb';
            }
        }
        $return .= '<tr><td style="font-size:0px !important;">'.round(1000000 / $aScanned[$i]['s']).'</td><td>'.$aScanned[$i]['o'].'</td><td><a class="popup" href="https://login.digitar.nu/admin/client/details/'.$aScanned[$i]['c']['id'].'">'.$aScanned[$i]['c']['username'].'</a></td><td>'.$sizeReturn.'</td></tr>';
    }

    $return .= '</tbody></table>';

    return view('blank', [
        'title' => 'Verbruik klanten',
        'content' => $return,
    ]);
})->before('auth');

Route::get('admin/lastlogin', function () {

    $return = '<table class="table table-hover table-condensed"><thead><tr><th>Gebruiker</th><th>Klant</th><th>Ingelogd</th></tr></thead><tbody>';

    $users = user::whereNotNull('lastlogin')->orderBy('lastlogin', 'DESC')->take(10)->get();
    foreach ($users as $user) {
        $Date = new DateTime($user->lastlogin);
        $client = user::getUserName($user->cid);
        if ($client == '') {
            $client = user::getUserName($user->oid);
            if ($client == '') {
                $client = '<span style="color:#ddd;">Geen</span>';
            }
        }
        $return .= '<tr><td>'.$user->name.' <small>('.$user->username.')</small></td><td>'.$client.'</td><td>'.$Date->format('H:i:s').' &nbsp; <small>('.$Date->format('j F Y').')</small></td></tr>';
    }

    $return .= '</tbody></table><br /><br />';
    $Date = new DateTime;
    $return .= 'Huidige servertijd: <b>'.$Date->format('H:i:s').'</b>';

    return view('blank', [
        'title' => 'De 10 laatst ingelogde gebruikers',
        'content' => $return,
    ]);
})->before('auth');

Route::get('admin/client/details/{id}', function ($id) {
    $user = user::byId($id)->first();
    $subuser = user::getFirstUser($user->oid, $user->id);
    $org = user::byId($user->oid)->first();

    $f = '/home/digitar/clients/'.$org->username.'/'.$user->username;
    $io = shell_exec('du -hs '.$f.' --block-size=1024');
    $size0 = substr($io, 0, strpos($io, "\t"));
    $size = round($size0 / 1024, 2);
    if ($size > 1000) {
        $size = round(($size0 / 1024) / 1024, 2);
        $size = explode('.', $size);
        if (count($size) > 1) {
            $sizeReturn = number_format($size[0], 0, '.', '.').'.<small style="color:#777;">'.$size[1].'</small> gb';
        } else {
            $sizeReturn = $size[0].'gb';
        }
    } elseif ($size < 1) {
        $sizeReturn = $size0.' kb';
    } else {
        $size = explode('.', $size);
        if (count($size) > 1) {
            $sizeReturn = number_format($size[0], 0, '.', '.').'.<small style="color:#777;">'.$size[1].'</small> mb';
        } else {
            $sizeReturn = $size[0].'mb';
        }
    }

    return view('admin.clientpopup', [
        'trueblank' => true,
        'user' => $user,
        'subuser' => $subuser,
        'dirsize' => $sizeReturn,
    ]);
})->before('auth');

/*
 * ======================
 * Tools
 * ======================
 */

Route::get('admin/tools/forwardcheck', function () {

    return view('admin.tools.checkforwarders', [
        'title' => 'Check e-mail forwarders',
        'users' => ToolsController::checkForwarders(),
    ]);

})->before('auth');
Route::post('admin/tools/forwardcheck', [
    'before' => 'auth',
    'uses' => [ToolsController::class, 'createForwarder'],
]);

Route::get('admin/tools/ftpcheck', function () {

    return view('admin.tools.ftpcheck', [
        'title' => 'Check FTP accounts',
        'users' => ToolsController::checkFtp(),
    ]);

})->before('auth');
Route::post('admin/tools/ftpcheck', [
    'before' => 'auth',
    'uses' => [ToolsController::class, 'createFtp'],
]);

Route::get('admin/tools/getpdfcontents', function () {

    return view('admin.tools.savepdfcontents', [
        'title' => 'Check FTP accounts',
        'amount' => Files::whereNull('contents')->where('updated_at', '<', Carbon::today())->orderBy('ID', 'DESC')->count(),
    ]);

})->before('auth');
Route::post('admin/tools/getpdfcontents', [ToolsController::class, 'savePdfContents'])->before('auth');
