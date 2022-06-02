# laravel-dynamic-text

This package is meant to make it possible to quickly and easily turn snippets of texts, like headlines, descriptions and button texts editable via the dashboard. 

to require the package run:

<code>php composer require the-riptide/laravel-dynamic-texts</code>

to set up the package, first go to your config app file and make sure 'locale' is set to the locale you'll be using in your project. This is important to do now, as the name of the locale will be attached to the new 'texts' table. 

This done, run

<code>php artisan language:setup</code>

You can now have access to the special inline helper on the front. Under the hood, this makes use of Laravel's trans feature. 

To use it, simply put <code>__i('category.key', 'string you'd like to put in the database') </code>

Then hit your page on the front and the string will be loaded into the database. Note, once the string is in the database with that category and key, it will stay there. This means, you can safetly remove the second string. 

To access the database table first publish the config file

<code>php artisan vendor:publish</code> 

and select this package from the list. 

in the config file put the email you're going to use to access the dashboard. Then log in and hit the route 

<code>/dashboard/texts/edit</code>

All your dynamic strings should appear here.

## debuging
If you haven't yet used livewire in your project it's possible livewire will blow up. In that case, try adding the folder Livewire to app/Http folder. Nothing needs to be in it. 