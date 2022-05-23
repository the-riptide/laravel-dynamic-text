<?php 

namespace TheRiptide\LaravelDynamicText;

use TheRiptide\LaravelDynamicText\Models\Text;
use Illuminate\Support\Facades\Cache;

class PrepText {

    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    public function retrieve() {

        if (!Cache::has('translations.' . $this->locale)) {
            
            $this->updateCache();
        }

        return Cache::get('translations.' . $this->locale);
    }

    public function updateCache() {

        Cache::put(
            'translations.' . $this->locale, 
            Text::get()->mapWithKeys(
                fn ($text) => [
                    $text->category . '.' . $text->key => $text->{$this->locale}
                ]
            )
        );
    }
}