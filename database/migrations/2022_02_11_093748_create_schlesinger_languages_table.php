<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchlesingerLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schlesinger_languages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("LanguageId");
            $table->string("LanguageCode");
            $table->string("CountryCode");
            $table->string("Description");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schlesinger_languages');
    }
}
