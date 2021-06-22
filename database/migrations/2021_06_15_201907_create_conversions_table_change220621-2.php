<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversionsTableChange220621_2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversions', function (Blueprint $table) {

            $table->text("affiliate_info1" )->nullable()->change();
            $table->text("affiliate_info2" )->nullable()->change();
            $table->text("affiliate_info3" )->nullable()->change();
            $table->text("affiliate_info4" )->nullable()->change();
            $table->text("affiliate_info5" )->nullable()->change();




        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversions');
    }
}
