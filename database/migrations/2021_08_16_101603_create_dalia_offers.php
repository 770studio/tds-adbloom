<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDaliaOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dalia_offers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title')->nullable();
            $table->string('info_short')->nullable();
            $table->string('info')->nullable();
            $table->json('json')->nullable();
            $table->timestamps();

            if(!app()->runningUnitTests()) {
                $table->index([DB::raw('info(100)')]);
                $table->index([DB::raw('info_short(100)')]);
                $table->index([DB::raw('title(100)')]);
            }



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dalia_offers');
    }
}
