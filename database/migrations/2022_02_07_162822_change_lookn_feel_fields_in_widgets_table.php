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
        if (Schema::hasColumn('widgets', 'primaryColor')) {
            Schema::table('widgets', function (BLueprint $table) {
                $table->dropColumn('primaryColor');
            });
        }
        if (Schema::hasColumn('widgets', 'secondaryColor')) {
            Schema::table('widgets', function (BLueprint $table) {
                $table->dropColumn('secondaryColor');
            });
        }
        Schema::table('widgets', function (Blueprint $table) {
            $table->string('buttonBackground')->nullable();
            $table->string('buttonTextColor')->nullable();
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
