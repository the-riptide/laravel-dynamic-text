<?php 

if (! function_exists('__i')) {

    function __i($key, $value = null) {

        return app('DynamicText')->firstOrCreate($key, $value);    
    }
}
