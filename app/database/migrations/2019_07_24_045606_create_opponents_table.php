<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOpponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('opponents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('match_id');
            $table->unsignedBigInteger('team_id');
            $table->morphs('opponents'); // зделаем морфологисскую связь для разделению по типу команда или участник

            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matche_opponents');
    }
}
