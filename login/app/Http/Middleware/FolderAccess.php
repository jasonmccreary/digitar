<?php

namespace App\Http\Middleware;

use App\Models\Folder;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FolderAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route()->parameter('id');
        if (! is_null($id)) {
            $did = Folder::find($id);
            if ($did->uid != $request->user()->id) {
                return response()->view('blank', [
                    'title' => 'We hebben een probleem!',
                    'content' => 'U hebt niet genoeg rechten om dit te doen.',
                ]);
            }
        }

        return $next($request);
    }
}
