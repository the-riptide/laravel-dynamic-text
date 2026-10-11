<?php

namespace TheRiptide\LaravelDynamicText;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use TheRiptide\LaravelDynamicText\Models\Text;

class DynamicText
{
    public function firstOrCreate(string $key, string|array|null $value): string
    {
        $response = trans('lang.'.$key);

        if ($value) {

            if ($response === 'lang.'.$key && $value) {

                $response = $this->create($key, $value);
            }
        }

        return $response;
    }

    public function create($key, $value): string
    {
        [$catAndKey['category'], $catAndKey['key']] = Str::of($key)->explode('.');

        if (is_array($value)) {

            foreach ($value as $key => $item) {

                $data[$key] = $item;
            }
        } else {

            if (config('app.locales')) {
                foreach (config('app.locales') as $locale) {

                    $data[$locale] = $value;
                }
            } else {

                $data[config('app.locale')] = $value;
            }
        }

        $text = Text::firstorcreate($catAndKey, $data);

        if (config('app.locales')) {

            foreach (config('app.locales') as $locale) {
                (new PrepText($locale))->updateCache();
            }
        } else {
            (new PrepText(config('app.locale')))->updateCache();
        }

        return $text->{App::getLocale()};
    }
}
