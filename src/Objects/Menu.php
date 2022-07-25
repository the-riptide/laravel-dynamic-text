<?php

namespace TheRiptide\LaravelDynamicText\Objects;

use Illuminate\Support\Str;

class Menu
{
    public $items;

    public function __construct() {

        $this->items = collect(config('dynamictext.menu_items'))->map(function ($item, $key) 
        {
            if (isset($item['route'])) {
                
                return [ 
                    'name' => Str::of($key)->snake()->replace('_', ' ')->ucfirst(),
                    'route' => $item['route'],
                    'parameter' => $item['parameter'] ?? null,
                    'active' => request()->route()->getName() == $item['route'],
                ];
            }
            else {
                return [
                    
                    'route' => $item,
                    'name' => Str::of($key)->snake()->replace('_', ' ')->ucfirst(),
                    'active' => request()->route()->getName() == $item,
                ];
            }
        });
    
    }
}