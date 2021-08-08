<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPartner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {

                $table->text('pending_url')->nullable();
                $table->boolean('send_pending_postback')->default(0);
                $table->string('send_pending_status')->default('[]');
                $table->tinyInteger('pending_timeout', null, true)->default(24);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partner', function (Blueprint $table) {
            //
        });
    }
}
