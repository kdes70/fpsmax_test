<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->unsignedBigInteger('id', false)->primary(); // id pandascore api
            $table->unsignedBigInteger('league_id');
            $table->string('name', 100)->index();
            $table->string('slug', 100)->index();
            $table->string('status', 30)->index();
            $table->string('match_type', 100)->index();
            $table->integer('number_of_games');
            $table->boolean('draw');
            $table->boolean('forfeit');
            $table->string('winner')->nullable();
            $table->bigInteger('winner_id')->nullable();
            $table->timestampTz('end_at')->nullable();
            $table->timestampTz('modified_at')->nullable();
            $table->timestampTz('begin_at')->nullable();
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
        Schema::dropIfExists('matches');
    }
}
