<?php

namespace TheRiptide\LaravelDynamicText;

use Illuminate\Support\ServiceProvider;

class DynamicTextServiceProvider extends ServiceProvider
{

    public function boot() {

        $this->app->singleton('DynamicText', function() {

            return new DynamicText;

        });

        $this->loadViewsFrom(__DIR__.'/../views', 'dyntext');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        

    }

    public function register() {


    }
}