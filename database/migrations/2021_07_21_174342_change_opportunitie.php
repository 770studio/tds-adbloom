<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!app()->runningUnitTests()) {
            DB::unprepared('alter table `opportunities` convert to character set utf8mb4 collate utf8mb4_general_ci');
        }
        Schema::table('opportunities', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->text('description')->nullable();
            $table->decimal('payout', 15, 5)->default(0);
            $table->enum('currency', ['USD'])->default('USD'); //#TODO move to config
            $table->unsignedInteger('timeToComplete')->default(0);
        });

/*
        image - нужно решить как проще хранить.
    description (текст до 1000 знаков, поддержка emoji)
payout (значение в виде 1.00)
currency (пока по дефолту USD)
link (ссылка на эту opportunity)
tags (текстовые теги для фильтрации и пометки, может быть несколько на opportunity)
Если это type=survey, то:
timeToComplete (минуты)
    */

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
};
