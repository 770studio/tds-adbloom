<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLooknFeelToWidgets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->boolean('showHead')->default(0);
            $table->string('partnerName')->nullable();
            $table->string('fontColor')->nullable();
            $table->string('fontSize')->nullable();
            $table->string('primaryColor')->nullable();
            $table->string('secondaryColor')->nullable();
            $table->string('fontFamily')->nullable();
            $table->string('inAppCurrencySymbolUrl')->nullable();


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
            $table->dropColumn('showHead', 'partnerName', 'fontColor', 'fontSize', 'primaryColor',
                'secondaryColor', 'fontFamily', 'inAppCurrencySymbolUrl');
        });
    }
}
