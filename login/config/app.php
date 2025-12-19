<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'log' => 'daily',

    'liveurl' => 'https://login.digitar.nu',

    'timezone' => 'Europe/Amsterdam',

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),

    'manifest' => storage_path().'/meta',

    'aliases' => Facade::defaultAliases()->merge([
        'Alert' => Prologue\Alerts\Facades\Alert::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
    ])->toArray(),

];
