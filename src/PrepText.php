<?php

namespace TheRiptide\LaravelDynamicText;

use Illuminate\Support\Facades\Cache;
use TheRiptide\LaravelDynamicText\Models\Text;

class PrepText
{
    private $locale;

    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    /** Returns a plain array: the translation loader merges lang files with array_replace_recursive() since Laravel 10. */
    public function retrieve($cache = true)
    {

        switch ($cache) {

            case true:

                return (! Cache::has('translations.'.$this->locale)
                    ? $this->updateCache()
                    : Cache::get('translations.'.$this->locale))->all();

            case false:

                return $this->getTexts()->all();
        }
    }

    public function updateCache()
    {

        $translations = $this->getTexts();

        Cache::put(
            'translations.'.$this->locale,
            $translations,
            1200
        );

        return $translations;
    }

    private function getTexts()
    {
        return Text::get()->mapWithKeys(
            fn ($text) => [
                $text->category.'.'.$text->key => $text->{$this->locale},
            ]
        );
    }
}
