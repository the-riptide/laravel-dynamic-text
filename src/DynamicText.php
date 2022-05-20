<?php 

namespace TheRiptide\LaravelDynamicText;

use App\Models\Text;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class DynamicText {


    public function firstOrCreate(string $key, string|array|null $value) : string
    {
        $response = trans('lang.' . $key);
        
        if ($value) {
       
            if ($response === 'lang.' . $key && $value) {

                $response = $this->create($key, $value);
            }
        }

        return $response;
    }

    public function create($key, $value) : string 
    {
        [$catAndKey['category'], $catAndKey['key']] = Str::of($key)->explode('.');

    
        if (is_array($value)) {

            foreach ($value as $key => $item) {

                $data[$key] = $item;
            }
        }
        else {

            foreach (config('app.locales') as $locale) {

                $data[$locale] = $value;
            }
        }
        
        $text = Text::firstorcreate($catAndKey, $data);

        foreach (config('app.locales') as $locale) {

            (New PrepText($locale))->updateCache();
        }        

        return $text->{App::getLocale()};
    }
}
