<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLooknFeelFieldsInWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widgets', function (Blueprint $table) {
            $table->dropColumn('primaryColor');
            $table->string('buttonBackground')->nullable();
            $table->string('buttonTextColor')->nullable();
            $table->dropColumn('secondaryColor');
            $table->string('rewardBackground')->nullable();
            $table->string('rewardTextColor')->nullable();
            $table->string('inAppCurrencySymbolUrl_type')->default('text');
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
            //
        });
    }
}
