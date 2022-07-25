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

    public function retrieve($cache = true) {

        switch ($cache) {

            case true:
                
                return ! Cache::has('translations.' . $this->locale) 
                    ? $this->updateCache()
                    : Cache::get('translations.' . $this->locale);
                            
            case false:

                return $this->getTexts();
        }
    }

    public function updateCache() {

        $translations = $this->getTexts();

        Cache::put(
            'translations.' . $this->locale, 
            $translations,
            1200
        );

        return $translations;
    }

    private function getTexts()
    {
        return Text::get()->mapWithKeys(
            fn ($text) => [
                $text->category . '.' . $text->key => $text->{$this->locale}
            ]
        );
    }
}