<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetPartnerPostbackTimeoutAndUrlAsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('send_pending_status')->nullable()->default('[]')->change();
            $table->boolean('pending_timeout')->nullable()->default(0)->change();
            $table->text('pending_url')->nullable()->change();
            $table->decimal('points_multiplier', 8, 2, true)->nullable()->default(0)->change();
            $table->boolean('percentage')->nullable()->default(0)->change();
            $table->string('points_logo')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
