<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leagues', function (Blueprint $table) {
            $table->unsignedBigInteger('id', false)->primary(); // id pandascore api
            $table->string('image_url', 200);
            $table->string('name', 200)->index();
            $table->string('slug', 200)->index();
            $table->string('url')->nullable();
            $table->boolean('live_supported')->index();
            $table->timestampTz('modified_at');
            $table->timestamps();


        });

        Schema::table('matches', function (Blueprint $table) {
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
        Schema::table('matches', function(Blueprint $table){
            $table->dropForeign('matches_league_id_foreign');
        });

        Schema::dropIfExists('leagues');
    }
}
