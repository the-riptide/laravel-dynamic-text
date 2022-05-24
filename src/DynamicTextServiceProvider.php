<?php

namespace TheRiptide\LaravelDynamicText;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use TheRiptide\LaravelDynamicText\Commands\SetupLanguage;
use TheRiptide\LaravelDynamicText\Http\Livewire\TextIndex;

class DynamicTextServiceProvider extends ServiceProvider
{

    public function boot() {

        $this->app->singleton('DynamicText', function() {

            return new DynamicText;

        });

        $this->loadViewsFrom(__DIR__.'/../views', 'dyntext');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->mergeConfigFrom(
            __DIR__.'/../config/dynamictext.php', 'dynamictext'
        );
        
        $this->publishes([
            __DIR__.'/../config/dynamictext.php' => config_path('dynamictext.php'),
            __DIR__.'/../views' => resource_path('views/vendor/dyntext'),

        ]);



        Livewire::component('laravel-dynamic-text', TextIndex::class);


        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupLanguage::class,
            ]);
        }


    }

    public function register() {


    }
}