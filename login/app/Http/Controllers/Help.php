<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;

class Help extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (is_null($request->user())) {
            return redirect('/');
        }

        return view('help.overview', [
            'title' => 'Veel gestelde vragen &amp; uitleg',
            'aFolders' => $request->user()->rights == 1 ? Folder::getAllUserFolders() : null,
        ]);
    }
}
