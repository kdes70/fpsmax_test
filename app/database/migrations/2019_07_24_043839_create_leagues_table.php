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
            $table->unsignedBigInteger('id')->primary(); // id pandascore api
            $table->string('image_url', 200);
            $table->string('name', 200)->index();
            $table->string('slug', 200)->index();
            $table->string('url')->index();
            $table->boolean('live_supported');
            $table->timestamp('modified_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leagues');
    }
}
