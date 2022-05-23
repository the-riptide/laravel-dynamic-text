<?php

return [

    /** set this variable to true if you're using this package together with the riptide's Laravel Dynamic Dash */
    'dyndash' => false,

    /** If not using the Laravel Dynamic Dash, include emails of user in this array to give access to the text field */
    'emails' => [
        'inotherwords@gmail.com',
    ],

    /** if you're not using the dyndash menu, put any menu entries in the following array */

    'menu' => [
        'dynamic texts' => 'dashboard_texts',
    ],

];