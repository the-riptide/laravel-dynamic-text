<?php

namespace TheRiptide\LaravelDynamicText;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
        if (! File::exists(lang_path())) {
            File::makeDirectory(lang_path());
        }

        $path = lang_path($locale);

        if (! File::exists($path.'/lang.php')) {
            if (! File::exists($path)) {
                File::makeDirectory($path);
            }

            File::copy(__DIR__.'/Lang/lang.php', $path.'/lang.php');

            $content = file_get_contents($path.'/lang.php');

            $content = Str::of($content)->replace('$$locale$$', $locale);

            file_put_contents($path.'/lang.php', $content);
        }
    }
}
