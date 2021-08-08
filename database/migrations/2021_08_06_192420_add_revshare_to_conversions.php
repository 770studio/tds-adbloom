<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRevshareToConversions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->decimal('user_payout', 15, 2, true)->default(0); // Required field is RevShare is Enabled.
            $table->integer('user_points', false, true)->default(0);
        });


        Schema::table('partners', function (Blueprint $table) {
            $table->boolean('convert_to_points')->default(0);
            $table->decimal('points_multiplier')->default(0); // Required field if Convert to Points is  Enabled
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversions', function (Blueprint $table) {
            $table->dropColumn(['user_payout','user_points']);
        });
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn(['convert_to_points','points_multiplier']);
        });
    }
}
