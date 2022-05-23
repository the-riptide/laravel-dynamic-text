<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $locale = config('app.locales') 
            ? collect(config('app.locales')) 
            : collect(config('app.locale'));

        Schema::create('texts', function (Blueprint $table) use ($locale) {
            $table->id();
            $table->string('category');
            $table->string('key');        
            $locale->map(fn ($item) => $table->mediumtext($item));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('texts');
    }
};
