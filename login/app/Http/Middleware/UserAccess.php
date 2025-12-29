<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route()->parameter('id');
        if (! is_null($id)) {
            $did = User::find($id);
            $cu = $request->user();
            if ($did->rights >= $cu->rights || $did->rights >= $cu->rights && $did->cid != $cu->cid) {
                return response()->view('blank', [
                    'title' => 'We hebben een probleem!',
                    'content' => 'U hebt niet genoeg rechten om dit te doen.',
                ]);
            }
        }

        return $next($request);
    }
}
