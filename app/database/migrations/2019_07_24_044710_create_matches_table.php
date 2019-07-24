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
            $table->unsignedBigInteger('id')->primary(); // id pandascore api
            $table->unsignedBigInteger('league_id');
            $table->string('name', 100)->index();
            $table->string('slug', 100)->index();
            $table->string('status', 30)->index();
            $table->string('match_type', 100)->index();
            $table->integer('number_of_games');
            $table->boolean('draw');
            $table->boolean('end_at');
            $table->boolean('forfeit');
            $table->string('winner');
            $table->bigInteger('winner_id');
            $table->timestamp('modified_at')->nullable();
            $table->timestamp('begin_at')->nullable();
            $table->timestamps();

            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade');
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
