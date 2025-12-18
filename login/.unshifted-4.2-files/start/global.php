<?php

App::error(function (Exception $exception, $code) {
    if (strpos(Request::url(), 'login.php')) {
        return Redirect::to('/');
    }

    $count = Session::get('error_count', 0);
    Session::put('error_count', ++$count);

    $data = [
        'context' => 'PHP',
        'user_id' => Auth::check() ? Auth::user()->id : 0,
        'user_name' => Auth::check() ? Auth::user()->name : '',
        'url' => Request::url(),
        'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
        'ip' => Request::getClientIp(),
        'count' => $count,
        'code' => $code,
    ];

    Log::error($exception, $data);
    if (App::environment('prod') && Config::get('app.debug') !== true) {
        View::share('errorpage', true);

        switch ($code) {
            case 403:
                return Response::view('errors.403', [], 403);

            case 404:
                return Response::view('errors.404', [], 404);

            case 500:
                return Response::view('errors.500', [], 500);

            default:
                return Response::view('errors.default', [], $code);
        }
    }
});
