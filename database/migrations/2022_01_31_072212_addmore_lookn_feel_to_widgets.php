<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddmoreLooknFeelToWidgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->string('cta')->nullable();
            $table->string('textColor')->nullable();
            $table->string('headingFontFamily')->nullable();
            $table->string('ctaFontFamily')->nullable();
            $table->string('bodyFontFamily')->nullable();
            $table->string('headingfontWeight')->default(700);
            $table->string('ctaFontWeight')->default(700);
            $table->string('bodyFontWeight')->default(400);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->dropColumn('cta', 'textColor', 'headingFontFamily', 'ctaFontFamily', 'bodyFontFamily',
                'headingfontWeight', 'ctaFontWeight', 'bodyFontWeight');
        });
    }
}
