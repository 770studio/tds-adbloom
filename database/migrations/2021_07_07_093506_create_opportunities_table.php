<?php

use App\Models\Opportunity;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpportunitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('short_id', 21);
            $table->string('external_id', 100);
            $table->string('name', 100);
            $table->enum('type', Opportunity::TYPES);
            $table->timestamps();
        });

     }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('opportunities');
    }
}
