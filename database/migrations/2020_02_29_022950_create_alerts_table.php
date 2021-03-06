<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alerts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->index()->nullable();
            $table->string('user_uuid')->index();
            $table->string('photo_uuid')->index();
            $table->text('photo_url')->nullable();
            $table->uuidMorphs('alert');
            $table->tinyInteger('type');
            $table->point('location'); // Latitude, Longitude
            $table->string('title');
            $table->longText('body');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('alerts');
    }
}
