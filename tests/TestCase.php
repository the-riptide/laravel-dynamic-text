<?php

namespace TheRiptide\LaravelDynamicText\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Attributes\WithMigration;
use Orchestra\Testbench\TestCase as Orchestra;
use TheRiptide\LaravelDashboardComponents\ComponentServiceProvider;
use TheRiptide\LaravelDynamicText\DynamicTextServiceProvider;

#[WithMigration]
abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    /** The locales the texts table is built with; null means the single app.locale. */
    protected ?array $locales = null;

    protected string $langPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        // __i() reads through trans('lang.*'), which loads lang/{locale}/lang.php.
        $this->artisan('language:setup')->assertSuccessful();
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->langPath);

        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            ComponentServiceProvider::class,
            DynamicTextServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $this->langPath = sys_get_temp_dir().'/dyntext-lang-'.getmypid();
        $app->useLangPath($this->langPath);

        $app['config']->set('app.locales', $this->locales);
        $app['config']->set('dynamictext.emails', ['editor@example.com']);
    }

    protected function defineRoutes($router)
    {
        // The dashboard menu posts to the host app's logout route.
        $router->post('logout', fn () => '')->name('logout');
    }

    protected function user(string $email = 'editor@example.com'): User
    {
        return User::forceCreate(['name' => 'Editor', 'email' => $email, 'password' => 'secret']);
    }
}
