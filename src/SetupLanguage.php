<?php 

namespace TheRiptide\LaravelDynamicText;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SetupLanguage 

{
    public function activate()
    {
        $locale = config('app.locales') 
            ? collect(config('app.locales'))->map(fn ($item) => $this->generateLanguage($item))
            : $this->generateLanguage(config('app.locale'));

    }

    public function generateLanguage($locale)
    {
        if (! File::exists(resource_path('lang/'))) File::makeDirectory(resource_path('lang/'));

        $path = resource_path('lang/' . $locale);
     
        if (! File::exists($path . '/lang.php')) 
        {
            if (! File::exists($path)) File::makeDirectory($path);
            
            File::copy(__DIR__ . '/Lang/lang.php', $path . '/lang.php');

            $content = file_get_contents($path . '/lang.php');

            $content = Str::of($content)->replace('$$locale$$', $locale);

            file_put_contents($path . '/lang.php', $content);
        }
    }
}