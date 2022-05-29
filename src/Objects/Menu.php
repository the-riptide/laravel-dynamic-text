<?php

namespace TheRiptide\LaravelDynamicText\Objects;

class Menu
{
    public $items;

    public function __construct() {

        $this->items = collect(config('dynamictext.menu_items'))->map(function ($item, $key) 
        {
            if (isset($item['route'])) {
                
                return [ 
                    'name' => $key,
                    'route' => $item['route'],
                    'parameter' => $item['parameter'] ?? null,
                
                ];
            }
            else {
                return [
                    
                    'route' => $item,
                    'name' => $key,
                ];
            }
        });
    
    }
}