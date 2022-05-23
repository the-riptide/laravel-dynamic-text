<?php

namespace TheRiptide\LaravelDynamicText\Security;

use Illuminate\Support\Facades\App;


class Authorize {

    public function canTakeAction() {

    $config = class_exists(TheRiptide\LaravelDynamicDashboard\DynamicDashboardServiceProvider::class) 
        ? 'dyndash.emails' 
        : 'dynamictext.emails';

    return auth()->user() && in_array(auth()->user()->email, config($config))
        ? true  
        : false;

    }
}