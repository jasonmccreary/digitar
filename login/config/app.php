<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'log' => 'daily',

    'liveurl' => 'https://login.digitar.nu',

    'timezone' => 'Europe/Amsterdam',


    'manifest' => storage_path().'/meta',

    'aliases' => Facade::defaultAliases()->merge([
        'Alert' => Prologue\Alerts\Facades\Alert::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
    ])->toArray(),

];
