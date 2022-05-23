<?php

namespace TheRiptide\LaravelDynamicText\Commands;

use Illuminate\Console\Command;
use TheRiptide\LaravelDynamicText\SetupLanguage as Setup;

class SetupLanguage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'language:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will set up the language files in your resources folder for the dynamic texts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return (new Setup)->activate();
    }
}
